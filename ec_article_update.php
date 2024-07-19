<?php
// 初期処理
require_once('function.php');
session_start();
// DB接続
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
$today = date('Y/m/d');
$user_code = $_SESSION["login"];
$success = true;

//ec_article_input2の登録ボタンを押下場合
if (isset($_POST['submit'])) {
  $process = $_POST['process'];
  $ec_property = $_POST['ec_property']?? ''; //物件種別

  $datas = [
    'ec_property' => $_POST['ec_property'] ?? '',     //物件種別
    'bridge' => $_POST['bridge'] ?? '',               //出先
    'add_date' => $_POST['add_date'] ?? '',           //登録日
    'ec_name' => $_POST['ec_name'] ?? 0,             //工事件名
    'pipe' => $_POST['pipe'] ?? 0,                   //管種
    'size' => $_POST['size'] ?? 0,                   //サイズ
    'valve' => $_POST['valve'] ?? 0,                 //バルブ種類
    'maker' => $_POST['maker'] ?? 0,                 //メーカー
    'bifurcation' => $_POST['bifurcation']?? 0,      //分岐形状
    'mpa' => $_POST['mpa'] ?? 0,                     //仕様(Mpa)
    'supplier' => $_POST['supplier'] ?? 0,           //発注者
    'card_no' => $_POST['card_no'] ?? '',             //カードNo.
    'ec_no' => $_POST['ec_no'] ?? '',                 //工事番号
    'contact' => $_POST['contact'] ?? '',             //契約先
    'estimate_date' => $_POST['estimate_date'] ?? '', //見積返答日
    'cost_date' => $_POST['cost_date'] ?? '',         //原価返答日
    'card_date' => $_POST['card_date'] ?? '',         //カード計上日
    'construction_date' => $_POST['construction_date'] ?? '', //施工予定日
    'footnote' => $_POST['footnote'] ?? '',           //備考    
    'con_listprice' => $_POST['con_listprice'] ?? '', //定価(工事)
    't_listprice' => $_POST['t_listprice'] ?? '',     //定価(計)
    'con_cost' => $_POST['con_cost'] ?? '',           //原価(工事)
    't_cost' => $_POST['t_cost'] ?? '',               //原価(計)    
    'con_orders' => $_POST['con_orders'] ?? '',       //受注(工事)
    't_orders' => $_POST['t_orders'] ?? '',           //受注(計)
    'con_partition' => $_POST['con_partition'] ?? '', //仕切(工事)
    't_partition' => $_POST['t_partition'] ?? '',     //仕切(計)
    'con_grossprofit' => $_POST['con_grossprofit'] ?? '',//粗利(工事)
    't_grossprofit' => $_POST['t_grossprofit'] ?? '',  //粗利(計)
  ];
  
  //IV/IVT物件情報の場合
  if ($ec_property == '1') {
    $datas['tank'] = $_POST['tank'] ?? '';//仕様タンク
    $datas['m_listprice'] = $_POST['m_listprice'] ?? '';//定価(材料)
    $datas['m_cost'] = $_POST['m_cost'] ?? '';//原価(材料)
    $datas['m_orders'] = $_POST['m_orders'] ?? '';//受注(材料)
    $datas['m_partition'] = $_POST['m_partition'] ?? '';//仕切(材料)
    $datas['m_grossprofit'] = $_POST['m_grossprofit'] ?? '';//粗利(材料)

    $insert_cols = ', tank, m_listprice, m_cost, m_orders, m_partition, m_grossprofit';
    $insert_vals = ', :tank, :m_listprice, :m_cost, :m_orders, :m_partition, :m_grossprofit';

    $update_cols_vals = ', tank=:tank, m_listprice=:m_listprice, m_cost=:m_cost, m_orders=:m_orders, m_partition=:m_partition, m_grossprofit=:m_grossprofit';
  }
  //穿孔工事物件情報の場合
  else {
    $datas['wt_bifurcation'] = $_POST['wt_bifurcation'] ?? '';//割T字形状
    $datas['wt_listprice'] = $_POST['wt_listprice'] ?? '';//定価(割T)
    $datas['valve_listprice'] = $_POST['valve_listprice'] ?? '';//定価(バルブ)
    $datas['wt_cost'] = $_POST['wt_cost'] ?? '';//原価(割T)
    $datas['valve_cost'] = $_POST['valve_cost'] ?? '';//原画(バルブ)
    $datas['wt_orders'] = $_POST['wt_orders'] ?? '';//受注(割T)
    $datas['valve_orders'] = $_POST['valve_orders'] ?? '';//受注(バルブ)
    $datas['wt_partition'] = $_POST['wt_partition'] ?? '';//仕切(割T)
    $datas['valve_partition'] = $_POST['valve_partition'] ?? '';//仕切(バルブ)
    $datas['wt_grossprofit'] = $_POST['wt_grossprofit'] ?? '';//粗利(割T)
    $datas['valve_grossprofit'] = $_POST['valve_grossprofit'] ?? '';//粗利(バルブ)

    $insert_cols = ', wt_bifurcation, wt_listprice, valve_listprice, wt_cost, valve_cost, wt_orders, valve_orders, wt_partition, valve_partition, wt_grossprofit, valve_grossprofit';
    $insert_vals = ', :wt_bifurcation, :wt_listprice, :valve_listprice, :wt_cost, :valve_cost, :wt_orders, :valve_orders, :wt_partition, :valve_partition, :wt_grossprofit, :valve_grossprofit';

    $update_cols_vals = ', wt_bifurcation=:wt_bifurcation, wt_listprice=:wt_listprice, valve_listprice=:valve_listprice, wt_cost=:wt_cost, valve_cost=:valve_cost, wt_orders=:wt_orders, 
                          valve_orders=:valve_orders, wt_partition=:wt_partition, valve_partition=:valve_partition, wt_grossprofit=:wt_grossprofit, valve_grossprofit=:valve_grossprofit';
  }

  //新規の場合
  if ($process == 'new') {
    $ym = substr(str_replace('/', '', $today), 0, 6);

    try {
      $pdo->beginTransaction();
      $sql1 = "SELECT MAX(key_number) AS key_number FROM ec_article_detail_tr_procurement WHERE key_number LIKE '$ym%'";
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

      $sql = "INSERT INTO ec_article_detail_tr_procurement(key_number, ec_property, bridge, add_date, ec_name, pipe, size, valve, maker, 
              bifurcation, mpa, supplier, card_no, ec_no, contact, estimate_date, cost_date, card_date, construction_date, 
              footnote, con_listprice, t_listprice, con_cost, t_cost, con_orders, t_orders, con_partition, t_partition, 
              con_grossprofit, t_grossprofit $insert_cols)
              VALUES (:key_number, :ec_property, :bridge, :add_date, :ec_name, :pipe, :size, :valve, :maker, 
              :bifurcation, :mpa, :supplier, :card_no, :ec_no, :contact, :estimate_date, :cost_date, :card_date, :construction_date, 
              :footnote, :con_listprice, :t_listprice, :con_cost, :t_cost, :con_orders, :t_orders, :con_partition, :t_partition, 
              :con_grossprofit, :t_grossprofit $insert_vals)";

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

      $sql = "UPDATE ec_article_detail_tr_procurement 
              SET ec_property=:ec_property, bridge=:bridge, add_date=:add_date, ec_name=:ec_name, pipe=:pipe, size=:size, valve=:valve, maker=:maker, 
              bifurcation=:bifurcation, mpa=:mpa, supplier=:supplier, card_no=:card_no, ec_no=:ec_no, contact=:contact, estimate_date=:estimate_date, 
              cost_date=:cost_date, card_date=:card_date, construction_date=:construction_date, footnote=:footnote, con_listprice=:con_listprice, 
              t_listprice=:t_listprice, con_cost=:con_cost, t_cost=:t_cost, con_orders=:con_orders, t_orders=:t_orders, con_partition=:con_partition, 
              t_partition=:t_partition, con_grossprofit=:con_grossprofit, t_grossprofit=:t_grossprofit $update_cols_vals
              WHERE key_number=:key_number";

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
    echo "<script>window.location.href='ec_article_input1.php'</script>";
  } else {
    echo "<script>window.location.href='ec_article_input2.php?err=exceErr'</script>";
  }
}
