<?php
  session_start();
  header('Program-id: estimate_entry.php');
  header('Content-type: text/html; charset=utf-8');
  require_once('function.php');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
  include("header1.php");
  $dept_code = $_SESSION['department_code'];
  include("sq_detail_tr_engineering_input1_data_set.php");
  
  $title = isset($_GET['title']) ? $_GET['title'] : '';
  switch ($title) {
    case 'td_receipt':
      $title_in_j = '受付';
      break;
    case 'td_entrant':
      $title_in_j = '入力';
      break;
    case 'td_confirm':
      $title_in_j = '確認';
      break;
    case 'td_approve':
      $title_in_j = '承認';
      break;
    default:
      $title_in_j = '';
      break;
  }
  $sq_datas = get_sq_datas($title);
?>

<main>
  <div class="pagetitle">
    <h3>【　営業依頼書：技術部　<?=$title_in_j?>　】</h3>
    <?php include("common_sales_input1.php"); ?>
  </div>
</main><!-- End #main -->
</body>
</html>
<script src="assets/js/inquiry_ent.js"></script>
<script src="assets/js/inquiry_ent_check.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    //Handle detail button click
    $(".updateBtn").click(function(){
      var selectedId = $(this).data('sq_no');
      $('.sq_no').val(selectedId);
      $("#input1").attr("action", "sq_detail_tr_engineering_input2.php?title=<?= $title ?>");
    }); 
  });
  // localStorage.removeItem('detail_engineering');
</script>