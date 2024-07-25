<?php
// 初期処理
require_once('function.php');
session_start();
require('card_input2_data_set.php');
// DB接続
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
$today = date('Y/m/d');
$user_code = $_SESSION["login"];

$success = true;

//card_input2の登録ボタンを押下場合
if (isset($_POST['submit'])) {
  $card_no = $_POST['card_no'] ?? '';                               //依頼書No  
  $user_code = $_SESSION["login"] ?? '';                            //申請者
  $pf_code = $_POST['pf_code'] ?? '';                               //事業体コード
  $preferred_date = $_POST['preferred_date'] ?? '';                 //出図希望日
  $deadline = $_POST['deadline'] ?? '';                             //納期
  $sq_card_no = $_POST['card_no'] ?? '';                            //依頼書No
  $process = $_POST['process'] ?? '';                               //処理
  $approver = $_POST['approver'] ?? '';                             //承認者
  $approver_comments = $_POST['approver_comments'] ?? ''; //承認者
  $detail_datas = [];

  $header_datas = [
    'card_no' => $card_no,                //依頼書No    
    'client' => $user_code,               //登録者
    'p_office_no' => $pf_code,            //事業体コード
    'preferred_date' => $preferred_date,  //出図希望日
    'deadline' => $deadline,              //納期
    'procurement_approver' => $approver,  //資材部承認者
    'approver_comments' => $approver_comments,  //資材部承認者コメント
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
    $success = false;
    $pdo->rollback();
    error_log("PDOException: " . $e->getMessage(), 3, 'error_log.txt');
  }  

  //エラーがない場合
  if ($success == true) {
    include('card_mail_send1.php');
  } else {
    echo "<script>window.location.href='card_input2.php?err=exceErr'</script>";
  }
}

//card_input3の更新ボタンを押下場合
if (isset($_POST['update'])) {
  $sq_card_no = $_POST['sq_card_no'] ?? '';              //依頼書No  
  $sq_card_line_no = $_POST['sq_card_line_no'] ?? '';   //依頼書行No
  $entrant = $_POST['entrant'] ?? $_POST['hid_entrant']; //担当者
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

      case '詳細':
        //procurement_statusに2をセット
        $procurement_status = '2';
        $datas['confirmer'] = NULL;
        $datas['confirm_date'] = NULL;
        $datas['approver'] = NULL;
        $datas['approve_date'] = NULL;
        $column_names = 'confirmer=:confirmer, confirm_date=:confirm_date, approver=:approver, approve_date=:approve_date,';
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
    $pdo->rollback();
    error_log("PDO Exception: " . $e->getMessage(),3,'error_log.txt');
  }

  //登録更新処理にエラーがなければ、メール送信する
  if ($success) {
    include('card_mail_send2.php');
  } else {
    echo "<script>window.location.href='card_input2.php?err=exceErr'</script>";
  }
}

/**
 * card_detail_trに登録する
 */
function cu_card_header_tr($header_datas)
{
  global $pdo;
  global $today;

  //登録更新する用のデータ
  $datas = [
    'card_no' => $header_datas['card_no'],    
    'p_office_no' => $header_datas['p_office_no'],
    'preferred_date' => $header_datas['preferred_date'],
    'deadline' => $header_datas['deadline'],
    'procurement_approver' => $header_datas['procurement_approver'],
    'add_upd_date' => $today
  ];

  //新規登録の場合、
  if ($header_datas['process'] == 'new') {
    // 依頼書№ 自動採番
    /**-------------------------------------------------------------------------------- */
    $today = date('Y/m/d');
    $ym = substr(str_replace('/', '', $today), 0, 6);
    $code_id = 'card_request_no';

    $sql = "SELECT code_no FROM sq_code WHERE code_id = '$code_id' AND text1 = '$ym'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll();

    if (isset($data) && !empty($data)){
      $code_no = $data[0]['code_no'];
      $no = $code_no + 1;
      $sql = "UPDATE sq_code SET code_no=:code_no WHERE code_id=:code_id AND text1=:text1"; 
    } else {
      $no = '1';
      // テーブルsq_codeへ登録する
      $sql = "INSERT INTO sq_code(code_id, code_no, text1) VALUES (:code_id, :code_no, :text1)";
    }
    $data = [
      'code_id' => $code_id,
      'code_no' => $no,
        'text1' => $ym
    ];
    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);
    /**----------------------------------------------------------------------------------- */

    $datas['card_status'] = '1'; //状況
    $datas['client'] = $header_datas['client'];
    $datas['procurement_approver_date'] = NULL;  //資材部承認日
    $datas['procurement_approver_comments'] = NULL;  //資材部承認者コメント

    $sql = "INSERT INTO card_header_tr 
            (card_no, client, card_status, p_office_no, preferred_date, deadline, procurement_approver, procurement_approver_date, procurement_approver_comments, add_date) 
            VALUES 
            (:card_no, :client, :card_status, :p_office_no, :preferred_date, :deadline, :procurement_approver, :procurement_approver_date, 
            :procurement_approver_comments, :add_upd_date)";
  } else {
    //更新の場合
    if ($header_datas['process'] == 'update') {
      $column = "";
    } else {
      //承認の場合
      $column = "card_status=:card_status , procurement_approver_date=:procurement_approver_date, procurement_approver_comments=:procurement_approver_comments,";
      $datas['card_status'] = '2'; //状況
      $datas['procurement_approver_date'] = $today;  //資材部承認日
      $datas['procurement_approver_comments'] = $header_datas['approver_comments'];  //資材部承認者コメント
    }    

    // Update data into card_header_tr table
    $sql = "UPDATE card_header_tr SET $column p_office_no=:p_office_no, preferred_date=:preferred_date, deadline=:deadline,
             procurement_approver=:procurement_approver, 
             upd_date=:add_upd_date
             WHERE card_no=:card_no";
  }

  $stmt = $pdo->prepare($sql);
  $stmt->execute($datas);
}

/**
 * card_detail_trに登録する
 */
function cu_card_detail_tr($sq_card_no, $detail_datas)
{
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
