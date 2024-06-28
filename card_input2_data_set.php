<?php
  // 初期処理
  require_once('function.php');

  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
  
  // 初期設定 & データセット
  $p_office_name = '';          //事業体名
  $p_office_no = '';            //事業体コード
  $card_no = '';                //依頼書№
  $preferred_date = '';         //出図希望日
  $deadline = '';               //納期
  $approver = '';               //承認者
  $approver_comments = '';      //承認者コメント
  $btn_name = '登録';

  for ($i = 1; $i <= 4; $i++) {
    ${'procurement_no' . $i} = '';         //資材部No
    ${'maker' . $i} = '';                  //製造メーカー
    ${'class_code' . $i} = '';             //分類
    ${'zkm_code' . $i} = '';               //材工名
    ${'pipe' . $i} = '';                   //管種
    ${'sizeA' . $i} = '';                   //サイズA
    ${'sizeB' . $i} = '';                   //サイズB
    ${'specification_no' . $i} = '';       //仕様書No
    ${'special_note' . $i} = '';           //特記事項
    ${'disabled_detail_btn' . $i} = 'disabled';
  }
  $err = $_GET['err'] ?? '';//エラーを取得する

  //一覧画面から移動した場合　あるいは　戻りボタンが押された場合
  if (isset($_POST['process']) || isset($_GET['card_no'])) {
    $process = $_POST['process'] ?? $_GET['process'];

    //ボタン名
    if ($process == 'update') {
      $btn_name = '承認';
    }

    //ログインユーザーの部署ID
    $department_code = getDeptId($dept_code);  
    $card_no = $_POST['card_no'] ?? $_GET['card_no']; //依頼書No

    $class_datas = get_class_datas();                     //分類プルダウンにセットするデータを取得する
    $pipeList = getDropdownData('pipe');                  //管種
    $approverList = getApproverList($department_code);    //承認者

    //card_header_trテーブルから取得する
    $header_datas = get_card_header_datas($card_no);
    if (!empty($header_datas)) {
      foreach ($header_datas as $header_data) {
        $client = $header_data['client'];                   //申請者
        $approver = $header_data['procurement_approver'];   //資材部承認者
        $p_office_no = $header_data['p_office_no'];         //事業体コード
        $p_office_name = $header_data['pf_name'];           //事業体名
        $preferred_date = $header_data['preferred_date'];   //出図希望日
        $deadline = $header_data['deadline'];               //納期
      }
      //申請者のデータを取得する
      $client_datas = get_client_infos($client);
      if (isset($client_datas) && !empty($client_datas)) {
        $user_name = $client_datas['employee_name'];  //登録者名  
        $office_name = $client_datas['dept_name'];        //部署名
        $office_position_name = $client_datas['role_name'];        //役職
      }
    }

    //card_detail_trテーブルから取得する
    $detail_datas = get_card_detail_datas($card_no);
    if (!empty($detail_datas)) {
      foreach ($detail_datas as $detail_data) {
        $i = $detail_data['sq_card_line_no'];
        ${'procurement_no' . $i} = $detail_data['procurement_no'];          //資材部No
        ${'maker' . $i} = $detail_data['maker'];                            //製造メーカー
        ${'class_code' . $i} = $detail_data['class_code'];                  //分類
        ${'zkm_code' . $i} = $detail_data['zkm_code'];                      //材工名
        ${'pipe' . $i} = $detail_data['pipe'];                              //管種
        ${'sizeA' . $i} = $detail_data['sizeA'];                            //サイズA
        ${'sizeB' . $i} = $detail_data['sizeB'];                            //サイズB
        ${'specification_no' . $i} = $detail_data['specification_no'];      //仕様書No
        ${'special_note' . $i} = $detail_data['special_note'];              //特記事項
        //データがある詳細だけ詳細ボタンを押せるようにする
        ${'disabled_detail_btn' . $i} = '';
      }
    }
  }

  //分類プルダウンがCHANGEした場合
  if (isset($_POST['function_name'])) {
   $result = get_zaikoumei_datas();
   echo json_encode($result);
 }


/*----------------------------------------------------------------FUNCTIONS---------------------------------------------------------------------*/
  /**
   * card_header_trテーブルから取得する
   */
  function get_card_header_datas($card_no) {
    global $pdo;
    $datas = [];
    $sql = "SELECT h.client, h.procurement_approver, h.p_office_no, o.pf_name, h.preferred_date, h.deadline 
            FROM card_header_tr h
            LEFT JOIN public_office o
            ON h.p_office_no = o.pf_code
            WHERE h.card_no = :card_no";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':card_no', $card_no);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $datas[] = $row;
    }
    return $datas;
  }
  
/**
   * card_detail_trテーブルから取得する
   */
  function get_card_detail_datas($card_no) {
    global $pdo;
    $datas = [];

    $sql = "SELECT * FROM card_detail_tr WHERE sq_card_no = :sq_card_no";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':sq_card_no', $card_no);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $datas[] = $row;
    }
    return $datas;
  }
  /**
   * 承認者リストを取得する
   */
  function getApproverList($department_code) {
    global $pdo;
    $datas = [];

    $sql = "SELECT e.employee_code, e.employee_name 
            FROM card_route_in_dept r
            LEFT JOIN employee e 
            ON r.employee_code = e.employee_code 
            WHERE r.department_code = '$department_code' 
            AND r.role = '3'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $datas[] = $row;
    }
    return $datas;
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

  /**
 * client産の情報を取得する
 */
  function get_client_infos($client) {
    global $pdo;

    $sql = "SELECT e.employee_name, cmd.text2 AS dept_name, cmp.text1 AS role_name, pf.pf_code AS p_office_code, pf.pf_name AS p_office_name 
    FROM card_header_tr h
    LEFT JOIN employee e
    ON e.employee_code = h.client
    LEFT JOIN code_master cmd
    ON e.department_code = cmd.text1
    AND cmd.code_id = 'department'
    LEFT JOIN code_master cmp
    ON e.office_position_code = cmp.code_no
    AND cmp.code_id = 'office_position'
    LEFT JOIN public_office pf
    ON pf.pf_code = h.p_office_no
    WHERE h.client = :client";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':client', $client);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
