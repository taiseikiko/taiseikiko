<?php
// 初期処理
require_once('function.php');
session_start();
// DB接続
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
$today = date('Y/m/d');
$user_code = $_SESSION["login"];

$success = true;

//dw_input2の登録ボタンを押下場合
if (isset($_POST['submit'])) {
  $process = $_POST['process'];  

  $dw_datas = [
    'dw_div1' => $_POST['dw_div1'] ?? '',         //区分
    'open_div' => $_POST['open_div'] ?? '',       //公開区分
    'class_code' => $_POST['class_code'] ?? '',   //分類コード
    'zkm_code' => $_POST['zaikoumei'] ?? '',       //材工名コード
    'size' => $_POST['size'] ?? '',               //サイズ
    'joint' => $_POST['joint'] ?? '',             //接合形状
    'pipe' => $_POST['pipe'] ?? '',               //管種
    'specification' => $_POST['specification'] ?? '', //仕様
    'dw_div2' => $_POST['dw_div2']?? '',               //種類
    'date' => $today                              //種類
  ];

  try {
    $pdo->beginTransaction();
    //新規の場合
    if ($process == 'new') {
      //図面№（sq_no）自動採番
      /**--------------------------------------------------------------------------------------------------**/
      //システム日付の年月を採取
      $ym = substr(str_replace('/', '', $today), 0, 6);
      $code_id = 'dw_request_no';

      $sql = "SELECT code_no FROM sq_code WHERE code_id = '$code_id' AND text1 = '$ym'";
      $stmt = $pdo->prepare($sql);
      $stmt->execute();
      $data = $stmt->fetchAll();

      if (isset ($data) && !empty($data)) {
        $code_no = $data[0]['code_no'];
        $no = $code_no+1;
        $dw_no = $ym.$no;
        //テーブルsq_codeへ更新する
        $sql = "UPDATE sq_code SET code_no=:code_no WHERE code_id=:code_id AND text1=:text1";
      } else {
        $no = '1';
        $dw_no = $ym.$no;
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

      //dw_management_trへ登録する
      /**--------------------------------------------------------------------------------------------------**/
      $dw_datas['dw_no'] = $dw_no;
      $dw_datas['client'] = $user_code;
      $dw_datas['dw_status'] = '1';
      $dw_sql = "INSERT INTO dw_management_tr (dw_no, client, dw_status, dw_div1, open_div, class_code, zkm_code, size, joint, pipe, specification,
                dw_div2, add_date)
                VALUES (:dw_no, :client, :dw_status, :dw_div1, :open_div, :class_code, :zkm_code, :size, :joint, :pipe, :specification,
                :dw_div2, :date)";

      $dw_stmt = $pdo->prepare($dw_sql);
      $dw_stmt->execute($dw_datas);
      /**--------------------------------------------------------------------------------------------------**/


    }
    //更新または承認の場合
    else {
      $dw_datas['dw_no'] = $_POST['dw_no'];
      $dw_datas['dw_status'] = $process == 'update' ? '1' : '2';
      $dw_sql = "UPDATE dw_management_tr SET dw_status=:dw_status, dw_div1=:dw_div1, open_div=:open_div, class_code=:class_code, 
                zkm_code=:zkm_code, size=:size, joint=:joint, pipe=:pipe, specification=:specification, dw_div2=:dw_div2,
                upd_date=:date 
                WHERE dw_no=:dw_no";
      $dw_stmt = $pdo->prepare($dw_sql);
      $dw_stmt->execute($dw_datas);
    }

    $dw_no = $dw_datas['dw_no'];
    $pdo->commit();
  } catch (PDOException $e) {
    $success = false;
    $pdo->rollback();
    error_log("PDO Exception: " . $e->getMessage(),3,'error_log.txt');
  }

  //エラーがない場合
  if ($success == true) {
    include('dw_mail_send1.php');
  } else {
    echo "<script>window.location.href='dw_input2.php?err=exceErr'</script>";
  }
}
