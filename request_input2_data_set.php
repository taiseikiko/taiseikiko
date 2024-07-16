<?php
  // 初期処理
  require_once('function.php');
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
  // 初期設定 & データセット
  $today = date('Y/m/d');
  $process = '';
  $request_class = '';      //分類コード
  $request_comment = '';    //コメント
  $request_dept = '';       //依頼部署
  $request_form_url = '';
  $btn_class = 'updRegBtn'; 
  $header = '入力';
  $err = $_GET['err'] ?? '';
  $btn_name = "依頼書登録";
  $class_datas = get_class_datas(); //分類プルダウンにセットするデータを取得する
  
  //一覧画面から来た場合
  if (isset($_POST['process1'])) {
    $process = $_POST['process1'];
    //依頼書No.自動採番
    /**--------------------------------------------------------------------------------------------------**/
    //システム日付の年月を採取
    $ym = substr(str_replace('/', '', $today), 0, 6);
    $code_id = 'request_form_no';

    $sql = "SELECT code_no FROM sq_code WHERE code_id = '$code_id' AND text1 = '$ym'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll();

    if (isset ($data) && !empty($data)) {
      $code_no = $data[0]['code_no'];
      $no = $code_no+1;
      $request_form_number = $ym.$no;
    } else {
      $no = '1';
      $request_form_number = $ym.$no;
    }
  }

  function get_class_datas()
  {
    global $pdo;
    $sql = "SELECT * FROM sq_class";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $datas = $stmt->fetchAll();

    return $datas;
  }

?>