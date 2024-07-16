<?php
session_start();
header('Program-id: estimate_entry.php');
header('Content-type: text/html; charset=utf-8');
require_once('function.php');
$_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
$dept_code = $_SESSION['department_code'];
$title = $_GET['title'];
include("receipt_input1_data_set.php");
include("header1.php");
?>
<main>
  <h3>依頼書　受付一覧 　】</h3>
   <!-- PHP to display result if available -->
  <?php include('common_request1.php'); ?>
</main>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript">
  
$(document).ready(function(){
  //新規登録の場合
  $('#regBtn').click(function() {
    $('#req_rec_form1').attr('action', 'request_input2.php?title=request');
  });

  $(document).on('click', '.updateBtn', function() {
    var selectedId = $(this).data('request_form_number');
    $('.request_form_number').val(selectedId);
    var status_no = $(this).data('status_no');

    // status3の場合、受付画面へ移動する
    if (status_no == '3') {
      $('#req_rec_form1').attr('action', 'receipt_input2.php?title=receipt');
    }
    //status4の場合、確認画面へ移動する
    else if (status_no == '4') {
      $('#req_rec_form1').attr('action', 'receipt_input3.php?title=receipt');
    }
    //status5の場合、承認画面へ移動する
    else if (status_no == '5') {
      $('#req_rec_form1').attr('action', 'receipt_input4.php?title=receipt');
    }
  });

});

/**-------------------------------------------------------------------------------------------------------------- */
</script>
<?php
include("footer.html");
?>
