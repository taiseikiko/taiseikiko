<?php
  // 初期処理
  require_once('function.php');
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

  // 初期設定 & データセット
  $process = '';  //処理
  $class_code = ''; //分類コード
  $class_name = $class_name_err = ''; //分類名
  $btn_name = '登録';  
  $datas = [];
  $err = $_GET['err'] ?? '';

  //一覧画面からPOSTを取得
  if (isset($_POST['process'])) {
    $process = $_POST['process'];
    
    //新規作成の場合
    if ($process == 'create') {
      //分類コード取得
      $class_code = getClassCode();
    } else {
      $btn_name = '更新';
      $class_code = $_POST['class_code'];

      //分類マスタからclass_nameを取得する
      $datas = getClassDatasByClassCode($class_code);
      if (!empty($datas)) {
        $class_name = $datas[0]['class_name'];
      }
    }
  }

  function getClassCode() {
    global $pdo;
    //分類マスタからMAXデータ取得する
    $sql = "SELECT MAX(class_code) as max FROM sq_class";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $max_class_code = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($max_class_code) {
      $class_code = $max_class_code['max'] + 1;
    } else {
      $class_code = 1;
    }
    return $class_code;
  }

  function getClassDatasByClassCode($class_code) {
    global $pdo;
    $sql = "SELECT * FROM sq_class WHERE class_code = '$class_code'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $datas = $stmt->fetchAll();
    
    return $datas;
  }
?>