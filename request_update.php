<?php
// 初期処理
require_once('function.php');
session_start();
$dept_code = $_SESSION['department_code'];
$dept_id = getDeptId($dept_code);
$err_redirect = '';
$recipent_dept = '';  //受付部署
$request_class = '';  //依頼分類
// DB接続
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
$today = date('Y/m/d');
$user_code = $_SESSION["login"];

$success = true;

//request_input2の登録ボタンを押下場合
if (isset($_POST['submit'])) {
  $process = $_POST['process2'];  

  //分類
  //$_POST['request_class']に受付部署と依頼分類がセットされているから別に分けします。
  if(isset($_POST['request_class'])) {
    $values = explode(',', $_POST['request_class']);
    $recipent_dept = $values[0];  //受付部署
    $request_class = $values[1];  //依頼分類
  }
  //確認画面と承認画面にrequest_classをdisabledにしてるから値が貰えないのでhiddenに保存したのを取得してセットする
  else {
    $recipent_dept = $_POST['recipent_dept']?? ''; //受付部署
  }

  $request_datas = [
    'request_dept' => $dept_code ?? '',                       //依頼部署コード
    'request_person' => $_POST['user_code'] ?? '',            //依頼担当者
    'request_class' => $request_class,                        //依頼分類
    'recipent_dept' => $recipent_dept,                        //受付部署
    'request_comment' => $_POST['request_comment'] ?? ''     //コメント
  ];

  try {
    $pdo->beginTransaction();
    //新規の場合 || 差し戻しされた場合
    if ($process == 'new' || $process == 'update') {  
      $err_redirect = 'request_input2.php?err=exceErr&title=request';   
      if ($process == 'new') { 
        //依頼書（request_form_number）自動採番
        /**--------------------------------------------------------------------------------------------------**/
        //システム日付の年月を採取
        $ym = substr(str_replace('/', '', $today), 0, 6);
        $code_id = 'request_form_no';

        $sql = "SELECT code_no FROM sq_code WHERE code_id = '$code_id' AND text1 = '$ym'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();

        if (isset ($data) && !empty($data)) {
          $code_no = $data[0]['code_no'];
          $no = $code_no+1;
          $request_form_number = $ym.$no;
          //テーブルsq_codeへ更新する
          $sql = "UPDATE sq_code SET code_no=:code_no WHERE code_id=:code_id AND text1=:text1";
        } else {
          $no = '1';
          $request_form_number = $ym.$no;
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
        /**--------------------------------------------------------------------------------------------------**/
      } else {
        $request_form_number = $_POST['request_form_number'];
      }

      //request_form_trへ登録する
      /**--------------------------------------------------------------------------------------------------**/
      $request_datas['request_form_number'] = $request_form_number; //依頼書No.
      $request_datas['status'] = '1'; //ステータス
      
      $request_sql = "UPDATE request_form_tr SET status=:status, request_dept=:request_dept, request_person=:request_person, request_class=:request_class, 
                request_comment=:request_comment, recipent_dept=:recipent_dept WHERE request_form_number=:request_form_number";

      $request_stmt = $pdo->prepare($request_sql);
      $request_stmt->execute($request_datas);
      /**--------------------------------------------------------------------------------------------------**/
      $request_form_number = $request_datas['request_form_number'];      
    }
    //確認の場合
    else if ($process == 'confirm'){
      $request_form_number = $_POST['request_form_number'];

      $err_redirect = 'request_input3.php?err=exceErr&title=request&request_form_number=' . $request_form_number;
      
      $request_confirm_datas['request_form_number'] = $request_form_number;  //依頼書No.
      $request_confirm_datas['status'] = '2'; //ステータス
      $request_confirm_datas['comfirmor_comment'] = $_POST['comfirmor_comment'] ?? ''; //確認者コメント
      $request_confirm_datas['comfirmor'] = $_POST['user_code']; //確認者
      $request_confirm_datas['comfirm_date'] = $today; //確認日
      $request_confirm_datas['upd_date'] = $today;

      $request_sql = "UPDATE request_form_tr SET status=:status, comfirmor_comment=:comfirmor_comment, comfirmor=:comfirmor, comfirm_date=:comfirm_date,
                      upd_date=:upd_date WHERE request_form_number=:request_form_number";
      $request_stmt = $pdo->prepare($request_sql);
      $request_stmt->execute($request_confirm_datas);
      
    }
    //承認の場合
    else if ($process == 'approve'){
      $request_form_number = $_POST['request_form_number'];

      $err_redirect = 'request_input4.php?err=exceErr&title=request&request_form_number=' . $request_form_number;
      
      $request_approve_datas['request_form_number'] = $request_form_number;  //依頼書No.
      $request_approve_datas['status'] = '3'; //ステータス
      $request_approve_datas['approval_comment'] = $_POST['approval_comment'] ?? ''; //承認者コメント
      $request_approve_datas['approver'] = $_POST['user_code']; //承認者
      $request_approve_datas['approval_date'] = $today; //承認日
      $request_approve_datas['upd_date'] = $today;

      $request_sql = "UPDATE request_form_tr SET status=:status, approval_comment=:approval_comment, approver=:approver, approval_date=:approval_date,
                      upd_date=:upd_date WHERE request_form_number=:request_form_number";
      $request_stmt = $pdo->prepare($request_sql);
      $request_stmt->execute($request_approve_datas);      
    }
    $pdo->commit();
  } catch (PDOException $e) {
    $success = false;
    $pdo->rollback();
    error_log("PDO Exception: " . $e->getMessage(),3,'error_log.txt');
  }

  //エラーがない場合
  if ($success == true) {
    include('request_mail_send1.php');
  } else {
    echo "<script>window.location.href='$err_redirect'</script>";
  }
}
