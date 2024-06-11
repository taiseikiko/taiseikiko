<?php
  // 初期処理
  require_once('function.php');
  session_start();
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
  $today = date('Y/m/d');
  $user_code = $_SESSION["login"];
  $sq_no = $_POST['sq_no'];
  $sq_line_no = $_POST['sq_line_no'];
  $dept_id = $_POST['dept_id'];

  if (isset($_POST['submit'])) {
    $url = $_POST['url'] ?? ''; //メール送信する時、利用するため
    $route_pattern = isset($_POST['route_pattern']) ? $_POST['route_pattern'] : '';
    $success = true;
    try {
      $pdo->beginTransaction();
      //テーブルID : sq_header_tr / テーブル名称：営業依頼書・ヘッダーT/Rへ更新する
      cu_sq_header_tr();

      //テーブルID : sq_detail_tr / テーブル名称：営業依頼書・明細T/Rへ更新する
      cu_sq_detail_tr($route_pattern);

      //テーブルID : sq_route_tr / テーブル名称：部署ルートトランザクションへ更新する
      cu_sq_route_tr($route_pattern);

      //テーブルID : sq_route_mail_tr / テーブル名称：部署ルートメールトランザクション
      cu_sq_route_mail_tr($route_pattern);

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
    //登録処理にエラーが無ければメール送信する
    if ($success) {
      //メール送信する
      include('sq_mail_send2.php');
    }
  }

  //テーブルID : sq_header_tr / テーブル名称：営業依頼書・ヘッダーT/R
  function cu_sq_header_tr() {
    global $pdo;
    global $today;
    global $sq_no;
    global $user_code;

    $data = [
      'sq_no' => $sq_no,
      'client' => $user_code,
      'upd_date' => $today
    ];

    $sql = 'UPDATE sq_header_tr SET client=:client, upd_date=:upd_date WHERE sq_no=:sq_no';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);
  }

  //テーブルID : sq_detail_tr / テーブル名称：営業依頼書・明細T/R
  function cu_sq_detail_tr($route_pattern) {
    global $pdo;
    global $today;
    global $sq_no;
    global $sq_line_no;
    global $dept_id;

    $data = [
      'sq_no' => $sq_no,
      'sq_line_no' => $sq_line_no,
      'processing_dept' => $dept_id,
      'processing_status' => 1,
      'route_pattern' => $route_pattern,
      'status' => '',
      'upd_date' => $today
    ];

    $sql = 'UPDATE sq_detail_tr SET processing_dept=:processing_dept, processing_status=:processing_status, route_pattern=:route_pattern,
          status=:status, upd_date=:upd_date WHERE sq_no=:sq_no AND sq_line_no=:sq_line_no';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);
  }

  //テーブルID : sq_route_tr / テーブル名称：部署ルートトランザクションへ更新する
  function cu_sq_route_tr($route_pattern) {
    global $pdo;
    global $today;
    global $sq_no;
    global $sq_line_no;
    global $user_code;
    $entrant = $confirmer = $approver = '';
    for ($i=1;$i<=5;$i++) {
      ${"route".$i."_dept"} = '';
    }

    //sq_header_trからデータを取得する
    $sq_header_tr = get_sq_header_tr();
    if (!empty($sq_header_tr) && isset($sq_header_tr)) {
      $entrant = $sq_header_tr['client'];
    }

    //sq_detail_trからデータを取得する
    $sq_detail_tr = get_sq_detail_tr();
    if (!empty($sq_detail_tr) && isset($sq_detail_tr)) {
      $confirmer = $sq_detail_tr['confirmer'];
      $approver = $sq_detail_tr['approver'];
    }

    //sq_routeからデータを取得する
    $sq_route = get_sq_route($route_pattern);
    if (!empty($sq_route) && isset($sq_route)) {
      for ($i=1;$i<=5;$i++) {
        ${"route".$i."_dept"} = $sq_route['route'.$i.'_dept'];
      }
    }

    $data = [
      'route_id' => $route_pattern,
      'sq_no' => $sq_no,
      'sq_line_no' => $sq_line_no,
      'entrant' => $entrant,
      'confirmer' => $confirmer,
      'approver' => $approver, 
      'reception' => $user_code,
      'route1_dept' => $route1_dept,
      'route2_dept' => $route2_dept,
      'route3_dept' => $route3_dept,
      'route4_dept' => $route4_dept,
      'route5_dept' => $route5_dept,
      'add_date' => $today
    ];

    $sql = "INSERT INTO sq_route_tr (route_id, sq_no, sq_line_no, entrant, confirmer, approver, reception, route1_dept, route2_dept, route3_dept,
          route4_dept, route5_dept, add_date) 
          VALUES (:route_id, :sq_no, :sq_line_no, :entrant, :confirmer, :approver, :reception, :route1_dept, :route2_dept, :route3_dept,
          :route4_dept, :route5_dept, :add_date)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);
  }

  //テーブルID : sq_route_mail_tr / テーブル名称：部署ルートメールトランザクション
  function cu_sq_route_mail_tr($route_pattern) {
    global $pdo;
    global $today;
    global $sq_no;
    global $sq_line_no;
    global $user_code;
    $entrant = $confirmer = $approver = '';
    for ($i=1;$i<=5;$i++) {
      ${"route".$i."_dept"} = '';
    }

    //sq_header_trからデータを取得する
    $sq_header_tr = get_sq_header_tr();
    if (!empty($sq_header_tr) && isset($sq_header_tr)) {
      $entrant = $sq_header_tr['client'];
    }

    //sq_detail_trからデータを取得する
    $sq_detail_tr = get_sq_detail_tr();
    if (!empty($sq_detail_tr) && isset($sq_detail_tr)) {
      $confirmer = $sq_detail_tr['confirmer'];
      $approver = $sq_detail_tr['approver'];
    }

    //sq_routeからデータを取得する
    $sq_route = get_sq_route($route_pattern);
    if (!empty($sq_route) && isset($sq_route)) {
      for ($i=1;$i<=5;$i++) {
        ${"route".$i."_dept"} = $sq_route['route'.$i.'_dept'];
      }
    }

    $data = [
      'route_id' => $route_pattern,
      'sq_no' => $sq_no,
      'sq_line_no' => $sq_line_no,
      'entrant' => $entrant,
      'confirmer' => $confirmer,
      'approver' => $approver,
      'entrant_ad' => getEmail($entrant),
      'confirmer_ad' => getEmail($confirmer),
      'approver_ad' => getEmail($approver),
      'reception' => $user_code,
      'reception_ad' => getEmail($user_code),
      'route1_dept' => $route1_dept,
      'route2_dept' => $route2_dept,
      'route3_dept' => $route3_dept,
      'route4_dept' => $route4_dept,
      'route5_dept' => $route5_dept,
      'add_date' => $today
    ];

    $sql = "INSERT INTO sq_route_mail_tr (route_id, sq_no, sq_line_no, entrant, confirmer, approver, entrant_ad, confirmer_ad, approver_ad, reception, 
          reception_ad, route1_dept, route2_dept, route3_dept, route4_dept, route5_dept, add_date) 
          VALUES (:route_id, :sq_no, :sq_line_no, :entrant, :confirmer, :approver, :entrant_ad, :confirmer_ad, :approver_ad, :reception, 
          :reception_ad, :route1_dept, :route2_dept, :route3_dept, :route4_dept, :route5_dept, :add_date)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);
  }

  function get_sq_detail_tr() {
    global $pdo;
    global $sq_no;
    global $sq_line_no;
    $datas = [];

    $sql = "SELECT confirmer, approver FROM sq_detail_tr WHERE sq_no='$sq_no' AND sq_line_no='$sq_line_no'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
      $datas = $row;
    }
    return $datas;
  }

  function get_sq_header_tr() {
    global $pdo;
    global $sq_no;
    $datas = [];

    $sql = "SELECT client FROM sq_header_tr WHERE sq_no='$sq_no'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
      $datas = $row;
    }
    return $datas;
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
  
  function get_sq_route($route_pattern) {
    global $pdo;
    $datas = [];

    $sql = "SELECT * FROM sq_route WHERE route_id='$route_pattern'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
      $datas = $row;
    }
    return $datas;
  }

?>