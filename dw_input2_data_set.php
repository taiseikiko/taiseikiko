<?php
  // 初期処理
  require_once('function.php');
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
  //ログインユーザーの部署ID
  $dept_id1 = getDeptId($dept_code);
  // 初期設定 & データセット
  $dw_no = '';              //図面No
  $client = '';             //申請者
  $dw_status = '';          //状況
  $dw_div1 = '';            //区分
  $open_div = '';           //公開区分
  $class_code = '';         //分類コード
  $zkm_code = '';           //材工名コード
  $size = '';               //サイズ
  $joint = '';              //接合形状
  $pipe = '';               //管種
  $specification = '';      //仕様
  $dw_div2 = '';            //種類
  $comments = '';
  $btn_name = '登録';
  $err = $_GET['err'] ?? '';

  $class_datas = get_class_datas();                     //分類プルダウンにセットするデータを取得する
  $sizeList = getDropdownData('size');                  //サイズ
  $jointList = getDropdownData('joint');                //接合形状
  $pipeList = getDropdownData('pipe');                  //管種

  /*----------------------------------------------------------------FUNCTION---------------------------------------------------------------------*/
  
  function get_class_datas()
  {
    global $pdo;
    $sql = "SELECT * FROM sq_class";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $datas = $stmt->fetchAll();

    return $datas;
  }

  /*-------------------------------------------------------------------------------------------------------------------------------------*/

  function getDropdownData($code_id) {
    global $pdo;
    //sq_code テーブルからデータ取得する
    $sql = "SELECT text1, code_no FROM sq_code WHERE code_id='$code_id'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $datas = $stmt->fetchAll();
    return $datas;
  }

  /*-------------------------------------------------------------------------------------------------------------------------------------*/

?>