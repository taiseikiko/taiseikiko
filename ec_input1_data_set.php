<?php
// 初期処理
require_once('function.php');
// DB接続
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $property_code = $_POST['property_code'];
  if ($property_code) {
    $stmt = $pdo->prepare("SELECT bridge, add_date, ec_name, pipe, size, supplier FROM ec_article_detail_tr_procurement WHERE ec_property = :property_code");
    $stmt->bindParam(':property_code', $property_code, PDO::PARAM_INT);
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
            <td style='text-align:center'><button class='updateBtn' id='update' name='process' value='update'>更新</button></td>
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
  $sql = "SELECT DISTINCT ec_property FROM ec_article_detail_tr_procurement";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
