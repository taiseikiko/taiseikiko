<?php
  require_once('function.php');
  session_start();
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
  $today = date('Y/m/d');
  $user_code = $_SESSION["login"];
  $sq_no = $_POST['sq_no'] ?? '';
  $sq_line_no = $_POST['sq_line_no'] ?? '';
  $dept_id = $_POST['dept_id'] ?? '';
  $title = $_POST['title'] ?? '';
  $comments = $_POST['comments']; //中止コメント
  $success = true;

  if (isset($_POST['cancel'])) {   
    try {
      $pdo->beginTransaction();
      
      if (!empty($sq_line_no)) {
        //テーブルID : sq_detail_tr / テーブル名称：営業依頼書・明細T/R
        cu_sq_detail_tr($sq_no, $sq_line_no, $comments);
  
        //中止処理が行われた場合、cancel_log_trを更新する。
        cu_cancel_log_tr($sq_no, $sq_line_no, $comments);
      
      } else {
        cu_sq_header_tr($sq_no, $comments); 
        
        //テーブルID : sq_detail_tr / テーブル名称：営業依頼書・明細T/R
        cu_sq_detail_tr($sq_no, $sq_line_no, $comments);
  
        //中止処理が行われた場合、cancel_log_trを更新する。
        cu_cancel_log_tr($sq_no, $sq_line_no, $comments);
      }

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
      // delete_sq_route_records($sq_no, $sq_line_no);
      include('sq_mail_send6.php');
    }
  }

  //テーブルID : sq_detail_tr / テーブル名称：営業依頼書・明細T/R
  function cu_sq_detail_tr($sq_no, $sq_line_no, $comments) {
    global $pdo;
    global $today;
    global $sq_no;
    global $sq_line_no;
    global $user_code;
    global $comments;
    $processing_dept = 00;

       

    $data = [
      'sq_no' => $sq_no,
      'sq_line_no' => $sq_line_no,
      'processing_dept' => $processing_dept,
      'processing_status' => 0,
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
  function cu_cancel_log_tr($sq_no, $sq_line_no, $comments) {
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
        'add_date' => $today,
        'upd_date' => $today 
    ];

    $sql = "SELECT * FROM cancel_log_tr WHERE sq_no=:sq_no AND sq_line_no=:sq_line_no";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':sq_no', $sq_no);
    $stmt->bindParam(':sq_line_no', $sq_line_no);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        $sql1 = 'INSERT INTO cancel_log_tr (sq_no, sq_line_no, cancel_person, comments, add_date, upd_date) VALUES (:sq_no, :sq_line_no, :cancel_person, :comments, :add_date, :upd_date)';
        $stmt1 = $pdo->prepare($sql1);
        $stmt1->execute([
            'sq_no' => $sq_no,
            'sq_line_no' => $sq_line_no,
            'cancel_person' => $user_code,
            'comments' => $comments,
            'add_date' => $today,
            'upd_date' => $today
        ]);
    } else {
        $sql1 = 'UPDATE cancel_log_tr SET cancel_person=:cancel_person, comments=:comments, upd_date=:upd_date WHERE sq_no=:sq_no AND sq_line_no=:sq_line_no';
        $stmt1 = $pdo->prepare($sql1);
        $stmt1->execute([
            'cancel_person' => $user_code,
            'comments' => $comments,
            'upd_date' => $today,
            'sq_no' => $sq_no,
            'sq_line_no' => $sq_line_no
        ]);
    }
  }

  // sq_header_trの更新処理
  function cu_sq_header_tr($sq_no, $comments) {
    global $pdo;
    global $today;
    global $sq_no;
    global $user_code;
    global $comments;

    $data = [
      'sq_no' => $sq_no,
      'abort_person' => $user_code,
      'abort_date' => $today,
      'abort_comments' => $comments
    ];

    $sql = 'UPDATE sq_header_tr SET abort_person=:abort_person, abort_date=:abort_date, abort_comments=:abort_comments WHERE sq_no=:sq_no';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);
  }

  // sq_route_trとsq_route_mail_trのレコード削除
  function delete_sq_route_records($sq_no, $sq_line_no) {
    global $pdo;

    // Delete from sq_route_tr
    $sql = 'DELETE FROM sq_route_tr WHERE sq_no=:sq_no AND sq_line_no=:sq_line_no';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['sq_no' => $sq_no, 'sq_line_no' => $sq_line_no]);

    // Delete from sq_route_mail_tr
    $sql = 'DELETE FROM sq_route_mail_tr WHERE sq_no=:sq_no AND sq_line_no=:sq_line_no';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['sq_no' => $sq_no, 'sq_line_no' => $sq_line_no]);
  }
?>