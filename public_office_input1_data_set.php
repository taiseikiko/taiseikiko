<?php
  // 初期処理
  require_once('function.php');

  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
  // 初期設定 & データセット
  $office_datas = [];
  $pf_datas = [];
  $count = 0;

  // 事業所データを取得する
  $office_datas = getOfficeDatas();

  //選択された事業所コードを取得する
  $office_code_filter = isset($_POST['office_category']) ? $_POST['office_category'] : '';
  
  //検索データを取得する
  if ($office_code_filter !== '') {
    $pf_datas = getPfDatas($office_code_filter);
    if(!empty($pf_datas)) {
      $count = count($pf_datas);
    }
  }
  
  function getOfficeDatas() {
    global $pdo;
    
    $sql = "SELECT office_code, office_name FROM office_m WHERE sales_dept = 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll();
  }

  function getPfDatas($office_code_filter) {
    global $pdo;
    $pf_datas = [];
    $sql = "SELECT 
          pf.pf_code, pf.pf_name, pf.person_in_charge, e.employee_name 
          FROM public_office pf
          LEFT JOIN employee e
          ON pf.person_in_charge = e.employee_code
          WHERE pf.office_code = '$office_code_filter'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
  
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $pf_datas[] = $row;
    }
    return $pf_datas;
  }
?>