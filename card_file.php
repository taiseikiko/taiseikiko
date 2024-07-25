<?php
  // 初期処理
  require_once('function.php');
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
  $today = date('Y/m/d');
  $success = false;

  //重複エラーチェック
  if (isset($_POST['checkDuplicate'])) {
    $sq_card_no = $_POST['sq_card_no'];
    $sq_card_line_no = $_POST['sq_card_line_no'];
    $file_name = $_POST['file_name'];

    $isExist = false;
    $sql = "SELECT * FROM card_file_tr WHERE sq_card_no = '$sq_card_no' AND sq_card_line_no = '$sq_card_line_no' AND file_name = '$file_name'";
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
      cu_card_file_tr();
      $pdo->commit();
    } catch (PDOException $e) {
      $pdo->rollback();
      error_log("PDO Exception: " . $e->getMessage(),3,'error_log.txt');
    }
  }

  /**
   * card_file_trテーブルに更新する
   */
  function cu_card_file_tr() {
    global $pdo;
    global $today;
    global $sq_card_no;
    global $sq_card_line_no;
    global $save_file_name;
    global $file_name;
    global $file_comments;

    $datas = [
      'sq_card_no' => $sq_card_no,
      'sq_card_line_no' => $sq_card_line_no,
      'file_name' => $save_file_name . $file_name,
      'file_comments' => $file_comments,
      'add_date' => $today,
    ];
    $sql = "INSERT INTO card_file_tr (sq_card_no, sq_card_line_no, file_name, file_comments, add_date)
            VALUES (:sq_card_no, :sq_card_line_no, :file_name, :file_comments, :add_date)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($datas);
  }

?>