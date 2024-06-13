<?php
  require_once('function.php');
  session_start();
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
  $today = date('Y/m/d');
  $user_code = $_SESSION["login"];
  $sq_no = $_POST['sq_no'] ?? '';
  $sq_line_no = $_POST['sq_line_no'] ?? '';
  $dept_id = $_POST['dept_id'] ?? '';
  $title = $_POST['title'] ?? '';
  $route_pattern = $_POST['route_pattern'] ?? '';
  $comments = $_POST['comments']; //スキップコメント
  $success = true;

  if (isset($_POST['skip'])) {   
    try {
      $pdo->beginTransaction();
      //テーブルID : sq_detail_tr / テーブル名称：営業依頼書・明細T/R
      cu_sq_detail_tr();

      //スキップ処理が行われた場合、skip_log_trを更新する。
      cu_skip_log_tr();

      //※sq_route_tr  の、自部署の、入力日、確認日、承認日　に処理日をセットして更新する
      cu_sq_route_tr();

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

    //更新処理にエラーがなければメール送信する
    if ($success) {
      include('sq_mail_send4.php');
    }
  }

  //テーブルID : sq_detail_tr / テーブル名称：営業依頼書・明細T/R
  function cu_sq_detail_tr() {
    global $pdo;
    global $today;
    global $sq_no;
    global $sq_line_no;
    global $dept_id;
    global $user_code;
    global $comments;
    global $route_pattern;
    $next_dept_id = '';

    //次の部署IDを取得する
    $sql_route = "SELECT COALESCE (
                    CASE 
                      WHEN route1_dept = '$dept_id' THEN COALESCE (route2_dept, route3_dept, route4_dept, route5_dept)
                    END,
                    CASE
                      WHEN route2_dept = '$dept_id' THEN COALESCE (route3_dept, route4_dept, route5_dept)
                    END,
                    CASE 
                      WHEN route3_dept = '$dept_id' THEN COALESCE (route4_dept, route5_dept)
                    END,
                    CASE
                      WHEN route4_dept = '$dept_id' THEN COALESCE (route5_dept)
                    END
                  ) AS next_dept_id
                  FROM sq_route
                  WHERE route_id = :route_id";
    $stmt_route = $pdo->prepare($sql_route);
    $stmt_route->bindParam(':route_id', $route_pattern);
    $stmt_route->execute();
    $row = $stmt_route->fetch(PDO::FETCH_ASSOC);
    if ($row['next_dept_id']) {
      $next_dept_id = $row['next_dept_id'];
    }

    $data = [
      'sq_no' => $sq_no,
      'sq_line_no' => $sq_line_no,
      'processing_dept' => $next_dept_id,
      'processing_status' => NULL,
      'skip_person' => $user_code,
      'skip_date' => $today,
      'skip_comments' => $comments
    ];

    $sql = 'UPDATE sq_detail_tr SET processing_dept=:processing_dept, processing_status=:processing_status, skip_person=:skip_person,
          skip_date=:skip_date, skip_comments=:skip_comments WHERE sq_no=:sq_no AND sq_line_no=:sq_line_no';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);
  }

  //スキップ処理が行われた場合、skip_log_trを更新する。
  function cu_skip_log_tr() {
    global $pdo;
    global $today;
    global $sq_no;
    global $sq_line_no;
    global $dept_id;
    global $user_code;
    global $comments;

    $datas = [
      'dept_id' => $dept_id,
      'sq_no' => $sq_no,
      'sq_line_no' => $sq_line_no,
      'skip_person' => $user_code,
      'comments' => $comments
    ];

    $sql = "SELECT * FROM skip_log_tr WHERE dept_id=:dept_id AND sq_no=:sq_no AND sq_line_no=:sq_line_no";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':dept_id', $dept_id);
    $stmt->bindParam(':sq_no', $sq_no);
    $stmt->bindParam(':sq_line_no', $sq_line_no);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
      $datas['add_date'] = $today;
      $sql1 = 'INSERT INTO skip_log_tr (dept_id, sq_no, sq_line_no, skip_person, comments, add_date) VALUES (:dept_id, :sq_no, :sq_line_no, :skip_person, :comments, :add_date)';
      
    } else {
      $datas['upd_date'] = $today;
      $sql1 = 'UPDATE skip_log_tr SET dept_id=:dept_id, sq_no=:sq_no, sq_line_no=:sq_line_no, skip_person=:skip_person, comments=:comments, upd_date=:upd_date';
    }

    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute($datas);
  }

  /**
   * sq_route_tr  の、自部署の、入力日、確認日、承認日　に処理日をセットして更新する
   */
  function cu_sq_route_tr() {
    global $pdo;
    global $today;
    global $sq_no;
    global $sq_line_no;
    global $dept_id;
    global $route_pattern;

    $datas = [
      'sq_no' => $sq_no,
      'sq_line_no' => $sq_line_no,
      'route_id' => $route_pattern,
      'upd_date' => $today
    ];    

    $sql1 = "SELECT * FROM sq_route_tr WHERE route_id='$route_pattern' AND sq_no='$sq_no' AND sq_line_no='$sq_line_no'";
    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute();
    $row = $stmt1->fetch(PDO::FETCH_ASSOC);
    if ($row) {
      for ($i = 1; $i <= 5; $i++) {
        if ($row['route'.$i.'_dept'] == $dept_id) {
          $datas['route' . $i . '_receipt_date'] = $today;
          $datas['route' . $i . '_entrant_date'] = $today;
          $datas['route' . $i . '_confirm_date'] = $today;
          $datas['route' . $i . '_approval_date'] = $today;
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
?>