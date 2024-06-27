<?php
  // 初期処理
  require_once('function.php');
  session_start();
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
  $today = date('Y/m/d');
  $user_code = $_SESSION["login"];
  $dept_cd = $_SESSION['department_code'];
  //ログインユーザーの部署ID
  $department_code = getDeptId($dept_cd);
  $success = true;

  //更新ボタンを押下場合
  if (isset($_POST['update'])) {
    $sq_card_no = $_POST['sq_card_no']?? '';              //依頼書No  
    $sq_card_line_no = $_POST['sq_card_line_no'] ?? '';   //依頼書行No
    $entrant = $_POST['entrant'] ?? '';                   //担当者
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
  if (isset($_POST['new'])){
    $card_no = $_POST['card_no'] ?? '';
    $user_name = $_SESSION['user_name'] ?? '';
    $user_code = $_SESSION["login"] ?? '';
    $office_name = $_SESSION['office_name'] ?? '';
    $office_position_name = $_SESSION['office_position_name'] ?? '';
    $pf_code = $_POST['pf_code'] ?? '';
    $preferred_date = $_POST['preferred_date'] ?? '';
    $deadline = $_POST['deadline'] ?? '';
    
    try {
      $pdo->beginTransaction();

      // Insert data into card_header_tr table
      $sql = "INSERT INTO card_header_tr 
              (card_no, client, p_office_no, preferred_date, deadline, add_date) 
              VALUES 
              (:card_no, :client, :p_office_no, :preferred_date, :deadline, :add_date)";
               
      $stmt = $pdo->prepare($sql);
      $stmt->execute([
          'card_no' => $card_no,
          'client' => $user_code,
          'p_office_no' => $pf_code,
          'preferred_date' => $preferred_date,
          'deadline' => $deadline,
          'add_date' => $today
      ]);

      $pdo->commit();
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
  }
?>    