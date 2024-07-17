<?php
require_once('function.php');
session_start();
// DB接続
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$today = date('Y/m/d');
$user_code = $_SESSION["login"];
$request_form_number = $_POST['request_form_number'] ?? '';    //依頼書No  
$restoration_comments = $_POST['restoration_comments']; //差し戻しコメント
$send_back_to_person = $_POST['send_back_to_person'];  //差し戻し先担当者
$type = $_POST['type']; //client／entrant
$success = true;
$from = isset($_GET['from']) ? $_GET['from'] : 'request';

//処理後、移動する画面を指定する
// $redirect = './request_input1.php';
if ($from === 'request') {
  $redirect = './request_input1.php';
  $err_redirect = './request_input4.php?err=exceErr&request_form_number=' . $request_form_number;
} else if ($from === 'receipt') {
  $redirect = './receipt_input1.php';
  $err_redirect = './receipt_input2.php?err=exceErr&request_form_number=' . $request_form_number;
}
//資材部の場合、input2へ移動する



$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

if (isset($_POST['send_back'])) {
  try {
    $pdo->beginTransaction();

    //request_personへ差し戻しする場合、
    cu_request_form_tr();


    //テーブルID : card_send_back_tr
    cu_request_send_back_tr();
    $pdo->commit();
  } catch (PDOException $e) {
    $success = false;
    $pdo->rollback();
    error_log("PDO Exception: " . $e->getMessage(), 3, 'error_log.txt');
  }

  //エラーがない場合、メール送信する
  if ($success) {
    //メール送信する
    include('request_mail_send3.php');
  } else {
    echo "<script>window.close();window.opener.location.href='$err_redirect';</script>";
  }
}

//テーブルID : card_header_tr
function cu_request_form_tr()
{
  global $pdo;
  global $request_form_number;
  global $today;

  $data = [
    'request_form_number' => $request_form_number,
    'status' => '7',                 //状況
    'upd_date' => $today
  ];

  $sql = 'UPDATE request_form_tr SET status=:status,
                  upd_date=:upd_date
            WHERE request_form_number=:request_form_number';

  $stmt = $pdo->prepare($sql);
  $stmt->execute($data);
}


//テーブルID : card_header_tr
function cu_request_send_back_tr()
{
  global $pdo;
  global $request_form_number;
  global $user_code;
  global $restoration_comments;
  global $send_back_to_person;
  global $today;


  $datas = [
    'request_form_number' => $request_form_number,
    'restoration_person' => $user_code,             //差し戻し者
    'restoration_date' => $today,                   //差し戻し日
    'restoration_comments' => $restoration_comments, //差し戻しコメント	
    'send_back_to_person' => $send_back_to_person,
    'add_upd_date' => $today
  ];

  $sql = "SELECT * FROM request_form_send_back_tr WHERE request_form_number=:request_form_number";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':request_form_number', $request_form_number);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if (!$row) {
    $sql1 = 'INSERT INTO request_form_send_back_tr (request_form_number, restoration_person, restoration_date, restoration_comments, send_back_to_person, add_date) 
              VALUES (:request_form_number, :restoration_person, :restoration_date, :restoration_comments, :send_back_to_person, :add_upd_date)';
  } else {
    $sql1 = 'UPDATE request_form_send_back_tr SET restoration_person=:restoration_person, restoration_date=:restoration_date, restoration_comments=:restoration_comments,
              send_back_to_person=:send_back_to_person, upd_date=:add_upd_date WHERE request_form_number=:request_form_number';
  }

  $stmt1 = $pdo->prepare($sql1);
  $stmt1->execute($datas);
}
