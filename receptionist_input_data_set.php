<?php
// 初期処理
  session_start();
  header('Program-id: estimate_entry.php');
  header('Content-type: text/html; charset=utf-8');
  require_once('function.php');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
  $today = date('Y/m/d');
  $nendo = substr($today,0,4);

// DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

// フィールド初期化
  //$dept_code = $_SESSION['department_code'];
  //$dept_name = $_SESSION['office_name'];
  $dept_code = '';
  $dept_name = '';
  $emp_code = array();
  $emp_name = array();
  $recept1_code = '';
  $recept2_code = '';
  $recept3_code = '';
  $recept4_code = '';
  $recept5_code = '';
  $recept1_name = '';
  $recept2_name = '';
  $recept3_name = '';
  $recept4_name = '';
  $recept5_name = '';
  $r_com1 = '';
  $r_com2 = '';
  $r_com3 = '';
  $r_com4 = '';
  $r_com5 = '';
  $r_mail1 = '';
  $r_mail2 = '';
  $r_mail3 = '';
  $r_mail4 = '';
  $r_mail5 = '';
  $recept = '';
  $r_code = '';
  $$r_code = '';
  $r_com = '';
  $$r_com = '';
  $r_cm = '';
  $$r_cm = '';
  $r_m = '';
  $r_ml = '';

// テーブル読み込み

    // 営業依頼書_部署マスター
    $sql1 = "SELECT * FROM sq_dept;";
      $stmt1 = $pdo->prepare($sql1);
      $stmt1->execute();
    $i=0;
    WHILE($row = $stmt1->fetch(PDO::FETCH_ASSOC)){
      $dp_code[$i] = $row['sq_dept_code'];
      $dp_name[$i] = $row['sq_dept_name'];
      $i++;
    }

if(isset($_POST['dept'])){
  $dept_code = $_POST['dept'];
  if(!empty($dept_code)){
    // 社員マスター
    $sql2 = "SELECT * FROM employee WHERE department_code = $dept_code;";
      $stmt2 = $pdo->prepare($sql2);
      $stmt2->execute();
    $i=0;
    WHILE($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)){
      $emp_code[$i] = $row2['employee_code'];
      $emp_name[$i] = $row2['employee_name'];
      $i++;
      }
    }

    // 営業依頼書_受付担当者M/F
    $sql1 = "SELECT * FROM receptionist WHERE dept_code = '$dept_code';";
      $stmt1 = $pdo->prepare($sql1);
      $stmt1->execute();
    IF($row = $stmt1->fetch(PDO::FETCH_ASSOC)){

    for($i=1; $i<6; $i++){
      $recpt = 'receptionist'.$i;
      $r_code = '$recept'.$i.'_code';
//error_log('recpt='.$recpt."\n",3,'error_log.txt');

      $$r_code = $row[$recpt];
//error_log('r_code='.$$r_code."\n",3,'error_log.txt');
        if(!empty($$r_code)){
          // コメント
          $r_c = 'r_com'.$i;
          $r_cm = '$r_com'.$i;
          $$r_cm = $row[$r_c];
          //$r_com.$i = $$r_cm;
//error_log('r_cm='.$$r_cm."\n",3,'error_log.txt');
          // メールアドレス
          $r_m = 'r_mail'.$i;
//error_log('r_m='.$r_m."\n",3,'error_log.txt');
          $r_ml = '$r_mail'.$i;
          $$r_ml = $row[$r_m];
//error_log('r_ml='.$$r_ml."\n",3,'error_log.txt');
          }
      }
    }
    else{
      for($i=1; $i<6; $i++){
        $recpt = 'receptionist'.$i;
        $r_code = '$recept'.$i.'_code';
        $$r_code = '';
        $r_c = 'r_com'.$i;
        $r_cm = '$r_com'.$i;
        $$r_cm = '';
        $r_m = 'r_mail'.$i;
        $r_ml = '$r_mail'.$i;
        $$r_ml = '';
      }
    }
}

// 画面フィールドへデータセット


?>