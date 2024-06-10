<?php
//define('DNS','mysql:host=mysql;dbname=taiseiinner1;charset=utf8');
define('DNS','mysql:host=192.168.3.15;dbname=taiseiinner1;charset=utf8');
define('USER_NAME', 'root');
define('PASSWORD', 'taisei2041');
define('SERVER', '192.168.3.15:3036');
define('SENDER_EMAIL', 'k-maeda@taiseikiko.com');
/*
* PDO の接続オプション取得
*/
function get_pdo_options() {
  return array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
               //PDO::MYSQL_ATTR_MULTI_STATEMENTS => false,
               PDO::ATTR_EMULATE_PREPARES => false);
}

/*
* AS/400へODBC接続
*/
function odbc_connect(){ 
  odbc_pconnect(
      'Driver={IBM i Access ODBC Driver 64-bit};System=192.168.1.240;Port=50000;protocol=TCPIP',
      'QSECOFR',
      'QSECOFR'
  );
}

/*
* CSRF トークン作成
*/
function get_csrf_token() {
 $token_legth = 16;//16*2=32byteS
 $bytes = openssl_random_pseudo_bytes($token_legth);
 return bin2hex($bytes);
}
/*
* URL の一時パスワードを作成
*/
function get_url_password() {
  $token_legth = 16;//16*2=32byte
  $bytes = openssl_random_pseudo_bytes($token_legth);
  return hash('sha256', $bytes);
}
/*
* ログイン画面へのリダイレクト
*/
function redirect_to_login() {
  header('HTTP/1.1 301 Moved Permanently');
  header('Location: login.php');
}
/*
* パスワードリセット画面へのリダイレクト
*/
function redirect_to_password_reset() {
  header('HTTP/1.1 301 Moved Permanently');
  header('Location: password_reset.php');
}
/*
* Welcome画面へのリダイレクト
*/
function redirect_to_welcome() {
  header('HTTP/1.1 301 Moved Permanently');
  header('Location: welcome.php');
}
/*
* 登録画面へのリダイレクト
*/
function redirect_to_register() {
  header('HTTP/1.1 301 Moved Permanently');
  header('Location: register.php');
}
/*
* ログインユーザーの部署ID
*/
function getDeptId($dept_code) {
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

  $dept_id = '';
  $sql = "SELECT dept_id FROM sq_dept WHERE sq_dept_code='$dept_code'";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($row) {
    $dept_id = $row['dept_id'];
  }
  return $dept_id;
}

/*
* 共通ヘッダー
*/
function header_set1() {
  $header1 = '
  <!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Information System Group</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- CSS Files -->
    <link rel="stylesheet" href="dist/7.css">
    <link rel="stylesheet" href="https://unpkg.com/7.css">
    <link rel="stylesheet" href="docs/docs.css">
    <link rel="stylesheet" href="docs/vs.css">

  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag() { dataLayer.push(arguments); }
    gtag("js", new Date());
    gtag("config", "G-G4ZQ4HN11K");
  </script>
  ';
  echo $header1;
}


