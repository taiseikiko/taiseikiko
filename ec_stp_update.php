<?php
// 初期処理
require_once('function.php');
session_start();

// DB接続
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
$today = date('Y/m/d');
$user_code = $_SESSION["login"];
$success = true;

if (isset($_POST['submit'])) {
  $process = $_POST['process'];

  $datas = [
    'bridge' => filter_input(INPUT_POST, 'bridge', FILTER_SANITIZE_SPECIAL_CHARS),
    'company' => filter_input(INPUT_POST, 'company', FILTER_SANITIZE_SPECIAL_CHARS),
    'name' => filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS),
    'birthday' => filter_input(INPUT_POST, 'birthday', FILTER_SANITIZE_SPECIAL_CHARS),
    'attendance_year' => filter_input(INPUT_POST, 'attendance_year', FILTER_SANITIZE_SPECIAL_CHARS),
    'elementary_number' => filter_input(INPUT_POST, 'elementary_number', FILTER_SANITIZE_SPECIAL_CHARS),
    'advance_number' => filter_input(INPUT_POST, 'advance_number', FILTER_SANITIZE_SPECIAL_CHARS),
    'con_qualification' => filter_input(INPUT_POST, 'con_qualification', FILTER_SANITIZE_SPECIAL_CHARS),
    'renewal_date' => filter_input(INPUT_POST, 'renewal_date', FILTER_SANITIZE_SPECIAL_CHARS),
    'expiration_date' => filter_input(INPUT_POST, 'expiration_date', FILTER_SANITIZE_SPECIAL_CHARS),
    'footnote' => filter_input(INPUT_POST, 'footnote', FILTER_SANITIZE_SPECIAL_CHARS),
  ];

  //新規の場合
  if ($process == 'new') {
    $ym = substr(str_replace('/', '', $today), 0, 6);

    try {
      $pdo->beginTransaction();
      $sql1 = "SELECT MAX(key_number) AS key_number FROM ec_stp_detail_tr_procurment WHERE key_number LIKE :ym";
      $stmt1 = $pdo->prepare($sql1);
      $stmt1->execute(['ym' => "$ym%"]);
      $from_tb_key_number = $stmt1->fetchColumn();

      if (!empty($from_tb_key_number)) {
        $no = substr($from_tb_key_number, 6, 2) + 1;
      } else {
        $no = '1';
      }

      $key_number = $ym . sprintf('%02d', $no);
      $datas['key_number'] = $key_number; //連番キー

      $sql = "INSERT INTO 
                ec_stp_detail_tr_procurment (
                key_number, bridge, company, name, birthday, attendance_year, 
                elementary_number, advance_number, con_qualification, renewal_date, 
                expiration_date, footnote
              ) VALUES (
                :key_number, :bridge, :company, :name, :birthday, :attendance_year, 
                :elementary_number, :advance_number, :con_qualification, :renewal_date, 
                :expiration_date, :footnote
              )";

      $stmt = $pdo->prepare($sql);
      $stmt->execute($datas);
      $pdo->commit();
    } catch (PDOException $e) {
      $success = false;
      $pdo->rollBack();
      error_log("PDO Exception: " . $e->getMessage(), 3, 'error_log.txt');
    }
  } else { // 更新の場合
    try {
      $pdo->beginTransaction();
      $datas['key_number'] = filter_input(INPUT_POST, 'key_number', FILTER_SANITIZE_SPECIAL_CHARS); //連番キー

      $sql = "UPDATE ec_stp_detail_tr_procurment 
              SET bridge=:bridge, company=:company, name=:name, birthday=:birthday, 
                  attendance_year=:attendance_year, elementary_number=:elementary_number, 
                  advance_number=:advance_number, con_qualification=:con_qualification, 
                  renewal_date=:renewal_date, expiration_date=:expiration_date, footnote=:footnote
              WHERE key_number=:key_number";

      $stmt = $pdo->prepare($sql);
      $stmt->execute($datas);
      $pdo->commit();
    } catch (PDOException $e) {
      $success = false;
      $pdo->rollBack();
      error_log("PDO Exception: " . $e->getMessage(), 3, 'error_log.txt');
    }
  }

  if ($success) {
    echo "<script>window.location.href='ec_stp_input1.php'</script>";
  } else {
    echo "<script>window.location.href='ec_stp_input2.php?err=exceErr'</script>";
  }
}
