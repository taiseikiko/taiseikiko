<?php
  // 初期処理
  require_once('function.php');
  include('customer_update.php');
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

  // 初期設定 & データセット
  $btn_name = '登録';
  $cust_code =  $cust_name = '';
  $office_code = '';
  $office_name = '';
  $employee_code = '';
  $employee_name = '';
  $custmer_div = '';
  $custmer_div_options = getDistinctCustmerDiv();
  $department_name = '';
  $office_position_name = '';
  $err = $_GET['err'] ?? '';
  
  //一覧画面からPOSTを取得
  if (isset($_POST['process'])) {
    $process = $_POST['process'];
    
    //一覧画面に選択された事業所コードを取得する
    $office_code = $_POST['office_code'];
    $office_name = getOfficeName($office_code);

    //新規作成の場合
    if ($process == 'create') {
      //得意先コード取得
      $cust_code = getCustomerCode();
      
    } else {
      $btn_name = '更新';
      $cust_code = $_POST['cust_code'];
      
      //得意先マスタからデータを取得する
      $customer_datas = getCustDatasByCustCode($cust_code);
      
      if (!empty($customer_datas)) {
        $cust_name = $customer_datas[0]['cust_name'];
        $custmer_div = $customer_datas[0]['custmer_div'];
        $employee_code = $customer_datas[0]['person_in_charge'];
        // $employee_name = $customer_datas[0]['employee_name'];

        if ($employee_code !== '') {
          $cpDatas = getEmpDatasByEmpCd($employee_code);
          if (isset($cpDatas)) {
            $employee_name = $cpDatas['employee_name'];
            $department_name = $cpDatas['text2'];
            $office_position_name = $cpDatas['text1'];
          }
        }
      }
    }
  }
  $_SESSION['cust_name'] = $cust_name;
  
  function getCustomerCode() {
    global $pdo;
    //得意先マスターからMAXを取得する
    $sql = "SELECT MAX(cust_code) as max FROM customer";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $max_cust_code = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($max_cust_code) {
      $cust_code = $max_cust_code['max'] + 1;
    } else {
      $cust_code = 1;
    }
    return $cust_code;
  }

  function getOfficeName($office_code) {
    global $pdo;
    $office_name = '';
    $sql = "SELECT office_name FROM office_m WHERE office_code = '$office_code'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $datas = $stmt->fetch(PDO::FETCH_ASSOC);
    if(isset($datas)) {
      $office_name = $datas['office_name'];
    }
    return $office_name;
  }

  function getDistinctCustmerDiv() {
    global $pdo;
    $sql = "SELECT DISTINCT c.custmer_div, s.text1 
            FROM customer c 
            JOIN sq_code s ON c.custmer_div = s.code_no 
            WHERE s.code_id = 'customer_div' AND c.custmer_div IS NOT NULL";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  function getCustDatasByCustCode($cust_code) {
    global $pdo;
    //得意先マスターからデータを取得する
    $sql = "SELECT c.*, e.employee_name 
            FROM customer c 
            LEFT JOIN employee e ON c.person_in_charge = e.employee_code 
            WHERE cust_code = :cust_code";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':cust_code', $cust_code, PDO::PARAM_INT);
    $stmt->execute();
    $datas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $datas;
  }
  function getEmpDatasByEmpCd($employee_code) {
    global $pdo;

    $sql = "SELECT e.employee_name, cmd.text2, cmp.text1 
    FROM employee e
    LEFT JOIN code_master cmd
    ON e.department_code = cmd.text1
    AND cmd.code_id = 'department'
    LEFT JOIN code_master cmp
    ON e.office_position_code = cmp.code_no
    AND cmp.code_id = 'office_position'
    WHERE employee_code = '$employee_code'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
?>