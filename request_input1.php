<?php
session_start();
header('Program-id: estimate_entry.php');
header('Content-type: text/html; charset=utf-8');
require_once('function.php');
$_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
$dept_code = $_SESSION['department_code'];
include("request_input1_data_set.php");
include("header1.php");
?>
<main>
  <h3>【　依頼書　一覧 　】</h3>
   <!-- PHP to display result if available -->
  <?php include('common_request1.php'); ?>
</main>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="assets/js/customer_ent.js"></script> 
<script src="assets/js/public_office_ent.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript">
  
$(document).ready(function(){
  $('#regBtn').click(function() {
    $('#request_input_form').attr('action', 'request_input2.php');
  });

  $(document).on('click', '.updateBtn', function() {
    var selectedId = $(this).data('request_no');
    $('.request_no').val(selectedId);
    $('#request_input_form').attr('action', 'request_input3.php');
  });

});

/**-------------------------------------------------------------------------------------------------------------- */

function handleWindowClose() {
  $.ajax({
    type: 'POST',
    url: 'sales_request_input1_data_set.php',
    data: { 
      return: false,
      cust_name: cust_name, 
      pf_name: pf_name },
    success: function(response) {
      $('#sq_data_table').html(response);
    }
    
  });
}

/**-------------------------------------------------------------------------------------------------------------- */
</script>
<?php
include("footer.html");
?>
