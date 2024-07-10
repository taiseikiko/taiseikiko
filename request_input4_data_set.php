<?php
  // 初期処理
  require_once('function.php');
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
  // 初期設定 & データセット
  $client = '';             //申請者
  $process = '';
  $class_code = '';         //分類コード
  $comments = '';  
  $btn_class = 'updRegBtn'; 
  $header = '承認';
  $err = $_GET['err'] ?? '';
  $class_datas = get_class_datas(); //分類プルダウンにセットするデータを取得する
  


  /*----------------------------------------------------------------FUNCTION---------------------------------------------------------------------*/
  
  function get_class_datas()
  {
    global $pdo;
    $sql = "SELECT * FROM sq_class";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $datas = $stmt->fetchAll();

    return $datas;
  }

?>