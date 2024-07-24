<?php
  // 初期処理
  require_once('function.php');
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
  $today = date('Y/m/d');
  $success = true;
  $sq_card_no = $_POST['sq_card_no']?? '';            //依頼書No  
  $sq_card_line_no = $_POST['sq_card_line_no'] ?? ''; //依頼書行No
  $file_name = $_POST["file_name"] ?? '';

  //card_fileテーブルから削除する
  try {
    $pdo->beginTransaction();

    $sql = "DELETE FROM card_file_tr WHERE sq_card_no=:sq_card_no AND sq_card_line_no=:sq_card_line_no AND file_name=:file_name";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":sq_card_no", $sq_card_no);
    $stmt->bindParam(":sq_card_line_no", $sq_card_line_no);
    $stmt->bindParam(":file_name", $file_name);
    $stmt->execute();

    $pdo->commit();
  } catch (PDOException $e) {
    $success = false;
    $pdo->rollback();
    error_log("PDO Exception: " . $e->getMessage(),3,'error_log.txt');
  }

  if ($success) {
    $directory = 'document/card_engineering/card_detail_no' . $sq_card_line_no . '/';
    $dirHandle = opendir($directory);
    while ($file = readdir($dirHandle)) {
      if ($file_name == $file) {
        unlink($directory . $file);
      }
    }
    closedir($dirHandle);
  }
?>