<?php
  // メインのスライド画像をセット
  $slider1 = 'img/sample1.jpg';
  $slider2 = 'img/sample2.jpg';
  $slider3 = 'img/sample3.jpg';
  $size_w = '80';
?>

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

    <!-- グラフ定義 Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.2.0/chart.min.js" integrity="sha512-VMsZqo0ar06BMtg0tPsdgRADvl0kDHpTbugCBBrL55KmucH6hP9zWdLIWY//OTfMnzz6xWQRxQqsUFefwHuHyg==" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@next/dist/chartjs-adapter-date-fns.bundle.min.js"></script>

    <!-- Slider スライダーのCSS -->
  </head>
  <body class="surface has-scrollbar" ontouchstart style="width:80%">
    <div class="page">
      <header>
        <img src="img/cplogo.png" width="30%">
        <section class="social">
          <!-- <div class="c1">使用者：'.$_SESSION['office_name'].'　'.$_SESSION['user_name'].'</div> -->
          <a role="button" ><font color="blue"><b>使用者：<?php echo $_SESSION['office_name'].'　'.$_SESSION['user_name'] ?></b></font></a>
          <br>
          <a role="button" href="javascript:void(0)" onclick="child1_open()">パスワード変更</a>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <a role="button" href="logout.php">ログオフ</a>
        </section>
      </header>

      <div id="nav-container">
        <div class="bg"></div>
        <div id="icon-bar-bg" >
          <div class="button" tabindex="0" onclick="focus_icon_bar()" >
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </div>
        </div>
        <div id="nav-content" tabindex="0" style="display: none">
          <ul>
            <li><a href="#intro"><b>【処理メニュー】</b></a></li>
            <?php if($_SESSION['m1'] =="1"){ 
              $s_menu1 = '
              <li>
                <details>
                  <summary><a href="#components">営業依頼書</a></summary>
                  <ul>
                    <li><a href="sales_request_input1.php">営業依頼書　登録</a></li>
                  </ul>
                </details>
              </li>';
              echo $s_menu1;}
            ?>
            <?php if($_SESSION['m2'] =="1"){ 
              $s_menu2 = '
              <li>
                <details>
                  <summary><a href="#components">依頼書</a></summary>
                  <ul>
                    <li><a href="">依頼書　登録</a></li>
                  </ul>
                </details>
              </li>';
              echo $s_menu2;}
            ?>

            <?php if($_SESSION['m3'] =="1"){ 
                $s_menu3 = '
                <li>
                  <details>
                    <summary><a href="#components">電子カード処理</a></summary>
                    <ul>
                      <li><a href="">電子カード処理　登録</a></li>
                    </ul>
                  </details>
                </li>';
                echo $s_menu3;}
            ?>

            <?php if($_SESSION['m4'] =="1"){ 
                $s_menu4 = '
                <li>
                  <details>
                    <summary><a href="#components">図面管理</a></summary>
                    <ul>
                      <li><a href="">図面　登録</a></li>
                    </ul>
                  </details>
                </li>';
                echo $s_menu4;}
            ?>

            <?php if($_SESSION['m5'] =="1"){ 
                $s_menu5 = '
                <li>
                  <details>
                    <summary><a href="#components">見学・立会・研修依頼</a></summary>
                    <ul>
                      <li><a href="">見学・立会・研修依頼　登録</a></li>
                    </ul>
                  </details>
                </li>';
                echo $s_menu5;}
            ?>

            <?php if($_SESSION['m6'] =="1"){ 
                $s_menu6 = '
                <li>
                  <details>
                    <summary><a href="#components">既存工事実績</a></summary>
                    <ul>
                      <li><a href="">既存工事実績　登録</a></li>
                    </ul>
                  </details>
                </li>';
                echo $s_menu6;}
            ?>
            <?php if($_SESSION['m7'] =="1"){ 
                $s_menu7 = '
                <li>
                  <details>
                    <summary><a href="#components">マスター関連</a></summary>
                    <ul>
                      <li><a href="receptionist_input.php">営業依頼書・受付担当　登録</a></li>
                    </ul>
                    <ul>
                      <li><a href="sq_class_input1.php">分類M/F　登録</a></li>
                    </ul>
                    <ul>
                      <li><a href="sq_zkm_input1.php">材工名M/F　登録</a></li>
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
                  </details>
                </li>';
                echo $s_menu7;}
            ?>
          </ul>
        </div>
      </div>

      <main>
        <div class="content">

