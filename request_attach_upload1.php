<?php
  // 初期処理
  require_once('function.php');
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

  $request_form_number = $_POST['request_form_number'] ?? '';
  $tmp_file_name = $_FILES["uploaded_file"]["tmp_name"];
  $file_name = $_FILES["uploaded_file"]["name"];
  $uploadDir = "document/request/";
  $save_file_name = $request_form_number;

  if (!empty($tmp_file_name)) {
    if (!file_exists($uploadDir)) {
      mkdir($uploadDir, 0777, true);
    }
  
    $destination = $uploadDir . $save_file_name . $file_name;
  
    if (move_uploaded_file($tmp_file_name, $destination)) {
      chmod($destination, 0644);
      echo "ファイル「".$destination."」　をアップロードしました。";

      $datas = [
        'request_form_url' => $destination,
        'request_form_number' => $request_form_number,
      ];

      $sql = "UPDATE request_form_tr SET request_form_url=:request_form_url WHERE request_form_number=:request_form_number";
      $stmt = $pdo->prepare($sql);
      $stmt->execute($datas);

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