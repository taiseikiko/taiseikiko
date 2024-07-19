<?php
  // メインのスライド画像をセット
    $slider1 = 'img/sample1.jpg';
    $slider2 = 'img/sample2.jpg';
    $slider3 = 'img/sample3.jpg';
    $size_w = '80';
    require_once('auth.php');
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
<style>
.img-box{
  width: <?php echo $size_w; ?>;
  height: 46vw;
  overflow: hidden;
  position: relative;
}
.img-box>div{
  position: absolute;
  top: 0;
  left: 0;
  width: <?php echo $size_w; ?>;
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
</style>



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
    <!-- <div class="c1">使用者：'.$_SESSION['office_name'].'　'.$_SESSION['user_name'].'</div> -->
    <a role="button" ><font color="blue"><b>使用者：<?php echo $_SESSION['office_name'].'　'.$_SESSION['user_name'] ?></b></font></a>
    <br>
    <a role="button" href="javascript:void(0)" onclick="child1_open()">パスワード変更</a>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <a role="button" href="logout.php">ログオフ</a>
  </section>  

  </header><!-- End Header -->
  
  <!-- ======= Sidebar ======= -->
  <aside>
    <ul class="tree-view has-connector has-collapse-button has-container has-scrollbar">
    <li><a href="#intro"><b>【処理メニュー】</b></a></li>

<?php if($_SESSION['m1'] =="1"){ 
    $s_menu1 = '
    <li>
      <details>
        <summary><a href="#components">営業依頼書：営業</a></summary>
        <ul>
          <li><a href="sales_request_input1.php?title=input">営業依頼書　登録</a></li>
        </ul>
        <ul>
          <li><a href="sales_request_check1.php?title=check">営業依頼書　確認</a></li>
        </ul>
        <ul>
          <li><a href="sales_request_approve1.php?title=approve">営業依頼書　承認</a></li>
        </ul>
        <ul>
          <li><a href="sq_default_role.php">部署内初期ルート設定</a></li>
        </ul>
      </details>
    </li>';
    echo $s_menu1;}
?>

<?php if($_SESSION['m2'] =="1"){ 
    $s_menu1 = '
    <li>
      <details>
        <summary><a href="#components">営業依頼書：技術部</a></summary>
        <ul>
          <li><a href="sq_detail_tr_engineering_input1.php?title=td_receipt">営業依頼書：技術部　受付</a></li>
        </ul>
        <ul>
          <li><a href="sq_detail_tr_engineering_input1.php?title=td_entrant">営業依頼書：技術部　入力</a></li>
        </ul>
        <ul>
          <li><a href="sq_detail_tr_engineering_input1.php?title=td_confirm">営業依頼書：技術部　確認</a></li>
        </ul>
        <ul>
          <li><a href="sq_detail_tr_engineering_input1.php?title=td_approve">営業依頼書：技術部　承認</a></li>
        </ul>
        <ul>
          <li><a href="sq_default_role.php">部署内初期ルート設定</a></li>
        </ul>
      </details>
    </li>';
    echo $s_menu1;}
?>

<?php if($_SESSION['m3'] =="1"){ 
    $s_menu1 = '
    <li>
      <details>
        <summary><a href="#components">営業依頼書：営業管理部</a></summary>
        <ul>
          <li><a href="sales_route_input1.php?title=set_route">営業依頼書：ルート設定</a></li>
        </ul>
        <ul>
          <li><a href="sq_detail_tr_sales_management_input1.php?title=sm_receipt">営業依頼書：営業管理部　受付</a></li>
        </ul>
        <ul>
          <li><a href="sq_detail_tr_sales_management_input1.php?title=sm_entrant">営業依頼書：営業管理部　入力</a></li>
        </ul>
        <ul>
          <li><a href="sq_detail_tr_sales_management_input1.php?title=sm_confirm">営業依頼書：営業管理部　確認</a></li>
        </ul>
        <ul>
          <li><a href="sq_detail_tr_sales_management_input1.php?title=sm_approve">営業依頼書：営業管理部　承認</a></li>
        </ul>
        <ul>
          <li><a href="sq_default_role.php">部署内初期ルート設定</a></li>
        </ul>
      </details>
    </li>';
    echo $s_menu1;}
?>

<?php if($_SESSION['m4'] =="1"){ 
    $s_menu1 = '
    <li>
      <details>
        <summary><a href="#components">営業依頼書：工事管理部</a></summary>
        <ul>
          <li><a href="sq_detail_tr_const_management_input1.php?title=cm_receipt">営業依頼書：工事管理部　受付</a></li>
        </ul>
        <ul>
          <li><a href="sq_detail_tr_const_management_input1.php?title=cm_entrant">営業依頼書：工事管理部　入力</a></li>
        </ul>
        <ul>
          <li><a href="sq_detail_tr_const_management_input1.php?title=cm_confirm">営業依頼書：工事管理部　確認</a></li>
        </ul>
        <ul>
          <li><a href="sq_detail_tr_const_management_input1.php?title=cm_approve">営業依頼書：工事管理部　承認</a></li>
        </ul>
        <ul>
          <li><a href="sq_default_role.php">部署内初期ルート設定</a></li>
        </ul>
      </details>
    </li>';
    echo $s_menu1;}
?>

<?php if($_SESSION['m5'] =="1"){ 
    $s_menu1 = '
    <li>
      <details>
        <summary><a href="#components">営業依頼書：資材部</a></summary>
        <ul>
          <li><a href="sq_detail_tr_procurement_input1.php?title=pc_receipt">営業依頼書：資材部　受付</a></li>
        </ul>
        <ul>
          <li><a href="sq_detail_tr_procurement_input1.php?title=pc_entrant">営業依頼書：資材部　入力</a></li>
        </ul>
        <ul>
          <li><a href="sq_detail_tr_procurement_input1.php?title=pc_confirm">営業依頼書：資材部　確認</a></li>
        </ul>
        <ul>
          <li><a href="sq_detail_tr_procurement_input1.php?title=pc_approve">営業依頼書：資材部　承認</a></li>
        </ul>
        <ul>
          <li><a href="sq_default_role.php">部署内初期ルート設定</a></li>
        </ul>
      </details>
    </li>';
    echo $s_menu1;}
?>

<?php if($_SESSION['m6'] =="1"){ 
    $s_menu2 = '
    <li>
      <details>
        <summary><a href="#components">依頼書</a></summary>
        <ul>
          <li><a href="request_input1.php?title=request">依頼書　登録</a></li>
          <li><a href="receipt_input1.php?title=receipt">依頼受付～承認</a></li>
          <li><a href="request_item_input1.php">依頼案件マスター保守</a></li>
        </ul>
      </details>
    </li>';
    echo $s_menu2;}
?>

<?php if($_SESSION['m7'] =="1"){ 
    $s_menu3 = '
    <li>
      <details>
        <summary><a href="#components">電子カード処理</a></summary>
        <ul>
          <li><a href="card_input1.php">電子カード処理　登録、承認</a></li>
          <li><a href="card_route_in_dept_input1.php">部署内ルートマスター保守</a></li>
        </ul>
      </details>
    </li>';
    echo $s_menu3;}
?>

<?php if($_SESSION['m8'] =="1"){ 
    $s_menu4 = '
    <li>
      <details>
        <summary><a href="#components">図面管理</a></summary>
        <ul>
          <li><a href="dw_input1.php">図面　登録</a></li>
          <li><a href="dw_route_in_dept_input1.php">部署内ルートマスター保守</a></li>
        </ul>
      </details>
    </li>';
    echo $s_menu4;}
?>

<?php if($_SESSION['m9'] =="1"){ 
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

<?php if($_SESSION['m10'] =="1"){ 
    $s_menu6 = '
    <li>
      <details>
        <summary><a href="#components">既存工事実績</a></summary>
        <ul>
          <li><a href="ec_article_input1.php">既存工事実績　登録</a></li>
        </ul>
      </details>
    </li>';
    echo $s_menu6;}
?>

<?php if($_SESSION['m11'] =="1"){ 
    $s_menu7 = '
    <li>
      <details>
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
          <li><a href="sr_route_in_dept_input1.php">部署内ルートM/F　登録</a></li>
        </ul>

      </details>
    </li>';
    echo $s_menu7;}
?>

<?php if($_SESSION['m12'] =="1"){ 
    $s_menu8 = '
    <li>
      <details>
        <summary><a href="#components">情シスメンテ項目</a></summary>
        <ul>
          <li><a href="sr_route_input1.php">部署ルートM/F　登録</a></li>
        </ul>
        <ul>
          <li><a href="listmail_request_input1.php">通知メール保守</a></li>
        </ul>
        <ul>
          <li><a href="employee_ent1.php">社員マスタ保守</a></li>
        </ul>
      </details>
    </li>';
    echo $s_menu8;}
?>
  </ul>
</aside><!-- End Sidebar-->
