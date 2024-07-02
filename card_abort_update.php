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
        $test_datas = get_mail_recipient_datas();        
        // 中止処理
        process_cancellation();
        
        
        $pdo->commit();
    } catch (PDOException $e) {
        $success = false;
        $pdo->rollback();
        error_log("PDO Exception: " . $e->getMessage(), 3, 'error_log.txt');
    }

    // エラーがない場合、メール送信する
    if (!$success) {               
        echo "<script>window.close();window.opener.location.href='$err_redirect';</script>";
    } else {
        include('card_mail_send4.php');
        echo "<script>window.close();window.opener.location.href='$redirect';</script>";
    }
}

/***
 * 送信先のデータを取得する
 */
function get_mail_recipient_datas()
{
    global $pdo;
    global $entrant;
    global $from;
    global $card_no;
    global $sq_card_line_no;
    global $type;
    $header = [];
    $detail = [];
    $datas = [];

    //headerからclientデータを取得する
    $sql_h = "SELECT e.employee_code, e.employee_name, e.email
                FROM card_header_tr h
                LEFT JOIN employee e ON h.client = e.employee_code
                WHERE h.card_no = :card_no";
    $stmt_h = $pdo->prepare($sql_h);
    $stmt_h->bindParam(':card_no', $card_no);
    $stmt_h->execute();
    while ($row = $stmt_h->fetch(PDO::FETCH_ASSOC)) {
        $header[] = $row;
    }

    //detailからentrantデータを取得する
    $sql_d = "SELECT e.employee_code, e.employee_name, e.email
                FROM card_detail_tr d
                LEFT JOIN employee e ON d.entrant = e.employee_code                
                WHERE d.sq_card_no = :sq_card_no AND d.sq_card_line_no = :sq_card_line_no";
    $stmt_d = $pdo->prepare($sql_d);
    $stmt_d->bindParam(':sq_card_no', $card_no);
    $stmt_d->bindParam(':sq_card_line_no', $sq_card_line_no);
    $stmt_d->execute();
    while ($row = $stmt_d->fetch(PDO::FETCH_ASSOC)) {
        $detail[] = $row;
    }

    //資材部での差し戻しの場合
    if ($from == 'procurement') {
        //→headerのclientにメール送信
        if (!empty($header)) {
            $datas = $header;
        }
    }
    //技術部工事技術部での差し戻しの場合
    else {       
        //detailのentrantにメール送信
        if (!empty($detail)) {
            $datas = $detail;
        }
        
    }

    return $datas;
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

    // Check if record exists in `card_cancel_log_tr`
    $check_sql = 'SELECT COUNT(*) FROM card_cancel_log_tr WHERE sq_card_no = :sq_card_no AND sq_card_line_no = :sq_card_line_no';
    $stmt = $pdo->prepare($check_sql);
    $stmt->execute(['sq_card_no' => $card_no, 'sq_card_line_no' => 0]);
    $exists = $stmt->fetchColumn();

    if ($exists) {
        // Update existing record
        $update_sql = 'UPDATE card_cancel_log_tr
                       SET cancel_person = :cancel_person, comments = :comments, upd_date = :upd_date
                       WHERE sq_card_no = :sq_card_no AND sq_card_line_no = :sq_card_line_no';
        $stmt = $pdo->prepare($update_sql);
        $stmt->execute([
            'cancel_person' => $user_code,
            'comments' => $abort_comments,
            'upd_date' => $today,
            'sq_card_no' => $card_no,
            'sq_card_line_no' => 0
        ]);
    } else {
        // Insert new record
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
    }

    // Delete from `card_header_tr`
    $header_sql = 'DELETE FROM card_header_tr WHERE card_no = :card_no';
    $stmt = $pdo->prepare($header_sql);
    $stmt->execute(['card_no' => $card_no]);

    // Delete from `card_detail_tr`
    $detail_sql = 'DELETE FROM card_detail_tr WHERE sq_card_no = :sq_card_no';
    $stmt = $pdo->prepare($detail_sql);
    $stmt->execute(['sq_card_no' => $card_no]);
}

// その他の部署の場合の中止処理
function delete_card_detail_tr() {
    global $pdo, $card_no, $sq_card_line_no, $abort_comments, $user_code, $today;

    // Check if record exists in `card_cancel_log_tr`
    $check_sql = 'SELECT COUNT(*) FROM card_cancel_log_tr WHERE sq_card_no = :sq_card_no AND sq_card_line_no = :sq_card_line_no';
    $stmt = $pdo->prepare($check_sql);
    $stmt->execute(['sq_card_no' => $card_no, 'sq_card_line_no' => $sq_card_line_no]);
    $exists = $stmt->fetchColumn();

    if ($exists) {
        // Update existing record
        $update_sql = 'UPDATE card_cancel_log_tr
                       SET cancel_person = :cancel_person, comments = :comments, upd_date = :upd_date
                       WHERE sq_card_no = :sq_card_no AND sq_card_line_no = :sq_card_line_no';
        $stmt = $pdo->prepare($update_sql);
        $stmt->execute([
            'cancel_person' => $user_code,
            'comments' => $abort_comments,
            'upd_date' => $today,
            'sq_card_no' => $card_no,
            'sq_card_line_no' => $sq_card_line_no
        ]);
    } else {
        // Insert new record
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
    }

    // Delete from `card_detail_tr`
    $detail_sql = 'DELETE FROM card_detail_tr WHERE sq_card_no = :sq_card_no AND sq_card_line_no = :sq_card_line_no';
    $stmt = $pdo->prepare($detail_sql);
    $stmt->execute(['sq_card_no' => $card_no, 'sq_card_line_no' => $sq_card_line_no]);
}

?>
