<?php
  // 初期処理
  require_once('function.php');
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
  $today = date('Y/m/d');
  //ログインユーザーの部署ID
  $dept_id1 = getDeptId($dept_code);
  // 初期設定 & データセット
  $degree_of_order = '';            //発注確度
  $degree_of_order_list = [];
  $order_accuracy = '';             //受注確度
  $order_accuracy_list = [];
  $cust_code = '';                  //提出先名
  $cust_name = '';                  //提出先
  $cust_dept = '';                  //担当部署
  $cust_pic = '';                   //担当者
  $pf_code = '';                    //事業体名
  $pf_name = '';                    //事業体コード
  $pf_dept = '';                    //事業体担当部署
  $pf_pic = '';                     //事業体担当者
  $planned_order_date = '';         //発注予定日
  $planned_construction_date = '';  //施工予定日
  $case_div = '';                   //案件区分
  $note = '';                       //備考
  $prior_notice_div = '';           //技術への事前連絡区分
  $prior_notice_date = '';          //技術への事前連絡日
  $sq_header_datas = [];
  $item_name = '';                  //件名
  $daily_report_url = '';           //営業日報URL
  $client = '';                     //依頼者
  $sq_no = '';                      //営業依頼書№
  $new_sq_no = '';
  $process = '';                    //処理
  $regBtnDisabled = '';
  $lock_header = false;
  $err = $_GET['err'] ?? '';

  //sales_request_input3から営業依頼書№を取得する
  $sq_no = isset($_GET['sq_no']) ? $_GET['sq_no'] : '';
  $process = isset($_GET['process']) ? $_GET['process'] : '';
  $dept_id = isset($_POST['dept_id']) ? $_POST['dept_id'] : $dept_id1;

  //メールからURLをクリックしてた場合
  if (isset($_GET['from'])) {
    if ($_GET['from'] == 'mail') {
      $from_mail_sq_no = isset($_GET['sq_no']) ? $_GET['sq_no'] : '';
    }
  } else if (isset($_GET['sq_no'])) {
    //明細画面から来る場合
    $from_detail_sq_no = isset($_GET['sq_no']) ? $_GET['sq_no'] : '';
  }

  if (isset($_POST['process']) || !empty($from_mail_sq_no) || !empty($from_detail_sq_no)) {
    if (isset($_POST['process'])) {
      $process = $_POST['process']; //処理
      $sq_no = $_POST['sq_no']; //営業依頼書№
    } else if (!empty($from_mail_sq_no)) {
      //メールからURLをクリックして来た場合
      if ($title == 'input' || $title == 'check' || $title == 'confirm' || $title == 'approve') {
        //確認画面と承認画面の場合更新処理を行う
        $process = 'update';
      } else {
        //ルート設定の場合、画面の更新はしない
        $process = 'detail';
      }
      //営業依頼書No
      $sq_no = $from_mail_sq_no;
    } else {
      $sq_no = $from_detail_sq_no;
    }

    //一覧画面に更新ボタンを押下場合
    if($process == 'update' || $process == 'detail') {
      
      //営業依頼書№でsq_header_trからデータを取得する
      $sq_header_datas = get_sq_header_datas($sq_no);

      if (isset($sq_header_datas) && !empty($sq_header_datas)) {
        $cust_code = $sq_header_datas['cust_no'];                  //提出先
        $cust_dept = $sq_header_datas['cust_dept'];                //担当部署
        $cust_pic = $sq_header_datas['cust_pic'];                  //担当者
        $pf_code = $sq_header_datas['p_office_no'];                //事業体コード
        $pf_dept = $sq_header_datas['p_office_dept'];              //事業体担当部署
        $pf_pic = $sq_header_datas['p_office_pic'];                //事業体担当者
        $planned_order_date = $sq_header_datas['planned_order_date'];              //発注予定日
        $planned_construction_date = $sq_header_datas['planned_construction_date'];//施工予定日
        $case_div = $sq_header_datas['case_div'];                  //案件区分
        $note = $sq_header_datas['note'];                          //備考
        $prior_notice_div = $sq_header_datas['prior_notice_div'];  //技術への事前連絡区分
        $prior_notice_date = $sq_header_datas['prior_notice_date'];//技術への事前連絡日
        $degree_of_order = $sq_header_datas['degree_of_order'];     //発注確度
        $order_accuracy = $sq_header_datas['order_accuracy'];       //受注確度
        $item_name = $sq_header_datas['item_name'];                 //件名
        $daily_report_url = $sq_header_datas['daily_report_url'];   //営業日報
        $client = $sq_header_datas['client'];                       //依頼者

        //担当名を取得する
        if ($cust_code !== '') {
          $cust_name = get_cust_name($cust_code);
        }
        //事業体名を取得する
        if ($pf_code !== '') {
          $pf_name = get_pf_name($pf_code);
        }    
        //申請者のデータを取得する
        $client_datas = get_client_infos($client);
        if (isset($client_datas) && !empty($client_datas)) {
          $user_name = $client_datas['employee_name'];  //登録者名  
          $office_name = $client_datas['dept_name'];        //部署名
          $office_position_name = $client_datas['role_name'];        //役職
        }
      }    
    } else {
      //営業依頼書№ 自動採番
      //システム日付の年月を採取
      $ym = substr(str_replace('/', '', $today), 0, 6);
      $code_id = 'sales_request_no';

      $sql = "SELECT code_no FROM sq_code WHERE code_id = '$code_id' AND text1 = '$ym'";
      $stmt = $pdo->prepare($sql);
      $stmt->execute();
      $data = $stmt->fetchAll();

      //営業依頼書№（sq_no）自動採番
      if (isset ($data) && !empty($data)) {
        $code_no = $data[0]['code_no'];
        $no = $code_no+1;
        $new_sq_no = $ym.$no;
      } else {
        $no = '1';
        $new_sq_no = $ym.$no;
      }
    }
  }

  //「発注確度」セレクトボックスにセットするデータを取得する
  $code_id = 'degree_of_order';
  $degree_of_order_list = get_dropdown_datas($code_id);

  //「受注確度」セレクトボックスにセットするデータを取得する
  $code_id = 'order_accuracy';
  $order_accuracy_list = get_dropdown_datas($code_id);

  if ($sq_no !== '') {
    //sq_detail_trからデータを取得してテーブルにセットする
    $sq_detail_list = get_sq_detail_datas($sq_no, $process);

    //detailのできたheaderをロックする
    if (count($sq_detail_list) > 0) {
      $lock_header = true;
    } 
  }

  /*----------------------------------------------------------------FUNCTION---------------------------------------------------------------------*/
  
  function get_dropdown_datas($code_id) {
    global $pdo;
    $sql = "SELECT code_no, text1 FROM sq_code WHERE code_id = '$code_id'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $datas = $stmt->fetchAll();

    return $datas;
  }

  function get_sq_header_datas($sq_no) {
    global $pdo;
    $sql = "SELECT * FROM sq_header_tr WHERE sq_no = '$sq_no'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $datas = $stmt->fetch(PDO::FETCH_ASSOC);

    return $datas;
  }

  function get_cust_name($cust_code) {
    global $pdo;
    $cust_name = '';
    $sql = "SELECT cust_name FROM customer WHERE cust_code = '$cust_code'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $datas = $stmt->fetch(PDO::FETCH_ASSOC);

    if (isset($datas) && !empty($datas)) {
      $cust_name = $datas['cust_name'];
    }
    return $cust_name;
  }

  function get_pf_name($pf_code) {
    global $pdo;
    $pf_name = '';
    $sql = "SELECT pf_name FROM public_office WHERE pf_code = '$pf_code'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $datas = $stmt->fetch(PDO::FETCH_ASSOC);

    if (isset($datas) && !empty($datas)) {
      $pf_name = $datas['pf_name'];
    }
    return $pf_name;
  }

  /**
 * client産の情報を取得する
 */
  function get_client_infos($client)
  {
    global $pdo;

    $sql = "SELECT e.employee_name, cmd.text2 AS dept_name, cmp.text1 AS role_name, pf.pf_code AS p_office_code, pf.pf_name AS p_office_name 
      FROM sq_header_tr h
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

  /**
   * 明細一覧のデータを取得する
   */
  function get_sq_detail_datas($sq_no, $process) {
    global $pdo;
    global $title;
    global $dept_id;

    $s_title = substr($title, 0, 2);
    $e_title = substr($title, 3);
    //営業依頼書技術部の場合
    if ($process == 'detail' && $s_title == 'td') {
      $sql = "SELECT d.sq_no, d.sq_line_no, z.zkm_name, d.size, d.joint, d.pipe, d.inner_coating, d.outer_coating, d.fluid, d.valve, e.employee_name,
            CASE d.record_div 
            WHEN '1' THEN '見積'
            WHEN '2' THEN '図面'
            ELSE ''
            END AS record_div_nm
            FROM sq_detail_tr d
            LEFT JOIN sq_zaikoumei z ON d.zkm_code = z.zkm_code AND d.class_code = z.class_code
            INNER JOIN sq_route_tr r ON r.sq_no = d.sq_no AND r.sq_line_no = d.sq_line_no AND r.route1_dept = ? ";
      switch ($title) {
        case 'td_receipt':
          $sql .= "AND r.route1_receipt_date IS NULL ";
          break;
        case 'td_entrant':
          $sql .= "AND r.route1_entrant_date IS NULL AND r.route1_receipt_date IS NOT NULL ";
          break;
        case 'td_confirm':
          $sql .= "AND r.route1_confirm_date IS NULL AND r.route1_entrant_date IS NOT NULL ";
          break;
        case 'td_approve':
          $sql .= "AND r.route1_approval_date IS NULL AND r.route1_confirm_date IS NOT NULL ";
          break;
      }
      $sql .=  "LEFT JOIN employee e ON e.employee_code = r.route1_entrant WHERE d.sq_no='$sq_no'";
    } 
    //営業依頼書：営業管理部、工事管理部、資材部の場合
    else if ($process == 'detail' && ($s_title == 'sm' || $s_title == 'cm' || $s_title == 'pc')) {
      $sql = "SELECT d.sq_no, d.sq_line_no, z.zkm_name, d.size, d.joint, d.pipe, d.inner_coating, d.outer_coating, d.fluid, d.valve, e.employee_name,
            CASE d.record_div 
            WHEN '1' THEN '見積'
            WHEN '2' THEN '図面'
            ELSE ''
            END AS record_div_nm
            FROM sq_detail_tr d
            LEFT JOIN sq_zaikoumei z ON d.zkm_code = z.zkm_code AND d.class_code = z.class_code
            INNER JOIN sq_route_tr r ON r.sq_no = d.sq_no AND r.sq_line_no = d.sq_line_no AND (";
      switch ($e_title) {
        case 'receipt':
          $sql .= "(
                      route1_dept = ? AND route1_receipt_date IS NULL
                    ) OR (
                      route2_dept = ? AND route2_receipt_date IS NULL AND route1_approval_date IS NOT NULL
                    ) OR (
                      route3_dept = ? AND route3_receipt_date IS NULL AND route2_approval_date IS NOT NULL
                    ) OR (
                      route4_dept = ? AND route4_receipt_date IS NULL AND route3_approval_date IS NOT NULL
                    ) OR (
                      route5_dept = ? AND route5_receipt_date IS NULL AND route4_approval_date IS NOT NULL
                    ))";
          break;
        case 'entrant':
          $sql .= "(
                      route1_dept = ? AND route1_entrant_date IS NULL AND route1_receipt_date IS NOT NULL
                    ) OR (
                      route2_dept = ? AND route2_entrant_date IS NULL AND route2_receipt_date IS NOT NULL
                    ) OR (
                      route3_dept = ? AND route3_entrant_date IS NULL AND route3_receipt_date IS NOT NULL
                    ) OR (
                      route4_dept = ? AND route4_entrant_date IS NULL AND route4_receipt_date IS NOT NULL
                    ) OR (
                      route5_dept = ? AND route5_entrant_date IS NULL AND route5_receipt_date IS NOT NULL
                    ))";
          break;
        case 'confirm':
          $sql .= "(
                      route1_dept = ? AND route1_confirm_date IS NULL AND route1_entrant_date IS NOT NULL
                    ) OR (
                      route2_dept = ? AND route2_confirm_date IS NULL AND route2_entrant_date IS NOT NULL
                    ) OR (
                      route3_dept = ? AND route3_confirm_date IS NULL AND route3_entrant_date IS NOT NULL
                    ) OR (
                      route4_dept = ? AND route4_confirm_date IS NULL AND route4_entrant_date IS NOT NULL
                    ) OR (
                      route5_dept = ? AND route5_confirm_date IS NULL AND route5_entrant_date IS NOT NULL
                    ))";
          break;
        case 'approve':
          $sql .= "(
                      route1_dept = ? AND route1_approval_date IS NULL AND route1_confirm_date IS NOT NULL
                    ) OR (
                      route2_dept = ? AND route2_approval_date IS NULL AND route2_confirm_date IS NOT NULL
                    ) OR (
                      route3_dept = ? AND route3_approval_date IS NULL AND route3_confirm_date IS NOT NULL
                    ) OR (
                      route4_dept = ? AND route4_approval_date IS NULL AND route4_confirm_date IS NOT NULL
                    ) OR (
                      route5_dept = ? AND route5_approval_date IS NULL AND route5_confirm_date IS NOT NULL
                    ))";
          break;
      }
      $sql .=  " LEFT JOIN employee e ON (
                  (
                    CASE route1_dept 
                      WHEN ? 
                      THEN e.employee_code = route1_entrant 
                    END
                  ) OR (
                    CASE route2_dept 
                      WHEN ? 
                      THEN e.employee_code = route2_entrant 
                    END
                  ) OR (
                    CASE route3_dept 
                      WHEN ? 
                      THEN e.employee_code = route3_entrant 
                    END
                  ) OR (
                    CASE route4_dept 
                      WHEN ? 
                      THEN e.employee_code = route4_entrant 
                    END
                  ) OR (
                    CASE route5_dept 
                      WHEN ? 
                      THEN e.employee_code = route5_entrant 
                    END
                  )
                )
                WHERE d.sq_no='$sq_no'";
    } else {
      //営業依頼書依頼入力の場合
      $sql = "SELECT d.sq_no, d.sq_line_no, z.zkm_name, d.size, d.joint, d.pipe, d.inner_coating, d.outer_coating, d.fluid, d.valve,
            e1.employee_name AS confirmer, e2.employee_name AS approver,
            CASE d.record_div 
            WHEN '1' THEN '見積'
            WHEN '2' THEN '図面'
            ELSE ''
            END AS record_div_nm
            FROM sq_header_tr h
            LEFT JOIN sq_detail_tr d ON h.sq_no = d.sq_no
            LEFT JOIN sq_zaikoumei z ON d.zkm_code = z.zkm_code AND d.class_code = z.class_code
            LEFT JOIN sq_default_role r ON r.entrant = h.client AND r.dept_id = '$dept_id'
            LEFT JOIN employee e1 ON e1.employee_code = r.confirmer
            LEFT JOIN employee e2 ON e2.employee_code = r.approver
            WHERE d.sq_no='$sq_no'";
      //営業依頼書依頼確認の場合、確認日付がNULLのデータだけを取得する
      if ($title == 'check') {
        $sql .= "AND d.confirm_date IS NULL";
      }
      //営業依頼書依頼承認の場合、承認日付がNULLのデータだけを取得する
      if ($title == 'approve') {
        $sql .= "AND d.approve_date IS NULL AND d.confirm_date IS NOT NULL";
      }
      //ルート設定の場合、承認日がNOT NULLかつroute_patternがNULLのデータだけを取得する
      if ($title == 'set_route') {
        $sql .= "AND d.approve_date IS NOT NULL AND d.route_pattern IS NULL";
      }
    }
    $stmt = $pdo->prepare($sql);
    $params = array_fill(0, substr_count($sql, '?'), $dept_id);
    $stmt->execute($params);
    $datas = $stmt->fetchAll();

    return $datas;
  }
?>