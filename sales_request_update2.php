<?php
  // 初期処理
  require_once('function.php');
  include('sales_request_update2_data_set.php');
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
  $today = date('Y/m/d');
  $success = true;
  $datas = [];
  $record_div_type = '';
  $title = $_GET['title'] ?? '';

  if (isset($_POST['submit'])) {
    $process = $_POST['process2'];  //更新 || 登録
    $sq_no = $_POST['sq_no'];       //営業依頼書№
    $dept_id = $_POST['dept_id'] ?? '';

    $estimate_div1 = $_POST['estimate_div1'] ?? '';
    $estimate_div2 = $_POST['estimate_div2'] ?? '';
    $specification_div = $_POST['specification_div'] ?? '';
    $drawing_div = $_POST['drawing_div'] ?? '';
    $document_div = $_POST['document_div'] ?? '';

    //入力画面の場合
    if ($title == 'input') {
      $datas = [
        'sq_no' => $sq_no, //営業依頼書№
        'estimate_div1' => $estimate_div1, //見積区分：材料
        'estimate_div2' => $estimate_div2, //見積区分：工事
        'deadline_estimate_date' => $_POST['deadline_estimate_date'], //見積提出期限
        'specification_div' => $specification_div, //仕様書必須区分
        'check_type' => $_POST['check_type'] ?? '', //検査書区分
        'drawing_div' => $drawing_div, //参考図面区分
        'document_div' => $document_div, //資料区分
        'deadline_drawing_date' => $_POST['deadline_drawing_date'], //図面提出期限
        'cad_data_div' => $_POST['cad_data_div'] ?? '', //CADデータ区分
        'class_code' => $_POST['class'], //分類コード
        'zkm_code' => $_POST['zaikoumei'], //材工名コード
        'c_div' => $_POST['c_div_code'], //一般工事区分
        'const_div1' => $_POST['const_div1'] ?? '', //工事区分1
        'const_div2' => $_POST['const_div2'] ?? '', //工事区分2
        'const_div3' => $_POST['const_div3'] ?? '', //工事区分3
        'const_div4' => $_POST['const_div4'] ?? '', //工事区分4
        'size' => $_POST['size'], //サイズ
        'joint' => $_POST['joint'], //接合形状
        'pipe' => $_POST['pipe'], //管種
        'size2' => $_POST['size2'], //サイズ
        'joint2' => $_POST['joint2'], //接合形状
        'pipe2' => $_POST['pipe2'], //管種
        'inner_coating' => $_POST['inner_coating'], //内面塗装
        'inner_film' => $_POST['inner_film'], //内面塗装：膜厚
        'outer_coating' => $_POST['outer_coating'], //外面塗装
        'outer_film' => $_POST['outer_film'], //外面塗装：膜厚
        'fluid' => $_POST['fluid'], //管内流体
        'valve' => $_POST['valve'], //バルブ仕様
        'o_c_direction' => $_POST['o_c_direction'], //開閉方向
        'special_tube_od1' => $_POST['special_tube_od1'] ? $_POST['special_tube_od1'] : 0, //特殊管外径1
        'special_tube_od2' => $_POST['special_tube_od2'] ? $_POST['special_tube_od2'] : 0, //特殊管外径2
        'quantity' => $_POST['quantity'] ? $_POST['quantity'] : 0, //数量
        'right_quantity' => $_POST['right_quantity'] ? $_POST['right_quantity'] : 0, //右用数量
        'left_quantity' => $_POST['left_quantity'] ? $_POST['left_quantity'] : 0, //左用数量
        'design_water_pressure' => $_POST['design_water_pressure'], //設計水圧
        'water_outage' => $_POST['water_outage'] ?? '', //断水区分
        'normal_water_puressure' => $_POST['normal_water_puressure'], //常圧
        'reducing_pressure_div' => $_POST['reducing_pressure_div'] ?? '', //施工時減圧区分
        'special_note' => $_POST['special_note'], //特記事項
        'entrant_comments' => $_POST['entrant_comments'],  //入力者コメント
        'status' => '1'
      ];

      //見積区分１（estimate_div1）または、見積区分２（estimate_div2）が、"1" の場合で、
      //仕様書必須区分（specification_div）、参考図面区分（drawing_div）、資料区分（document_div）のいずれかが、"1" の場合
      if (($estimate_div1 == "1" || $estimate_div2 == "1") && ($specification_div == "1" || $drawing_div == "1" || $document_div == "1")) {
        //レコード区分（record_div）＝ ”１”　：見積レコード と　レコード区分（record_div）＝ ”２”　：図面レコードの２つのレコードを作成する。
        $record_div_type = 'both';
      } else if (($estimate_div1 == "1" || $estimate_div2 == "1") && ($specification_div == "" && $drawing_div == "" && $document_div == "")) {
        $datas['record_div'] = '1';
      } else if (($estimate_div1 == "" && $estimate_div2 == "") && ($specification_div == "1" || $drawing_div == "1" || $document_div == "1")) {
        $datas['record_div'] = '2';
      } else {
        $datas['record_div'] = NULL;
      }
    }

    //確認画面の時だけ確認者をセットする
    else if ($title == 'check') {
      $datas = [
       'sq_no' => $sq_no, //営業依頼書№
       'confirmer_comments' => $_POST['confirmer_comments'],  //確認者コメント
       'confirmer' => $_POST['user_code'],                    //確認者
       'confirm_date' => $today                               //確認日
      ];
    } 
    //承認画面の時だけ確認者をセットする
    else if ($title == 'approve') {
      $datas = [
       'sq_no' => $sq_no, //営業依頼書№
       'approver_comments' => $_POST['approver_comments'],  //承認者コメント
       'approver' => $_POST['user_code'],                    //承認者
       'approve_date' => $today                              //承認日
      ];
    }

    //新規作成の場合
    if ($process == 'new' || $process == 'copy') {
      $sq_line_no = get_sq_line_no($sq_no); //営業依頼書行№
      $datas['sq_line_no'] = $sq_line_no;
      $datas['add_date'] = $today;
      $datas['status'] = '1';

      if ($record_div_type == "both") {
        for ($i=0; $i<2; $i++) { 
          if ($i == 0) {
            //見積レコード
            $datas['estimate_div1'] = $estimate_div1;
            $datas['estimate_div2'] = $estimate_div2;
            $datas['specification_div'] = '';
            $datas['drawing_div'] = '';
            $datas['document_div'] = '';
            $datas['record_div'] = '1';
            $success = insertData($datas);
          } else {
            //図面レコード
            $datas['estimate_div1'] = '';
            $datas['estimate_div2'] = '';
            $datas['specification_div'] = $specification_div;
            $datas['drawing_div'] = $drawing_div;
            $datas['document_div'] = $document_div;
            $datas['record_div'] = '2';
            $datas['sq_line_no'] = $sq_line_no+1;
            $success = insertData($datas);
          }
        }
      } else {
        //登録する
        $success = insertData($datas);
      }      
    } else {
      //更新の場合
      $sq_line_no = $_POST['sq_line_no'];
      $datas['sq_line_no'] = $sq_line_no;
      $datas['upd_date'] = $today;
      //更新する
      $success = updateData($datas);
    }

    //エラーがなかったらメール送信する
    if ($success) {
      //Send Mail
      include('sq_mail_send1.php');
    } else {
      $redirect_url = 'sales_request_' . $title . '3.php?err=errExec&title=' . $title . "&process2=" . $process . "&sq_no=" . $sq_no . "&line=" . $datas['sq_line_no'];
      header('location:' . $redirect_url);
    }
  }

  function insertData($datas) {
    global $pdo;
    $success = true;
    global $title;
    try {
      $pdo->beginTransaction();
      
      $sql = "INSERT INTO sq_detail_tr (sq_no,sq_line_no,estimate_div1,estimate_div2,deadline_estimate_date,specification_div,check_type,drawing_div,document_div,deadline_drawing_date,
              cad_data_div,class_code,zkm_code,c_div,const_div1,const_div2,const_div3,const_div4,size,
              joint,pipe,size2,joint2,pipe2,inner_coating,inner_film,outer_coating,outer_film,fluid,valve,o_c_direction,special_tube_od1,special_tube_od2,
              quantity,right_quantity,left_quantity,design_water_pressure,water_outage,normal_water_puressure,reducing_pressure_div,special_note,entrant_comments,";
      //確認画面の時だけ確認者をセットする
      if ($title == 'check') {
        $sql.= 'confirmer_comments,confirmer,confirm_date,';
      } 
      //承認画面の時だけ確認者をセットする
      else if ($title == 'approve') {
        $sql.= 'approver_comments,approver,approve_date,';
      }
      //入力画面の時だけステータスをセットする
      else {
        $sql.= 'status,';
      }
      $sql.= "record_div,add_date)
              VALUES(
              :sq_no,:sq_line_no,:estimate_div1,:estimate_div2,:deadline_estimate_date,:specification_div,:check_type,:drawing_div,:document_div,:deadline_drawing_date,
              :cad_data_div,:class_code,:zkm_code,:c_div,:const_div1,:const_div2,:const_div3,:const_div4,:size,
              :joint,:pipe,:size2,:joint2,:pipe2,
              :inner_coating,:inner_film,:outer_coating,:outer_film,:fluid,:valve,:o_c_direction,:special_tube_od1,:special_tube_od2,
              :quantity,:right_quantity,:left_quantity,:design_water_pressure,:water_outage,:normal_water_puressure,:reducing_pressure_div,:special_note,:entrant_comments,";
      //確認画面の時だけ確認者をセットする
      if ($title == 'check') {
        $sql.= ':confirmer_comments,:confirmer,:confirm_date,';
      } 
      //承認画面の時だけ確認者をセットする
      else if ($title == 'approve') {
        $sql.= ':approver_comments,:approver,:approve_date,';
      }
      //入力画面の時だけステータスをセットする
      else {
        $sql.= ':status,';
      }
      $sql.= ":record_div,:add_date)";

      $stmt = $pdo->prepare($sql);
      $stmt->execute($datas);
      $pdo->commit();
    } catch (PDOException $e) {
      $success = false;
      $pdo->rollback();
      error_log("PDO Exception: " . $e->getMessage(),3,'error_log.txt');
    }
    return $success;
  }

  function updateData($datas) {
    global $pdo;
    global $title;
    $success = true;
    try {
      $pdo->beginTransaction();

      $sql = "UPDATE sq_detail_tr SET ";
      //確認画面の時
      if ($title == 'input') {
        $sql .= "estimate_div1=:estimate_div1,estimate_div2=:estimate_div2,deadline_estimate_date=:deadline_estimate_date,
                specification_div=:specification_div,check_type=:check_type,drawing_div=:drawing_div,document_div=:document_div,deadline_drawing_date=:deadline_drawing_date,
                cad_data_div=:cad_data_div,class_code=:class_code,zkm_code=:zkm_code,c_div=:c_div,const_div1=:const_div1,const_div2=:const_div2,const_div3=:const_div3,
                const_div4=:const_div4,size=:size,joint=:joint,pipe=:pipe,size2=:size2,joint2=:joint2,pipe2=:pipe2,inner_coating=:inner_coating,inner_film=:inner_film,outer_coating=:outer_coating,
                outer_film=:outer_film,fluid=:fluid,valve=:valve,o_c_direction=:o_c_direction,special_tube_od1=:special_tube_od1,special_tube_od2=:special_tube_od2,quantity=:quantity,right_quantity=:right_quantity,
                left_quantity=:left_quantity,design_water_pressure=:design_water_pressure,water_outage=:water_outage,normal_water_puressure=:normal_water_puressure,
                reducing_pressure_div=:reducing_pressure_div,special_note=:special_note,entrant_comments=:entrant_comments,record_div=:record_div,status=:status,";
      }
      //確認画面の時だけ確認者をセットする
      else if ($title == 'check') {
        $sql .= 'confirmer_comments=:confirmer_comments,confirmer=:confirmer,confirm_date=:confirm_date,';
      }
      //承認画面の時だけ確認者をセットする
      else if ($title == 'approve') {
        $sql .= 'approver_comments=:approver_comments,approver=:approver,approve_date=:approve_date,';
      }

      $sql.= "upd_date=:upd_date WHERE sq_no=:sq_no AND sq_line_no=:sq_line_no";

      $stmt = $pdo->prepare($sql);      
      $stmt->execute($datas);
      $pdo->commit();
    } catch (PDOException $e) {
      $success = false;
      $pdo->rollback();
      error_log("PDO Exception: " . $e->getMessage(),3,'error_log.txt');
    }

    return $success;
  }

?>