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
  $restoration_comments = $_POST['restoration_comments']; //差し戻しコメント
  $send_back_to_person = $_POST['send_back_to_person'];  //差し戻し先担当者
  $type = $_POST['type']; //client／entrant
  $from = $_POST['from']; //どの画面から来たかをセットされている（資材部。技術部）
  $success = true;

  //処理後、移動する画面を指定する
  $redirect = './card_input1.php';
  //資材部の場合、input2へ移動する
  if ($from == 'procurement') {
    $err_redirect = './card_input2.php?err=exceErr';
  } else {
    $err_redirect = './card_input3.php?err=exceErr&sq_card_no=' . $card_no . '&sq_card_line_no=' . $sq_card_line_no;
  }

  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

  if (isset($_POST['send_back'])) {
    try {
      $pdo->beginTransaction();

      //clientへ差し戻しする場合だけ、headerを更新する
      //テーブルID : card_header_tr
      if ($type == 'client') {
        cu_card_header_tr();
      }

      //テーブルID : card_detail_tr
      cu_card_detail_tr();

      //テーブルID : card_send_back_tr
      cu_card_send_back_tr();
      $pdo->commit();
    } catch (PDOException $e) {
      $success = false;
      $pdo->rollback();
      error_log("PDO Exception: " . $e->getMessage(),3,'error_log.txt');
    }

    //エラーがない場合、メール送信する
    if ($success) {
      //メール送信する
      include('card_mail_send3.php');
    } else {
      echo "<script>window.close();window.opener.location.href='$err_redirect';</script>";
    }
  }

  //テーブルID : card_header_tr
  function cu_card_header_tr() {
    global $pdo;
    global $card_no;
    global $today;

    $data = [
      'card_no' => $card_no,
      'card_status' => '4',                 //状況
      'procurement_approver' => NULL,       //資材部承認者
      'procurement_approver_date' => NULL,  //資材部承認日
      'procurement_approver_comments' => NULL, //資材部承認者コメント
      'upd_date' => $today
    ];
    
    $sql = 'UPDATE card_header_tr SET card_status=:card_status, procurement_approver=:procurement_approver, 
            procurement_approver_date=:procurement_approver_date, procurement_approver_comments=:procurement_approver_comments, upd_date=:upd_date
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
    global $from;
    global $today;

    $data = [
      'sq_card_no' => $card_no,
    ];
      
    //同部署内
    if ($type == 'entrant') {
      $data['sq_card_line_no'] = $sq_card_line_no;
      $data['procurement_status'] = '5';  //資材部Noステータス
      $data['entrant_date'] = NULL;       //入力日
      $data['entrant_comments'] = NULL;   //入力者コメント
      $data['confirmer'] = 	NULL;         //確認者
      $data['confirm_date'] = NULL;       //確認日
      $data['confirmer_comments'] = NULL; //確認者コメント
      $data['approver'] = NULL;           //承認者
      $data['approve_date'] = NULL;       //承認日
      $data['approver_comments'] = NULL;  //承認者コメント
      $data['upd_date'] = $today;

      //更新する
      $sql = 'UPDATE card_detail_tr SET procurement_status=:procurement_status, entrant_date=:entrant_date, entrant_comments=:entrant_comments,
            confirmer=:confirmer, confirm_date=:confirm_date, confirmer_comments=:confirmer_comments, approver=:approver, 
            approve_date=:approve_date, approver_comments=:approver_comments, upd_date=:upd_date
            WHERE sq_card_no=:sq_card_no AND sq_card_line_no=:sq_card_line_no';
    } else {
      if ($from == 'procurement') {
        //資材部以外の部署の場合、headerのclientに差し戻したらdetailテーブルから、該当のレコードを削除する
        $sql = 'DELETE FROM card_detail_tr WHERE sq_card_no=:sq_card_no';
      } else {
        $data['sq_card_line_no'] = $sq_card_line_no;
        //資材部以外の部署の場合、headerのclientに差し戻したらdetailテーブルから、該当のレコードを削除する
        $sql = 'DELETE FROM card_detail_tr WHERE sq_card_no=:sq_card_no AND sq_card_line_no=:sq_card_line_no';
      }
    }   

    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);
  }

    //テーブルID : card_header_tr
  function cu_card_send_back_tr() {
    global $pdo;
    global $card_no;
    global $sq_card_line_no;
    global $user_code;
    global $restoration_comments;
    global $send_back_to_person;
    global $today;
    global $from;

    //資材部から差し戻しする場合、カード行Noに０をセットする
    if ($from == 'procurement') {
      $sq_card_line_no = 0;
    }

    $datas = [
      'sq_card_no' => $card_no,
      'sq_card_line_no' => $sq_card_line_no,
      'restoration_person' => $user_code,             //差し戻し者
      'restoration_date' => $today,                   //差し戻し日
      'restoration_comments' => $restoration_comments,//差し戻しコメント	
      'send_back_to_person' => $send_back_to_person,
      'add_upd_date' => $today
    ];

    $sql = "SELECT * FROM card_send_back_tr WHERE sq_card_no=:sq_card_no AND sq_card_line_no=:sq_card_line_no";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':sq_card_no', $card_no);
    $stmt->bindParam(':sq_card_line_no', $sq_card_line_no);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
      $sql1 = 'INSERT INTO card_send_back_tr (sq_card_no, sq_card_line_no, restoration_person, restoration_date, restoration_comments, send_back_to_person, add_date) 
              VALUES (:sq_card_no, :sq_card_line_no, :restoration_person, :restoration_date, :restoration_comments, :send_back_to_person, :add_upd_date)';
      
    } else {
      $sql1 = 'UPDATE card_send_back_tr SET restoration_person=:restoration_person, restoration_date=:restoration_date, restoration_comments=:restoration_comments,
              send_back_to_person=:send_back_to_person, upd_date=:add_upd_date WHERE sq_card_no=:sq_card_no AND sq_card_line_no=:sq_card_line_no';
    }

    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute($datas);
  }
?>