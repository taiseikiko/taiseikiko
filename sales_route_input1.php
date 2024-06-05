<?php
  session_start();
  header('Program-id: estimate_entry.php');
  header('Content-type: text/html; charset=utf-8');
  require_once('function.php');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
  include("sales_request_input01_data_set.php");
  include("header1.php");
  $dept_code = $_SESSION['department_code'];
  $title = isset($_GET['title']) ? $_GET['title'] : '';
  $sq_datas = get_sq_datas($title);
 
?>
<main>
  <div class="pagetitle">
    <h3>【　営業依頼書：ルート設定　】</h3>
    <?php include("common_sales_input1.php"); ?>
  </div>
</main>
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
      $("#input1").attr("action", "sales_route_input2.php?title=set_route");
    }); 
  });
  localStorage.removeItem('sales_route');
</script>
