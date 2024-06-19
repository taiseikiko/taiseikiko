<?php
  require_once('function.php');
  //初期処理
  $comments = '';
  $disabled_btn = 'disabled';

  //Parent Pageからデータを取得
  $sq_no = $_GET['sq_no'] ?? '';
  $sq_line_no = $_GET['sq_line_no'] ?? '';
  $dept_id = $_GET['dept_id'] ?? '';
  $title = $_GET['title'] ?? '';
  $route_pattern = $_GET['route_pattern'] ?? '';
  $e_title = substr($title, 3);

  if (($e_title == 'confirm') || ($e_title == 'approve')) {
    $disabled_btn = '';
  }
?>