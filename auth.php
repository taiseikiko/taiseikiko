<?php
  require_once('function.php');
  $url = $_SERVER['REQUEST_URI'];

  // ログインされているのかを チェックする
  if (!isset($_SESSION['login'])) {
    $_SESSION['request_url'] = $url;
    redirect_to_login();
    exit();
  }
?>