<?php
  require_once('function.php');
  session_start();
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $today = date('Y/m/d');
  $user_code = $_SESSION["login"];
  $dw_no = $_POST['dw_no'] ?? '';    //依頼書No  
  $restoration_comments = $_POST['restoration_comments']; //差し戻しコメント
  $send_back_to_person = $_POST['send_back_to_person'];  //差し戻し先担当者
  $type = $_POST['type']; //client／entrant
  $success = true;

  //処理後、移動する画面を指定する
  $redirect = './dw_input1.php';
  //資材部の場合、input2へ移動する
  
  $err_redirect = './dw_input2.php?err=exceErr&dw_no=' . $dw_no;


  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

  if (isset($_POST['send_back'])) {
    try {
      $pdo->beginTransaction();

      //clientへ差し戻しする場合、
      cu_dw_management_tr();


      //テーブルID : card_send_back_tr
      cu_dw_send_back_tr();
      $pdo->commit();
    } catch (PDOException $e) {
      $success = false;
      $pdo->rollback();
      error_log("PDO Exception: " . $e->getMessage(),3,'error_log.txt');
    }

    //エラーがない場合、メール送信する
    if ($success) {
      //メール送信する
    //   echo "Success";
      include('dw_mail_send2.php');
    } else {
      echo "<script>window.close();window.opener.location.href='$err_redirect';</script>";
    }
  }

  //テーブルID : card_header_tr
  function cu_dw_management_tr() {
    global $pdo;
    global $dw_no;
    global $today;

    $data = [
      'dw_no' => $dw_no,
      'dw_status' => '3',                 //状況
      'upd_date' => $today
    ];
    
    $sql = 'UPDATE dw_management_tr SET dw_status=:dw_status,
                  upd_date=:upd_date
            WHERE dw_no=:dw_no';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);
  }


    //テーブルID : card_header_tr
  function cu_dw_send_back_tr() {
    global $pdo;
    global $dw_no;
    global $user_code;
    global $restoration_comments;
    global $send_back_to_person;
    global $today;
    

    $datas = [
      'dw_no' => $dw_no,
      'restoration_person' => $user_code,             //差し戻し者
      'restoration_date' => $today,                   //差し戻し日
      'restoration_comments' => $restoration_comments,//差し戻しコメント	
      'send_back_to_person' => $send_back_to_person,
      'add_upd_date' => $today
    ];

    $sql = "SELECT * FROM dw_send_back_tr WHERE dw_no=:dw_no";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':dw_no', $dw_no);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
      $sql1 = 'INSERT INTO dw_send_back_tr (dw_no, restoration_person, restoration_date, restoration_comments, send_back_to_person, add_date) 
              VALUES (:dw_no, :restoration_person, :restoration_date, :restoration_comments, :send_back_to_person, :add_upd_date)';
      
    } else {
      $sql1 = 'UPDATE dw_send_back_tr SET restoration_person=:restoration_person, restoration_date=:restoration_date, restoration_comments=:restoration_comments,
              send_back_to_person=:send_back_to_person, upd_date=:add_upd_date WHERE dw_no=:dw_no';
    }

    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute($datas);
  }
?>