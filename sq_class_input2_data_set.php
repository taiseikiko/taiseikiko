<?php
  // 初期処理
  require_once('function.php');
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

  function getClassCode() {
    global $pdo;
    //分類マスタからMAXデータ取得する
    $sql = "SELECT MAX(class_code) as max FROM sq_class";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $max_class_code = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($max_class_code) {
      $class_code = $max_class_code['max'] + 1;
    } else {
      $class_code = 1;
    }
    return $class_code;
  }

  function getClassDatasByClassCode($class_code) {
    global $pdo;
    $sql = "SELECT * FROM sq_class WHERE class_code = '$class_code'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $datas = $stmt->fetchAll();
    
    return $datas;
  }

  function reg_or_upd_sq_class() {
    global $pdo;
    $today = date('Y/m/d');
    $success = true;

    if (isset($_POST['process'])) {
      //validation
      // if (empty ($_POST['class_name'])) {
      //   $class_name_err = '「分類名称」を入力して下さい。';
      //   $err_msgs = true;
      //   header('Location: sq_class_input2.php?err_msg='.$class_name_err);
      // }

      //if (!$err_msgs) {
        //新規作成or更新の場合
      $process = $_POST['process'];
      try {
        if ($process == 'create') {
          //新規作成の場合      
          $data = [
            'class_code' => $_POST['class_code'],
            'class_name' => $_POST['class_name'],
            'add_date' => $today
          ];
          $sql = "INSERT INTO sq_class (class_code, class_name, add_date) VALUES (:class_code, :class_name, :add_date)";
          $stmt = $pdo->prepare($sql);
        } else {
          //更新の場合
          $data = [
            'class_code' => $_POST['class_code'],
            'class_name' => $_POST['class_name'],
            'upd_date' => $today
          ];
          $sql = "UPDATE sq_class SET class_name=:class_name, upd_date=:upd_date WHERE class_code=:class_code";
          $stmt = $pdo->prepare($sql);       
        }

        $pdo->beginTransaction();
        $stmt->execute($data);
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
      return $success;
    }
  }
?>