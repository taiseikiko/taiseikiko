<?php
  require_once('function.php');
  session_start();
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $today = date('Y/m/d');
  $user_code = $_SESSION["login"];
  $card_no = $_POST['sq_card_no'] ?? '';    //依頼書No  
  $sq_card_line_no = $_POST['sq_card_line_no'] ?? ''; //依頼書行No
  $abort_comments = $_POST['abort_comments']; //中止コメント
  $send_back_to_person = $_POST['send_back_to_person'];  //中止先担当者
  $type = $_POST['type']; //client／entrant
  $from = $_POST['from']; //どの画面から来たかをセットされている
  $success = true;
  $success_mail = true;

  //処理後、移動する画面を指定する
  $redirect = './card_input1.php';
  //資材部の場合、input2へ移動する
  if ($from == 'procurement') {
    $err_redirect = './card_input2.php?err=exceErr';
  } else {
    $err_redirect = './card_input3.php?err=exceErr';
  }

 $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  if (isset($_POST['abort'])) {
    try {
      $pdo->beginTransaction();
      //テーブルID : card_header_tr
      cu_card_header_tr();

      //テーブルID : card_detail_tr
      if ($from == 'other_dept') {
        cu_card_detail_tr();
      }
      $pdo->commit();
    } catch (PDOException $e) {
      $success = false;
      $pdo->rollback();
      error_log("PDO Exception: " . $e->getMessage(),3,'error_log.txt');
    }

    //エラーがない場合、メール送信する
    if ($success) {
      //メール送信する
      include('card_mail_send4.php');
    } else {
      echo "<script>window.close();window.opener.location.href='$err_redirect';</script>";
    }
  }

  //テーブルID : card_header_tr
  function cu_card_header_tr() {
    global $pdo;
    global $card_no;
    global $abort_comments;
    global $user_code;
    global $today;

    $data = [
      'card_no' => $card_no,
      'card_status' => '5',                 //状況
      'abort_person' => $user_code,   //中止者
      'abort_date' => $today,         //中止日
      'abort_comments' => $abort_comments, //中止コメント	
      'upd_date' => $today
    ];
      
    $sql = 'UPDATE card_header_tr SET card_status=:card_status, abort_person=:abort_person, abort_date=:abort_date,
            abort_comments=:abort_comments, upd_date=:upd_date
            WHERE card_no=:card_no';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);
  }

  //テーブルID : card_detail_tr
  function cu_card_detail_tr() {
    global $pdo;
    global $card_no;
    global $sq_card_line_no;
    global $type;
    global $today;

    $data = [
      'sq_card_no' => $card_no,
      'sq_card_line_no' => $sq_card_line_no,
    ];
      
    //同部署内
    if ($type == 'entrant') {
      $data['procurement_status'] = '5';//資材部Noステータス
      $data['upd_date'] = $today;

      //更新する
      $sql = 'UPDATE card_detail_tr SET procurement_status=:procurement_status, upd_date=:upd_date
            WHERE sq_card_no=:sq_card_no AND sq_card_line_no=:sq_card_line_no';
    } else {
      //資材部以外の部署の場合、headerのclientに中止たらdetailテーブルから、該当のレコードを削除する
      $sql = 'DELETE FROM card_detail_tr WHERE sq_card_no=:sq_card_no AND sq_card_line_no=:sq_card_line_no';
    }   

    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);
  }
?>