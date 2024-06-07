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


$sql = "UPDATE sq_default_role SET entrant = ?, confirmer = ?, approver = ?, upd_date = ? WHERE dept_id = ? AND group_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$entrant, $confirmer, $approver, $current_date, $dept_id, $group_id]);

// 更新が成功したら sq_default_role.php にリダイレクトします
header('Location: sq_default_role.php');
exit();
?>
