<?php
  $mail_to1 = $mail_to2 = $mail_to3 = $mail_to4 = $mail_to5 = $entrant_comments = $confirmer_comments = $approver_comments = '';
  if(isset($_POST['process2'])) {
    $process2 = $_POST['process2'];
    $sq_no = $_POST['sq_no'];

    if ($process2 == 'detail') {
      $sq_line_no = $_GET['line'];
    }

    $sq_detail_sm_datas = get_sq_detail_tr_procurement($sq_no, $sq_line_no);
    if (isset($sq_detail_sm_datas) && !empty($sq_detail_sm_datas)) {
      $mail_to1 = $sq_detail_sm_datas['mail_to1'];
      $mail_to2 = $sq_detail_sm_datas['mail_to2'];
      $mail_to3 = $sq_detail_sm_datas['mail_to3'];
      $mail_to4 = $sq_detail_sm_datas['mail_to4'];
      $mail_to5 = $sq_detail_sm_datas['mail_to5'];
      $entrant_comments = $sq_detail_sm_datas['entrant_comments'];
      $confirmer_comments = $sq_detail_sm_datas['confirmer_comments'];
      $approver_comments = $sq_detail_sm_datas['approver_comments'];
    }

  }

  function get_sq_detail_tr_procurement($sq_no, $sq_line_no) {
    global $pdo;
    $sql = "SELECT * FROM sq_detail_tr_procurement WHERE sq_no='$sq_no' AND sq_line_no='$sq_line_no'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $datas = $stmt->fetch(PDO::FETCH_ASSOC);
    return $datas;
  }
?>