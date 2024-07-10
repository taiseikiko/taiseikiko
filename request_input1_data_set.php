<?php
require_once('function.php');
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
$dept_id = getDeptId($dept_code);
$dw_datas = [];
$class_name = $requester = $publish_department = '';


//検索ボタンを押下した場合


