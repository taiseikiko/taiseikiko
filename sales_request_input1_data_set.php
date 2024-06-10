<?php
require_once('function.php');
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
//ログインユーザーの部署ID
$dept_id = getDeptId();

function get_sq_datas($cust_name = "", $pf_name = "") {
  global $pdo;
  $sql = "SELECT h.sq_no, h.cust_no, c.cust_name, h.p_office_no, pf.pf_name, pf.person_in_charge, e.employee_name, h.item_name
          FROM sq_header_tr h
          LEFT JOIN customer c ON h.cust_no = c.cust_code
          LEFT JOIN public_office pf ON h.p_office_no = pf.pf_code
          LEFT JOIN employee e ON pf.person_in_charge = e.employee_code
          -- LEFT JOIN sq_detail_tr d ON h.sq_no = d.sq_no
          -- LEFT JOIN sq_zaikoumei zk ON d.zkm_code = zk.zkm_code AND d.class_code = zk.class_code // d.zkm_code, zk.zkm_name 
          WHERE c.cust_name LIKE :cust_name AND pf.pf_name LIKE :pf_name";
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
      echo '<td>' . htmlspecialchars($item['zkm_name']) . '</td>';
      echo '<td></td>';
      echo '<td>' . htmlspecialchars($item['employee_name']) . '</td>';
      echo '<td style="text-align:center"><button type="submit" class="updateBtn" data-sq_no="' . htmlspecialchars($item['sq_no']) . '" name="process" value="update">更新</button></td>';
      echo '<input type="hidden" class="sq_no" name="sq_no" value="' . htmlspecialchars($item['sq_no']) . '">';
      echo '</tr>';
    }
    exit;
  }
}

function getDeptId() {
  global $pdo;
  global $dept_code;

  $dept_id = '';
  $sql = "SELECT dept_id FROM sq_dept WHERE sq_dept_code='$dept_code'";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($row) {
    $dept_id = $row['dept_id'];
  }
  return $dept_id;
}
