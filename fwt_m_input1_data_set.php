<?php
require_once('function.php');
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
//ログインユーザーの部署ID
$dept_cd = $_POST['dept_code'] ?? $dept_code;
$dept_id = getDeptId($dept_cd);
$title1 = $_POST['title'] ?? $_GET['title'];


