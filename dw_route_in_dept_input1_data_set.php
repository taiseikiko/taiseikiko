<?php
// 初期処理
require_once('function.php');
// DB接続
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

// 初期設定 & データセット
$route_dept_datas = array();
$deptList = [];           //部署
$count = 0;

//部署リスト
$deptList = getDropdownDataOfDept('sq_dept');

$dept_filter = isset($_POST['dept']) ? $_POST['dept'] : '';

if ($dept_filter !== '') {
  //分類マスタからデータ取得する
  $route_dept_datas = getRouteInDepartDatas($dept_filter);
  if (!empty($route_dept_datas)) {
    $count = count($route_dept_datas);
  }
}

function getRouteInDepartDatas($dept)
{
  global $pdo;
  $sql = "SELECT rd.department_code, rd.employee_code, rd.role, cd.text2 as dept_name, e.employee_name,
    CASE rd.role
      WHEN 1 THEN '入力'
      WHEN 3 THEN '承認'
    END as role_name
    FROM dw_route_in_dept rd
    LEFT JOIN sq_code cd ON rd.department_code = cd.text1 AND cd.code_id = 'sq_dept' 
    LEFT JOIN employee e ON e.employee_code = rd.employee_code
    WHERE rd.department_code = '$dept'";

  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $datas = $stmt->fetchAll();
  return $datas;
}

function getDropdownDataOfDept($code_id)
{
  global $pdo;
  $sql = "SELECT text1, text2 FROM sq_code WHERE code_id='$code_id'";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $datas = $stmt->fetchAll();

  return $datas;
}
