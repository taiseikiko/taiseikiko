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

//request_input2の登録ボタンを押下場合
if (isset($_POST['submit'])) {
  $process = $_POST['process'];  

  $datas = [
    'request_case_dept' => $dept_id ?? '',                   //依頼案件部署コード
    'request_item_name' => $_POST['request_item_name'] ?? '',  //依頼案件名
    'date' => $today
  ];

  // try {
    $pdo->beginTransaction();
    //新規の場合
    if ($process == 'new') {
      $datas['request_case_item_id'] = '1'; //案件No
      $datas['request_case_person'] = $user_code;                 //依頼案件担当者
      //request_mへ登録する
      $sql = "INSERT INTO request_m (request_case_dept, request_case_item_id, request_case_person, request_item_name, add_date)
                VALUES (:request_case_dept, :request_case_item_id, :request_case_person, :request_item_name, :date)";

      $stmt = $pdo->prepare($sql);
      $stmt->execute($datas);
    } else {
      //request_mへ更新する
      $datas['request_case_item_id'] = '1'; //案件No
      $sql = "UPDATE request_m SET request_item_name=:request_item_name, upd_date=:date WHERE request_case_dept=:request_case_dept AND request_case_item_id=:request_case_item_id";
      $stmt = $pdo->prepare($sql);
      $stmt->execute($datas);
    }
    $pdo->commit();
  // } catch (PDOException $e) {
  //   $success = false;
  //   $pdo->rollback();
  //   error_log("PDO Exception: " . $e->getMessage(),3,'error_log.txt');
  // }

  //エラーがない場合
  if ($success == true) {
    // include('request_mail_send1.php');
  } else {
    echo "<script>window.location.href='request_item_input2.php?err=exceErr'</script>";
  }
}
