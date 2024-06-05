<?php
  session_start();
  require_once('function.php');
  $token = $_POST['token'];
  // CSRF チェック
  if ($token != $_SESSION['token']) {
    // リダイレクト
      header("Location: login.php"); //ログインページにリダイレクト
    exit();
  }
  //セッション破棄
  $_SESSION = array();
  session_destroy();
  //リダイレクト
      header("Location: login.php"); //ログインページにリダイレクト
?>