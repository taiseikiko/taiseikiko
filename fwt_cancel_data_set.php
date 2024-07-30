<?php
  require_once('function.php');
  //初期処理
  $comments = '';
  $err = $_GET['err']?? '';
  //Parent Pageからデータを取得
  $fwt_m_no = $_GET['fwt_m_no'] ?? '';
?>