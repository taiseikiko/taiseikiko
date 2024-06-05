<?php
require_once('function.php');

// DB接続
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $dept_id = $_POST['dept_id'];
  $group_id = $_POST['group_id'];
  $entrant = $_POST['entrant'];
  $confirmor = $_POST['confirmor'];
  $approver = $_POST['approver'];
  $current_date = date('Y/m/d');

  // Check if the record already exists
  $sql = "SELECT COUNT(*) FROM sq_default_role WHERE dept_id = ? AND group_id = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$dept_id, $group_id]);
  $exists = $stmt->fetchColumn() > 0;

  if ($exists) {
    // Update existing record
    $sql = "UPDATE sq_default_role SET entrant = ?, confirmor = ?, approver = ?, upd_date = ? WHERE dept_id = ? AND group_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$entrant, $confirmor, $approver, $current_date, $dept_id, $group_id]);
  } else {
    // Insert new record
    // $sql = "INSERT INTO sq_default_role (dept_id, group_id, entrant, confirmor, approver, add_date) VALUES (?, ?, ?, ?, ?, ?)";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute([$dept_id, $group_id, $entrant, $confirmor, $approver, $current_date]);
  }

  header('Location: sq_default_role.php');
  exit();
}

echo json_encode($response);
