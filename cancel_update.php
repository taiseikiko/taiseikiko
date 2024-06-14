<?php
  require_once('function.php');
  session_start();
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
  $today = date('Y/m/d');
  $user_code = $_SESSION["login"];
  $sq_no = $_POST['sq_no'] ?? '';
  $sq_line_no = $_POST['sq_line_no'] ?? '';
  $title = $_POST['title'] ?? '';
  $route_pattern = $_POST['route_pattern'] ?? '';
  $comments = $_POST['comments']; //中止コメント
  $success = true;

  if (isset($_POST['cancel'])) {   
    try {
      $pdo->beginTransaction();
      //テーブルID : sq_detail_tr / テーブル名称：営業依頼書・明細T/R
      cu_sq_detail_tr();

      //中止処理が行われた場合、cancel_log_trを更新する。
      cu_cancel_log_tr();
      

      $pdo->commit();
    } catch (PDOException $e) {
      $success = false;
      if (strpos($e->getMessage(), 'SQLSTATE[42000]') !== false) {
        error_log("SQL Syntax Error or Access Violation: " . $e->getMessage(),3,'error_log.txt');
      } else {
        $pdo->rollback();
        throw($e);
        error_log("PDO Exception: " . $e->getMessage(),3,'error_log.txt');
      }
    }

    //更新処理にエラーがなければメール送信する
    if ($success) {
      include('sq_mail_send6.php');
    }
  }

  //テーブルID : sq_detail_tr / テーブル名称：営業依頼書・明細T/R
  function cu_sq_detail_tr() {
    global $pdo;
    global $today;
    global $sq_no;
    global $sq_line_no;
    global $user_code;
    global $comments;
    global $route_pattern;
    $processing_dept = 00;

       

    $data = [
      'sq_no' => $sq_no,
      'sq_line_no' => $sq_line_no,
      'processing_dept' => $processing_dept,
      'processing_status' => NULL,
      'abort_person' => $user_code,
      'abort_date' => $today,
      'abort_comments' => $comments
    ];

    $sql = 'UPDATE sq_detail_tr SET processing_dept=:processing_dept, processing_status=:processing_status, abort_person=:abort_person,
          abort_date=:abort_date, abort_comments=:abort_comments WHERE sq_no=:sq_no AND sq_line_no=:sq_line_no';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);
  }

  //中止処理が行われた場合、cancel_log_trを更新する。
  function cu_cancel_log_tr() {
    global $pdo;
    global $today;
    global $sq_no;
    global $sq_line_no;
    global $user_code;
    global $comments;

    $datas = [
      'sq_no' => $sq_no,
      'sq_line_no' => $sq_line_no,
      'cancel_person' => $user_code,
      'comments' => $comments,
      'add_date' => '',
      'upd_date' => ''
    ];

    $sql = "SELECT * FROM cancel_log_tr WHERE sq_no=:sq_no AND sq_line_no=:sq_line_no";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':sq_no', $sq_no);
    $stmt->bindParam(':sq_line_no', $sq_line_no);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
      $datas['add_date'] = $today;
      $sql1 = 'INSERT INTO cancel_log_tr (sq_no, sq_line_no, cancel_person, comments, add_date) VALUES ( :sq_no, :sq_line_no, :cancel_person, :comments, :add_date)';
      
    } else {
    //   $datas['upd_date'] = $today;
    //   $sql1 = 'UPDATE cancel_log_tr SET cancel_person=:cancel_person, comments=:comments, upd_date=:upd_date WHERE sq_no=:sq_no AND sq_line_no=:sq_line_no';
    }

    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute($datas);
  }

  
?>