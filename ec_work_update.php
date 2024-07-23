<?php
// 初期処理
require_once('function.php');
session_start();
// DB接続
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
$today = date('Y/m/d');
$user_code = $_SESSION["login"];
$success = true;
$insert_cols = '';

//ec_work_input2の登録ボタンを押下場合
if (isset($_POST['submit'])) {
  $process = $_POST['process'];
  $ec_division = $_POST['ec_division']?? ''; //工事区分

  $datas = [
    'ec_division' => $_POST['ec_division'] ?? '',     //物件種別
    'bridge' => $_POST['bridge'] ?? '',               //出先
    'government' => $_POST['pf_code'] ?? '',           //官庁
    'customers' => $_POST['cust_code'] ?? '',         //得意先
    'ec_date' => $_POST['ec_date'] ?? '',             //工事日
    'ec_number' => $_POST['ec_number'] ?? '',         //工事番号
    'ec_place' => $_POST['ec_place'] ?? 0,           //施工場所
    'pipe' => $_POST['pipe'] ?? 0 ,                  //管種
    'size' => $_POST['size'] ?? 0 ,                  //サイズ    
    'valve' => $_POST['valve'] ?? 0 ,                //バルブ種類
    'specification_number' => $_POST['specification_number'] ?? '' ,  //仕様書番号
    'design_pressure' => $_POST['design_pressure'] ?? 0 ,            //設計圧
    'scene_water_pressure' => $_POST['scene_water_pressure'] ?? '' ,  //現場水圧
    'slant' => $_POST['slant'] ?? '' ,                //傾斜
    'tank' => $_POST['tank'] ?? 0 ,                  //タンク
    'cutter' => $_POST['cutter'] ?? 0 ,              //切断機
    'maker' => $_POST['maker'] ?? 0 ,                //メーカー
    'coating' => $_POST['coating'] ?? 0 ,            //塗装
    'm_cost' => $_POST['m_cost'] ?? '' ,              //原価(材料)
    'wt_cost' => $_POST['wt_cost'] ?? '' ,            //原価(割T)
    'valve_cost' => $_POST['valve_cost'] ?? '' ,      //原画(バルブ)
    'con_cost' => $_POST['con_cost'] ?? '' ,          //原価(工事)
    't_cost' => $_POST['t_cost'] ?? '' ,              //原価(計)
    'm_orders' => $_POST['m_orders'] ?? '' ,          //受注(材料)
    'wt_orders' => $_POST['wt_orders'] ?? '' ,        //受注(割T)
    'valve_orders' => $_POST['valve_orders'] ?? '' ,  //受注(バルブ)
    'con_orders' => $_POST['con_orders'] ?? '' ,      //受注(工事)
    't_orders' => $_POST['t_orders'] ?? '' ,          //受注(計)
    'm_grossprofit' => $_POST['m_grossprofit'] ?? '' ,//粗利(材料)
    'wt_grossprofit' => $_POST['wt_grossprofit'] ?? '' ,        //粗利(割T)
    'valve_grossprofit' => $_POST['valve_grossprofit'] ?? '' ,  //粗利(バルブ)
    'con_grossprofit' => $_POST['con_grossprofit'] ?? '' ,      //粗利(工事)
    't_grossprofit' => $_POST['t_grossprofit'] ?? '' ,          //粗利(計)
    'trouble' => $_POST['trouble'] ?? '' ,            //トラブル
    'cause' => $_POST['cause'] ?? '' ,                //原因
    'gross_footnote' => $_POST['gross_footnote'] ?? '' ,        //粗利備考 
    'bifurcation' => $_POST['bifurcation'] ?? 0 ,    //分岐形状
    'shape' => $_POST['shape'] ?? '' ,                //形
    'drill' => $_POST['drill'] ?? 0 ,                //穿孔機
    'drill2' => $_POST['drill2'] ?? 0 ,              //穿孔機2回目
    'water_pressure' => $_POST['water_pressure'] ?? '' ,         //水圧
    'quantity' => $_POST['quantity'] ?? '' ,          //数量
    'wt_bifurcation' => $_POST['wt_bifurcation'] ?? 0 ,         //割T字形状
    'footnote1' => $_POST['footnote1'] ?? '' ,        //備考
    'ec_extension' => $_POST['ec_extension'] ?? '' ,  //施工延長
    'ec_name' => $_POST['ec_name'] ?? '' ,            //工事名称
    'ec_ready_from' => $_POST['ec_ready_from'] ?? '' ,//工事開始（from)
    'ec_ready_to' => $_POST['ec_ready_to'] ?? '' ,    //工事開始（to)
    'footnote2' => $_POST['footnote2'] ?? '' ,        //備考２
  ]; 
  
  //新規の場合
  if ($process == 'new') {
    $ym = substr(str_replace('/', '', $today), 0 , 6);

    try {
      $pdo->beginTransaction();
      $sql1 = "SELECT MAX(key_number) AS key_number FROM ec_work_detail_tr_procurment WHERE key_number LIKE '$ym%'";
      $stmt1 = $pdo->prepare($sql1);
      $stmt1->execute();
      $from_tb_key_number = $stmt1->fetchColumn();
      
      if (!empty($from_tb_key_number)) {
        $no = substr($from_tb_key_number, 6, 2) + 1;      
      } else {
        $no = '1';
      }

      $key_number = $ym . sprintf('%02d', $no);
      $datas['key_number'] = $key_number;//連番キー

      $sql = "INSERT INTO ec_work_detail_tr_procurment(key_number, ec_division, bridge, government, customers, ec_date, ec_number, ec_place, pipe, 
              size, valve, specification_number, design_pressure, scene_water_pressure, slant, tank, cutter, maker, coating, m_cost, wt_cost, 
              valve_cost, con_cost, t_cost, m_orders, wt_orders, valve_orders, con_orders, t_orders, m_grossprofit, wt_grossprofit, 
              valve_grossprofit, con_grossprofit, t_grossprofit, trouble, cause, gross_footnote, bifurcation, shape, drill, drill2, 
              water_pressure, quantity, wt_bifurcation, footnote1, ec_extension, ec_name, ec_ready_from, ec_ready_to, footnote2)
              VALUES (:key_number, :ec_division, :bridge, :government, :customers, :ec_date, :ec_number, :ec_place, :pipe, 
              :size, :valve, :specification_number, :design_pressure, :scene_water_pressure, :slant, :tank, :cutter, :maker, :coating, :m_cost, :wt_cost, 
              :valve_cost, :con_cost, :t_cost, :m_orders, :wt_orders, :valve_orders, :con_orders, :t_orders, :m_grossprofit, :wt_grossprofit, 
              :valve_grossprofit, :con_grossprofit, :t_grossprofit, :trouble, :cause, :gross_footnote, :bifurcation, :shape, :drill, :drill2, 
              :water_pressure, :quantity, :wt_bifurcation, :footnote1, :ec_extension, :ec_name, :ec_ready_from, :ec_ready_to, :footnote2)";

      $stmt = $pdo->prepare($sql);
      $stmt->execute($datas);

      $pdo->commit();
    } catch (PDOException $e) {
      $success = false;
      $pdo->rollback();
      error_log("PDO Exception: " . $e->getMessage(),3,'error_log.txt');
    }

      
  } else {
    try {
      $pdo->beginTransaction();      
      $datas['key_number'] = $_POST['key_number'];//連番キー

      $sql = "UPDATE ec_work_detail_tr_procurment
              SET ec_division=:ec_division, bridge=:bridge, government=:government, customers=:customers, ec_date=:ec_date, ec_number=:ec_number, ec_place=:ec_place, 
              pipe=:pipe, size=:size, valve=:valve, specification_number=:specification_number, design_pressure=:design_pressure, 
              scene_water_pressure=:scene_water_pressure, slant=:slant, tank=:tank, cutter=:cutter, maker=:maker, coating=:coating, m_cost=:m_cost, wt_cost=:wt_cost, 
              valve_cost=:valve_cost, con_cost=:con_cost, t_cost=:t_cost, m_orders=:m_orders, wt_orders=:wt_orders, valve_orders=:valve_orders, con_orders=:con_orders,
              t_orders=:t_orders, m_grossprofit=:m_grossprofit, wt_grossprofit=:wt_grossprofit, valve_grossprofit=:valve_grossprofit, con_grossprofit=:con_grossprofit,
              t_grossprofit=:t_grossprofit, trouble=:trouble, cause=:cause, gross_footnote=:gross_footnote, bifurcation=:bifurcation, shape=:shape, drill=:drill, 
              drill2=:drill2, water_pressure=:water_pressure, quantity=:quantity, wt_bifurcation=:wt_bifurcation, footnote1=:footnote1, ec_extension=:ec_extension, 
              ec_name=:ec_name, ec_ready_from=:ec_ready_from, ec_ready_to=:ec_ready_to, footnote2=:footnote2
              WHERE key_number = :key_number";

      $stmt = $pdo->prepare($sql);
      $stmt->execute($datas);

      $pdo->commit();
    } catch (PDOException $e) {
      $success = false;
      $pdo->rollback();
      error_log("PDO Exception: " . $e->getMessage(),3,'error_log.txt');
    }
  }

  //エラーがない場合
  if ($success == true) {
    echo "<script>window.location.href='ec_work_input1.php'</script>";
  } else {
    echo "<script>window.location.href='ec_work_input2.php?err=exceErr'</script>";
  }
}
