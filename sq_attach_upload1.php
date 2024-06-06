<?php

$from = isset($_GET['from']) ? $_GET['from'] : '';
$title = isset($_POST['title']) ? $_POST['title'] : '';
$sq_no = $_POST['sq_no'];
//'sq' = 【 営業依頼書：依頼入力】【アップロードボタン】
//e1 = 【　営業依頼書：技術部　入力　】【技術員入力画面・見積処理】【見積図面のアップロードボタン】
//e2 = 【　営業依頼書：技術部　入力　】【技術員入力画面・見積処理】【資料のアップロードボタン】
//e3 = 【　営業依頼書：技術部　入力　】【技術員入力画面・図面・資料処理】納入仕様書のアップロードボタン 
//e4 = 【　営業依頼書：技術部　入力　】【技術員入力画面・図面・資料処理】参考図面のアップロードボタン 
//e5 = 【　営業依頼書：技術部　入力　】【技術員入力画面・図面・資料処理】資料のアップロードボタン 
//sm1 = 【　営業依頼書：営業管理部　入力　】【営業管理部入力画面・見積処理】見積原価のアップロードボタン 
//sm2 = 【　営業依頼書：営業管理部　入力　】【営業管理部入力画面・見積処理】見積定価のアップロードボタン 
$engineering_dir1 = '<meta http-equiv="refresh" content=" 1; url=./sq_detail_tr_engineering_input1.php?title=' . urlencode($title) . '">';
$engineering_dir2 = '<meta http-equiv="refresh" content=" 2; url=./sq_detail_tr_engineering_input1.php?title=' . urlencode($title) . '">';
$engineering_dir3 = 'header("Location:sq_detail_tr_engineering_input1.php?title=".urlencode($title))';

$sales_manag_dir1 = '<meta http-equiv="refresh" content=" 1; url=./sq_detail_tr_sales_management_input1.php?title=' . urlencode($title) . '">';
$sales_manag_dir2 = '<meta http-equiv="refresh" content=" 2; url=./sq_detail_tr_sales_management_input1.php?title=' . urlencode($title) . '">';
$sales_manag_dir3 = 'header("Location:sq_detail_tr_sales_management_input1.php?title=".urlencode($title))';

$const_manag_dir1 = '<meta http-equiv="refresh" content=" 1; url=./sq_detail_tr_const_management_input1.php?title=' . urlencode($title) . '">';
$const_manag_dir2 = '<meta http-equiv="refresh" content=" 2; url=./sq_detail_tr_const_management_input1.php?title=' . urlencode($title) . '">';
$const_manag_dir3 = 'header("Location:sq_detail_tr_const_management_input1.php?title=".urlencode($title))';

