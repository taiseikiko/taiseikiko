<?php

// 更新PGM
  include("inquiry_upd.php");

// 登録ファイル名
  $upload = "./document/".$inq_no2."_".$_FILES["upfile"]["name"];

if (is_uploaded_file($_FILES["upfile"]["tmp_name"])) {
  if (move_uploaded_file($_FILES["upfile"]["tmp_name"], $upload)) {
//    chmod("./document/".$_FILES["upfile"]["name"], 0644);
    chmod($upload, 0644);
    echo "ファイル「".$upload."」　をアップロードしました。";

//echo "<br>inq_no=".$inq_no;
//echo "<br>upload=".$upload."<br>";

    echo '<meta http-equiv="refresh" content=" 1; url=./inquiry_ent.php">';
  } else {
    echo "<b><font color='red'>ファイルをアップロードできません。</font></b>";
    echo '<meta http-equiv="refresh" content=" 2; url=./inquiry_ent.php">';
  }
} else {
  // 一覧に戻る
    header("Location:inquiry_ent.php");                
    exit();

//echo "SESSION mode=".$_SESSION['mode']."<br>";
//echo "inq_no=".$inq_no."<br>";

  }

// 前画面に戻る
//    header("Location:inquiry_ent.php");                
//    exit();


?>