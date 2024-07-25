<?php
  //input3_1 = 【 技術部工事技術部入力】【制作図面のアップロードボタン】
  //input3_2 = 【 技術部工事技術部入力】【資料のアップロードボタン】

  $from = $_GET['from'] ?? '';
  $card_no = $_POST['card_no'] ?? '';
  $sq_card_no = $_POST['sq_card_no']?? '';            //依頼書No  
  $sq_card_line_no = $_POST['sq_card_line_no'] ?? ''; //依頼書行No
  $tmp_file_name = $_FILES["uploaded_file"]["tmp_name"];
  $file_name = $_FILES["uploaded_file"]["name"];
  $file_comments = $_POST['upload_comments']?? '';
  $save_file_name = $_POST['save_file_name'];
  $uploadDir = "";

  //登録ファイル名
  switch ($from) {
    case 'input3_1':
    case 'input3_2':
      $uploadDir = "document/card_engineering/card_detail_no" . $sq_card_line_no . "/";
      $include_file = "card_file.php";
      break;
    case 'input2':
      $uploadDir = "document/card_procurement/";
      $include_file = "";
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