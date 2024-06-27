<?php
  // 初期処理
  require_once('function.php');
  session_start();
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
  $today = date('Y/m/d');
  $user_code = $_SESSION["login"];
  
  $success = true;

  //card_input2の登録ボタンを押下場合
  if (isset($_POST['submit'])) {
    $card_no = $_POST['card_no'] ?? '';
    $user_name = $_SESSION['user_name'] ?? '';
    $user_code = $_SESSION["login"] ?? '';
    $office_name = $_SESSION['office_name'] ?? '';
    $office_position_name = $_SESSION['office_position_name'] ?? '';
    $pf_code = $_POST['pf_code'] ?? '';
    $preferred_date = $_POST['preferred_date'] ?? '';
    $deadline = $_POST['deadline'] ?? '';
    $sq_card_no = $_POST['card_no']; //依頼書No
    $process = $_POST['process'];       //処理
    $detail_datas = [];

    $header_datas = [
      'card_no' => $card_no,
      'client' => $user_code,
      'p_office_no' => $pf_code,
      'preferred_date' => $preferred_date,
      'deadline' => $deadline,
      'process' => $process
    ];
    
    for ($i = 1; $i <= 4; $i++) {
      //資材部Noが入力された場合
      if (isset($_POST['procurement_no' . $i]) && $_POST['procurement_no' . $i] !== '') {
        //資材部No、資材部Noステータス、製造メーカー、材工名、管種、サイズA、サイズB、仕様書No、特記事項、承認者
        $column_names = ['procurement_no', 'maker', 'class_code', 'zaikoumei', 'pipe', 'sizeA', 'sizeB', 'specification_no', 'special_note'];
        $detail_datas[$i]['sq_card_line_no'] = $i;  //	依頼書行No

        foreach ($column_names as $column_name) {
          $detail_datas[$i][$column_name] = $_POST[$column_name . $i] ?? '';  
        }
      }
    }

    try {
      $pdo->beginTransaction();
      //card_header_trに登録する
      cu_card_header_tr($header_datas);
      //card_detail_trに登録する
      cu_card_detail_tr($sq_card_no, $detail_datas);

      $pdo->commit();
    } catch (PDOException $e) {
      $success = 'false';
      $pdo->rollback();
      error_log("PDOException: " . $e->getMessage(), 3, 'error_log.txt');
      throw($e);
    }

    // エラーがある場合
    if ($success !== true) {
      echo "<script>window.location.href='card_input2.php?err=yes'</script>";
    } else {
      echo "<script>window.location.href='card_input1.php'</script>";
    }
  }

  //card_input3の更新ボタンを押下場合
  if (isset($_POST['update'])) {
    $sq_card_no = $_POST['sq_card_no']?? '';              //依頼書No  
    $sq_card_line_no = $_POST['sq_card_line_no'] ?? '';   //依頼書行No
    $entrant = $_POST['entrant'] ?? $_POST['hid_entrant'];//担当者
    $entrant_set_date = $_POST['entrant_set_date'] ?? ''; //　担当指定日
    $entrant_set_comments = $_POST['entrant_set_comments'] ?? ''; //コメント
    $entrant_date = $_POST['entrant_date'] ?? '';                 //登録日
    $entrant_comments = $_POST['entrant_comments'] ?? '';         //入力者コメント
    $confirmer_comments = $_POST['confirmer_comments'] ?? '';     //確認者コメント
    $approver_comments = $_POST['approver_comments'] ?? '';       //承認者コメント
    $page = $_POST['page'] ?? ''; //コメント

    try {
      $pdo->beginTransaction();
      
      $datas = [      
        'sq_card_no' => $sq_card_no,
        'sq_card_line_no' => $sq_card_line_no,
        'upd_date' => $today,
      ];

      switch ($page) {
        //受付画面の場合
        case '受付':
          //procurement_statusに1をセット
          $procurement_status = '1';
          $column_names = 'entrant=:entrant, entrant_set_date=:entrant_set_date, entrant_set_comments=:entrant_set_comments,';
          $datas['entrant'] = $entrant;
          $datas['entrant_set_date'] = $entrant_set_date;
          $datas['entrant_set_comments'] = $entrant_set_comments;        
          break;

        case '入力':
          //procurement_statusに2をセット
          $procurement_status = '2';
          $column_names = 'entrant_date=:entrant_date, entrant_comments=:entrant_comments,';
          $datas['entrant_date'] = $entrant_date;
          $datas['entrant_comments'] = $entrant_comments;
          break;

        case '確認':
          //procurement_statusに3をセット
          $procurement_status = '3';
          $column_names = 'confirmer=:confirmer, confirm_date=:confirm_date, confirmer_comments=:confirmer_comments,';
          $datas['confirmer'] = $user_code;
          $datas['confirm_date'] = $today;
          $datas['confirmer_comments'] = $confirmer_comments;
          break;

        case '承認':
          //procurement_statusに4をセット
          $procurement_status = '4';
          $column_names = 'approver=:approver, approve_date=:approve_date, approver_comments=:approver_comments,';
          $datas['approver'] = $user_code;
          $datas['approve_date'] = $today;
          $datas['approver_comments'] = $approver_comments;
          break;
      }
      $datas['procurement_status'] = $procurement_status; //資材部Noステータス
      
      //card_detail_trテーブルに更新する
      $sql = "UPDATE card_detail_tr SET procurement_status=:procurement_status, $column_names
              upd_date=:upd_date
              WHERE sq_card_no=:sq_card_no AND sq_card_line_no=:sq_card_line_no";
      $stmt = $pdo->prepare($sql);
      $stmt->execute($datas);
      $pdo->commit();
    } catch (PDOException $e) {
      $success = false;
    }
  }

  /**
   * card_detail_trに登録する
   */
  function cu_card_header_tr($header_datas) {
    global $pdo;
    global $today;

    if ($header_datas['process'] == 'new') {
      $sql = "INSERT INTO card_header_tr 
            (card_no, client, p_office_no, preferred_date, deadline, add_date) 
            VALUES 
            (:card_no, :client, :p_office_no, :preferred_date, :deadline, :add_upd_date)";
    } else {
      // Update data into card_header_tr table
      $sql = "UPDATE card_header_tr SET client=:client, p_office_no=:p_office_no, preferred_date=:preferred_date, deadline=:deadline,
             upd_date=:add_upd_date
             WHERE card_no=:card_no";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      'card_no' => $header_datas['card_no'],
      'client' => $header_datas['client'],
      'p_office_no' => $header_datas['p_office_no'],
      'preferred_date' => $header_datas['preferred_date'],
      'deadline' => $header_datas['deadline'],
      'add_upd_date' => $today
    ]);
  }

  /**
   * card_detail_trに登録する
   */
  function cu_card_detail_tr($sq_card_no, $detail_datas) {
    global $pdo;
    global $today;

    //新規の場合
    $insert_sql = "INSERT INTO card_detail_tr (sq_card_no, sq_card_line_no, procurement_no, maker, class_code, zkm_code, pipe, sizeA, sizeB, specification_no,
                  special_note, add_date)
                  VALUES (:sq_card_no, :sq_card_line_no, :procurement_no, :maker, :class_code, :zkm_code, :pipe, :sizeA, :sizeB, :specification_no,
                  :special_note, :upd_add_date)";

    //更新の場合
    $update_sql = "UPDATE card_detail_tr SET procurement_no=:procurement_no, maker=:maker, class_code=:class_code, zkm_code=:zkm_code, 
                  pipe=:pipe, sizeA=:sizeA, sizeB=:sizeB, specification_no=:specification_no, special_note=:special_note, upd_date=:upd_add_date
                  WHERE sq_card_no=:sq_card_no AND sq_card_line_no=:sq_card_line_no";
 

    foreach ($detail_datas as $item) {
      //テーブルにレコードがあるかどうか確認する
      $sq_card_line_no = $item['sq_card_line_no'];
      $sql1 = "SELECT * FROM card_detail_tr WHERE sq_card_no=:sq_card_no AND sq_card_line_no=:sq_card_line_no";
      $stmt1 = $pdo->prepare($sql1);
      $stmt1->bindParam(':sq_card_no', $sq_card_no);
      $stmt1->bindParam(':sq_card_line_no', $sq_card_line_no);
      $stmt1->execute();
      $row = $stmt1->fetch(PDO::FETCH_ASSOC);

      //データがある場合、更新する
      if ($row) {
        $stmt = $pdo->prepare($update_sql);
      } else {
        $stmt = $pdo->prepare($insert_sql);
      }
      
      $stmt->bindParam(':sq_card_no', $sq_card_no);
      $stmt->bindParam(':sq_card_line_no', $sq_card_line_no);
      $stmt->bindParam(':procurement_no', $item['procurement_no']);
      $stmt->bindParam(':maker', $item['maker']);
      $stmt->bindParam(':class_code', $item['class_code']);
      $stmt->bindParam(':zkm_code', $item['zaikoumei']);
      $stmt->bindParam(':pipe', $item['pipe']);
      $stmt->bindParam(':sizeA', $item['sizeA']);
      $stmt->bindParam(':sizeB', $item['sizeB']);
      $stmt->bindParam(':specification_no', $item['specification_no']);
      $stmt->bindParam(':special_note', $item['special_note']);
      $stmt->bindParam(':upd_add_date', $today);
      $stmt->execute();
    }
  }
?>
