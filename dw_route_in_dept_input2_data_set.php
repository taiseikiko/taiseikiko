<?php
// 初期処理
require_once('function.php');

// DB接続
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

// 初期設定 & データセット
$deptList = [];           //部署
$btn_name = '登録';
$employee_name = '';
$department_name = '';
$office_position_name = '';
$role = '';
$dept_id = '';
$employee_code = '';
$err = $_GET['err'] ?? '';

//部署プルダウンにセットするデータを取得する
$deptList = getDropdownDataOfDept('sq_dept');

//一覧画面から来た場合($_POST)あるいは重複エラーがあったので戻ってきた場合
if (isset($_POST['process']) || isset($_GET['process'])) {
  $process = $_POST['process'] ?? $_GET['process'];
  $dept_id = $_POST['dept'] ?? $_GET['dept'];
  $employee_code = $_POST['employee_code'] ?? ''; //担当者
  $role = $_POST['role'] ?? ''; //役職

  //更新の場合
  if ($process == 'update') {
    $btn_name = '更新'; //ボタン名
  }
  //担当者がある場合
  if ($employee_code !== '') {
    //担当者情報を取得する
    $cpDatas = getEmpDatasByEmpCd($employee_code);
    //データがある場合
    if (isset($cpDatas)) {
      $employee_name = $cpDatas['employee_name'];   //担当者名
      $department_name = $cpDatas['dept_name'];     //部署名
      $office_position_name = $cpDatas['role_name']; //役職
    }
  }
}

/**
 * 部署リストを取得する
 */
function getDropdownDataOfDept($code_id)
{
  global $pdo;
  $sql = "SELECT text1, text2 FROM sq_code WHERE code_id='$code_id'";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $datas = $stmt->fetchAll();

  return $datas;
}

/**
 * 担当者情報を取得する
 */
function getEmpDatasByEmpCd($employee_code)
{
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
