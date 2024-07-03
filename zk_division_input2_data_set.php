<?php
  // 初期処理
  require_once('function.php');
 
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

  
  function getDistinctValues($column) {
    global $pdo;
    $sql = "SELECT DISTINCT $column FROM sq_zk2";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  $zk_div_names = getDistinctValues('zk_div_name');
  $zk_tp_values = getDistinctValues('zk_tp');
  $zk_no_values = getDistinctValues('zk_no');
?>
