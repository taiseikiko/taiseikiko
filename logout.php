<?php
  session_start();
  require_once('function.php');
  $token = $_POST['token'];

  //セッション破棄
  $_SESSION = array();
  // Destroy all sessions
  session_destroy();
  //リダイレクト
  header("Location: login.php"); //ログインページにリダイレクト
  exit();
?>