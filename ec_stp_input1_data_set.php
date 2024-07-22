<?php
// 初期処理
require_once('function.php');
// DB接続
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());


$property_datas = getPropertyDatas();

if (empty($property_datas)) {
    echo "<tr><td colspan='7' style='text-align:center'><h4 style='font-size: 12px;'>表示するデータがございません。</h4></td></tr>";
  } else {
    foreach ($property_data as $row) {
      echo 
        "<tr>
          <td>{$row['bridge']}</td>
          <td>{$row['renewal_date']}</td>
          <td>{$row['company']}</td>
          <td>{$row['name']}</td>
          <td>{$row['attendance_year']}</td>
          <td>{$row['footnote']}</td>
          <td style='text-align:center'><button class='updateBtn' data-key_number='{$row['key_number']}' id='update' name='process' value='update'>更新</button></td>
        </tr>";
    }
  }
function getPropertyDatas() {
  global $pdo;
  $sql = "SELECT DISTINCT * FROM ec_stp_detail_tr_procurment";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
