<?php
require_once('function.php');
session_start();

// DB接続
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
$today = date('Y/m/d');
$user_code = $_SESSION["login"];
$fwt_m_no = $_POST['fwt_m_no'] ?? '';
$comments = $_POST['comments'] ?? ''; //中止コメント
$success = true;

if (isset($_POST['cancel'])) {
  try {
    $pdo->beginTransaction();
    


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
    
  }
}
?>
