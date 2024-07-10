<?php
  // 初期処理
  require_once('function.php');
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

  $employee_code = '';          //社員番号
  $employee_name = '';          //社員名
  $kana = '';                   //社員名カナ
  $company_code = '';           //会社コード
  $department_code = '';        //部署コード
  $office_position_code = '';   //役職コード
  $qualifications_code = '';    //職位コード
  $goho = '';                   //号棒コード
  $pay_division = '';           //支給区分
  $authorization = '';         //権限
  $pass = '';                   //パスワード
  $email = '';                  //メールアドレス
  $birthday = '';               //生年月日
  $date_of_entry = '';          //入社日
  $err = $_GET['err']?? '';
  $disabled_emp_code = true;
  $btn_name = '登録';

  if (isset($_POST['process'])) {
    $process = $_POST['process'];

    //新規の場合
    if ($process == 'new') {
      $disabled_emp_code = false;
    } else {
      $btn_name = '更新';
      $employee_code = $_POST['employee_code'];

      //employeeデータを取得する
      $datas = getEmployeeDatas($employee_code);
      if (!empty($datas)) {
        $variables = ['employee_name', 'kana', 'company_code', 'department_code', 'office_position_code', 'qualifications_code', 'goho', 'pay_division', 
                      'authorization', 'pass', 'email', 'birthday', 'date_of_entry'];
        foreach ($variables as $variable) {
          ${$variable} = $datas[$variable];
        }
      }
    }

    //会社を取得する
    $company_datas = getDropdownData("company");
    //部署を取得する
    $department_datas = getDropdownData("department");
    //役職を取得する
    $office_position_datas = getDropdownData2("office_position");
    //職位を取得する
    $qualification_datas = getDropdownData2("qualifications");
    //支給区分を取得する
    $pay_division_datas = getDropdownData2("pay_division");
  }

  /**
   * employeeデータを取得する
   * 
   */
  function getEmployeeDatas($employee_code) {
    global $pdo;
    $datas = [];

    $sql = "SELECT * FROM employee WHERE employee_code = '$employee_code'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $datas = $stmt->fetch(PDO::FETCH_ASSOC);
    return $datas;
  }

  /**
 * プルダウンにセットするデータを取得する
 */
function getDropdownData($code_id) {
  global $pdo;
  //sq_code テーブルからデータ取得する
  $sql = "SELECT text1, text2
  FROM code_master
  WHERE code_id='$code_id'";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $datas = $stmt->fetchAll();
  return $datas;
}

  /**
 * プルダウンにセットするデータを取得する
 */
function getDropdownData2($code_id) {
  global $pdo;
  //sq_code テーブルからデータ取得する
  $sql = "SELECT text1, code_no
  FROM code_master
  WHERE code_id='$code_id'";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $datas = $stmt->fetchAll();
  return $datas;
}

?>