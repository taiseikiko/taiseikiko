<?php
// 初期処理
require_once('function.php');
// DB接続
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
//初期項目
$bridge = ''; //出先
$ec_name = '';//工事件名
$pipe = '';   //管種
$size = '';   //サイズ
$valve = '';   //バルブ種類
$maker = '';   //メーカー
$bifurcation = '';   //分岐形状
$tank = '';   //使用タンク
$mpa = '';   //仕様(Mpa)
$supplier = '';   //発注者
$wt_bifurcation = ''; //割T字形状
$m_listprice = 0; //定価(材料)
$wt_listprice = 0; //定価(割T)
$valve_listprice = 0;  //定価(バルブ)
$con_listprice = 0;  //定価(工事)
$t_listprice = 0;  //定価(計)
$m_cost = 0; //原価(材料)
$wt_cost = 0; //原価(割T)
$valve_cost = 0; //原画(バルブ)
$con_cost = 0; //原価(工事)
$t_cost = 0; //原価(計)
$card_no = ''; //カードNo.
$ec_no = ''; //工事番号
$contact = ''; //契約先
$estimate_date = ''; //見積返答日
$cost_date = ''; //原価返答日
$card_date = ''; //カード計上日
$construction_date = ''; //施工予定日
$m_orders = 0; //受注(材料)
$wt_orders = 0; //受注(割T)
$valve_orders = 0; //受注(バルブ)
$con_orders = 0; //受注(工事)
$t_orders = 0; //受注(計)
$m_partition = 0; //仕切(材料)
$wt_partition = 0; //仕切(割T)
$valve_partition = 0; //仕切(バルブ)
$con_partition = 0; //仕切(工事)
$t_partition = 0; //仕切(計)
$m_grossprofit = 0; //粗利(材料)
$wt_grossprofit = 0; //※粗利(割T)
$valve_grossprofit = 0; //粗利(バルブ)
$con_grossprofit = 0; //粗利(工事)
$t_grossprofit = 0; //粗利(計)
$footnote = ''; //備考
$sq_no = ''; //営業依頼書No
$add_date = date('Y-m-d');
$btn_name = '登録';
$err = $_GET['err']?? '';

//プルダウン「出先」のデータを取得する
$bridgeList = getBridgeList();

//プルダウン「工事件名」のデータを取得する
$ec_nameList = getListFrom_Ec_Code_Master('ec_name');

//プルダウン「管種」のデータを取得する
$pipeList = getListFrom_Ec_Code_Master('pipe');

//プルダウン「サイズ」のデータを取得する
$sizeList = getListFrom_Ec_Code_Master('size');

//プルダウン「バルブ種類」のデータを取得する
$valveList = getListFrom_Ec_Code_Master('valve');

//プルダウン「メーカー」のデータを取得する
$makerList = getListFrom_Ec_Code_Master('maker');

//プルダウン「分岐形状」のデータを取得する
$bifurcationList = getListFrom_Ec_Code_Master('bifurcation');

//プルダウン「使用タンク」のデータを取得する
$tankList = getListFrom_Ec_Code_Master('tank');

//プルダウン「使用タンク」のデータを取得する
$wt_bifurcationList = getListFrom_Ec_Code_Master('wt_bifurcation');

//プルダウン「割T字形状」のデータを取得する
$mpaList = getListFrom_Ec_Code_Master('mpa');

//プルダウン「発注者」のデータを取得する
$supplierList = getListFrom_Ec_Code_Master('supplier');

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
      $variables = ['bridge', 'sq_no', 'add_date', 'ec_name', 'pipe', 'size', 'valve', 'maker', 'bifurcation', 'tank', 
                    'wt_bifurcation', 'mpa', 'supplier', 'card_no', 'ec_no', 'contact', 'estimate_date', 'cost_date', 'card_date', 
                    'construction_date', 'footnote', 'm_listprice', 'wt_listprice', 'valve_listprice', 'con_listprice', 't_listprice', 
                    'm_cost', 'wt_cost', 'valve_cost', 'con_cost', 't_cost', 'm_orders', 'wt_orders', 'valve_orders', 'con_orders', 
                    't_orders', 'm_partition', 'wt_partition', 'valve_partition', 'con_partition', 't_partition', 'm_grossprofit', 
                    'wt_grossprofit', 'valve_grossprofit', 'con_grossprofit', 't_grossprofit'];

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

  $sql = "SELECT * FROM ec_article_detail_tr_procurement WHERE key_number=:key_number";
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

  $sql = "SELECT * FROM sq_dept WHERE dept_type='1'";
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
