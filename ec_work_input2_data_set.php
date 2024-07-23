<?php
// 初期処理
require_once('function.php');
// DB接続
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
//初期項目
$bridge = '';//出先
$government  = '';   //官庁
$pf_code = '';
$pf_name = '';
$customers = '';   //得意先
$cust_code = '';
$cust_name = '';
$ec_date = '';  //工事日
$ec_number = ''; //工事番号
$ec_place = ''; //施工場所
$pipe = ''; //管種
$size = ''; //サイズ
$valve = '';   //バルブ種類
$specification_number = '';   //仕様書番号
$design_pressure = '';   //設計圧
$scene_water_pressure = '';   //現場水圧
$slant = '';   //傾斜
$tank = '';   //タンク
$cutter = ''; //切断機
$maker = ''; //メーカー
$coating = ''; //塗装
$m_cost = 0;  //原価(材料)
$wt_cost = 0;  //※原価(割T)
$valve_cost = 0;  //原画(バルブ)
$con_cost = 0; //原価(工事)
$t_cost = 0; //原価(計)
$m_orders = 0; //受注(材料)
$wt_orders = 0; //受注(割T)
$valve_orders = 0; //受注(バルブ)
$con_orders = 0; //受注(工事)
$t_orders = 0; //受注(計)
$m_grossprofit = 0; //粗利(材料)
$wt_grossprofit = 0; //※粗利(割T)
$valve_grossprofit = 0; //粗利(バルブ)
$con_grossprofit = 0; //粗利(工事)
$t_grossprofit = 0; //粗利(計)
$trouble = ''; //トラブル
$cause = ''; //原因
$gross_footnote = ''; //粗利備考
$bifurcation = ''; //分岐形状
$shape = ''; //形
$drill = ''; //穿孔機
$drill2 = ''; //穿孔機2回目
$water_pressure = ''; //水圧
$quantity = ''; //数量
$wt_bifurcation = ''; //割T字形状
$footnote1 = ''; //備考
$ec_extension = ''; //施工延長
$ec_name = ''; //工事名称
$ec_ready_from = ''; //工事開始（from)
$ec_ready_to = ''; //工事開始（to)
$footnote2 = ''; //備考2
$btn_name = '登録';
$err = $_GET['err']?? '';

//プルダウン「出先」のデータを取得する
$bridgeList = getBridgeList();

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

//プルダウン「割T字形状」のデータを取得する
$wt_bifurcationList = getListFrom_Ec_Code_Master('wt_bifurcation');

//プルダウン「施工場所」のデータを取得する
$ec_placeList = getListFrom_Ec_Code_Master('ec_place');

//プルダウン「設計圧」のデータを取得する
$design_pressureList = getListFrom_Ec_Code_Master('design_pressure');

//プルダウン「塗装」のデータを取得する
$coatingList = getListFrom_Ec_Code_Master('coating');

//プルダウン「穿孔機」のデータを取得する
$drillList = getListFrom_Ec_Code_Master('drill');

//プルダウン「割T字形状」のデータを取得する
$cutterList = getListFrom_Ec_Code_Master('cutter');


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
    $datas = getDatasFromEcWork($key_number);

    if (isset($datas) && !empty($datas)) {
      $variables = ['bridge', 'government', 'customers', 'ec_date', 'ec_number', 'ec_place', 'pipe', 'size', 
                  'valve', 'specification_number', 'design_pressure', 'scene_water_pressure', 'slant', 'tank', 'cutter', 'maker', 'coating', 
                  'm_cost', 'wt_cost', 'valve_cost', 'con_cost', 't_cost', 'm_orders', 'wt_orders', 'valve_orders', 'con_orders', 't_orders', 
                  'm_grossprofit', 'wt_grossprofit', 'valve_grossprofit', 'con_grossprofit', 't_grossprofit', 'trouble', 'cause', 'gross_footnote', 
                  'bifurcation', 'shape', 'drill', 'drill2', 'water_pressure', 'quantity', 'wt_bifurcation', 'footnote1', 'ec_extension', 'ec_name', 
                  'ec_ready_from', 'ec_ready_to', 'footnote2'];

      foreach ($variables as $variable) {
        ${$variable} = $datas[$variable];
      }
      
      //官庁
      if ($government !== '') {
        $pf_code = $government;
        $pf_name = getPf_name($pf_code);
      }

      //官庁
      if ($customers !== '') {
        $cust_code = $customers;
        $cust_name = getCus_name($cust_code);
      }
    }
  }
}

/**
 * customerデータを取得する
 */
function getCus_name($cust_code) {
  global $pdo;
  $cust_name = '';
  $datas = [];

  $sql = "SELECT cust_name FROM customer WHERE cust_code=:cust_code";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':cust_code', $cust_code);
  $stmt->execute();
  $datas = $stmt->fetch(PDO::FETCH_ASSOC);

  if (isset($datas) && !empty($datas)) {
    $cust_name = $datas['cust_name'];
  }

  return $cust_name;
}

/**
 * public_officeデータを取得する
 */
function getPf_name($pf_code) {
  global $pdo;
  $pf_name = '';
  $datas = [];

  $sql = "SELECT pf_name FROM public_office WHERE pf_code=:pf_code";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':pf_code', $pf_code);
  $stmt->execute();
  $datas = $stmt->fetch(PDO::FETCH_ASSOC);

  if (isset($datas) && !empty($datas)) {
    $pf_name = $datas['pf_name'];
  }

  return $pf_name;
}

/**
 * ec_work_detail_tr_procurementデータを取得する
 */
function getDatasFromEcWork($key_number) {
  global $pdo;
  $datas = [];

  $sql = "SELECT * FROM ec_work_detail_tr_procurment WHERE key_number=:key_number";
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
