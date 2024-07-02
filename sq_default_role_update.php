<?php
session_start();
require_once('function.php');

// DB接続
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

$dept_id = $_POST['dept_id'];
$group_id = $_POST['group_id'] ?? '';
$entrant = $_POST['entrant'];
$confirmer = $_POST['confirmer'];
$approver = $_POST['approver'];
$current_date = date('Y/m/d');

// ON DUPLICATE KEY UPDATE
$sql = "INSERT INTO sq_default_role (dept_id, group_id, entrant, confirmer, approver, upd_date) 
      VALUES (?, ?, ?, ?, ?, ?)
      ON DUPLICATE KEY UPDATE
      entrant = VALUES(entrant), confirmer = VALUES(confirmer), approver = VALUES(approver), upd_date = VALUES(upd_date)";
$stmt = $pdo->prepare($sql);

try {
  $stmt->execute([$dept_id, $group_id, $entrant, $confirmer, $approver, $current_date]);
  // 更新が成功したら sq_default_role.php にリダイレクトします
  header('Location: sq_default_role.php');
  exit();
} catch (PDOException $e) {
  echo "Error: " . $e->getMessage();
  exit();
}
