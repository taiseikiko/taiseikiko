<?php
// 初期処理
require_once('function.php');
session_start();
// DB接続
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
$today = date('Y/m/d');
$user_code = $_SESSION["login"];

$success = true;

//employee_ent2の登録ボタンを押下場合
if (isset($_POST['submit'])) {
  $process = $_POST['process'];  

  $emp_datas = [
    'employee_code' => $_POST['employee_code'] ?? '',               //社員番号
    'employee_name' => $_POST['employee_name'] ?? '',               //社員名
    'kana' => $_POST['kana'] ?? '',                                 //社員名カナ
    'company_code' => $_POST['company_code'] ?? '',                 //会社コード
    'department_code' => $_POST['department_code'] ?? '',           //部署コード
    'office_position_code' => $_POST['office_position_code'] ?? '', //役職コード
    'qualifications_code' => $_POST['qualifications_code'] ?? '',   //職位コード
    'goho' => $_POST['goho'] ?? '',                                 //号棒コード
    'pay_division' => $_POST['pay_division']?? '',                  //支給区分
    'authorization' => $_POST['authorization']?? 0,                //権限
    'pass' => $_POST['pass'] ?? '',                                 //パスワード
    'email' => $_POST['email'] ?? '',                               //メールアドレス
    'birthday' => $_POST['birthday']?? '',                          //生年月日
    'date_of_entry' => $_POST['date_of_entry']?? ''                 //入社日
  ];

  try {
    $pdo->beginTransaction();
    //新規の場合
    if ($process == 'new') {
      //重複エラーチェック
      $sql1 = "SELECT * FROM employee WHERE employee_code = '".$_POST['employee_code']."'";
      $stmt1 = $pdo->prepare($sql1);
      $stmt1->execute();
      $row = $stmt1->fetchAll();

      //データがある場合
      if ($row) {
        $success = false;
        $err = 'duplicate';
      } else {
        //employeeへ登録する
        $sql = "INSERT INTO employee (employee_code, employee_name, kana, company_code, department_code, office_position_code, qualifications_code, pay_division, 
                  authorization, pass, email, birthday, date_of_entry, goho)
                  VALUES (:employee_code, :employee_name, :kana, :company_code, :department_code, :office_position_code, :qualifications_code, :pay_division, 
                  :authorization, :pass, :email, :birthday, :date_of_entry, :goho)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($emp_datas);
      }
    }
    //更新の場合
    else {      
      $sql = "UPDATE employee SET employee_name=:employee_name, kana=:kana, company_code=:company_code, department_code=:department_code, 
                office_position_code=:office_position_code, qualifications_code=:qualifications_code, pay_division=:pay_division, authorization=:authorization, 
                pass=:pass, email=:email, birthday=:birthday, date_of_entry=:date_of_entry, goho=:goho
                WHERE employee_code=:employee_code";
      $stmt = $pdo->prepare($sql);
      $stmt->execute($emp_datas);
    }
    $pdo->commit();
  } catch (PDOException $e) {
    $success = false;
    $err = 'exceErr';
    $pdo->rollback();
    error_log("PDO Exception: " . $e->getMessage(),3,'error_log.txt');
  }

  //エラーがない場合
  if ($success == true) {
    header('location:employee_ent1.php');
  } else {
    header('location:employee_ent2.php?err=' . $err);
  }
}
