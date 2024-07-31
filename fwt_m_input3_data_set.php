<?php
  // 初期処理
  require_once('function.php');
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
  $today = date('Y/m/d');
  // 初期設定 & データセット
  $btn_name = '';
  $err = $_GET['err']?? '';
  $stop_name = $stop_time = $stop_date = $stop_note = $stop_p = '';
  $stop_note_arr = [];
  $add_date = date('Y-m-d');

  //カレンダー上で指定された日を予約不可日にセットする
  if (isset($_POST['set_date'])) {
    $stop_date = $_POST['set_date'];
  }
  
?>