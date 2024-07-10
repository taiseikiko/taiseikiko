<?php
  // 初期処理
  require_once('function.php');
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

  //初期処理
  $emp_nm = ''; 
  $employee_code = array();
  $employee_name = array();
  $employee_cd = "";
  $employee_nm = "";
  $kana = array();
  $department_code =array();
  $office_position_code = array();
  $dept_name = array();
  $op_name = array();

  //検索ボタンを押下した場合
  if (isset($_POST['process']) == 'search') {
    $emp_nm = $_POST['emp_nm']?? '';  //社員名

    $sql = "SELECT e.employee_code, e.employee_name, e.kana, e.department_code, e.office_position_code, cd1.text2 AS dept_name, cd2.text1 AS op_name
            FROM employee e
            LEFT JOIN code_master cd1 ON cd1.code_id = 'department' AND cd1.text1 = e.department_code
            LEFT JOIN code_master cd2 ON cd2.code_id = 'office_position' AND cd2.code_no = e.office_position_code
            WHERE e.employee_name LIKE '%$emp_nm%'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $emp_datas = $stmt->fetchAll();
  }

?>