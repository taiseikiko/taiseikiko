<?php
  session_start();
  require_once('function.php');
  $token = $_POST['token'];
  // CSRF チェック
  if ($token != $_SESSION['token']) {
    // リダイレクト
    header("Location: login1.php"); //ログインページにリダイレクト
    exit();
  }
  //セッション破棄
  $_SESSION = array();
  // Destroy all sessions
  unset($_SESSION['login']);
  session_destroy();  
  ob_end_clean();
  //リダイレクト
  header("Location: login1.php"); //ログインページにリダイレクト
  exit();
?>