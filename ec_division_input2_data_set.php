<?php
  // 初期処理
  require_once('function.php');
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

  // 初期設定 & データセット
  $process = '';  //処理
  $code_key = $code_no = ''; //キーコード
  $spec_name = '';
  $code_name = $code_name_err = ''; //分類名
  $btn_name = '登録';
  $btn_class = 'approveBtn';  
  $datas = [];
  $err = $_GET['err'] ?? '';
  
  //一覧画面からPOSTを取得
  if (isset($_POST['process'])) {
    $process = $_POST['process'];
    
    //新規作成の場合
    if ($process == 'create') {
      $code_key = $_POST['code_key'];
      //キーコード取得
      $code_no = getEcCode($code_key);
      $spec_name = $_POST['spec_name'];
      
    } else {
      $btn_name = '更新';
      $btn_class = 'updateBtn';  
      $code_key = $_POST['code_key'];
      $spec_name = $_POST['spec_name'];
      $code_no = $_POST['code_no']; // Ensure this is set correctly
      
      //既存工事実績マスタからcode_nameを取得する
      $datas = getEcDatasByClassCodeAndNo($code_key, $code_no);
      if (!empty($datas)) {
        $code_name = $datas[0]['code_name'];
      }
    }
  }

  function getEcCode($code_key) {
    global $pdo;
    //既存工事実績マスタからMAXデータ取得する
    $sql = "SELECT MAX(code_no) as max FROM ec_code_master WHERE code_key = :code_key";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':code_key', $code_key, PDO::PARAM_STR);
    $stmt->execute();
    $max_code_no = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($max_code_no && $max_code_no['max']) {
        $code_no = $max_code_no['max'] + 1;
    } else {
        $code_no = 1;
    }
    return $code_no;
  }

  function getEcDatasByClassCodeAndNo($code_key, $code_no) {
    global $pdo;
    $sql = "SELECT * FROM ec_code_master WHERE code_key = :code_key AND code_no = :code_no";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':code_key', $code_key);
    $stmt->bindParam(':code_no', $code_no);
    $stmt->execute();
    $datas = $stmt->fetchAll();
    
    return $datas;
  }
?>
