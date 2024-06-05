<?php
  // 初期処理
  require_once('function.php');
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
 
  // 初期設定 & データセット
  $count = 0;
  $material_datas = [];

  // 分類コードを取得する
  $class_datas = getClassDatas();

  //選択された分類コードを取得する
  $class_code_filter = isset($_POST['class_category']) ? $_POST['class_category'] : '';

  // 検索データ取得する
  if ($class_code_filter !== '') {
    $material_datas = getZaikomeiDatas($class_code_filter);
    if(!empty($material_datas)) {
      $count = count($material_datas);
    }
  }
  function getClassDatas() {
    global $pdo;
    $sql = "SELECT class_code, class_name FROM sq_class";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $class_codes = $stmt->fetchAll();

    return $class_codes;
  }

  function getZaikomeiDatas($class_code_filter) {
    global $pdo;
    $material_datas = [];
    $sql = "SELECT 
          zk.class_code, zk.zkm_code, zk.zkm_name, zk.size, zk.joint, zk.pipe, zk.inner_coating, zk.outer_coating, zk.fluid, zk.valve, zk.o_c_direction, zk.c_div,
          c.text1
          FROM sq_zaikoumei zk
          LEFT JOIN sq_code c
          ON zk.c_div = c.code_no
          WHERE zk.class_code = '$class_code_filter'
          AND c.code_id = 'c_div'";

    $stmt = $pdo->prepare($sql);
    
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $material_datas[] = $row;
    }

    return $material_datas;
  }
?>