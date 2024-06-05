<?php

// sessionの場所を変更
//session_save_path('./session');

  session_start();
  require_once('function.php');
  header('Content-type: text/html; charset=utf-8');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する

// ヘッダーセット
  header_set1();
?>
<style>
.fsize{
  font-size: 24px;
  text-align: center;
}

.login {
  width: 300px;
  margin: 0 auto;
  padding: 20px;
  background-color: #ffffff;
  border: 1px solid #ccc;
  border-radius: 5px;
}

h2 {
  text-align: center;
  margin-bottom: 20px;
}

input[type="text"],
input[type="password"] {
  width: 93%;
  padding: 10px;
  margin-bottom: 10px;
  border: 1px solid #ccc;
  border-radius: 4px;
}

button[type="submit"] {
  width: 100%;
  padding: 10px;
  background-color: #4CAF50;
  color: #ffffff;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

button[type="submit"]:hover {
  background-color: #45a049;
}
</style>

</head>
<body>
  <br><br>
  <div class="login">

  <?php // エラー処理
    if(isset($_SESSION['e_msg'])){
        echo '<h2 style="color:red">'.$_SESSION['e_msg'].'</h2>';
      }
  ?>
      <form action="login_check.php" method="post" novalidate>
        <img src="img/cplogo.png" width="80%">
        <p class="fsize">(仮)Notes重要機能移行</p>
        <input type="text" placeholder="Username" name="u_id"/>
        <input type="password" placeholder="Password" name="pass" />
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($_SESSION['token'], ENT_QUOTES, "UTF-8") ?>">
        <button type="submit">ログイン</button>
      </form>
    </div>
</body>
</html>