<?php
// 初期処理
require_once('function.php');
// DB接続
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
$today = date('Y/m/d');
$error = false;
global $pdo;
//更新あるいは登録ボタンを押下場合
if (isset($_POST['submit'])) {
  try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //新規作成か更新かを$_processにセットする
    $process = $_POST['process'] ?? '';
    $dept = $_POST['hid_dept_id'];
    $employee_code = $_POST['employee_code'];

    $data = [
      'department_code' => $dept,
      'employee_code' => $employee_code,
      'role' => $_POST['role'],
      'add_date' => $today
    ];

    //重複チェック
    $exist = checkDuplicate_In_Dw_Route_In_Dept($data);

    if ($exist) {
      echo "<script>
          window.location.href='dw_route_in_dept_input2.php?err=dupErr&process=" . $process . "&dept=" . $dept . "'</script>";
    } else {
      $pdo->beginTransaction();
      //新規作成の場合
      if ($process == 'create') {
        //新規作成の場合
        $sql = "INSERT INTO dw_route_in_dept (department_code, employee_code, role, add_date) 
          VALUES (:department_code, :employee_code, :role, :add_date)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);
      }
      //更新の場合
      else {
        $data1 = [
          'b_department_code' => $_POST['hid_dept_id'],
          'b_employee_code' => $_POST['hid_employee_cd'],
          'b_role' => $_POST['hid_role']
        ];
        //更新の場合
        //先に削除してからまた登録する
        $del_sql = "DELETE FROM dw_route_in_dept WHERE department_code=:b_department_code AND employee_code=:b_employee_code AND role=:b_role";
        $del_stmt = $pdo->prepare($del_sql);
        $del_stmt->execute($data1);

        $sql = "INSERT INTO dw_route_in_dept (department_code, employee_code, role, add_date) 
          VALUES (:department_code, :employee_code, :role, :add_date)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);
      }
      $pdo->commit();
    }
  } catch (PDOException $e) {
    $error = true;
    // エラーをログに記録する    
    if (strpos($e->getMessage(), 'SQLSTATE[42000]') !== false) {
      error_log("SQL Syntax Error or Access Violation: " . $e->getMessage(), 3, 'error_log.txt');
    } else if (strpos($e->getMessage(), 'SQLSTATE[42S02]') !== false) {
      error_log("PDOException: " . $e->getMessage(), 3, 'error_log.txt');
    } else {
      $pdo->rollback();
      throw ($e);
      error_log("PDOException: " . $e->getMessage(), 3, 'error_log.txt');
    }
  }
  //エラーがある場合
  if ($error) {
    echo "<script>window.location.href='dw_route_in_dept_input2.php?err=exceErr'</script>";
  } else {
    echo "<script>window.location.href='dw_route_in_dept_input1.php'</script>";
  }
}

function checkDuplicate_In_Dw_Route_In_Dept($data)
{
  global $pdo;
  $dept_id = $data['department_code'];
  $employee_code = $data['employee_code'];
  $role = $data['role'];

  $sql = "SELECT * FROM dw_route_in_dept WHERE department_code='$dept_id' AND employee_code='$employee_code' AND role='$role'";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if (!$row) {
    $exist = false;
  } else {
    $exist = true;
  }
  return $exist;
}
