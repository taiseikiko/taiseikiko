<?php
  require_once('function.php');
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

  //初期処理
  $employee_datas = [];
  $restoration_comments = '';

  //Parent Pageからデータを取得
  $sq_card_no = $_GET['sq_card_no'] ?? '';
  $sq_card_line_no = $_GET['sq_card_line_no'] ?? '';
  $from = $_GET['from'] ?? '';
  $page = $_GET['page'] ?? '';
  $err = $_GET['err'] ?? '';//エラーを取得する

  //差し戻し先担当者
  if ($page == '入力') {
    $employee_datas = get_mail_receiver_from_card_route_in_dept();
  } else if ($page == '受付') {
    $employee_datas = get_client($sq_card_no);
  } else {
    $employee_datas = getEmployeeDatas($sq_card_no, $sq_card_line_no);
  }

  function get_mail_receiver_from_card_route_in_dept() {
    global $pdo;
    $employee_datas = [];

    $sql = "SELECT 'receipt' AS type, e.employee_code, e.employee_name, e.email
            FROM card_route_in_dept d
            LEFT JOIN employee e ON d.employee_code = e.employee_code
            WHERE (d.department_code = '02' OR d.department_code = '03') AND d.role = '0'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $employee_datas[] = $row;
    };

    return $employee_datas;
  }

  function get_client($sq_card_no) {
    global $pdo;
    $employee_datas = [];

    //headerテーブルからclientとdetailからentrantのデータを取得する
    $sql = "
            SELECT 'client' AS type, e_header.employee_code, e_header.employee_name
            FROM card_header_tr h
            LEFT JOIN employee e_header ON h.client = e_header.employee_code
            WHERE h.card_no = :card_no
        ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':card_no', $sq_card_no);
    $stmt->execute();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $employee_datas[] = $row;
    };

    return $employee_datas;
  }

  function getEmployeeDatas($sq_card_no, $sq_card_line_no) {
    global $pdo;
    $employee_datas = [];

    //headerテーブルからclientとdetailからentrantのデータを取得する
    $sql = "
            SELECT 'client' AS type, e_header.employee_code, e_header.employee_name
            FROM card_header_tr h
            LEFT JOIN employee e_header ON h.client = e_header.employee_code
            WHERE h.card_no = :card_no
            UNION ALL
            SELECT 'entrant' AS type, e.employee_code, e.employee_name
            FROM card_detail_tr d
            LEFT JOIN employee e ON d.entrant = e.employee_code 
            WHERE d.sq_card_no = :sq_card_no AND d.sq_card_line_no = :sq_card_line_no;
        ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':card_no', $sq_card_no);
    $stmt->bindParam(':sq_card_no', $sq_card_no);
    $stmt->bindParam(':sq_card_line_no', $sq_card_line_no);
    $stmt->execute();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $employee_datas[] = $row;
    };

    return $employee_datas;
  }
?>