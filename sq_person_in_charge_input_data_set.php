<?php
  require_once('function.php');
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

  //初期処理
  $group_datas = [];
  $employee_datas = [];
  $group = '';

  //Parent Pageからデータを取得
  $sq_no = isset($_GET['sq_no']) ? $_GET['sq_no'] : '';
  $sq_line_no = isset($_GET['sq_line_no']) ? $_GET['sq_line_no'] : '';
  $record_div = isset($_GET['record_div']) ? $_GET['record_div'] : '';
  $route_pattern = isset($_GET['route_pattern']) ? $_GET['route_pattern'] : '';
  $dept_id = isset($_GET['dept_id']) ? $_GET['dept_id'] : '';
  $title = isset($_GET['title']) ? $_GET['title'] : '';

  //グループプルダウンのデータを取得する
  $group_datas = getGroupDatas();

  //営業依頼書：営業管理部　受付の場合、グループはないので全部の担当者を取得する
  if ($title == 'sm_receipt') {
    $employee_datas = getEmployeeDatas();
  }

  if (isset($_POST['functionName'])) {
    $group_id = $_POST['group_id'];
    $dept_id = $_POST['dept_id'];

    if (!empty($group_id)) {
      $employee_datas = getEmployeeDatas($group_id);
      echo json_encode($employee_datas);
    }    
  }

  function getEmployeeDatas($group_id = '') {
    global $pdo;
    global $dept_id;
    global $title;

    //担当者プルダウンのデータを取得する
    $sql = "SELECT r.employee_code, e.employee_name
            FROM sq_route_in_dept r
            LEFT JOIN employee e ON r.employee_code = e.employee_code
            WHERE dept_id='$dept_id' AND role='1' AND group_id = '$group_id'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $group_datas = $stmt->fetchAll();

    return $group_datas;
  }

  function getGroupDatas() {
    global $pdo;
    global $dept_id;

    $sql = "SELECT text1, text2, text3 
            FROM sq_code
            WHERE code_id='dept_group' AND text1 = '$dept_id'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $group_datas = $stmt->fetchAll();

    return $group_datas;
  }
?>