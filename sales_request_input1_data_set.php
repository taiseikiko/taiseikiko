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

  $sql = "SELECT h.sq_no, h.cust_no, c.cust_name, h.p_office_no, pf.pf_name, pf.person_in_charge, e.employee_name, h.item_name
          FROM sq_header_tr h
          LEFT JOIN customer c ON h.cust_no = c.cust_code
          LEFT JOIN public_office pf ON h.p_office_no = pf.pf_code
          LEFT JOIN employee e ON h.client = e.employee_code ";
  //確認画面の場合、確認日がNULLのデータだけに表示させる
  if ($title1 == 'check') {
    $sql .= "INNER JOIN (
              SELECT DISTINCT (sq_no) 
              FROM sq_detail_tr
              WHERE confirm_date IS NULL
            ) AS detail 
            ON h.sq_no = detail.sq_no ";
  }
  //承認画面の場合、承認日がNULLかつ、確認日がNOT NULLのデータだけに表示させる
  if ($title1 == 'approve') {
    $sql .= "INNER JOIN (
              SELECT DISTINCT (sq_no) 
              FROM sq_detail_tr
              WHERE approve_date IS NULL AND confirm_date IS NOT NULL
            ) AS detail 
            ON h.sq_no = detail.sq_no ";
  }
  //ルート設定の場合、承認日がNOT NULLかつroute_patternがNULLのデータだけを取得する
  if ($title1 == 'set_route') {
    $sql .= "INNER JOIN (
              SELECT DISTINCT (sq_no) 
              FROM sq_detail_tr
              WHERE approve_date IS NOT NULL AND route_pattern IS NULL
            ) AS detail 
            ON h.sq_no = detail.sq_no ";
  }
  $sql .= "WHERE c.cust_name LIKE :cust_name AND pf.pf_name LIKE :pf_name";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    ':cust_name' => '%' . $cust_name . '%',
    ':pf_name' => '%' . $pf_name . '%'
  ]);

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
