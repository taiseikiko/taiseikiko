<?php
  // 初期処理
  require_once('function.php');
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

  if (isset($_POST['submit'])) {
    //check duplicate error
    global $pdo;
    $dept_id = $_POST['hid_dept_id'];
    $group_id = $_POST['group'];
    $employee_code = $_POST['employee_code'];
    $role = $_POST['role'];
    $success = true;

    $sql = "SELECT * FROM sq_route_in_dept WHERE dept_id='$dept_id' AND group_id='$group_id' AND employee_code='$employee_code' AND role='$role'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
      $success = reg_or_upd_route_in_dept();
    }
    $redirect_url = ($success && !$row) ? "sr_route_in_dept_input1.php" : "sr_route_in_dept_input2.php?err=" . ($row ? "duplicate" : "execErr");
    echo "<script>
      window.location.href='$redirect_url';
    </script>";
  }
  function reg_or_upd_route_in_dept() {
    $today = date('Y/m/d');
    $success = true;
    global $pdo;

    if (isset($_POST['process'])) {
      try {
        //新規作成or更新の場合
        $process = $_POST['process'];        
        $data = [
          'dept_id' => $_POST['hid_dept_id'],
          'group_id' => $_POST['group'],
          'employee_code' => $_POST['employee_code'],
          'role' => $_POST['role'],
          'add_date' => $today
        ];
        $pdo->beginTransaction();

        if ($process == 'create') {
          //新規作成の場合
          $sql = "INSERT INTO sq_route_in_dept (dept_id, group_id, employee_code, role, add_date) 
          VALUES (:dept_id, :group_id, :employee_code, :role, :add_date)";
          $stmt = $pdo->prepare($sql);
          $stmt->execute($data);
        } else {
          $data1 = [
            'b_dept_id' => $_POST['hid_dept_id'],
            'b_group_id' => $_POST['hid_group_id'],
            'b_employee_code' => $_POST['hid_employee_cd'],
            'b_role' => $_POST['hid_role']
          ];
          //更新の場合
          $del_sql = "DELETE FROM sq_route_in_dept WHERE dept_id=:b_dept_id AND group_id=:b_group_id AND employee_code=:b_employee_code AND role=:b_role";
          $del_stmt = $pdo->prepare($del_sql);
          $del_stmt->execute($data1);

          $sql = "INSERT INTO sq_route_in_dept (dept_id, group_id, employee_code, role, add_date) 
          VALUES (:dept_id, :group_id, :employee_code, :role, :add_date)";
          $stmt = $pdo->prepare($sql);
          $stmt->execute($data);
        }
        $pdo->commit();
      } catch (PDOException $e) {
        $success = false;
        $pdo->rollback();
        error_log("PDO Exception: " . $e->getMessage(),3,'error_log.txt');
      }
      return $success;
    }    
  }
?>