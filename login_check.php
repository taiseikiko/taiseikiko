<?php
  session_start();
  require_once('function.php');
  // パラメーター取得
  $u_id = $_POST['u_id'];
  // ログインユーザー
  $_SESSION['login'] = $u_id;
  $pass = $_POST['pass'];
  $token = $_POST['token'];
  $_SESSION['e_msg'] = "";
  $message = "";
  $_SESSION['login_dept'] = "";
  $_SESSION['pgm_id'] = "";  

  // CSRF チェック
  if ($token != $_SESSION['token']) {
    // リダイレクト
    $_SESSION['error_status'] = 2;
    redirect_to_login();
    exit();
  }
/*
*/

  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

  // ユーザー登録
  $sql1 = "SELECT * FROM employee WHERE employee_code = '$u_id';";
  $stmt1 = $pdo->prepare($sql1);
  $stmt1->execute();

  if($row = $stmt1->fetch(PDO::FETCH_ASSOC)){
    if($_POST['pass'] != $row['pass']){
        $message="パスワードが違います";
    }
    else{
      //メールから来た場合
      if(isset($_SESSION['request_url'])) {
        $url = $_SESSION['request_url'];
        unset($_SESSION['request_url']);
        include("index_data_set.php");
      } else {
        $url = 'index.php';
      }    
      header("Location: " . $url); 
    }
  }else{
    $message="ユーザーIDが違います";
  }

  if(!empty($message)){
    $_SESSION['e_msg'] = htmlspecialchars($message);
    header("Location: login.php"); //ログインページにリダイレクト
  }

?>