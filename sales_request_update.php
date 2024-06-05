<?php
  // 初期処理
  require_once('function.php');
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
  $today = date('Y/m/d');
  $success = true;
  $title = isset($_GET['title']) ? $_GET['title'] : '';

  if (isset($_POST['submit'])) {
    $process = $_POST['process'];
    $dept_id = isset($_POST['dept_id']) ? $_POST['dept_id'] : '';
    //入力画面の場合確認と承認に関する項目にNULLをセットする
    if ($title == 'input') {
      $confirmer = NULL;
      $confirm_date = NULL;
      $approver = NULL;
      $approve_date = NULL;
    }

    //営業依頼書№ 自動採番
    //システム日付の年月を採取
    $ym = substr(str_replace('/', '', $today), 0, 6);
    $code_id = 'sales_request_no';

    $sql = "SELECT code_no FROM sq_code WHERE code_id = '$code_id' AND text1 = '$ym'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll();

    //新規作成の場合
    if ($process == 'new') {
      //営業依頼書№（sq_no）自動採番
      try {
        $pdo->beginTransaction();
        if (isset ($data) && !empty($data)) {
          $code_no = $data[0]['code_no'];
          $no = $code_no+1;
          $sq_no = $ym.$no;
          //テーブルsq_codeへ更新する
          $sql = "UPDATE sq_code SET code_no=:code_no WHERE code_id=:code_id AND text1=:text1";
        } else {
          $no = '1';
          $sq_no = $ym.$no;
          //テーブルsq_codeへ登録する
          $sql = "INSERT INTO sq_code(code_id, code_no, text1) VALUES (:code_id, :code_no, :text1)";
        }
        $data = [
          'code_id' => $code_id,
          'code_no' => $no,
          'text1' => $ym
        ];

        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);
        $pdo->commit();
      } catch (PDOException $e) {
        if (strpos($e->getMessage(), 'SQLSTATE[42000]') !== false) {
          error_log("SQL Syntax Error or Access Violation: " . $e->getMessage(),3,'error_log.txt');
        } else {
          $pdo->rollback();
          throw($e);
          error_log("PDO Exception: " . $e->getMessage(),3,'error_log.txt');
        }
      }
    }

    //承認画面の時だけ確認者をセットする
    if ($title == 'approve') {
      $approver = $_POST['user_code'];
      $approve_date = $today;
    }
    
    $datas = [          
      'client' => $_POST['user_code'],        //依頼者
      'cust_no' => $_POST['cust_code'],       //得意先コード
      'cust_dept' => $_POST['cust_dept'],     //得意先担当部署
      'cust_pic' => $_POST['cust_pic'],       //得意先担当者
      'p_office_no' => $_POST['pf_code'],     //事業体コード
      'p_office_dept' => $_POST['pf_dept'],   //事業体担当部署
      'p_office_pic' => $_POST['pf_pic'],     //事業体担当者
      'planned_order_date' => $_POST['planned_order_date'],              //発注予定日
      'planned_construction_date' => $_POST['planned_construction_date'],//施工予定日
      'degree_of_order' => $_POST['degree_of_order'],     //発注確度
      'order_accuracy' => $_POST['order_accuracy'],       //受注確度
      'case_div' => isset($_POST['case_div']) ? $_POST['case_div'] : '',                   //案件区分
      'related_sq_no' => "",//関連依頼書№
      'daily_report_url' => $_POST['daily_report_url'],  //営業日報URL
      'note' => $note = $_POST['note'],                           //備考
      'prior_notice_div' => isset($_POST['prior_notice_div']) ? $_POST['prior_notice_div'] : '',   //技術への事前連絡区分
      'prior_notice_date' => $_POST['prior_notice_date'], //技術への事前連絡日
      'entrant_comments' => $_POST['entrant_comments'],  //入力者コメント
      // 'approver_comments' => isset($_POST['approver_comments']) ? $_POST['approver_comments'] : NULL,
      // 'approver' => $approver,    //確認者
      // 'approve_date' => $approve_date,//確認日
      'item_name' => $_POST['item_name'],                //件名
      'status' => 'TEST'
    ];

    try {
      $pdo->beginTransaction();
      //確認画面の時だけ確認者をセットする
      if ($title == 'check') {
        $datas['confirmer_comments'] = $_POST['confirmer_comments'];  //確認者コメント
        $datas['confirmer'] = $_POST['user_code'];                    //確認者
        $datas['confirm_date'] = $today;                              //確認日
      } 
      //承認画面の時だけ確認者をセットする
      else if ($title == 'approve') {
        $datas['approver_comments'] = $_POST['approver_comments'];  //承認者コメント
        $datas['approver'] = $_POST['user_code'];                    //承認者
        $datas['approve_date'] = $today;                              //承認日
      }
      //新規作成の場合
      if ($process == 'new') {
        $datas['add_date'] = $today;
        $datas['sq_no'] = $sq_no;

        $sql = 'INSERT INTO sq_header_tr(sq_no,client,cust_no,cust_dept,cust_pic,p_office_no,p_office_dept,p_office_pic,planned_order_date,planned_construction_date,
              degree_of_order,order_accuracy,case_div,related_sq_no,daily_report_url,note,prior_notice_div,prior_notice_date,entrant_comments,';
        //確認画面の時だけ確認者をセットする
        if ($title == 'check') {
          $sql.= 'confirmer_comments,confirmer,confirm_date,';
        } 
        //承認画面の時だけ確認者をセットする
        else if ($title == 'approve') {
          $sql.= 'approver_comments,approver,approve_date,';
        }
        $sql.= 'add_date, item_name, status)';
        $sql.= 'VALUES (:sq_no,:client,:cust_no,:cust_dept,:cust_pic,:p_office_no,:p_office_dept,:p_office_pic,:planned_order_date,:planned_construction_date,
              :degree_of_order,:order_accuracy,:case_div,:related_sq_no,:daily_report_url,:note,:prior_notice_div,:prior_notice_date,:entrant_comments,'; 
        //確認画面の時だけ確認者をセットする
        if ($title == 'check') {
          $sql.= ':confirmer_comments,:confirmer,:confirm_date,';
        }
        //承認画面の時だけ確認者をセットする
        else if ($title == 'approve') {
          $sql.= ':approver_comments,:approver,:approve_date,';
        }
        $sql.= ':add_date,:item_name, :status)';
      } 
      //更新の場合
      else {
        $datas['upd_date'] = $today;
        $datas['sq_no'] = $_POST['sq_no'];

        $sql = 'UPDATE sq_header_tr SET client=:client,cust_no=:cust_no,cust_dept=:cust_dept,cust_pic=:cust_pic,p_office_no=:p_office_no,p_office_dept=:p_office_dept,
              p_office_pic=:p_office_pic,planned_order_date=:planned_order_date,planned_construction_date=:planned_construction_date,
              degree_of_order=:degree_of_order,order_accuracy=:order_accuracy,case_div=:case_div,related_sq_no=:related_sq_no,daily_report_url=:daily_report_url,note=:note,
              prior_notice_div=:prior_notice_div,prior_notice_date=:prior_notice_date,entrant_comments=:entrant_comments,';

        //確認画面の時だけ確認者をセットする
        if ($title == 'check') {
          $sql.= 'confirmer_comments=:confirmer_comments,confirmer=:confirmer,confirm_date=:confirm_date,';
        }
        //承認画面の時だけ確認者をセットする
        else if ($title == 'approve') {
          $sql.= 'approver_comments=:approver_comments,approver=:approver,approve_date=:approve_date,';
        }
        $sql.= 'item_name=:item_name,status=:status, upd_date=:upd_date WHERE sq_no=:sq_no';
      }
      
      $stmt = $pdo->prepare($sql);
      $stmt->execute($datas);
      $pdo->commit();
    } catch (PDOException $e) {
      $success = false;
      if (strpos($e->getMessage(), 'SQLSTATE[42000]') !== false) {
        error_log("SQL Syntax Error or Access Violation: " . $e->getMessage(),3,'error_log.txt');
      } else {
        $pdo->rollback();
        throw($e);
        error_log("PDO Exception: " . $e->getMessage(),3,'error_log.txt');
      }
    }

    if ($success) {
      header('location:sales_request_input01.php?title='.$title);
    }
  }

?>