// 更新PGM
switch ($from) {
  case 'sr':
    include("sales_request_update.php");
    $uploadDir = "document/sales_management/";  //登録ファイル名
    $tmp_file_name = $_FILES["uploaded_file"]["tmp_name"];
    $file_name = $_FILES["uploaded_file"]["name"];
    $save_file_name = $sq_no."_";
    $redirect1 = '<meta http-equiv="refresh" content=" 1; url=./sales_request_input01.php">';
    $redirect2 = '<meta http-equiv="refresh" content=" 2; url=./sales_request_input01.php">';
    $redirect3 = header("Location:sales_request_input01.php");
    break;
  case 'e1':
    include("sq_detail_tr_engineering_update.php");
    $uploadDir = "document/engineering/quotation/";  //登録ファイル名
    $tmp_file_name = $_FILES["uploaded_file1"]["tmp_name"];
    $file_name = $_FILES["uploaded_file1"]["name"];
    $save_file_name = $sq_no."_見積図面_";
    $redirect1 = $engineering_dir1;
    $redirect2 = $engineering_dir2;
    $redirect3 = $engineering_dir3;
    break;
  case 'e2':
    include("sq_detail_tr_engineering_update.php");
    $uploadDir = "document/engineering/quotation/";  //登録ファイル名
    $tmp_file_name = $_FILES["uploaded_file2"]["tmp_name"];
    $file_name = $_FILES["uploaded_file2"]["name"];
    $save_file_name = $sq_no."_資料_";
    $redirect1 = $engineering_dir1;
    $redirect2 = $engineering_dir2;
    $redirect3 = $engineering_dir3;
    break;
  case 'e3':
    include("sq_detail_tr_engineering_update.php");
    $uploadDir = "document/engineering/drawing/";  //登録ファイル名
    $tmp_file_name = $_FILES["uploaded_file1"]["tmp_name"];
    $file_name = $_FILES["uploaded_file1"]["name"];
    $save_file_name = $sq_no."_納入仕様書_";
    $redirect1 = $engineering_dir1;
    $redirect2 = $engineering_dir2;
    $redirect3 = $engineering_dir3;
    break;
  case 'e4':
    include("sq_detail_tr_engineering_update.php");
    $uploadDir = "document/engineering/drawing/";  //登録ファイル名
    $tmp_file_name = $_FILES["uploaded_file2"]["tmp_name"];
    $file_name = $_FILES["uploaded_file2"]["name"];
    $save_file_name = $sq_no."_参考図面_";
    $redirect1 = $engineering_dir1;
    $redirect2 = $engineering_dir2;
    $redirect3 = $engineering_dir3;
    break;
  case 'e5':
    include("sq_detail_tr_engineering_update.php");
    $uploadDir = "document/engineering/drawing/";  //登録ファイル名
    $tmp_file_name = $_FILES["uploaded_file3"]["tmp_name"];
    $file_name = $_FILES["uploaded_file3"]["name"];
    $save_file_name = $sq_no."_資料_";
    $redirect1 = $engineering_dir1;
    $redirect2 = $engineering_dir2;
    $redirect3 = $engineering_dir3;
    break;
  case 'sm1':
    include("sq_detail_tr_sales_management_update.php");
    $uploadDir = "document/sales_management/";  //登録ファイル名
    $tmp_file_name = $_FILES["uploaded_file1"]["tmp_name"];
    $file_name = $_FILES["uploaded_file1"]["name"];
    $save_file_name = $sq_no."_見積原価_";
    $redirect1 = $sales_manag_dir1;
    $redirect2 = $sales_manag_dir2;
    $redirect3 = $sales_manag_dir3;
    break;
  case 'sm2':
    include("sq_detail_tr_sales_management_update.php");
    echo 'here';
    $uploadDir = "document/sales_management/";  //登録ファイル名
    $tmp_file_name = $_FILES["uploaded_file2"]["tmp_name"];
    $file_name = $_FILES["uploaded_file2"]["name"];
    $save_file_name = $sq_no."_見積定価_";
    $redirect1 = $sales_manag_dir1;
    $redirect2 = $sales_manag_dir2;
    $redirect3 = $sales_manag_dir3;
    break;
  case 'cm1':
    include("sq_detail_tr_const_management_update.php");
    $uploadDir = "document/const_management/";  //登録ファイル名
    $tmp_file_name = $_FILES["uploaded_file1"]["tmp_name"];
    $file_name = $_FILES["uploaded_file1"]["name"];
    $save_file_name = $sq_no."_見積原価_";
    $redirect1 = $const_manag_dir1;
    $redirect2 = $const_manag_dir2;
    $redirect3 = $const_manag_dir3;
    break;
  case 'cm2':
    include("sq_detail_tr_const_management_update.php");
    echo 'here';
    $uploadDir = "document/const_management/";  //登録ファイル名
    $tmp_file_name = $_FILES["uploaded_file2"]["tmp_name"];
    $file_name = $_FILES["uploaded_file2"]["name"];
    $save_file_name = $sq_no."_見積定価_";
    $redirect1 = $const_manag_dir1;
    $redirect2 = $const_manag_dir2;
    $redirect3 = $const_manag_dir3;
    break;
}

if (is_uploaded_file($tmp_file_name)) {
  if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
  }

  $destination = $uploadDir.$save_file_name.$file_name;
  if (move_uploaded_file($tmp_file_name, $destination)) {
    chmod($destination, 0644);
    echo "ファイル「".$destination."」　をアップロードしました。";

    echo $redirect1;
  } else {
    echo "<b><font color='red'>ファイルをアップロードできません。</font></b>";
    echo $redirect2;
  }
} else {
  // 一覧に戻る
  $redirect3;
  exit();
}
?>