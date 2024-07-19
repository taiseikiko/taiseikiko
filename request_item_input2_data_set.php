<?php
  // 初期処理
  require_once('function.php');
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
  // 初期設定 & データセット
  $today = date('Y/m/d');
  $btn_class = 'updRegBtn'; 
  $err = $_GET['err'] ?? '';
  $header = '入力';  
  $btn_name = "案件登録";
  $request_dept = ''; //依頼部署
  $request_item_id = '';  //案件コード
  $request_item_name = '';  //依頼案件名

  //一覧画面から来た場合
  if (isset($_POST['process'])) {
    $process = $_POST['process'];
    
    //新規の場合
    if ($process == 'new') {
      //案件Noの自動セット
      $sql = "SELECT MAX(request_item_id) AS MAX_request_item_id FROM request_m";
      $stmt = $pdo->prepare($sql);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($row['MAX_request_item_id'] == '') {
        $request_item_id = 1;
      } else {
        $request_item_id = $row['MAX_request_item_id'] + 1;
      }
    }
    //更新の場合
    else {
      $header = '更新';
      $btn_name = "案件更新";

      $request_dept = $_POST['request_dept']?? '';
      $request_item_id = $_POST['request_item_id']?? '';

      //request_mのデータを取得する
      $sql = "SELECT * FROM request_m WHERE request_dept=:request_dept AND request_item_id=:request_item_id";
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':request_dept', $request_dept);
      $stmt->bindParam(':request_item_id', $request_item_id);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      if (isset($row)) {
        $request_item_name = $row['request_item_name'];
      }
    }
  }
?>