<?php
  require_once('function.php');
  session_start();
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
  $today = date('Y/m/d');
  $user_code = $_SESSION["login"];
  $sq_no = $_POST['sq_no'] ?? '';
  $sq_line_no = $_POST['sq_line_no'] ?? '';
  $dept_id = $_POST['dept_id'] ?? '';
  $title = $_POST['title'] ?? '';
  $route_pattern = $_POST['route_pattern'] ?? '';
  $restoration_comments = $_POST['restoration_comments']; //差し戻しコメント
  $return_dept = $_POST['dept'];  //差し戻し先部署
  $send_back_to_person = $_POST['send_back_to_person'];  //差し戻し先担当者
  $success = true;

  if (isset($_POST['send_back'])) {   
    //try {
      $pdo->beginTransaction();
      //テーブルID : sq_detail_tr
      cu_sq_detail_tr();

      //テーブルID : sq_send_back_tr / テーブル名称：差し戻しトランザクションを更新する
      cu_send_back_tr();

      //テーブルID : sq_route_tr
      cu_sq_route_tr();

      $pdo->commit();
    // } catch (PDOException $e) {
    //   $success = false;
    //   if (strpos($e->getMessage(), 'SQLSTATE[42000]') !== false) {
    //     error_log("SQL Syntax Error or Access Violation: " . $e->getMessage(),3,'error_log.txt');
    //   } else {
    //     $pdo->rollback();
    //     throw($e);
    //     error_log("PDO Exception: " . $e->getMessage(),3,'error_log.txt');
    //   }
    // }

    //更新処理にエラーがなければメール送信する
    // if ($success) {
    //   include('sq_mail_send5.php');
    // }
  }

  //テーブルID : sq_detail_tr
  function cu_sq_detail_tr() {
    global $pdo;
    global $sq_no;
    global $sq_line_no;
    global $return_dept;
    global $today;

    $data = [
      'sq_no' => $sq_no,
      'sq_line_no' => $sq_line_no,
      'processing_status' => NULL,
      'upd_date' => $today
    ];

    //営業部の場合
    if ($return_dept == '00') {
      $data['processing_dept'] = NULL;
      $data['route_pattern'] = NULL; //ルート設定もやり直し
      $sql = 'UPDATE sq_detail_tr SET processing_dept=:processing_dept, processing_status=:processing_status, route_pattern=:route_pattern, upd_date=:upd_date
              WHERE sq_no=:sq_no AND sq_line_no=:sq_line_no';
    } else {
      //その他の部署の場合
      $data['processing_dept'] = $return_dept;
      $data['route_pattern'] = 1; //受付
      $sql = 'UPDATE sq_detail_tr SET processing_dept=:processing_dept, processing_status=:processing_status, upd_date=:upd_date
              WHERE sq_no=:sq_no AND sq_line_no=:sq_line_no';
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);
  }

  //テーブルID : sq_send_back_tr / テーブル名称：差し戻しトランザクションを更新する
  function cu_send_back_tr() {
    global $pdo;
    global $today;
    global $sq_no;
    global $sq_line_no;
    global $dept_id;
    global $user_code;
    global $send_back_to_person;
    global $restoration_comments;

    $datas = [
      'sq_no' => $sq_no,
      'sq_line_no' => $sq_line_no,
      'restoration_person' => $user_code,
      'restoration_date' => $today,
      'restoration_comments' => $restoration_comments,
      'send_back_to_person' => $send_back_to_person
    ];

    $sql = "SELECT * FROM sq_send_back_tr WHERE sq_no=:sq_no AND sq_line_no=:sq_line_no";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':sq_no', $sq_no);
    $stmt->bindParam(':sq_line_no', $sq_line_no);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
      $datas['add_date'] = $today;
      $sql1 = 'INSERT INTO sq_send_back_tr (sq_no, sq_line_no, restoration_person, restoration_date, restoration_comments, send_back_to_person, add_date) 
              VALUES (:sq_no, :sq_line_no, :restoration_person, :restoration_date, :restoration_comments, :send_back_to_person, :add_date)';
      
    } else {
      $datas['upd_date'] = $today;
      $sql1 = 'UPDATE sq_send_back_tr SET restoration_person=:restoration_person, restoration_date=:restoration_date, restoration_comments=:restoration_comments,
              send_back_to_person=:send_back_to_person, upd_date=:upd_date WHERE sq_no=:sq_no AND sq_line_no=:sq_line_no';
    }

    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute($datas);
  }

  /**
   * sq_route_tr and sq_route_mail_tr
   */
  function cu_sq_route_tr() {
    global $pdo;
    global $today;
    global $sq_no;
    global $sq_line_no;
    global $dept_id;
    global $route_pattern;
    global $return_dept;
    $datas = [];
    $filter_datas = [];
    $start = false;
    $end = false;
    $column_name_mail = '';

    //sq_route_trからデータを取得する
    $sql1 = "SELECT * FROM sq_route_tr WHERE route_id='$route_pattern' AND sq_no='$sq_no' AND sq_line_no='$sq_line_no'";
    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute();
    $sq_route_tr = $stmt1->fetch(PDO::FETCH_ASSOC);

    //営業部の場合
    if ($return_dept == '00') {
      //全ての部署IDを取得する
      if (isset($sq_route_tr) && !empty($sq_route_tr)) {
        foreach ($sq_route_tr as $item) {
          for ($i = 0; $i < 5; $i ++) {
            $x = $i+1;
            if ($sq_route_tr['route'.$x.'_dept'] !== NULL) {
              $filter_datas[$i]['route'] = $x;
              $filter_datas[$i]['dept'] = $sq_route_tr['route'.$x.'_dept'];
            }                     
            $x++;
          }
        }
        //sq_route_trテーブルから、該当のレコードを削除
        del_route_tr();

        //sq_route_mail_trテーブルから、該当のレコードを削除
        del_route_mail_tr();   
      }
      
      if (!empty($filter_datas)) {
        foreach ($filter_datas as $item) {
          $dept = $item['dept'];
          $flg = 'del';
          //技術部の場合、trテーブルから、該当のレコードを削除
          if ($dept == '02') {
            del_upd_tr_engineering($flg);
          }
          //資材部の場合、trテーブルから、該当のレコードを削除
          else if ($dept == '04') {
            del_upd_tr_procurement($flg);
          }
          //営業管理部の場合、trテーブルから、該当のレコードを削除
          else if ($dept == '05') {
            del_upd_tr_sales_management($flg);
          }
          //工事管理部の場合、trテーブルから、該当のレコードを削除
          else if ($dept == '06') {
            del_upd_tr_const_management($flg);
          }
        } 
      }           
    }
    //その他の部署の場合
    else {
      //差し戻し先から現在の部署までの部署IDを取得する
      if (isset($sq_route_tr) && !empty($sq_route_tr)) {
        foreach ($sq_route_tr as $item) {
          for ($i = 0; $i < 5; $i ++) {
            $x = $i+1;
            if ($sq_route_tr['route'.$x.'_dept'] == $return_dept) {
              $filter_datas[$i]['route'] = $x;
              $filter_datas[$i]['dept'] = $sq_route_tr['route'.$x.'_dept'];
              $start = true;
            } 
            if ($sq_route_tr['route'.$x.'_dept'] == $dept_id) {
              $filter_datas[$i]['route'] = $x;
              $filter_datas[$i]['dept'] = $sq_route_tr['route'.$x.'_dept'];
              $end = true;
              break;
            }
            if ($start && !($end)) {
              $filter_datas[$i]['route'] = $x;
              $filter_datas[$i]['dept'] = $sq_route_tr['route'.$x.'_dept'];
            }
            $x++;
          }          
        }
        //array keyをrearrangeする
        $filter_datas = array_values($filter_datas); 
      }

      $upd_datas = [
        'sq_no' => $sq_no,
        'sq_line_no' => $sq_line_no,
        'route_id' => $route_pattern,
        'upd_date' => $today,
      ];

      //現在の部署から差し戻し先の部署までのデータをresetする
      if (!empty($filter_datas)) {
        //その他の部署へ差戻しする場合
        foreach ($filter_datas as $key=>$item) {
          $dept = $item['dept'];
          $route = $item['route'];

          for ($i = 1; $i <= 5; $i++) {
            if ($i == $route) {
              //差し戻し先の部署の場合
              //入力日、確認日と承認日をNULLにする
              if ($key == 0) {
                $flg = 'upd';
                //sq_route_tr
                $column_name = "route".$i."_entrant_date=NULL,
                              route".$i."_confirm_date=NULL,
                              route".$i."_approval_date=NULL, ";
              }
              //例えばルート 02, 04, 06がありとして06から02まで差し戻すると
              //06と04の受付者、受付日、入力者、入力日、確認者、確認日、承認者、承認日をNULLにする
              else {
                $flg = 'del';
                //sq_route_tr
                $column_name = "route".$i."_receipt_date=NULL,
                                route".$i."_receipt_person=NULL,
                                route".$i."_entrant_date=NULL,
                                route".$i."_entrant=NULL,
                                route".$i."_confirm_date=NULL,
                                route".$i."_confirmer=NULL,
                                route".$i."_approval_date=NULL,
                                route".$i."_approver=NULL, ";

                //sq_route_mail_tr
                $column_name_mail = "route".$i."_receipt_person=NULL,
                                      route".$i."_receipt_ad=NULL,
                                      route".$i."_entrant_person=NULL,
                                      route".$i."_entrant_ad=NULL,
                                      route".$i."_confirmer_person=NULL,
                                      route".$i."_confirmer_ad=NULL,
                                      route".$i."_approver_person=NULL,
                                      route".$i."_approver_ad=NULL, ";
              }                
            }
          }

          //sq_route_trへ更新する
          $sql = "UPDATE sq_route_tr SET $column_name upd_date=:upd_date
                  WHERE route_id=:route_id AND sq_no=:sq_no AND sq_line_no=:sq_line_no";
          $stmt = $pdo->prepare($sql);
          $stmt->execute($upd_datas);

          //sq_route_mail_trへ更新する
          if ($column_name_mail !== '') {
            $sql = "UPDATE sq_route_mail_tr SET $column_name_mail upd_date=:upd_date
                    WHERE route_id=:route_id AND sq_no=:sq_no AND sq_line_no=:sq_line_no";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($upd_datas);
          }

          //技術部の場合、trテーブルから、該当のレコードを削除
          if ($dept == '02') {
            del_upd_tr_engineering($flg);
          }
          //資材部の場合、trテーブルから、該当のレコードを削除
          else if ($dept == '04') {
            del_upd_tr_procurement($flg);
          }
          //営業管理部の場合、trテーブルから、該当のレコードを削除
          else if ($dept == '05') {
            del_upd_tr_sales_management($flg);
          }
          //工事管理部の場合、trテーブルから、該当のレコードを削除
          else if ($dept == '06') {
            del_upd_tr_const_management($flg);
          }
        }
      }
    }    
  }

  function del_upd_tr_engineering($flg) {
    global $pdo;
    global $sq_no;
    global $sq_line_no;
    global $today;

    //更新の場合
    if ($flg == 'upd') {
      $sql = "UPDATE sq_detail_tr_engineering SET entrant_comments=:entrant_comments, 
              entrant_date=:entrant_date, confirmer_comments=:confirmer_comments, confirm_date=:confirm_date,
              approver_comments=:approver_comments, approve_date=:approve_date, upd_date=:upd_date
              WHERE sq_no=:sq_no AND sq_line_no=:sq_line_no";
    }
    //削除の場合
    else {
      $sql = "DELETE FROM sq_detail_tr_engineering WHERE sq_no=:sq_no AND sq_line_no=:sq_line_no";
    }
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':sq_no', $sq_no);
    $stmt->bindParam(':sq_line_no', $sq_line_no);
    if ($flg == 'upd') {
      $entrant_comments = $entrant_date = $confirmer_comments = $confirm_date = $approver_comments = $approve_date = NULL;
      $stmt->bindParam(':entrant_comments', $entrant_comments);
      $stmt->bindParam(':entrant_date', $entrant_date);
      $stmt->bindParam(':confirmer_comments', $confirmer_comments);
      $stmt->bindParam(':confirm_date', $confirm_date);
      $stmt->bindParam(':approver_comments', $approver_comments);
      $stmt->bindParam(':approve_date', $approve_date);
      $stmt->bindParam(':upd_date', $today);
    }
    $stmt->execute();
  }

  function del_upd_tr_procurement($flg) {
    global $pdo;
    global $sq_no;
    global $sq_line_no;

    //更新の場合
    if ($flg == 'upd') {
      $sql = "UPDATE sq_detail_tr_procurement SET entrant_comments=:entrant_comments, 
              entrant_date=:entrant_date, confirmer_comments=:confirmer_comments, confirm_date=:confirm_date,
              approver_comments=:approver_comments, approve_date=:approve_date, upd_date=:upd_date
              WHERE sq_no=:sq_no AND sq_line_no=:sq_line_no";
    }
    //削除の場合
    else {
      $sql = "DELETE FROM sq_detail_tr_procurement WHERE sq_no=:sq_no AND sq_line_no=:sq_line_no";
    }
    
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':sq_no', $sq_no);
    $stmt->bindParam(':sq_line_no', $sq_line_no);
    if ($flg == 'upd') {
      $entrant_comments = $entrant_date = $confirmer_comments = $confirm_date = $approver_comments = $approve_date = NULL;
      $stmt->bindParam(':entrant_comments', $entrant_comments);
      $stmt->bindParam(':entrant_date', $entrant_date);
      $stmt->bindParam(':confirmer_comments', $confirmer_comments);
      $stmt->bindParam(':confirm_date', $confirm_date);
      $stmt->bindParam(':approver_comments', $approver_comments);
      $stmt->bindParam(':approve_date', $approve_date);
      $stmt->bindParam(':upd_date', $today);
    }
    $stmt->execute();
  }

  function del_upd_tr_sales_management($flg) {
    global $pdo;
    global $sq_no;
    global $sq_line_no;

    //更新の場合
    if ($flg == 'upd') {
      $sql = "UPDATE sq_detail_tr_sales_management SET entrant_comments=:entrant_comments, 
              entrant_date=:entrant_date, confirmer_comments=:confirmer_comments, confirm_date=:confirm_date,
              approver_comments=:approver_comments, approve_date=:approve_date, upd_date=:upd_date
              WHERE sq_no=:sq_no AND sq_line_no=:sq_line_no";
    }
    //削除の場合
    else {
      $sql = "DELETE FROM sq_detail_tr_sales_management WHERE sq_no=:sq_no AND sq_line_no=:sq_line_no";
    }
    
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':sq_no', $sq_no);
    $stmt->bindParam(':sq_line_no', $sq_line_no);
    if ($flg == 'upd') {
      $entrant_comments = $entrant_date = $confirmer_comments = $confirm_date = $approver_comments = $approve_date = NULL;
      $stmt->bindParam(':entrant_comments', $entrant_comments);
      $stmt->bindParam(':entrant_date', $entrant_date);
      $stmt->bindParam(':confirmer_comments', $confirmer_comments);
      $stmt->bindParam(':confirm_date', $confirm_date);
      $stmt->bindParam(':approver_comments', $approver_comments);
      $stmt->bindParam(':approve_date', $approve_date);
      $stmt->bindParam(':upd_date', $today);
    }
    $stmt->execute();
  }

  function del_upd_tr_const_management($flg) {
    global $pdo;
    global $sq_no;
    global $sq_line_no;

    //更新の場合
    if ($flg == 'upd') {
      $sql = "UPDATE sq_detail_tr_const_management SET entrant_comments=:entrant_comments, 
              entrant_date=:entrant_date, confirmer_comments=:confirmer_comments, confirm_date=:confirm_date,
              approver_comments=:approver_comments, approve_date=:approve_date, upd_date=:upd_date
              WHERE sq_no=:sq_no AND sq_line_no=:sq_line_no";
    }
    //削除の場合
    else {
      $sql = "DELETE FROM sq_detail_tr_const_management WHERE sq_no=:sq_no AND sq_line_no=:sq_line_no";
    }
    
    
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':sq_no', $sq_no);
    $stmt->bindParam(':sq_line_no', $sq_line_no);
    if ($flg == 'upd') {
      $entrant_comments = $entrant_date = $confirmer_comments = $confirm_date = $approver_comments = $approve_date = NULL;
      $stmt->bindParam(':entrant_comments', $entrant_comments);
      $stmt->bindParam(':entrant_date', $entrant_date);
      $stmt->bindParam(':confirmer_comments', $confirmer_comments);
      $stmt->bindParam(':confirm_date', $confirm_date);
      $stmt->bindParam(':approver_comments', $approver_comments);
      $stmt->bindParam(':approve_date', $approve_date);
      $stmt->bindParam(':upd_date', $today);
    }
    $stmt->execute();
  }

  function del_route_tr() {
    global $pdo;
    global $sq_no;
    global $sq_line_no;
    global $route_pattern;
    
    $sql = "DELETE FROM sq_route_tr WHERE route_id=:route_id AND sq_no=:sq_no AND sq_line_no=:sq_line_no";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':route_id', $route_pattern);
    $stmt->bindParam(':sq_no', $sq_no);
    $stmt->bindParam(':sq_line_no', $sq_line_no);
    $stmt->execute();
  }

  function del_route_mail_tr() {
    global $pdo;
    global $sq_no;
    global $sq_line_no;
    global $route_pattern;
    
    $sql = "DELETE FROM sq_route_mail_tr WHERE route_id=:route_id AND sq_no=:sq_no AND sq_line_no=:sq_line_no";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':route_id', $route_pattern);
    $stmt->bindParam(':sq_no', $sq_no);
    $stmt->bindParam(':sq_line_no', $sq_line_no);
    $stmt->execute();
  }
?>