<?php
  // 初期処理
  require_once('function.php');
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

  function get_sq_line_no($sq_no) {
    global $pdo;
    $sq_line_no = 1;
    $sql = "SELECT MAX(sq_line_no) AS max_line_no FROM sq_detail_tr WHERE sq_no='$sq_no'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $datas = $stmt->fetch(PDO::FETCH_ASSOC);

    if (isset($datas) && !empty($datas)) {
      $sq_line_no += $datas['max_line_no'];
    }

    return $sq_line_no;
  } 

?>