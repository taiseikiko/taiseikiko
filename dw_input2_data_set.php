<?php
  // 初期処理
  require_once('function.php');
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
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
  $btn_status = '';          //登録ボタンの表示状態
  $btn_class = 'updRegBtn'; 
  $header = '入力処理';
  $err = $_GET['err'] ?? '';

  $class_datas = get_class_datas();                     //分類プルダウンにセットするデータを取得する
  $sizeList = getDropdownData('size');                  //サイズ
  $jointList = getDropdownData('joint');                //接合形状
  $pipeList = getDropdownData('pipe');                  //管種

  if (isset($_POST['process']) || isset($_GET['dw_no'])) {
    //メールのURLからきた場合
    if (isset($_GET['dw_no'])) {
      $process = 'update';
    } else {
      $process = $_POST['process'];
    }
    //新規の場合
    if ($process == 'new') {
      $btn_status = 'hidden';
    }
    //更新の場合  
    else {
      $btn_name = '承認';
      $header = '承認処理';
      $dw_no = $_POST['dw_no'] ?? $_GET['dw_no'];

      //dw_management_trのデータを取得する
      $dw_datas = get_dw_management_tr($dw_no);

      if (!empty($dw_datas) && isset($dw_datas)) {
        $variables = ['client', 'dw_div1', 'open_div', 'class_code', 'zkm_code', 'size', 'joint', 'pipe', 'specification', 'dw_div2'];
        foreach ($variables as $variable) {
          ${$variable} = $dw_datas[$variable];
        }
        
        if ($dw_datas['dw_status'] == '3') {
          $btn_name = '更新';
          $btn_class = 'updateBtn';
          $btn_status = 'hidden';
        }        
      }
    }

  }

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

  /**
   * dw_management_trからデータを取得する
   */
  function get_dw_management_tr($dw_no) {
    global $pdo;
    $datas = [];

    $sql = "SELECT * FROM dw_management_tr WHERE dw_no = :dw_no";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':dw_no', $dw_no);
    $stmt->execute();
    $datas = $stmt->fetch(PDO::FETCH_ASSOC);

    return $datas;
    
  }

?>