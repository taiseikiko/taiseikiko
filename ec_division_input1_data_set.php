<?php
  // 初期処理
  require_once('function.php');
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
  $ec_names = [
    'ec_name'=>'工事件名',
    'pipe'=>'管種',
    'size'=>'サイズ',
    'valve'=>'バルブ種類',
    'maker'=>'メーカー',
    'bifurcation'=>	'分岐形状',
    'tank'=>'仕様タンク',
    'wt_bifurcation'=>'割T字形状',
    'mpa'=>'仕様(Mpa)',
    'supplier'=>'発注者',
    'government'=>'官庁',
    'customers'=>'得意先',
    'ec_place'=>'施工場所',
    'design_pressure'=>'設計圧',
    'cutter'=>'切断機',
    'drill'=>'穿孔機',
    'company'=>'stpの会社名リスト'
  ];

  function getEcDatas() {
    global $pdo;
    $sql = "SELECT * FROM ec_code_master";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $class_datas = $stmt->fetchAll();
    return $class_datas;
  }

?>