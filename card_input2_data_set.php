<?php
  // 初期処理
  require_once('function.php');
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
  include('card_update.php');
  
  
  // 初期設定 & データセット
  
  $class_code = '';             //分類
  $zkm_code = '';               //材工名
  $pipe = '';                   //管種
  $sizeA = '';                   //サイズA
  $sizeB = '';                   //サイズB
  $pf_code = '';                //事業体名
  $pf_name = '';                //事業体コード
  // $card_no = '';                //依頼書№
  $preferred_date = '';         //出図希望日
  $deadline = '';               //納期
  $procurement_no = '';         //資材部No
  $maker = '';                  //製造メーカー
  $specification_no = '';       //仕様書No
  $special_note = '';           //特記事項
  $approver = '';               //承認者
  
  
  
  $class_datas = get_class_datas();                     //分類プルダウンにセットするデータを取得する
  $pipeList = getDropdownData('pipe');                  //管種

  //事業体名を取得する
  if ($pf_code !== '') {
    $pf_name = get_pf_name($pf_code);
  } 

  if (isset($_POST['function_name'])) {
   $result = get_zaikoumei_datas();
   echo json_encode($result);
 }


/*----------------------------------------------------------------FUNCTIONS---------------------------------------------------------------------*/
    

  function get_class_datas() {
    global $pdo;
    $sql = "SELECT * FROM sq_class";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $datas = $stmt->fetchAll();

    return $datas;
  } 

  function get_zaikoumei_datas() {
    global $pdo;
    $class_code = $_POST['class_code'];
    $datas = [];

    $sql = "SELECT z.zkm_code, z.zkm_name, c.text1 ,c.code_no
    FROM sq_zaikoumei z
    LEFT JOIN sq_code c
    ON z.c_div = c.code_no
    AND c.code_id = 'c_div'
    WHERE z.class_code = '$class_code'";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $datas[] = $row;
    }

    return $datas;
  }

  function getDropdownData($code_id) {
    global $pdo;
    //sq_code テーブルからデータ取得する
    $sql = "SELECT c.text1, zk.zk_div_data 
    FROM sq_code c
    LEFT JOIN sq_zk2 zk
    ON c.code_id = zk.zk_division
    AND c.text1 = zk.zk_tp
    WHERE code_id='$code_id'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $datas = $stmt->fetchAll();
    return $datas;
  }

  function get_pf_name($pf_code) {
    global $pdo;
    $pf_name = '';
    $sql = "SELECT pf_name FROM public_office WHERE pf_code = '$pf_code'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $datas = $stmt->fetch(PDO::FETCH_ASSOC);

    if (isset($datas) && !empty($datas)) {
      $pf_name = $datas['pf_name'];
    }
    return $pf_name;
  }