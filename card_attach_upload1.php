<?php
  $from = $_GET['from'] ?? '';
  $card_no = $_POST['card_no'] ?? '';
  $sq_card_no = $_POST['sq_card_no']?? '';            //依頼書No  
  $sq_card_line_no = $_POST['sq_card_line_no'] ?? ''; //依頼書行No

  //input3_1 = 【 技術部工事技術部入力】【制作図面のアップロードボタン】
  //input3_2 = 【 技術部工事技術部入力】【資料のアップロードボタン】

  //directoryを設定する
  $redirect = "./card_input1.php";

  $uploadDir = "";
  $tmp_file_name = "";
  $file_name = "";
  $save_file_name = "";
  $file_comments = "";

  //登録ファイル名
  switch ($from) {
    case 'input3_1':
    case 'input3_2':
      $uploadDir = "document/card_engineering/";
      $include_file = "card_file.php";
      break;
    case 'input2':
      $uploadDir = "document/card_procurement/";///
      $include_file = "card_file.php";
      break;
  }

  switch ($from) {
    case 'input3_1':
      $tmp_file_name = $_FILES["uploaded_file1"]["tmp_name"];
      $file_name = $_FILES["uploaded_file1"]["name"];
      $file_comments = $_POST['upload_comments1'];
      $save_file_name = $sq_card_no."_制作図面_";
      break;
    case 'input3_2':
      $tmp_file_name = $_FILES["uploaded_file2"]["tmp_name"];
      $file_name = $_FILES["uploaded_file2"]["name"];
      $file_comments = $_POST['upload_comments2'];
      $save_file_name = $sq_card_no."_資料_";
      break;
    case 'input2':
      $tmp_file_name = $_FILES["uploaded_file"]["tmp_name"];
      $file_name = $_FILES["uploaded_file"]["name"];
      $save_file_name = $card_no."_電子カード_";
      break;
    
  }


  if (!empty($tmp_file_name)) {
    if (!file_exists($uploadDir)) {
      mkdir($uploadDir, 0777, true);
    }
  
    $destination = $uploadDir . $save_file_name . $file_name;
  
    if (move_uploaded_file($tmp_file_name, $destination)) {
      if ($include_file !== '') {
        include($include_file);
      }
      chmod($destination, 0644);
      echo "ファイル「".$destination."」　をアップロードしました。";
      
      header("Location: {$redirect}");
      exit();
    } else {
      echo "<b><font color='red'>ファイルをアップロードできません。</font></b>";
      header("Location: {$redirect}");
      exit();
    }
  } else {
    // 一覧に戻る
    header("Location: {$redirect}");
    exit();
  }


?>