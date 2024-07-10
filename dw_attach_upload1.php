<?php
  //input2_1 = 【 図面管理】【図面のアップロードボタン】
  //input2_2 = 【 図面管理】【資料のアップロードボタン】

  $from = $_GET['from'] ?? '';
  $dw_no = $_POST['dw_no'] ?? '';
  $client = $_POST['client'] ?? '';
  $tmp_file_name = $_FILES["uploaded_file"]["tmp_name"];
  $file_name = $_FILES["uploaded_file"]["name"];
  $file_comments = $_POST['upload_comments']?? '';
  $save_file_name = $_POST['save_file_name'];
  $uploadDir = "";

  //登録ファイル名
  switch ($from) {
    case 'input2_1':
    case 'input2_2':
      $uploadDir = "document/drawing_management/";
      $include_file = "dw_file.php";
      break;
  }

  if (!empty($tmp_file_name)) {
    if (!file_exists($uploadDir)) {
      mkdir($uploadDir, 0777, true);
    }
  
    $destination = $uploadDir . $save_file_name . $file_name;
  
    if (move_uploaded_file($tmp_file_name, $destination)) {
      chmod($destination, 0644);
      echo "ファイル「".$destination."」　をアップロードしました。";
      if ($include_file !== '') {
        include($include_file);
      }
      exit();
    } else {
      echo "<b><font color='red'>ファイルをアップロードできません。</font></b>";
      exit();
    }
  } else {
    echo json_encode("アップロードしました2。");
    exit();
  }


?>