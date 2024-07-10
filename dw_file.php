<?php
  // 初期処理
  require_once('function.php');
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
  $today = date('Y/m/d');
  $success = false;

  //重複エラーチェック
  if (isset($_POST['checkDuplicate'])) {
    $dw_no = $_POST['dw_no'];
    $dw_path = $_POST['file_path'];

    $isExist = false;
    $sql = "SELECT * FROM dw_fileupload_tr WHERE dw_no = '$dw_no' AND dw_path = '$dw_path'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
      $isExist = true;
    }
    echo json_encode([
      "isExist" => $isExist
    ]);
  } else {
    try {
      $pdo->beginTransaction();
      //card_file_trテーブルに更新する
      cu_dw_fileupload_tr();
      $pdo->commit();
    } catch (PDOException $e) {
      $pdo->rollback();
      error_log("PDO Exception: " . $e->getMessage(),3,'error_log.txt');
    }
  }

  /**
   * card_file_trテーブルに更新する
   */
  function cu_dw_fileupload_tr() {
    global $pdo;
    global $today;
    global $dw_no;
    global $client;
    global $file_name;
    global $file_comments;

    $datas = [
      'dw_no' => $dw_no,
      'client' => $client,
      'dw_path' => $file_name,
      'comment' => $file_comments,
      'add_date' => $today,
    ];
    $sql = "INSERT INTO dw_fileupload_tr (dw_no, client, dw_path, comment, add_date)
            VALUES (:dw_no, :client, :dw_path, :comment, :add_date)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($datas);
  }

?>