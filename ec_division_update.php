<?php
session_start();
header('Program-id: estimate_entry.php');
header('Content-type: text/html; charset=utf-8');
require_once('function.php');

// DB接続
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

$process = $_POST['process'];
$code_key = $_POST['code_key'];
$code_name = $_POST['code_name'];
$code_no = $_POST['code_no'];
$success = false;

try {
    $pdo->beginTransaction();
    if ($process == 'update') {
        // Update the existing record
        $sql = "UPDATE ec_code_master SET code_name = ? WHERE code_key = ? AND code_no = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$code_name, $code_key, $code_no]);
    } else {
        // Insert a new record
        $sql = "INSERT INTO ec_code_master (code_key, code_no ,code_name) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$code_key,$code_no, $code_name]);
    }
    $success = true;
    $pdo->commit();
} catch (Exception $e) {
    $success = false;
    $pdo->rollback();
    error_log("PDO Exception: " . $e->getMessage(),3,'error_log.txt');
}

if ($success) {
    header('Location: ec_division_input1.php');
} else {
    echo "<script>window.location.href='ec_division_input2.php?err=exceErr'</script>";
}
?>
