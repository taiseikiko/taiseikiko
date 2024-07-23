<?php
// 初期処理
require_once('function.php');
// DB接続
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $ec_division = $_POST['ec_division']?? '';
  if ($ec_division) {
    $stmt = $pdo->prepare("
    SELECT ec.key_number, ec.ec_date, ec.ec_name, c2.code_name AS ec_place, c3.code_name AS pipe, d.sq_dept_name AS bridge, pf.pf_name AS government,
    cus.cust_name AS customers
    FROM ec_work_detail_tr_procurment ec
    LEFT JOIN sq_dept d ON d.sq_dept_code = ec.bridge
    LEFT JOIN ec_code_master c2 ON c2.code_key = 'ec_place' AND c2.code_no = ec.ec_place
    LEFT JOIN ec_code_master c3 ON c3.code_key = 'pipe' AND c3.code_no = ec.pipe
    LEFT JOIN public_office pf ON pf.pf_code = ec.government
    LEFT JOIN customer cus ON cus.cust_code = ec.customers
    WHERE ec_division = :ec_division
    ");
    $stmt->bindParam(':ec_division', $ec_division, PDO::PARAM_INT);
    $stmt->execute();
    $property_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($property_data)) {
      echo "<tr><td colspan='7' style='text-align:center'><h4 style='font-size: 12px;'>表示するデータがございません。</h4></td></tr>";
    } else {
      foreach ($property_data as $row) {
        echo 
          "<tr>
            <td>{$row['bridge']}</td>
            <td>{$row['ec_date']}</td>
            <td>{$row['government']}</td>
            <td>{$row['customers']}</td>
            <td>{$row['ec_place']}</td>
            <td>{$row['pipe']}</td>
            <td style='text-align:center'><button class='updateBtn' data-key_number='{$row['key_number']}' id='update' name='process' value='update'>更新</button></td>
          </tr>";
      }
    }
  }
} else {
  // 初期データ
  $property_datas = getPropertyDatas();
}

function getPropertyDatas() {
  global $pdo;
  $stmt = $pdo->prepare("
  SELECT ec.key_number, ec.add_date, c1.code_name AS ec_name, c2.code_name AS pipe, c3.code_name AS size, c4.code_name AS supplier, d.sq_dept_name AS bridge
  FROM ec_article_detail_tr_procurement ec
  LEFT JOIN sq_dept d ON d.sq_dept_code = ec.bridge
  LEFT JOIN ec_code_master c1 ON c1.code_key = 'ec_name' AND c1.code_no = ec.ec_name
  LEFT JOIN ec_code_master c2 ON c2.code_key = 'pipe' AND c2.code_no = ec.pipe
  LEFT JOIN ec_code_master c3 ON c3.code_key = 'size' AND c3.code_no = ec.size
  LEFT JOIN ec_code_master c4 ON c4.code_key = 'supplier' AND c4.code_no = ec.supplier
  ");
  $stmt->execute();
  $property_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

  if (empty($property_data)) {
    echo "<tr><td colspan='7' style='text-align:center'><h4 style='font-size: 12px;'>表示するデータがございません。</h4></td></tr>";
  } else {
    foreach ($property_data as $row) {
      echo 
        "<tr>
          <td>{$row['bridge']}</td>
          <td>{$row['add_date']}</td>
          <td>{$row['ec_name']}</td>
          <td>{$row['pipe']}</td>
          <td>{$row['size']}</td>
          <td>{$row['supplier']}</td>
          <td style='text-align:center'><button class='updateBtn' data-key_number='{$row['key_number']}' id='update' name='process' value='update'>更新</button></td>
        </tr>";
    }
  }
}
?>
