<?php
  // 初期処理
  require_once('function.php');
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
  // 初期設定 & データセット
  $process = '';
  $request_class = '';      //分類コード
  $request_comment = '';    //コメント
  $request_dept = '';       //依頼部署
  $btn_class = 'updRegBtn'; 
  $header = '入力';
  $err = $_GET['err'] ?? '';
  $btn_name = "依頼書登録";
  $class_datas = get_class_datas(); //分類プルダウンにセットするデータを取得する
  
  //一覧画面から来た場合
  if (isset($_POST['process1'])) {
    $process = $_POST['process1'];
  }

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