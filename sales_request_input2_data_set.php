<?php
  // 初期処理
  require_once('function.php');
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

  // 初期設定 & データセット
  $class_code = '';             //分類
  $zkm_cd = '';                 //材工名
  $size = '';                   //サイズ
  $joint = '';                  //接合形状
  $pipe = '';                   //管種
  $fluid = '';                  //管内流体
  $inner_coating = '';          //内面塗装
  $outer_coating = '';          //外面塗装
  $valve = '';                  //バルブ仕様
  $estimate_div1 = '';          //材料費
  $estimate_div2 = '';          //工事費
  $specification_div = '';      //仕様書必要
  $drawing_div = '';            //参考図面必要
  $document_div = '';           //資料必要
  $check_type = '';             //日水協 or 社内証
  $deadline_estimate_date = ''; //見積提出期限
  $deadline_drawing_date = '';  //図面等提出期限
  $cad_data_div = '';           //CADデータ
  $const_div1 = '';             //昼間
  $const_div2 = '';             //夜間
  $const_div3 = '';             //昼間・夜間
  $const_div4 = '';             //昼夜通し
  $c_div = '';                  //区分
  $design_water_pressure = '';  //設計水圧
  $reducing_pressure_div = '';  //施工時減圧
  $normal_water_puressure = ''; //常圧
  $water_outage = '';           //断水
  $inner_film = '';             //膜厚
  $outer_film = '';             //膜厚
  $quantity = '';               //数量
  $right_quantity = '';         //右用
  $left_quantity = '';          //左用
  $special_note = '';           //特記仕様
  $sq_line_no = '';             //営業依頼書行№	
  $record_div = '';             //レコード区分
  $special_tube_od1 = '';       //特殊管外径１
  $special_tube_od2 = '';       //特殊管外径２
  $sq_no = '';
  $route_pattern = '';          //ルートパタン
  $entrant_comments = '';           //入力者コメント
  $confirmer_comments = '';          //確認者コメント
  $approver_comments = '';           //承認者コメント

  $sizeDisabled = '';
  $jointDisabled = '';
  $pipeDisabled = '';
  $fluidDisabled = '';
  $inner_coatingDisabled = '';
  $outer_coatingDisabled = '';
  $valveDisabled = '';
  $o_c_directionDisabled = '';
  $zumen_disabled = false;
  $mitsumori_disabled = false;

  $class_datas = get_class_datas();                     //分類プルダウンにセットするデータを取得する
  $sizeList = getDropdownData('size');                  //サイズ
  $jointList = getDropdownData('joint');                //接合形状
  $pipeList = getDropdownData('pipe');                  //管種
  $fluidList = getDropdownData('fluid');                //管内流体
  $inner_coatingList = getDropdownData('inner_coating');//内面塗装
  $outer_coatingList = getDropdownData('outer_coating');//外面塗装
  $valveList = getDropdownData('valve');                //バルブ仕様
  $o_c_directionList = getDropdownData('o_c_direction');//開閉方向

  if(isset($_POST['process2'])) {
    $process2 = $_POST['process2'];
    $sq_no = $_POST['sq_no'];
    $dept_id = isset($_POST['dept_id']) ? $_POST['dept_id'] : '';

    if ($process2 == 'update' || $process2 == 'copy' || $process2 == 'detail') {
      $sq_line_no = $_GET['line'];

      $sq_detail_datas = get_sq_detail_datas($sq_no, $sq_line_no);
      if (isset($sq_detail_datas) && !empty($sq_detail_datas)) {
        $estimate_div1 = $sq_detail_datas['estimate_div1'];                     //材料費
        $estimate_div2 = $sq_detail_datas['estimate_div2'];                     //工事費
        $specification_div = $sq_detail_datas['specification_div'];             //仕様書必要
        $drawing_div = $sq_detail_datas['drawing_div'];                         //参考図面必要
        $document_div = $sq_detail_datas['document_div'];                       //資料必要
        $check_type = $sq_detail_datas['check_type'];                           //日水協 or 社内証
        $deadline_estimate_date = $sq_detail_datas['deadline_estimate_date'];   //見積提出期限
        $deadline_drawing_date = $sq_detail_datas['deadline_drawing_date'];     //図面等提出期限
        $cad_data_div = $sq_detail_datas['cad_data_div'];                       //CADデータ
        $const_div1 = $sq_detail_datas['const_div1'];                           //昼間
        $const_div2 = $sq_detail_datas['const_div2'];                           //夜間
        $const_div3 = $sq_detail_datas['const_div3'];                           //昼間・夜間
        $const_div4 = $sq_detail_datas['const_div4'];                           //昼夜通し
        $c_div = $sq_detail_datas['c_div'];                                     //区分
        $design_water_pressure = $sq_detail_datas['design_water_pressure'];     //設計水圧
        $reducing_pressure_div = $sq_detail_datas['reducing_pressure_div'];     //施工時減圧
        $normal_water_puressure = $sq_detail_datas['normal_water_puressure'];   //常圧
        $water_outage = $sq_detail_datas['water_outage'];                       //断水
        $inner_film = $sq_detail_datas['inner_film'];                           //膜厚
        $outer_film = $sq_detail_datas['outer_film'];                           //膜厚
        $quantity = $sq_detail_datas['quantity'];                               //数量
        $right_quantity = $sq_detail_datas['right_quantity'];                   //右用
        $left_quantity = $sq_detail_datas['left_quantity'];                     //左用
        $special_note = $sq_detail_datas['special_note'];                       //特記仕様
        $size = $sq_detail_datas['size'];                                       //サイズ
        $joint = $sq_detail_datas['joint'];                                     //接合形状
        $pipe = $sq_detail_datas['pipe'];                                       //管種
        $fluid = $sq_detail_datas['fluid'];                                     //管内流体
        $inner_coating = $sq_detail_datas['inner_coating'];                     //内面塗装
        $outer_coating = $sq_detail_datas['outer_coating'];                     //外面塗装
        $valve = $sq_detail_datas['valve'];                                     //バルブ仕様
        $record_div = $sq_detail_datas['record_div'];                           //レコード区分
        $class_code = $sq_detail_datas['class_code'];                           //分類
        $zkm_cd = $sq_detail_datas['zkm_code'];                                 //材工名
        $special_tube_od1 = $sq_detail_datas['special_tube_od1'];               //特殊管外径１
        $special_tube_od2 = $sq_detail_datas['special_tube_od2'];               //特殊管外径２
        $route_pattern = $sq_detail_datas['route_pattern'];                     //ルートパタン
        $entrant_comments = $sq_detail_datas['entrant_comments'];             //入力者コメント
        $approver_comments = $sq_detail_datas['approver_comments'];          //承認者コメント
        $confirmer_comments = $sq_detail_datas['confirmer_comments'];        //確認者コメント
      }
    }
  }

    /*----------------------------------------------------------------FUNCTION---------------------------------------------------------------------*/
    
  if (isset($_POST['function_name'])) {
    $result = get_zaikoumei_datas();
    echo json_encode($result);
  }

  function get_class_datas() {
    global $pdo;
    $sql = "SELECT * FROM sq_class";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $datas = $stmt->fetchAll();

    return $datas;
  } 

  function get_zaikoumei_datas() {
    global $pdo;
    $class_code = $_POST['class_code'];
    $datas = [];

    $sql = "SELECT z.zkm_code, z.zkm_name, c.text1 ,c.code_no
    FROM sq_zaikoumei z
    LEFT JOIN sq_code c
    ON z.c_div = c.code_no
    AND c.code_id = 'c_div'
    WHERE z.class_code = '$class_code'";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $datas[] = $row;
    }

    return $datas;
  }

  function getDropdownData($code_id) {
    global $pdo;
    //sq_code テーブルからデータ取得する
    $sql = "SELECT c.text1, zk.zk_div_data 
    FROM sq_code c
    LEFT JOIN sq_zk2 zk
    ON c.code_id = zk.zk_division
    AND c.text1 = zk.zk_tp
    WHERE code_id='$code_id'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $datas = $stmt->fetchAll();
    return $datas;
  }

  function get_sq_detail_datas($sq_no, $sq_line_no) {
    global $pdo;
    $sql = "SELECT * FROM sq_detail_tr WHERE sq_no='$sq_no' AND sq_line_no='$sq_line_no'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $datas = $stmt->fetch(PDO::FETCH_ASSOC);

    return $datas;
  }
?>