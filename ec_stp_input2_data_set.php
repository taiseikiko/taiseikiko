<?php
// 初期処理
require_once('function.php');
// DB接続
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
//初期項目
$bridge = ''; //出先
$company = '';//会社名
$name = '';//氏名
$birthday = '';//生年月日
$attendance_year = '';//受講年
$elementary_number = '';//初級No.
$advance_number = '';//上級No.
$con_qualification = '';//施工資格
$renewal_date = '';//更新年月日
$expiration_date = '';//有効期限
$footnote = ''; //備考

$btn_name = '登録';
$err = $_GET['err']?? '';

//プルダウン「出先」のデータを取得する
$bridgeList = getBridgeList();

//プルダウン「工事件名」のデータを取得する
$companyList = getListFrom_Ec_Code_Master('company');



//一覧画面から来た場合
if (isset($_POST['process'])) {
  $process = $_POST['process'];

  //新規の場合
  if ($process == 'new') {
    
  }
  //更新の場合
  else {
    $btn_name = '更新';
    $key_number = $_POST['key_number'];
    $datas = getDatasFromEcArticle($key_number);

    if (isset($datas) && !empty($datas)) {
      $variables = ['bridge', 'company', 'name', 'birthday', 'attendance_year', 'elementary_number', 'advance_number', 'con_qualification', 'renewal_date', 
                    'expiration_date', 'footnote'];

      foreach ($variables as $variable) {
        ${$variable} = $datas[$variable];
      }
    }
  }
}

/**
 * ec_article_detail_tr_procurementデータを取得する
 */
function getDatasFromEcArticle($key_number) {
  global $pdo;
  $datas = [];

  $sql = "SELECT * FROM ec_stp_detail_tr_procurement WHERE key_number=:key_number";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':key_number', $key_number);
  $stmt->execute();
  $datas = $stmt->fetch(PDO::FETCH_ASSOC);

  return $datas;
}

/**
 * //プルダウン「出先」のデータを取得する
 */
function getBridgeList() {
  global $pdo;
  $datas = [];

  $sql = "SELECT * FROM sq_dept";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $datas = $stmt->fetchAll();

  return $datas;
}

/**
 * //プルダウンのデータを取得する
 */
function getListFrom_Ec_Code_Master($code_key) {
  global $pdo;
  $datas = [];

  $sql = "SELECT * FROM ec_code_master WHERE code_key=:code_key";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':code_key', $code_key);
  $stmt->execute();
  $datas = $stmt->fetchAll();

  return $datas;
}

?>
