<?php
  // 初期処理
  require_once('function.php');
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

  function getClassDatas() {
    global $pdo;
    $sql = "SELECT * FROM sq_class";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $class_datas = $stmt->fetchAll();
    return $class_datas;
  }

?>