<style>
  .img-box{
    width: <?php echo $size_w; ?>%;
    height: 46vw;
    overflow: hidden;
    position: relative;
  }
  .img-box>div{
    position: absolute;
    top: 0;
    left: 0;
    width: <?php echo $size_w; ?>%;
    height: 46vw;
    background-position: center center;
    background-size: cover;
    background-repeat: no-repeat;
    z-index: 10;
    opacity: 0;
    animation-name: fade;
    animation-duration: 12s;
    animation-iteration-count: infinite;
  }
  @keyframes fade {
    0%{
      opacity: 0;
    }
    20%{
      opacity: 7;
    }
    80%{
      opacity: 0;
      transform: scale(1.2);
    }
    100%{
      z-index: 0;
      opacity: 0;
    }
  }
  /* 1枚目のスライド */
  .img-box>div:first-of-type{
    background-image: url("<?php echo $slider1; ?>");
  }

  /* 2枚目のスライド */
  .img-box>div:nth-of-type(2){
    background-image: url("<?php echo $slider2; ?>");
    animation-delay: 4s;
  }

  /* 3枚目のスライド */
  .img-box>div:last-of-type{
    background-image: url("<?php echo $slider3; ?>");
    animation-delay: 8s;
  }

  main {
    display: flex;
    flex-direction: column;
    height: 100%;
  }

  main h2 span {
    color: #BF7497;
  }

  main p {
    line-height: 1.5;
    font-weight: 200;
    margin: 20px 0;
  }

  main small {
    font-weight: 300;
    color: #888;
  }

  #nav-container {
    position: fixed;
    height: 100vh;
    /* width: 100%; */
    pointer-events: none;
  }
  #nav-container .bg {
    position: absolute;
    top: 70px;
    left: 0;
    /* width: 100%; */
    height: calc(100% - 70px);
    visibility: visible;
    opacity: 0;
    transition: .3s;
  }

  #nav-container * {
    visibility: visible;
  }

  .button {
    margin-top: -8px;
    position: relative;
    display: flex;
    flex-direction: column;
    justify-content: center;
    z-index: 1;
    -webkit-appearance: none;
    border: 0;
    background: transparent;
    border-radius: 0;
    height: 70px;
    width: 30px;
    cursor: pointer;
    pointer-events: auto;
    margin-left: 25px;
    touch-action: manipulation;
    margin-left: 30px;
  }
  .icon-bar {
    display: block;
    width: 100%;
    height: 3px;
    background: #aaa;
    transition: .3s;
  }

  .icon-bar + .icon-bar {
    margin-top: 5px;
  }

  #nav-content {
    padding-left: 30px;
    padding-right: 20px;
    width: 200px;
    max-width: 300px;
    position: absolute;
    top: 5;
    left: 0;
    height: calc(100% - 170px);
    background: white;
    pointer-events: auto;
    -webkit-tap-highlight-color: rgba(0,0,0,0);
    transition: transform .3s;
    will-change: transform;
    contain: paint;
    overflow-y: auto;
  }

  #nav-content ul {
    height: 100%;
    display: flex;
    flex-direction: column;
  }

  #nav-content li a {
    padding: 10px 5px;
    display: block;
    text-transform: uppercase;
   
  }

  #nav-content li a:hover {
    color: #0776db  ;
  }

  #nav-content li:not(.small) + .small {
    margin-top: auto;
  }

  .small {
    display: flex;
    align-self: center;
  }

  .small a {
    font-size: 12px;
    font-weight: 400;
    color: #888;
  }
  .small a + a {
    margin-left: 15px;
  }

  /* #nav-container:focus-within #nav-content {
    transform: none;
  } */

  html, body {
    height: 100%;
  }

  a,
  a:visited,
  a:focus,
  a:active,
  a:link {
    text-decoration: none;
    outline: 0;
  }

  a {
    color: currentColor;
    transition: .2s ease-in-out;
  }

  h1, h2, h3, h4 {
    margin: 0;
  }

  ul {
    padding: 0;
    list-style: none;
  } 

  #icon-bar-bg {
    width : 223px;
    height : 60px;
  }
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
  function focus_icon_bar() {
    var toggle = document.getElementById("nav-content");
    if (toggle.style.display === "none") {
      toggle.style.display = "inline-block";
      document.getElementById('pagetitle').style.marginLeft = "10%";
      document.getElementById('icon-bar-bg').style.backgroundColor = "";
    } else {
      toggle.style.display = "none";
      document.getElementById('pagetitle').style.marginLeft = "2%";
      document.getElementById('icon-bar-bg').style.backgroundColor = "";
    }
    
  }
</script>