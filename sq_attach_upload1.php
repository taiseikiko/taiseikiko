<?php

$from = $_GET['from'] ?? '';
$title = $_POST['title'] ?? '';
if ($_POST['sq_no'] !== '') {
  $sq_no = $_POST['sq_no'];
} else {
  $sq_no = $_POST['new_sq_no']?? '';
}

//'sq' = 【 営業依頼書：依頼入力】【アップロードボタン】
//e1 = 【　営業依頼書：技術部　入力　】【技術員入力画面・見積処理】【見積図面のアップロードボタン】
//e2 = 【　営業依頼書：技術部　入力　】【技術員入力画面・見積処理】【資料のアップロードボタン】
//e3 = 【　営業依頼書：技術部　入力　】【技術員入力画面・図面・資料処理】納入仕様書のアップロードボタン 
//e4 = 【　営業依頼書：技術部　入力　】【技術員入力画面・図面・資料処理】参考図面のアップロードボタン 
//e5 = 【　営業依頼書：技術部　入力　】【技術員入力画面・図面・資料処理】資料のアップロードボタン 
//sm1 = 【　営業依頼書：営業管理部　入力　】【営業管理部入力画面・見積処理】見積原価のアップロードボタン 
//sm2 = 【　営業依頼書：営業管理部　入力　】【営業管理部入力画面・見積処理】見積定価のアップロードボタン 

//directoryを設定する
// $redirectBase = "./sales_request_input1.php";
// $redirectMap = [
//   'sr' => $redirectBase,
//   'e1' => "./sq_detail_tr_engineering_input1.php",
//   'e2' => "./sq_detail_tr_engineering_input1.php",
//   'e3' => "./sq_detail_tr_engineering_input1.php",
//   'e4' => "./sq_detail_tr_engineering_input1.php",
//   'e5' => "./sq_detail_tr_engineering_input1.php",
//   'sm1' => "./sq_detail_tr_sales_management_input1.php",
//   'sm2' => "./sq_detail_tr_sales_management_input1.php",
//   'cm1' => "./sq_detail_tr_const_management_input1.php",
//   'cm2' => "./sq_detail_tr_const_management_input1.php",
//   'pc1' => "./sq_detail_tr_procurement_input1.php",
//   'pc2' => "./sq_detail_tr_procurement_input1.php"
// ];

// $redirect = $redirectMap[$from] ?? $redirectBase;

$uploadDir = "";
$tmp_file_name = "";
$file_name = "";
$save_file_name = "";

// 更新PGM
switch ($from) {
  case 'sr':
    $uploadDir = "document/sales_management/";  //登録ファイル名
    break;
  case 'e1':
  case 'e2':
    $uploadDir = "document/engineering/quotation/";  //登録ファイル名
    break;
  case 'e3':
  case 'e4':
  case 'e5':
    $uploadDir = "document/engineering/drawing/";  //登録ファイル名
    break;
  case 'sm1':
  case 'sm2':
    $uploadDir = "document/sales_management/";  //登録ファイル名
    break;
  case 'cm1':
  case 'cm2':
    $uploadDir = "document/const_management/";  //登録ファイル名
    break;
  case 'pc1':
  case 'pc2':
    $uploadDir = "document/procurement/";  //登録ファイル名
    break;
}

switch ($from) {
  case 'sr':
    $save_file_name = $sq_no."_";
    break;
  case 'e1':
    $save_file_name = $sq_no."_見積図面_";
    break;
  case 'e2':
    $save_file_name = $sq_no."_資料_";
    break;
  case 'e3':
    $save_file_name = $sq_no."_納入仕様書_";
    break;
  case 'e4':
    $save_file_name = $sq_no."_参考図面_";
    break;
  case 'e5':
    $save_file_name = $sq_no."_資料_";
    break;
  case 'sm1':
    $save_file_name = $sq_no."_見積原価_";
    break;
  case 'sm2':
    $save_file_name = $sq_no."_見積定価_";
    break;
  case 'cm1':
    $save_file_name = $sq_no."_見積定価_";
    break;
  case 'cm2':
    $save_file_name = $sq_no."_資料_";
    break;
  case 'pc1':
    $save_file_name = $sq_no."_原価計算書_";
    break;
  case 'pc2':
    $save_file_name = $sq_no."_資料_";
    break;
}

$tmp_file_name = $_FILES["uploaded_file"]["tmp_name"];
$file_name = $_FILES["uploaded_file"]["name"];

if (!empty($tmp_file_name)) {
  if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
  }

  $destination = $uploadDir . $save_file_name . $file_name;

  if (move_uploaded_file($tmp_file_name, $destination)) {
    chmod($destination, 0644);
    echo "ファイル「".$destination."」　をアップロードしました。";
    exit();
  } else {
    echo "<b><font color='red'>ファイルをアップロードできません。</font></b>";
    exit();
  }
} else {
  echo "<b><font color='red'>ファイルをアップロードできません。</font></b>";
  exit();
}
?>