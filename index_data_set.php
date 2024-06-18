<?php
  session_start();
  require_once('function.php');
  header('Content-type: text/html; charset=utf-8');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
  $_SESSION['dw_tab'] = "tab1";

// 初期値
  $_SESSION['user_name'] = "";
  $_SESSION['office_name'] = "";
  $_SESSION['office_position_name'] = '';
  $department_code = "";
  $_SESSION['m1'] = "";
  $_SESSION['m2'] = "";
  $_SESSION['m3'] = "";
  $_SESSION['m4'] = "";
  $_SESSION['m5'] = "";

  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

  $ep_code = $_SESSION["login"];
    // 社員マスター
    $sql1 = "SELECT * FROM employee WHERE employee_code = '$ep_code';";
    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute();

    if($row = $stmt1->fetch(PDO::FETCH_ASSOC)){
      $_SESSION['user_name'] = $row['employee_name'];
      $department_code = $row['department_code'];
      $_SESSION['department_code'] = $row['department_code'];
      $office_position_code = $row['office_position_code'];
    }

    $code_id ="department";
    // コードマスター（組織）
    $sql1 = "SELECT * FROM code_master WHERE code_id = '$code_id' AND text1 = '$department_code';";
    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute();

    if($row = $stmt1->fetch(PDO::FETCH_ASSOC)){
      $_SESSION['office_name'] = $row['text2'];
    }

    $code_id = 'office_position';
    $sql1 = "SELECT * FROM code_master WHERE code_id = '$code_id' AND code_no = '$office_position_code';";
    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute();

    if($row = $stmt1->fetch(PDO::FETCH_ASSOC)){
      $_SESSION['office_position_name'] = $row['text1'];
    }

    // 組織・メニュー
    $sql1 = "SELECT * FROM dept_menu WHERE dept_code = '$department_code';";
      $stmt1 = $pdo->prepare($sql1);
      $stmt1->execute();

    if($row = $stmt1->fetch(PDO::FETCH_ASSOC)){
      $_SESSION['m1'] = $row['menu1'];
      $_SESSION['m2'] = $row['menu2'];
      $_SESSION['m3'] = $row['menu3'];
      $_SESSION['m4'] = $row['menu4'];
      $_SESSION['m5'] = $row['menu5'];
      $_SESSION['m6'] = $row['menu6'];
      $_SESSION['m7'] = $row['menu7'];
      $_SESSION['m8'] = $row['menu8'];
      $_SESSION['m9'] = $row['menu9'];
      $_SESSION['m10'] = $row['menu10'];
      $_SESSION['m11'] = $row['menu11'];
      $_SESSION['m12'] = $row['menu12'];
      $_SESSION['m13'] = $row['menu13'];
      $_SESSION['m14'] = $row['menu14'];
      $_SESSION['m15'] = $row['menu15'];
    }

?>