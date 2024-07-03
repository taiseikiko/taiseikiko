<?php
session_start();
require_once('function.php');

// Check CSRF token
if ($_SESSION['token'] !== $_POST['csrf_token']) {
    die('Invalid CSRF token');
}

// DB接続
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
$success = true;
$process = $_POST['process'];
$zk_div_name = $_POST['zk_div_name'] ?? null;
$zk_division = getZkDiv($pdo, $zk_div_name);
$zk_tp = $_POST['zk_tp'] ?? null;
$zk_no = $_POST['zk_no'] ?? null;
$zk_div_data = $_POST['zk_div_data'] ?? null;

function getZkDiv($pdo, $zk_div_name) {
  $sql = "SELECT DISTINCT zk_division FROM sq_zk2 WHERE zk_div_name = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$zk_div_name]);
  $zk_divisions = $stmt->fetchAll(PDO::FETCH_COLUMN);
  $zk_division = implode(", ", $zk_divisions);
  return $zk_division;
}


try {
  $pdo->beginTransaction();
  $add_date = date('Y/m/d');

  if ($process == 'create') {
    //新規作成の場合   
    $sql_check = "SELECT COUNT(*) FROM sq_zk2 WHERE zk_division = ? AND zk_div_name = ? AND zk_tp = ? AND zk_no = ? AND zk_div_data = ?";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([$zk_division, $zk_div_name, $zk_tp, $zk_no, $zk_div_data]);
    $count = $stmt_check->fetchColumn();

    if ($count > 0) {
      $sql_delete = "DELETE FROM sq_zk2 WHERE zk_division = ? AND zk_div_name = ? AND zk_tp = ? AND zk_no = ? AND zk_div_data = ?";
      $stmt_delete = $pdo->prepare($sql_delete);
      $stmt_delete->execute([$zk_division, $zk_div_name, $zk_tp, $zk_no, $zk_div_data]);
    }
    
    $sql = "INSERT INTO sq_zk2 (zk_division, zk_div_name, zk_tp, zk_no, zk_div_data, add_date) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$zk_division, $zk_div_name, $zk_tp, $zk_no, $zk_div_data, $add_date]);
  } else if ($process == 'update') {
    //更新の場合
    $original_zk_division = $_POST['original_zk_division'];
    $original_zk_div_name = $_POST['original_zk_div_name'];
    $original_zk_tp = $_POST['original_zk_tp'];
    $original_zk_no = $_POST['original_zk_no'];
    $original_zk_div_data = $_POST['original_zk_div_data'];
    $upd_date = date('Y/m/d');
    
    $sql_delete = "DELETE FROM sq_zk2 WHERE zk_division = ? AND zk_div_name = ? AND zk_tp = ? AND zk_no = ? AND zk_div_data = ?";
    $stmt_delete = $pdo->prepare($sql_delete);
    $stmt_delete->execute([$original_zk_division, $original_zk_div_name, $original_zk_tp, $original_zk_no, $original_zk_div_data]);

    $sql = "INSERT INTO sq_zk2 (zk_division, zk_div_name, zk_tp, zk_no, zk_div_data,add_date, upd_date) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$zk_division, $zk_div_name, $zk_tp, $zk_no, $zk_div_data, $add_date, $upd_date]);
  }

  $pdo->commit();
} catch (PDOException $e) {
  $success = false;
  $pdo->rollBack();
  error_log("PDO Exception: " . $e->getMessage(), 3, 'error_log.txt');
}
if ($success) {
    echo "<script>
    window.location.href='zk_division_input1.php';
    </script>";
} else {
    echo "<script>
    window.location.href='zk_division_input2.php?err=exceErr';
    </script>";
}
