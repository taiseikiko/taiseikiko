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
$route_pattern = $_POST['route_pattern'] ?? '';
$comments = $_POST['comments'] ?? ''; //中止コメント
$success = true;

if (isset($_POST['cancel'])) {
  try {
    $pdo->beginTransaction();
    
    if (!empty($sq_line_no)) {
      // sq_line_no が指定されている場合, sq_detail_trとcancel_log_trの更新
      cu_sq_detail_tr($sq_no, $sq_line_no, $comments);
      cu_cancel_log_tr($sq_no, $sq_line_no, $comments);
    } else {
      // sq_line_no が指定されてない場合
      cu_sq_header_tr($sq_no, $comments);
      update_all_details_and_logs($sq_no, $comments);
    }

    $pdo->commit();
  } catch (PDOException $e) {
    $success = false;
    $pdo->rollback();
    if (strpos($e->getMessage(), 'SQLSTATE[42000]') !== false) {
        error_log("SQL Syntax Error or Access Violation: " . $e->getMessage(), 3, 'error_log.txt');
    } else {
        error_log("PDO Exception: " . $e->getMessage(), 3, 'error_log.txt');
    }
    throw $e;
  }

    //更新処理にエラーがなければメール送信する
  if ($success) {
    include('sq_mail_send6.php');
    delete_sq_route_records($sq_no, $sq_line_no);
    echo '<script type="text/javascript">window.close();</script>';
    exit;
  }
}

//テーブルID : sq_detail_tr / テーブル名称：営業依頼書・明細T/R
function cu_sq_detail_tr($sq_no, $sq_line_no, $comments) {
  global $pdo, $today, $user_code;
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
  global $pdo, $today, $user_code;

  // Check if the record exists
  $checkSql = 'SELECT COUNT(*) FROM cancel_log_tr WHERE sq_no = :sq_no AND sq_line_no = :sq_line_no';
  $checkStmt = $pdo->prepare($checkSql);
  $checkStmt->execute([
      'sq_no' => $sq_no,
      'sq_line_no' => $sq_line_no
  ]);
  
  $exists = $checkStmt->fetchColumn();

  if ($exists) {
      // Update the existing record
      $sql = 'UPDATE cancel_log_tr SET cancel_person = :cancel_person, comments = :comments, upd_date = :upd_date WHERE sq_no = :sq_no AND sq_line_no = :sq_line_no';
      $stmt = $pdo->prepare($sql);
      $stmt->execute([
          'sq_no' => $sq_no,
          'sq_line_no' => $sq_line_no,
          'cancel_person' => $user_code,
          'comments' => $comments,
          'upd_date' => $today
      ]);
  } else {
      // Insert a new record
      $sql = 'INSERT INTO cancel_log_tr (sq_no, sq_line_no, cancel_person, comments, add_date, upd_date) VALUES (:sq_no, :sq_line_no, :cancel_person, :comments, :add_date, :upd_date)';
      $stmt = $pdo->prepare($sql);
      $stmt->execute([
          'sq_no' => $sq_no,
          'sq_line_no' => $sq_line_no,
          'cancel_person' => $user_code,
          'comments' => $comments,
          'add_date' => $today,
          'upd_date' => $today
      ]);
  }
}

// sq_header_trの更新処理
function cu_sq_header_tr($sq_no, $comments) {
  global $pdo, $today, $user_code;

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

//指定された sq_no のすべての詳細とログを更新する
function update_all_details_and_logs($sq_no, $comments) {
  global $pdo, $today, $user_code;

  // processing_statusが受付,確認or承認時のみsq_detail_trを更新する
  $sql = 'UPDATE sq_detail_tr SET processing_dept=00, processing_status=0, abort_person=:abort_person, abort_date=:abort_date, abort_comments=:abort_comments WHERE sq_no=:sq_no AND processing_status IN (1, 3, 4)';
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    'abort_person' => $user_code,
    'abort_date' => $today,
    'abort_comments' => $comments,
    'sq_no' => $sq_no
  ]);

  // cancel_log_tr に更新された情報を送る
  $sql = 'INSERT INTO cancel_log_tr (sq_no, sq_line_no, cancel_person, comments, add_date, upd_date)
          SELECT sq_no, sq_line_no, :cancel_person, :comments, :add_date, :upd_date FROM sq_detail_tr 
          WHERE sq_no = :sq_no AND processing_status IN (1, 3, 4)
          ON DUPLICATE KEY UPDATE 
            cancel_person=VALUES(cancel_person),
            comments=VALUES(comments),
            add_date=VALUES(add_date),
            upd_date=VALUES(upd_date)';
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    'cancel_person' => $user_code,
    'comments' => $comments,
    'add_date' => $today,
    'upd_date' => $today,
    'sq_no' => $sq_no
  ]);
}

// sq_route_trとsq_route_mail_trのレコード削除
function delete_sq_route_records($sq_no, $sq_line_no) {
  global $pdo;

  // sq_route_trから削除
  $sql = 'DELETE FROM sq_route_tr WHERE sq_no=:sq_no AND sq_line_no=:sq_line_no';
  $stmt = $pdo->prepare($sql);
  $stmt->execute(['sq_no' => $sq_no, 'sq_line_no' => $sq_line_no]);

  // sq_route_mail_trから削除
  $sql = 'DELETE FROM sq_route_mail_tr WHERE sq_no=:sq_no AND sq_line_no=:sq_line_no';
  $stmt = $pdo->prepare($sql);
  $stmt->execute(['sq_no' => $sq_no, 'sq_line_no' => $sq_line_no]);
}
?>
