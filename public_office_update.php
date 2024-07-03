<?php
// 初期処理
require_once('function.php');

// DB接続
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

//When form is submitted
if (isset($_POST['submit'])) {
  $success = reg_or_upd_public_office();
  if ($success) {
    echo "<script>
      window.location.href='public_office_input1.php';
    </script>";
  } else {
    echo "<script>
      window.location.href='public_office_input2.php?err=errExec';
    </script>";
  }
}
function reg_or_upd_public_office()
{
  $today = date('Y/m/d');
  $success = true;
  global $pdo;

  if (isset($_POST['process'])) {
    //新規作成or更新の場合
    $process = $_POST['process'];

    try {
      $pdo->beginTransaction();
      if ($process == 'create') {
        //新規作成の場合
        $data = [
          'pf_code' => $_POST['pf_code'],
          'pf_name' => $_POST['pf_name'],
          'office_code' => $_POST['office_code'],
          'person_in_charge' => $_POST['employee_code'],
          'add_date' => $today
        ];
        $sql = "INSERT INTO public_office (pf_code, pf_name, office_code, person_in_charge, add_date) 
        VALUES (:pf_code, :pf_name, :office_code, :person_in_charge, :add_date)";
        $stmt = $pdo->prepare($sql);
      } else {
        //更新の場合
        $data = [
          'pf_code' => $_POST['pf_code'],
          'pf_name' => $_POST['pf_name'],
          'office_code' => $_POST['office_code'],
          'person_in_charge' => $_POST['employee_code'],
          'upd_date' => $today
        ];
        $sql = "UPDATE public_office SET pf_name=:pf_name, office_code=:office_code, person_in_charge=:person_in_charge, upd_date=:upd_date
        WHERE pf_code=:pf_code";
        $stmt = $pdo->prepare($sql);
      }
      
      $stmt->execute($data);
      $pdo->commit();
    } catch (PDOException $e) {
      $success = false;
      $pdo->rollback();
      error_log("PDO Exception: " . $e->getMessage(), 3, 'error_log.txt');
    }
    return $success;
  }
}
