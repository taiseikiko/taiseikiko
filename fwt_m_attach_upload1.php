<?php

  $fwt_m_no = $_POST['fwt_m_no'] ?? '';
  $tmp_file_name = $_FILES["uploaded_file"]["tmp_name"];
  $file_name = $_FILES["uploaded_file"]["name"];
  $uploadDir = "";
  $save_file_name = $fwt_m_no . '_';
  $uploadDir = "document/fwt/";

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
    echo json_encode("アップロードしました。");
    exit();
  }


?>