/*
* 共通ヘッダー（コンテンツ画面）
*/
function header_set2() {
  $header2 = '
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Information System Group</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- CSS Files <link rel="stylesheet" href="https://unpkg.com/7.css">-->
    <link rel="stylesheet" href="dist/7.css">
    <link rel="stylesheet" href="docs/docs.css">
    <link rel="stylesheet" href="docs/vs.css">

    <!-- グラフ定義 Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.2.0/chart.min.js" integrity="sha512-VMsZqo0ar06BMtg0tPsdgRADvl0kDHpTbugCBBrL55KmucH6hP9zWdLIWY//OTfMnzz6xWQRxQqsUFefwHuHyg==" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@next/dist/chartjs-adapter-date-fns.bundle.min.js"></script>

  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag() { dataLayer.push(arguments); }
    gtag("js", new Date());
    gtag("config", "G-G4ZQ4HN11K");
  </script>

  </head>
  
<body class="surface has-scrollbar" ontouchstart style="width:80%">
  
  <!-- ======= Header ======= -->
  <header>
    <img src="img/cplogo.png" width="30%">

  <section class="social">
    <!-- <div class="c1">使用者：　</div> -->
    <a role="button" ><font color="blue"><b>使用者：　</b></font></a>
    <br>
    <a role="button" href="javascript:void(0)" onclick="child1_open()">パスワード変更</a>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <a role="button" href="logout.php">ログオフ</a>
  </section>  

  </header><!-- End Header -->
  
  <!-- ======= Sidebar ======= -->
  <aside>
    <ul class="tree-view has-connector has-collapse-button has-container has-scrollbar">
    <li><a href="#intro">処理メニュー</a></li>

    <li>
      <details open>
        <summary><a href="#components">問合せ対応</a></summary>
        <ul>
          <li><a href="inquiry_ent.php">受付内容　登録</a></li>
        </ul>
      </details>
    </li>
    <li>
      <details open>
        <summary><a href="#components">IT資産管理台帳</a></summary>
        <ul>
          <li><a href="it_ast_entry.php">IT資産管理台帳　保守</a></li>
          <li><a href="it_ast_inq.php">IT資産管理台帳　検索</a></li>
          <li><a href="it_ast_inq_lease.php">リース・レンタル状況</a></li>
        </ul>
      </details>
    </li>
    <li>
      <details open>
        <summary><a href="#components">開発ツール</a></summary>
        <ul>
          <li><a href="tools1.php">開発ツール1</a></li>
          <li><a href="hanko_name_ent.php">電子ハンコ名前登録</a></li>
          <li><a href="test_pdf1.php">HTML->PDF</a></li>
        </ul>
      </details>
    </li>
    <li>
      <details open>
        <summary><a href="#components">課題・要望　対応</a></summary>
        <ul>
          <li><a href="issue_ent.php">課題・要望　登録</a></li>
          <li><a href="issue_inq.php">課題・要望　照会</a></li>
        </ul>
      </details>
    </li>
    <li>
      <details open>
        <summary><a href="#components">目標管理</a></summary>
        <ul>
          <li><a href="pdca_objective_ent.php">目標設定</a></li>
          <li><a href="issue_ent.php">作業項目</a></li>
          <li><a href="issue_inq.php">作業日報</a></li>
        </ul>
      </details>
    </li>
    <li>
      <details open>
        <summary><a href="#components">マスター関連</a></summary>
        <ul>
          <li><a href="sq_class_input1.php">分類M/F　登録</a></li>
        </ul>
        <ul>
          <li><a href="sq_zkm_input1.php">材工名M/F　登録</a></li>
        </ul>
        <ul>
          <li><a href="zk_division_input1.php">材工名仕様M/F　登録</a></li>
        </ul>
        <ul>
          <li><a href="public_office_input1.php">官庁M/F　登録</a></li>
        </ul>
        <ul>
          <li><a href="customer_input1.php">得意先M/F　登録</a></li>
        </ul>
        <ul>
          <li><a href="sr_route_input1.php">部署ルートM/F　登録</a></li>
        </ul>
        <ul>
          <li><a href="sr_route_in_dept_input1.php">部署内ルートM/F　登録</a></li>
        </ul>
        <ul>
          <li><a href="sq_default_role.php">部署内初期ルート設定</a></li>
        </ul>
      </details>
    </li>
    <li><a href="listmail_request_input1.php">通知メール保守</a></li>
  </ul>
</aside><!-- End Sidebar-->
  
  ';
  echo $header2;
}


/*
* 共通フッター（コンテンツ画面）
*/
function footer_set() {
  $footer = '
  <!-- ======= Footer ======= -->
  <footer class="foot">
    Copyright <strong><span>情報システムグループ</span></strong>. All Rights Reserved
  </footer><!-- End Footer -->

  <!-- Vendor JS Files -->
<!-- 
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.min.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
-->

  <!-- Template Main JS File -->
<!-- 
  <script src="assets/js/main.js"></script>
-->

  <!-- パスワード変更ミニWindow -->
<!-- 
  <script src="assets/js/index.js"></script>
-->

</body>

</html>
  ';
  echo $footer;
}

/*
* 共通Javascript
*/
function footer_js() {
  $footer_js = '
  <!-- Vendor JS Files -->
<!-- 
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.min.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.min.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
-->

  <!-- Template Main JS File -->
<!-- 
  <script src="assets/js/main.js"></script>
-->

</body>

</html>
  ';
  echo $footer_js;
}




?>