<?php
  // 初期処理
  require_once('function.php');

  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

  //When form is submitted
  if (isset($_POST['submit'])) {
 
    $success = reg_or_upd_customer();
    if ($success) {
        echo "<script>
        window.location.href='customer_input1.php';
        </script>";
    } else {
        echo "<script>
        window.location.href='customer_input2.php?err=exceErr';
        </script>";
    }
  }

  function reg_or_upd_customer() {
    $today = date('Y/m/d');
    $success = true;
    global $pdo;

  if (isset($_POST['process'])) {
    //新規作成or更新の場合
    $process = $_POST['process'];      
    try {  
      $pdo->beginTransaction();
      if ($process == 'create') {
        //新規作成の場合
        $data = [
          'cust_code' => $_POST['cust_code'],
          'cust_name' => $_POST['cust_name'],
          'office_code' => $_POST['office_code'],
          'person_in_charge' => $_POST['employee_code'],
          'custmer_div' => $_POST['custmer_div'],
          'add_date' => $today
        ];
        $sql = "INSERT INTO customer (cust_code, cust_name, office_code, custmer_div, person_in_charge, add_date) 
        VALUES (:cust_code, :cust_name, :office_code, :custmer_div, :person_in_charge, :add_date)";
        $stmt = $pdo->prepare($sql);
      } else {
        //更新の場合
        $data = [
          'cust_code' => $_POST['cust_code'],
          'cust_name' => $_POST['cust_name'],
          'office_code' => $_POST['office_code'],
          'person_in_charge' => $_POST['employee_code'],
          'custmer_div' => $_POST['custmer_div'],
          'upd_date' => $today
        ];
        $sql = "UPDATE customer SET cust_name=:cust_name, office_code=:office_code, person_in_charge=:person_in_charge,  custmer_div=:custmer_div, upd_date=:upd_date
        WHERE cust_code=:cust_code";
        $stmt = $pdo->prepare($sql);       
      }          
        
        $stmt->execute($data);
        $pdo->commit();
      } catch (Exception $e) {
        $success = false;
        $pdo->rollback();
        error_log("PDO Exception: " . $e->getMessage(),3,'error_log.txt');
      }
      return $success;
    }    
  }
?>