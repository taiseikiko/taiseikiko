<?php
  // 初期処理
  require_once('function.php');
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

  // 初期設定 & データセット
  
  $card_no = '';                    //依頼書№
  $process = '';                    //処理
  $today = date('Y/m/d');
  $success = true;

//   システム日付の年月を採取
  $ym = substr(str_replace('/', '', $today), 0, 6);
  $code_id = 'card_request_no';
//   $card_no = $_GET['card_no'];
  $sql = "SELECT code_no FROM sq_code WHERE code_id = '$code_id' AND text1 = '$ym'";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $data = $stmt->fetchAll();

//新規作成の場合
//   if ($process == 'new') {  
//     echo "came here";    
    //依頼書№（card_no）自動採番
    try {
      $pdo->beginTransaction();
      if (isset ($data) && !empty($data)) {
        $code_no = $data[0]['code_no'];
        $no = $code_no+1;
        $card_no = $ym.$no;
        //テーブルsq_codeへ更新する
        $sql = "UPDATE sq_code SET code_no=:code_no WHERE code_id=:code_id AND text1=:text1";
      } else {
        $no = '1';
        $card_no = $ym.$no;
        //テーブルsq_codeへ登録する
        $sql = "INSERT INTO sq_code(code_id, code_no, text1) VALUES (:code_id, :code_no, :text1)";
      }
      $data = [
        'code_id' => $code_id,
        'code_no' => $no,
        'text1' => $ym
      ];
      $stmt = $pdo->prepare($sql);
      $stmt->execute($data);
      $pdo->commit();
    } catch (PDOException $e) {
      if (strpos($e->getMessage(), 'SQLSTATE[42000]') !== false) {
        error_log("SQL Syntax Error or Access Violation: " . $e->getMessage(),3,'error_log.txt');
      } else {
        $pdo->rollback();
        throw($e);
        error_log("PDO Exception: " . $e->getMessage(),3,'error_log.txt');
      }
    }
//   } else {
//     echo "did not success";
//   }