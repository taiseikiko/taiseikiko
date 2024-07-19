<?php
// 初期処理
require_once('function.php');
session_start();
$dept_code = $_SESSION['department_code'];
// DB接続
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
$today = date('Y/m/d');
$user_code = $_SESSION["login"];

$success = true;

//request_input2の登録ボタンを押下場合
if (isset($_POST['submit'])) {
  $process = $_POST['process'];

  $datas = [
    'request_dept' => $dept_code ?? '',                       //案件部署コード
    'request_item_id' => $_POST['request_item_id'] ?? '',     //案件コード
    'request_item_name' => $_POST['request_item_name'] ?? '', //依頼案件名
    'date' => $today
  ];

  try {
    $pdo->beginTransaction();
    //新規の場合
    if ($process == 'new') {

      //request_mへ登録する      
      $sql = "INSERT INTO request_m(request_dept, request_item_id, request_item_name, add_date) VALUES (:request_dept, :request_item_id, :request_item_name, :date)";
    }
    //更新の場合
    else {
      //request_mへ更新する      
      $sql = "UPDATE request_m SET request_item_name=:request_item_name, upd_date=:date WHERE request_dept=:request_dept AND request_item_id=:request_item_id";      
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute($datas);
    $pdo->commit();
  } catch (PDOException $e) {
    $success = false;
    $pdo->rollback();
    error_log("PDO Exception: " . $e->getMessage(),3,'error_log.txt');
  }

  //エラーがない場合
  if ($success == true) {
    echo "<script>window.location.href='request_item_input1.php'</script>";
  } else {
    echo "<script>window.location.href='request_item_input2.php?err=errExec'</script>";
  }
}
