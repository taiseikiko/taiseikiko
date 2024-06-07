<?php
  $mail_to1 = $mail_to2 = $mail_to3 = $mail_to4 = $mail_to5 = $entrant_comments = $confirmer_comments = $approver_comments = '';
  if(isset($_POST['process2'])) {
    $process2 = $_POST['process2'];
    $sq_no = $_POST['sq_no'];

    if ($process2 == 'detail') {
      $sq_line_no = $_GET['line'];
    }

    $sq_detail_sm_datas = get_sq_detail_tr_sales_management($sq_no, $sq_line_no);
    if (isset($sq_detail_sm_datas) && !empty($sq_detail_sm_datas)) {

      $fields = ['mail_to1', 'mail_to2', 'mail_to3', 'mail_to4', 'mail_to5', 'entrant_comments', 'confirmer_comments', 'approver_comments'];

      foreach ($fields as $field) {
        ${$field} = $sq_detail_sm_datas[$field] ?? '';
      }
    }

  }

  function get_sq_detail_tr_sales_management($sq_no, $sq_line_no) {
    global $pdo;
    $sql = "SELECT * FROM sq_detail_tr_sales_management WHERE sq_no='$sq_no' AND sq_line_no='$sq_line_no'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $datas = $stmt->fetch(PDO::FETCH_ASSOC);
    return $datas;
  }
?>