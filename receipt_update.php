<?php
// 初期処理
require_once('function.php');
session_start();
$dept_code = $_SESSION['department_code'];
$dept_id = getDeptId($dept_code);
// DB接続
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
$today = date('Y/m/d');
$user_code = $_SESSION["login"];

$success = true;

//receipt_input2の登録ボタンを押下場合
if (isset($_POST['submit'])) {
  $status = $_POST['status'];
  $request_form_number = $_POST['request_form_number'];

  try {
    $pdo->beginTransaction();
    //受付の場合
    if ($status == '3') {
      $status_n = '4';
      $err_redirect = 'receipt_input2.php?err=exceErr&title=receipt&request_form_number=' . $request_form_number;

      $sql = "UPDATE request_form_tr SET status=:status, recipent=:recipent, recipent_dept=:recipent_dept, recipi_comment=:recipi_comment, recipt_date=:recipt_date,
                      upd_date=:upd_date WHERE request_form_number=:request_form_number";
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':status', $status_n);
      $stmt->bindParam(':request_form_number', $request_form_number);
      $stmt->bindParam(':recipent', $user_code);
      $stmt->bindParam(':recipent_dept', $dept_code);
      $stmt->bindParam(':recipi_comment', $_POST['recipi_comment']);
      $stmt->bindParam(':recipt_date', $today);
      $stmt->bindParam(':upd_date', $today);
      $stmt->execute();
    }
    //確認の場合
    else if ($status == '4'){
      $status_n = '5';
      $err_redirect = 'receipt_input3.php?err=exceErr&title=receipt&request_form_number=' . $request_form_number;

      $sql = "UPDATE request_form_tr SET status=:status, recipt_comfirmor=:recipt_comfirmor, recipt_comfirmor_comment=:recipt_comfirmor_comment, recipt_comfirm_date=:recipt_comfirm_date,
                      upd_date=:upd_date WHERE request_form_number=:request_form_number";
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':status', $status_n);
      $stmt->bindParam(':request_form_number', $request_form_number);
      $stmt->bindParam(':recipt_comfirmor', $user_code);
      $stmt->bindParam(':recipt_comfirmor_comment', $_POST['recipt_comfirmor_comment']);
      $stmt->bindParam(':recipt_comfirm_date', $today);
      $stmt->bindParam(':upd_date', $today);
      $stmt->execute();
    }
    //承認の場合
    else if ($status == '5'){
      $status_n = '6';
      $err_redirect = 'receipt_input4.php?err=exceErr&title=receipt&request_form_number=' . $request_form_number;

      $sql = "UPDATE request_form_tr SET status=:status, recipt_approver=:recipt_approver, recipt_approval_comment=:recipt_approval_comment	, recipi_approval_date=:recipi_approval_date,
                      upd_date=:upd_date WHERE request_form_number=:request_form_number";
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':status', $status_n);
      $stmt->bindParam(':request_form_number', $request_form_number);
      $stmt->bindParam(':recipt_approver', $user_code);
      $stmt->bindParam(':recipt_approval_comment', $_POST['recipt_approval_comment']);
      $stmt->bindParam(':recipi_approval_date', $today);
      $stmt->bindParam(':upd_date', $today);
      $stmt->execute();
    }
    $pdo->commit();
  } catch (PDOException $e) {
    $success = false;
    $pdo->rollback();
    error_log("PDO Exception: " . $e->getMessage(),3,'error_log.txt');
  }

  //エラーがない場合
  if ($success == true) {
    include('request_mail_send2.php');
  } else {
    echo "<script>window.location.href='$err_redirect'</script>";
  }
}
