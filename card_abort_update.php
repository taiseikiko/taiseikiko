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
$abort_comments = $_POST['abort_comments'] ?? ''; //中止コメント
$type = $_POST['type'] ?? ''; //client／entrant
$from = $_POST['from'] ?? ''; //どの画面から来たかをセットされている
$success = true;
$success_mail = true;

// 処理後、移動する画面を指定する
$redirect = './card_input1.php';
// 資材部の場合、input2へ移動する
if ($from == 'procurement') {
    $err_redirect = './card_input2.php?err=exceErr';
} else {
    $err_redirect = './card_input3.php?err=exceErr';
}

if (isset($_POST['abort'])) {
    try {
        $pdo->beginTransaction();
        
        // 中止処理
        // process_cancellation();
        
        $pdo->commit();
    } catch (PDOException $e) {
        $success = false;
        $pdo->rollback();
        error_log("PDO Exception: " . $e->getMessage(), 3, 'error_log.txt');
    }

    // エラーがない場合、メール送信する
    if ($success) {
        // メール送信する
        include('card_mail_send4.php');
        // echo "<script>window.close();window.opener.location.href='$redirect';</script>";
    } else {
        echo "<script>window.close();window.opener.location.href='$err_redirect';</script>";
    }
}

// 中止処理
function process_cancellation() {
    global $pdo, $card_no, $sq_card_line_no, $abort_comments, $user_code, $today, $from, $type;

    if ($from == 'procurement') {
        // 資材部の場合
        delete_card_header_tr();
    } else {
        // その他の部署の場合
        delete_card_detail_tr();
    }
}

// 資材部の場合の中止処理
function delete_card_header_tr() {
    global $pdo, $card_no, $abort_comments, $user_code, $today;

    // 1. `card_cancel_log_tr`テーブルに記録する
    $log_data = [
        'sq_card_no' => $card_no,
        'sq_card_line_no' => 0, // 行番号は0
        'cancel_person' => $user_code,
        'comments' => $abort_comments,
        'add_date' => $today,
        'upd_date' => $today
    ];
    $log_sql = 'INSERT INTO card_cancel_log_tr (sq_card_no, sq_card_line_no, cancel_person, comments, add_date, upd_date)
                VALUES (:sq_card_no, :sq_card_line_no, :cancel_person, :comments, :add_date, :upd_date)';
    $stmt = $pdo->prepare($log_sql);
    $stmt->execute($log_data);

    // 2. `card_header_tr`テーブルから該当レコードを削除する
    $header_sql = 'DELETE FROM card_header_tr WHERE card_no = :card_no';
    $stmt = $pdo->prepare($header_sql);
    $stmt->execute(['card_no' => $card_no]);

    // 3. `card_detail_tr`テーブルから該当レコードを削除する
    $detail_sql = 'DELETE FROM card_detail_tr WHERE sq_card_no = :sq_card_no';
    $stmt = $pdo->prepare($detail_sql);
    $stmt->execute(['sq_card_no' => $card_no]);
}

// その他の部署の場合の中止処理
function delete_card_detail_tr() {
    global $pdo, $card_no, $sq_card_line_no, $abort_comments, $user_code, $today;

    // 1. `card_cancel_log_tr`テーブルに記録する
    $log_data = [
        'sq_card_no' => $card_no,
        'sq_card_line_no' => $sq_card_line_no, // 行番号は利用可能
        'cancel_person' => $user_code,
        'comments' => $abort_comments,
        'add_date' => $today,
        'upd_date' => $today
    ];
    $log_sql = 'INSERT INTO card_cancel_log_tr (sq_card_no, sq_card_line_no, cancel_person, comments, add_date, upd_date)
                VALUES (:sq_card_no, :sq_card_line_no, :cancel_person, :comments, :add_date, :upd_date)';
    $stmt = $pdo->prepare($log_sql);
    $stmt->execute($log_data);

    // 2. `card_detail_tr`テーブルから該当レコードを削除する
    $detail_sql = 'DELETE FROM card_detail_tr WHERE sq_card_no = :sq_card_no AND sq_card_line_no = :sq_card_line_no';
    $stmt = $pdo->prepare($detail_sql);
    $stmt->execute(['sq_card_no' => $card_no, 'sq_card_line_no' => $sq_card_line_no]);
}
?>
