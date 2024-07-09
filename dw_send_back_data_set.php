<?php
  require_once('function.php');
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

  //初期処理
  $employee_datas = [];
  $restoration_comments = '';

  //Parent Pageからデータを取得
  $dw_no = $_GET['dw_no'] ?? '';
  $err = $_GET['err'] ?? '';//エラーを取得する

  //差し戻し先担当者
  $employee_datas = getEmployeeDatas($dw_no);

  function getEmployeeDatas($dw_no) {
    global $pdo;
    $employee_datas = [];

    // `dw_managemant_tr` テーブルから `client` を取得
    $sql = "
            SELECT e.employee_code, e.employee_name, 'client' AS type
            FROM dw_management_tr dw
            JOIN employee e ON dw.client = e.employee_code
            WHERE dw.dw_no = :dw_no
        ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':dw_no', $dw_no);
    $stmt->execute();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $employee_datas[] = $row;
    };

    return $employee_datas;
  }
?>