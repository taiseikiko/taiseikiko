<?php
  // 初期処理
  require_once('function.php');
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
  // 初期設定 & データセット
  $count = 0;
  $zk_datas = array();

  //部署ルートマスターからデータ取得する
  $zk_datas = getZkDatas();
  function getZkDatas() {
    global $pdo;
    $sql = "SELECT * FROM sq_zk2";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $zk_datas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $zk_datas;   
  }  
  

?>