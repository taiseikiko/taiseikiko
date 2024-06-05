<?php
  // 初期処理
  require_once('function.php');
  include("sq_zkm_update.php");

  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

  // 初期設定 & データセット
  $class_code = '';
  $class_name = '';
  $btn_name = '';
  $zkm_code = '';
  $zkm_name = '';
  $size = '';
  $joint = '';
  $pipe = '';
  $inner_coating = '';
  $outer_coating = '';
  $fluid = '';
  $valve = '';
  $o_c_direction = '';
  $c_div = '';
  $sizeList = [];           //サイズ
  $jointList = [];          //接合形状
  $pipeList = [];           //管種
  $innerCoatingList = [];   //内面塗装
  $outerCoatingList = [];   //外面塗装
  $fluidList = [];          //管内流体
  $valveList = [];          //バルブ仕様
  $o_c_directionList = [];  //開閉方向
  $c_divList = [];          //一般・工事
  $datas = [];
  $btn_name = '登録';

  $sizeList = getDropdownData('size');                  //サイズ
  $jointList = getDropdownData('joint');                //接合形状
  $pipeList = getDropdownData('pipe');                  //管種
  $innerCoatingList = getDropdownData('inner_coating'); //内面塗装
  $outerCoatingList = getDropdownData('outer_coating'); //外面塗装
  $fluidList = getDropdownData('fluid');                //管内流体
  $valveList = getDropdownData('valve');                //バルブ仕様
  $o_c_directionList = getDropdownData('o_c_direction');//開閉方向
  $c_divList = getDropdownData('c_div');                //一般・工事

  //一覧画面からPOSTを取得
  if (isset($_POST['process'])) {
    $process = $_POST['process'];

    //一覧画面に選択された分類コードを取得する
    $class_code = isset($_POST['class_code']) ? $_POST['class_code'] : '';
    $class_name = getClassNameByClassCd($class_code);
    
    //新規作成の場合
    if ($process == 'create') {
      //材工名コードを取得する
      $zkm_code = getZkmCode();
    } else {
      //更新の場合
      $btn_name = '更新';
      $zkm_code = $_POST['zkm_code'];      

      //材工名マスタからデータを取得する
      $datas = getZkmDatasByZkmCode($zkm_code,$class_code);      

      if (isset($datas)) {
        $zkm_name = $datas['zkm_name'];
        $size = $datas['size'];
        $joint = $datas['joint'];
        $pipe = $datas['pipe'];
        $inner_coating = $datas['inner_coating'];
        $outer_coating = $datas['outer_coating'];
        $fluid = $datas['fluid'];
        $valve = $datas['valve'];
        $o_c_direction = $datas['o_c_direction'];
        $c_div = $datas['c_div'];
      }
    }
  }

  if (isset($_POST['submit'])) {
    $success = reg_or_upd_sq_zkm();
    if ($success) {
      echo "<script>
        window.location.href='sq_zkm_input1.php';
      </script>";
    } else {
      echo "<script>
        window.onload = function() { alert('失敗しました。'); }
      </script>";
    }
  }
  function getDropdownData($code_id) {
    global $pdo;
    //sq_code テーブルからデータ取得する
    $sql = "SELECT text1, code_no FROM sq_code WHERE code_id='$code_id'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $datas = $stmt->fetchAll();
    return $datas;
  }

  function getClassNameByClassCd($class_code) {
    global $pdo;
    $class_name = '';
    //sq_code テーブルからデータ取得する
    $sql = "SELECT class_name FROM sq_class WHERE class_code='$class_code'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $datas = $stmt->fetch(PDO::FETCH_ASSOC);

    if(isset($datas)) {
      $class_name = $datas['class_name'];
    }
    return $class_name;
  }

  function getZkmCode() {
    global $pdo;
    //材工名マスタからMAXデータ取得する
    $sql = "SELECT MAX(zkm_code) as max FROM sq_zaikoumei";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $max_zkm_code = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($max_zkm_code) {
      $zkm_code = $max_zkm_code['max'] + 1;
    } else {
      $zkm_code = 1;
    }
    return $zkm_code;
  }

  function getZkmDatasByZkmCode($zkm_code, $class_code) {
    global $pdo;
    $sql = "SELECT * FROM sq_zaikoumei WHERE zkm_code = '$zkm_code' AND class_code = '$class_code'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $datas = $stmt->fetch(PDO::FETCH_ASSOC);
    return $datas;
  }
?>