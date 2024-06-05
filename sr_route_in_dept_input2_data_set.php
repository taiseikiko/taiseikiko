<?php
  // 初期処理
  require_once('function.php');
  include("sr_route_in_dept_update.php");
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

  // 初期設定 & データセット
  $deptList = [];           //部署
  $btn_name = '登録';
  $employee_name = '';
  $department_name = '';
  $office_position_name = '';
  $role = '';
  $group_id = '';
  $dept_id = '';
  $employee_code = '';

  //部署リスト
  $deptList = getDropdownDataOfDept('sq_dept');                  

  //一覧画面からPOSTを取得
  if (isset($_POST['process'])) {
    $process = $_POST['process'];

    $dept_id = $_POST['dept'];
    
    //新規作成の場合
    if ($process !== 'create') {
      //更新の場合
      $btn_name = '更新';
      $group_id = isset($_POST['group_id']) ? $_POST['group_id'] : '';
      $employee_code = $_POST['employee_code'];
      $role = $_POST['role'];

      if ($employee_code !== '') {
        $cpDatas = getEmpDatasByEmpCd($employee_code);
        if (isset($cpDatas)) {
          $employee_name = $cpDatas['employee_name'];
          $department_name = $cpDatas['dept_name'];
          $office_position_name = $cpDatas['role_name'];
        }
      }
    }
  }

  if (isset($_POST['submit'])) {
    //check duplicate error
    global $pdo;
    $dept_id = $_POST['hid_dept_id'];
    $group_id = $_POST['group'];
    $employee_code = $_POST['employee_code'];
    $role = $_POST['role'];

    $sql = "SELECT * FROM sq_route_in_dept WHERE dept_id='$dept_id' AND group_id='$group_id' AND employee_code='$employee_code' AND role='$role'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
      $success = reg_or_upd_route_in_dept();
      if ($success) {
        echo "<script>
          window.location.href='sr_route_in_dept_input1.php';
        </script>";
      } else {
        echo "<script>
          window.onload = function() { alert('失敗しました。'); }
        </script>";
      }
    } else {
      echo "<script>
        window.onload = function() { alert('重複の登録があります。'); }
      </script>";
    }
  }

  if (isset($_POST['function_name'])) {
    $result = getDropdownDataOfGroup();
    echo json_encode($result);
  }

  function getDropdownDataOfDept($code_id) {
    global $pdo;
    $sql = "SELECT text1, text2 FROM sq_code WHERE code_id='$code_id'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $datas = $stmt->fetchAll();

    return $datas;
  }

  function getDropdownDataOfGroup() {
    global $pdo;
    $dept_code = $_POST['dept_code'];
    $code_id = $_POST['code_id'];
    $datas = [];

    $sql = "SELECT text2, text3 FROM sq_code WHERE code_id='$code_id' AND text1='$dept_code'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $datas[] = $row;
    }
    return $datas;
  }

  function getRouteInDepartDatas() {
    global $pdo;
    $sql = "SELECT rd.dept_id, rd.group_id, rd.employee_code, rd.role, cd.text2 as dept_name, cp.text3 as group_name, e.employee_name,
    CASE rd.role
      WHEN 0 THEN '受付'
      WHEN 1 THEN '入力'
      WHEN 2 THEN '確認'
      WHEN 3 THEN '承認'
    END as role_name
    FROM sq_route_in_dept rd
    LEFT JOIN sq_code cd ON rd.dept_id = cd.text1 AND cd.code_id = 'sq_dept' 
    LEFT JOIN sq_code cp ON rd.group_id = cp.text1 AND cp.code_id = 'dept_group'
    LEFT JOIN employee e ON e.employee_code = rd.employee_code";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $datas = $stmt->fetchAll();
    return $datas;
  }

  function getEmpDatasByEmpCd($employee_code) {
    global $pdo;

    $sql = "SELECT e.employee_name, cmd.text2 AS dept_name, cmp.text1 AS role_name
    FROM employee e
    LEFT JOIN code_master cmd
    ON e.department_code = cmd.text1
    AND cmd.code_id = 'department'
    LEFT JOIN code_master cmp
    ON e.office_position_code = cmp.code_no
    AND cmp.code_id = 'office_position'
    WHERE employee_code = '$employee_code'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

?>