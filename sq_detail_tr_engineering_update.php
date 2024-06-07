<?php
  // 初期処理
  require_once('function.php');
  session_start();
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
  $today = date('Y/m/d');
  $user_code = $_SESSION["login"];
  $dept_id = $_POST['dept_id'];
  $route_pattern = $_POST['route_pattern'];
  $sq_no = $_POST['sq_no'];
  $sq_line_no = $_POST['sq_line_no'];
  $title = isset($_POST['title']) ? $_POST['title'] : '';

  //担当者設定ボタンを押下する場合
  if (isset($_POST['submit_receipt'])) {
    $success = true;
    $record_div = $_POST['record_div'];
    $entrant = $_POST['entrant'];
    $group_id = $_POST['group'];
    
    $sq_default_role_datas = [];

    try {
      $pdo->beginTransaction();
      //テーブルID : sq_detail_tr_engineering / テーブル名称：営業依頼書・明細T/R_技術へ登録
      receipt_cu_sq_detail_tr_engineering($record_div, $entrant);

      //テーブルID : sq_detail_tr / テーブル名称：営業依頼書・明細T/R
      receipt_cu_sq_detail_tr();

      //テーブルID : sq_route_tr / テーブル名称：部署ルートトランザクション
      receipt_cu_sq_route_tr($entrant, $group_id);

      //テーブルID : sq_route_mail_tr / テーブル名称：部署ルートメールトランザクション
      receipt_cu_sq_route_mail_tr($entrant, $group_id);

      $pdo->commit();
    } catch (PDOException $e) {
      $success = false;
      if (strpos($e->getMessage(), 'SQLSTATE[42000]') !== false) {
        error_log("SQL Syntax Error or Access Violation: " . $e->getMessage(),3,'error_log.txt');
      } else {
        $pdo->rollback();
        throw($e);
        error_log("PDO Exception: " . $e->getMessage(),3,'error_log.txt');
      }
    }
    //登録処理にエラーがなければメール送信する
    if ($success) {
      include("sq_mail_send3.php");
    }
  }

  //入力画面から見積更新ボタンを押下場合
  if (isset($_POST['submit_entrant1'])) {
    $success = true;
    $variables = ['entrant_comments', 'confirmer_comments', 'approver_comments'];

    foreach ($variables as $var) {
        ${$var} = $_POST[$var] ?? '';
    }

    try {
      $pdo->beginTransaction();
      //テーブルID : sq_detail_tr_engineering / テーブル名称：営業依頼書・明細T/R_技術
      entrant_cu_sq_detail_tr_engineering1($entrant_comments, $confirmer_comments, $approver_comments, $title);

      //テーブルID : sq_detail_tr / テーブル名称：営業依頼書・明細T/R
      entrant_cu_sq_detail_tr($title);

      //テーブルID : sq_route_tr / テーブル名称：部署ルートトランザクション
      entrant_cu_sq_route_tr($title);

      $pdo->commit();
    } catch (PDOException $e) {
      $success = false;
      if (strpos($e->getMessage(), 'SQLSTATE[42000]') !== false) {
        error_log("SQL Syntax Error or Access Violation: " . $e->getMessage(),3,'error_log.txt');
      } else {
        $pdo->rollback();
        throw($e);
        error_log("PDO Exception: " . $e->getMessage(),3,'error_log.txt');
      }
    }
    //登録処理にエラーがない場合
    if ($success) {
      include('sq_mail_send3.php');
    }
  }

  //入力画面から図面更新ボタンを押下場合
  if (isset($_POST['submit_entrant2'])) {
    $success = true;
    $variables = ['entrant_comments', 'confirmer_comments', 'approver_comments', 'mail_to1', 'mail_to2', 'mail_to3', 'mail_to4', 'mail_to5'];

    foreach ($variables as $var) {
        ${$var} = $_POST[$var] ?? '';
    }

    try {
      $pdo->beginTransaction();
      //テーブルID : sq_detail_tr_engineering / テーブル名称：営業依頼書・明細T/R_技術
      entrant_cu_sq_detail_tr_engineering2($entrant_comments, $confirmer_comments, $approver_comments, $mail_to1, $mail_to2, $mail_to3, $mail_to4, $mail_to5, $title);

      //テーブルID : sq_detail_tr / テーブル名称：営業依頼書・明細T/R
      entrant_cu_sq_detail_tr($title);

      //テーブルID : sq_route_tr / テーブル名称：部署ルートトランザクション
      entrant_cu_sq_route_tr($title);

      $pdo->commit();
    } catch (PDOException $e) {
      $success = false;
      if (strpos($e->getMessage(), 'SQLSTATE[42000]') !== false) {
        error_log("SQL Syntax Error or Access Violation: " . $e->getMessage(),3,'error_log.txt');
      } else {
        $pdo->rollback();
        throw($e);
        error_log("PDO Exception: " . $e->getMessage(),3,'error_log.txt');
      }
    }
    //登録処理にエラーがない場合
    if ($success) {
      include('sq_mail_send3.php');
    }
  }

  function getEmail($employee_code) {
    global $pdo;
    $email = '';
    if (!empty($employee_code)) {
      $sql = "SELECT email FROM employee WHERE employee_code = '$employee_code'";
      $stmt = $pdo->prepare($sql);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($row) {
        $email = $row['email'];
      }
    }    
    return $email;
  }

  //テーブルID : sq_default_role / テーブル名称：営業依頼書・部署初期ルートM/Fへ登録
  function get_sq_default_role($entrant, $group_id) {
    global $pdo;
    global $dept_id;

    $datas = [];
    $sql = "SELECT * FROM sq_default_role WHERE dept_id = '$dept_id' AND entrant = '$entrant' AND group_id = '$group_id'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
      $datas = $row;
    }
    return $datas;
  }

  /*----------------------------------------------------------------受付画面の関数開始------------------------------------------------------------------------------------- */
  //テーブルID : sq_detail_tr_engineering / テーブル名称：営業依頼書・明細T/R_技術へ登録
  function receipt_cu_sq_detail_tr_engineering($record_div, $entrant) {
    global $pdo;
    global $today;
    global $sq_no;
    global $sq_line_no;

    $data = [
      'sq_no' => $sq_no,
      'sq_line_no' => $sq_line_no,
      'record_div' => $record_div,
      'entrant' => $entrant,
      'reception' => $_SESSION["login"],
      'reception_date' => $today
    ];

    $sql = "SELECT * FROM sq_detail_tr_engineering WHERE sq_no = '$sq_no' AND sq_line_no = '$sq_line_no'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
      $data['add_date'] = $today;
      $sql1 = "INSERT INTO sq_detail_tr_engineering (sq_no, sq_line_no, record_div, entrant, reception, reception_date, add_date) 
            VALUES(:sq_no, :sq_line_no, :record_div, :entrant, :reception, :reception_date, :add_date)";
    } else {
      $data['upd_date'] = $today;
      $sql1 = "UPDATE sq_detail_tr_engineering SET record_div=:record_div, entrant=:entrant, reception=:reception, reception_date=:reception_date, upd_date=:upd_date 
            WHERE sq_no=:sq_no AND sq_line_no=:sq_line_no";
    }
    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute($data);
  }

  //テーブルID : sq_detail_tr / テーブル名称：営業依頼書・明細T/R
  function receipt_cu_sq_detail_tr() {
    global $pdo;
    global $today;
    global $dept_id;
    global $sq_no;
    global $sq_line_no;

    $data = [
      'sq_no' => $sq_no,
      'sq_line_no' => $sq_line_no,
      'processing_dept' => $dept_id,
      'processing_status' => 1, //受付
      'upd_date' => $today
    ];

    $sql = "UPDATE sq_detail_tr SET processing_dept=:processing_dept, processing_status=:processing_status, upd_date=:upd_date WHERE sq_no=:sq_no AND sq_line_no=:sq_line_no";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);
  }

  //テーブルID : sq_route_tr / テーブル名称：部署ルートトランザクション
  function receipt_cu_sq_route_tr($entrant, $group_id) {
    global $pdo;
    global $today;
    global $dept_id;
    global $user_code;
    global $route_pattern;
    global $sq_no;
    global $sq_line_no;
    $entrant_dr = '';
    $confirmer_dr = '';
    $approver_dr = '';
    $datas = [
      'route_id' => $route_pattern,
      'sq_no' => $sq_no,
      'sq_line_no' => $sq_line_no,
      'upd_date' => $today
    ];
    $newArray = [];
    $cols = '';

    //テーブルID : sq_default_role / テーブル名称：営業依頼書・部署初期ルートM/Fからデータを取得
    $sq_default_role_datas = get_sq_default_role($entrant, $group_id);

    if (!empty($sq_default_role_datas) && isset($sq_default_role_datas)) {
      $entrant_dr = $sq_default_role_datas['entrant'];
      $confirmer_dr = $sq_default_role_datas['confirmer'];
      $approver_dr = $sq_default_role_datas['approver'];
    }

    $sql1 = "SELECT * FROM sq_route_tr WHERE route_id='$route_pattern' AND sq_no='$sq_no' AND sq_line_no='$sq_line_no'";
    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute();
    $row = $stmt1->fetch(PDO::FETCH_ASSOC);
    if ($row) {
      for ($i = 1; $i <= 5; $i++) {
        if ($row['route'.$i.'_dept'] == $dept_id) {
          $datas['route'.$i.'_receipt_date'] = $today;
          $datas['route'.$i.'_receipt_person'] = $user_code;
          $datas['route'.$i.'_entrant'] = $entrant_dr;
          $datas['route'.$i.'_confirmer'] = $confirmer_dr;
          $datas['route'.$i.'_approver'] = $approver_dr;
        }
      }
    }

    foreach ($datas as $key=>$index) {
      if ($key !== 'sq_no' && $key !== 'sq_line_no' && $key !== 'route_id') {
        $newKey = $key . '=:' . $key;
        $newValue = $key;
        $newArray[$newKey] = $newValue;
      }
    }

    if (!empty($newArray)) {
      $cols = implode(',', array_keys($newArray));

      $sql = "UPDATE sq_route_tr SET $cols WHERE sq_no=:sq_no AND sq_line_no=:sq_line_no AND route_id=:route_id";
      $stmt = $pdo->prepare($sql);
      $stmt->execute($datas);
    }
  }

  //テーブルID : sq_route_mail_tr / テーブル名称：部署ルートメールトランザクション
  function receipt_cu_sq_route_mail_tr($entrant, $group_id) {
    global $pdo;
    global $today;
    global $dept_id;
    global $user_code;
    global $route_pattern;
    global $sq_no;
    global $sq_line_no;
    $entrant_dr = '';
    $confirmer_dr = '';
    $approver_dr = '';
    $datas = [
      'route_id' => $route_pattern,
      'sq_no' => $sq_no,
      'sq_line_no' => $sq_line_no,
      'upd_date' => $today
    ];
    $newArray = [];
    $cols = '';

    //テーブルID : sq_default_role / テーブル名称：営業依頼書・部署初期ルートM/Fからデータを取得
    $sq_default_role_datas = get_sq_default_role($entrant, $group_id);

    if (!empty($sq_default_role_datas) && isset($sq_default_role_datas)) {
      $entrant_dr = $sq_default_role_datas['entrant'];
      $confirmer_dr = $sq_default_role_datas['confirmer'];
      $approver_dr = $sq_default_role_datas['approver'];
    }

    $sql1 = "SELECT * FROM sq_route_tr WHERE route_id='$route_pattern' AND sq_no='$sq_no' AND sq_line_no='$sq_line_no'";
    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute();
    $row = $stmt1->fetch(PDO::FETCH_ASSOC);
    if ($row) {
      for ($i = 1; $i <= 5; $i++) {
        if ($row['route'.$i.'_dept'] == $dept_id) {
          $datas['route'.$i.'_receipt_person'] = $user_code;
          $datas['route'.$i.'_receipt_ad'] = getEmail($user_code);
          $datas['route'.$i.'_entrant_person'] = $entrant_dr;
          $datas['route'.$i.'_entrant_ad'] = getEmail($entrant_dr);
          $datas['route'.$i.'_confirmer_person'] = $confirmer_dr;
          $datas['route'.$i.'_confirmer_ad'] = getEmail($confirmer_dr);
          $datas['route'.$i.'_approver_person'] = $approver_dr;
          $datas['route'.$i.'_approver_ad'] = getEmail($approver_dr);
        }
      }
    }

    foreach ($datas as $key=>$index) {
      if ($key !== 'sq_no' && $key !== 'sq_line_no' && $key !== 'route_id') {
        $newKey = $key . '=:' . $key;
        $newValue = $key;
        $newArray[$newKey] = $newValue;
      }
    }

    if (!empty($newArray)) {
      $cols = implode(',', array_keys($newArray));

      $sql = "UPDATE sq_route_mail_tr SET $cols WHERE sq_no=:sq_no AND sq_line_no=:sq_line_no AND route_id=:route_id";
      $stmt = $pdo->prepare($sql);
      $stmt->execute($datas);
    }
  }

  /*----------------------------------------------------------------受付画面の関数完了------------------------------------------------------------------------------------- */

  /*----------------------------------------------------------------入力画面の関数開始------------------------------------------------------------------------------------- */
  //テーブルID : sq_detail_tr_engineering / テーブル名称：営業依頼書・明細T/R_技術
  function entrant_cu_sq_detail_tr_engineering1($entrant_comments, $confirmer_comments, $approver_comments, $title) {
    global $pdo;
    global $today;
    global $sq_no;
    global $sq_line_no;

    $data = [
      'sq_no' => $sq_no,
      'sq_line_no' => $sq_line_no,
      'upd_date' => $today
    ];

    if ($title == 'td_entrant') {
      $data['entrant_comments'] = $entrant_comments;
      $data['entrant_date'] = $today;

      $sql1 = "UPDATE sq_detail_tr_engineering SET entrant_comments=:entrant_comments, entrant_date=:entrant_date, upd_date=:upd_date 
          WHERE sq_no=:sq_no AND sq_line_no=:sq_line_no";
    }

    if ($title == 'td_confirm') {
      $data['confirmer'] = $_SESSION["login"];
      $data['confirmer_comments'] = $confirmer_comments;
      $data['confirm_date'] = $today;

      $sql1 = "UPDATE sq_detail_tr_engineering SET confirmer=:confirmer, confirmer_comments=:confirmer_comments, confirm_date=:confirm_date, upd_date=:upd_date 
          WHERE sq_no=:sq_no AND sq_line_no=:sq_line_no";
    }

    if ($title == 'td_approve') {
      $data['approver'] = $_SESSION["login"];
      $data['approver_comments'] = $approver_comments;
      $data['approve_date'] = $today;

      $sql1 = "UPDATE sq_detail_tr_engineering SET approver=:approver, approver_comments=:approver_comments, approve_date=:approve_date, upd_date=:upd_date 
          WHERE sq_no=:sq_no AND sq_line_no=:sq_line_no";
    }

    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute($data);
  }

  function entrant_cu_sq_detail_tr_engineering2($entrant_comments, $confirmer_comments, $approver_comments, $mail_to1, $mail_to2, $mail_to3, $mail_to4, $mail_to5, $title) {
    global $pdo;
    global $today;
    global $sq_no;
    global $sq_line_no;

    $data = [
      'sq_no' => $sq_no,
      'sq_line_no' => $sq_line_no,
      'mail_to1' => $mail_to1,
      'mail_to2' => $mail_to2,
      'mail_to3' => $mail_to3,
      'mail_to4' => $mail_to4,
      'mail_to5' => $mail_to5,
      'upd_date' => $today
    ];

    if ($title == 'td_entrant') {
      $data['entrant_comments'] = $entrant_comments;
      $data['entrant_date'] = $today;

      $sql1 = "UPDATE sq_detail_tr_engineering SET entrant_comments=:entrant_comments, entrant_date=:entrant_date, mail_to1=:mail_to1, 
          mail_to2=:mail_to2, mail_to3=:mail_to3, mail_to4=:mail_to4, mail_to5=:mail_to5, upd_date=:upd_date 
          WHERE sq_no=:sq_no AND sq_line_no=:sq_line_no";
    }

    if ($title == 'td_confirm') {
      $data['confirmer'] = $_SESSION["login"];
      $data['confirmer_comments'] = $confirmer_comments;
      $data['confirm_date'] = $today;

      $sql1 = "UPDATE sq_detail_tr_engineering SET confirmer=:confirmer, confirmer_comments=:confirmer_comments, confirm_date=:confirm_date, mail_to1=:mail_to1, 
          mail_to2=:mail_to2, mail_to3=:mail_to3, mail_to4=:mail_to4, mail_to5=:mail_to5, upd_date=:upd_date 
          WHERE sq_no=:sq_no AND sq_line_no=:sq_line_no";
    }

    if ($title == 'td_approve') {
      $data['approver'] = $_SESSION["login"];
      $data['approver_comments'] = $approver_comments;
      $data['approve_date'] = $today;

      $sql1 = "UPDATE sq_detail_tr_engineering SET approver=:approver, approver_comments=:approver_comments, approve_date=:approve_date, mail_to1=:mail_to1, 
          mail_to2=:mail_to2, mail_to3=:mail_to3, mail_to4=:mail_to4, mail_to5=:mail_to5, upd_date=:upd_date 
          WHERE sq_no=:sq_no AND sq_line_no=:sq_line_no";
    }

    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute($data);
  }

  //テーブルID : sq_detail_tr / テーブル名称：営業依頼書・明細T/R
  function entrant_cu_sq_detail_tr($title) {
    global $pdo;
    global $today;
    global $dept_id;
    global $sq_no;
    global $sq_line_no;

    switch ($title) {
      case 'td_entrant':
        $status = 2;
        break;

      case 'td_confirm':
        $status = 3;
        break;

      case 'td_approve':
        $status = 4;
        break;
      
      default:
        $status = '';
        break;
    }

    $data = [
      'sq_no' => $sq_no,
      'sq_line_no' => $sq_line_no,
      'processing_dept' => $dept_id,
      'processing_status' => $status,
      'upd_date' => $today
    ];

    $sql = "UPDATE sq_detail_tr SET processing_dept=:processing_dept, processing_status=:processing_status, upd_date=:upd_date WHERE sq_no=:sq_no AND sq_line_no=:sq_line_no";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);
  }

  //テーブルID : sq_route_tr / テーブル名称：部署ルートトランザクション
  function entrant_cu_sq_route_tr($title) {
    global $pdo;
    global $today;
    global $dept_id;
    global $route_pattern;
    global $sq_no;
    global $sq_line_no;

    $datas = [
      'route_id' => $route_pattern,
      'sq_no' => $sq_no,
      'sq_line_no' => $sq_line_no,
      'upd_date' => $today
    ];
    $newArray = [];
    $cols = '';

    $sql1 = "SELECT * FROM sq_route_tr WHERE route_id='$route_pattern' AND sq_no='$sq_no' AND sq_line_no='$sq_line_no'";
    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute();
    $row = $stmt1->fetch(PDO::FETCH_ASSOC);
    if ($row) {
      for ($i = 1; $i <= 5; $i++) {
        if ($row['route'.$i.'_dept'] == $dept_id) {
          if ($title == 'td_entrant') {
            $datas['route'.$i.'_entrant_date'] = $today;
          } else if ($title == 'td_confirm') {
            $datas['route'.$i.'_confirm_date'] = $today;
          } else if ($title == 'td_approve') {
            $datas['route'.$i.'_approval_date'] = $today;
          }
        }
      }
    }

    foreach ($datas as $key=>$index) {
      if ($key !== 'sq_no' && $key !== 'sq_line_no' && $key !== 'route_id') {
        $newKey = $key . '=:' . $key;
        $newValue = $key;
        $newArray[$newKey] = $newValue;
      }
    }

    if (!empty($newArray)) {
      $cols = implode(',', array_keys($newArray));

      $sql = "UPDATE sq_route_tr SET $cols WHERE sq_no=:sq_no AND sq_line_no=:sq_line_no AND route_id=:route_id";
      $stmt = $pdo->prepare($sql);
      $stmt->execute($datas);
    }
  }

  /*----------------------------------------------------------------入力画面の関数完了------------------------------------------------------------------------------------- */
?>