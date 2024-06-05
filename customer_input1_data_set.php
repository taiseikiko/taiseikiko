<?php
  // 初期処理
  require_once('function.php');

  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
  
  function getOfficeDatas() {
    global $pdo;    
    $sql_office_code = "SELECT office_code, office_name FROM office_m WHERE sales_dept = 1";
    $stmt = $pdo->prepare($sql_office_code);
    $stmt->execute();
    return $stmt->fetchAll();
  }

  $office_code_filter = isset($_POST['office_code']) ? $_POST['office_code'] : '';
  function getCustDatas($office_code_filter) {
    global $pdo;
    $customer_datas = [];
    $sql = "SELECT c.*, e.employee_name AS employee_name 
            FROM customer c
            LEFT JOIN employee e ON c.person_in_charge = e.employee_code";
    if (!empty($office_code_filter)) {
      $sql .= " WHERE c.office_code = :office_code";
    }
    $stmt = $pdo->prepare($sql);
    if (!empty($office_code_filter)) {
      $stmt->bindValue(':office_code', $office_code_filter, PDO::PARAM_STR);
    }
    $stmt->execute();
  
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $customer_datas[] = $row;
    }
    return $customer_datas;
    
  }
?>