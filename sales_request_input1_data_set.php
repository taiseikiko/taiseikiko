<?php
require_once('function.php');
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
//ログインユーザーの部署ID
$dept_cd = $_POST['dept_code'] ?? $dept_code;
$dept_id = getDeptId($dept_cd);
$title1 = $_POST['title'] ?? $_GET['title'];

function get_sq_datas($cust_name = "", $pf_name = "") {
  global $pdo;
  global $title1;
  $search_kw = [];

  $sql = "SELECT h.sq_no, h.cust_no, c.cust_name, h.p_office_no, pf.pf_name, pf.person_in_charge, e.employee_name, h.item_name
          FROM sq_header_tr h
          LEFT JOIN customer c ON h.cust_no = c.cust_code
          LEFT JOIN public_office pf ON h.p_office_no = pf.pf_code
          LEFT JOIN employee e ON h.client = e.employee_code 
          WHERE 1=1 ";
  //確認画面の場合、確認日がNULLのデータだけに表示させる
  if ($title1 == 'check') {
    $sql .= "AND EXISTS (
            SELECT 1 
            FROM sq_detail_tr d 
            WHERE d.sq_no = h.sq_no 
            AND d.confirm_date IS NULL) ";
  }
  //承認画面の場合、承認日がNULLかつ、確認日がNOT NULLのデータだけに表示させる
  if ($title1 == 'approve') {
    $sql .= "AND EXISTS (
            SELECT 1 
            FROM sq_detail_tr d 
            WHERE d.sq_no = h.sq_no 
            AND d.confirm_date IS NOT NULL AND d.approve_date IS NULL) ";
  }
  //ルート設定の場合、承認日がNOT NULLかつroute_patternがNULLのデータだけを取得する
  if ($title1 == 'set_route') {
    $sql .= "AND EXISTS (
            SELECT 1 
            FROM sq_detail_tr d 
            WHERE d.sq_no = h.sq_no 
            AND d.approve_date IS NOT NULL AND d.route_pattern IS NULL) ";
  }

  
  if (!empty($cust_name)) {
    $search_kw['cust_name'] = '%' . $cust_name . '%';
    $sql .= " AND c.cust_name LIKE :cust_name";
  }
  if (!empty($pf_name)) {
    $search_kw['pf_name'] = '%' . $pf_name . '%';
    $sql .= " AND pf.pf_name LIKE :pf_name";
  }
  $stmt = $pdo->prepare($sql);
  $stmt->execute($search_kw);

  $datas = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $datas;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['isReturn'])) {
    $cust_name = $_POST['cust_name'] ?? "";
    $pf_name = $_POST['pf_name'] ?? "";

    $sq_datas = get_sq_datas($cust_name, $pf_name);

    foreach ($sq_datas as $item) {
      echo '<tr>';
      echo '<td>' . htmlspecialchars($item['cust_name']) . '</td>';
      echo '<td>' . htmlspecialchars($item['pf_name']) . '</td>';
      echo '<td>' . htmlspecialchars($item['item_name']) . '</td>';
      echo '<td></td>';
      echo '<td>' . htmlspecialchars($item['employee_name']) . '</td>';
      echo '<td style="text-align:center"><button type="submit" class="updateBtn" data-sq_no="' . htmlspecialchars($item['sq_no']) . '" name="process" value="update">更新</button></td>';
      echo '<input type="hidden" class="sq_no" name="sq_no" value="' . htmlspecialchars($item['sq_no']) . '">';
      echo '</tr>';
    }
    exit;
  }
}
