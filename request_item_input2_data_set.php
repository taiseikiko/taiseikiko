<?php
  // 初期処理
  require_once('function.php');
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
  // 初期設定 & データセット
  $today = date('Y/m/d');
  $process = '';
  $request_item_name = '';
  $request_case_form_url = '';
  $btn_class = 'updRegBtn';  
  $err = $_GET['err'] ?? '';  
  
  //一覧画面から来た場合
  if (isset($_POST['process'])) {
    $process = $_POST['process'];
    //新規の場合
    if ($process == 'new') {
      $header = '入力';
      $btn_name = "案件登録";
    } else {
      $header = '更新';
      $btn_name = "案件更新";
      $request_case_dept = $_POST['request_case_dept']?? '';
      $request_case_item_id  = $_POST['request_case_item_id ']?? '';

      $sql = "SELECT * FROM request_m WHERE request_case_dept=:request_case_dept AND request_case_item_id=:request_case_item_id";
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':request_case_dept', $request_case_dept);
      $stmt->bindParam(':request_case_item_id', $request_case_item_id);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      print_r($row);
    }
  }

?>