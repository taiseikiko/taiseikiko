<?php
  session_start();
  header('Program-id: estimate_entry.php');
  header('Content-type: text/html; charset=utf-8');
  require_once('function.php');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
  include("header1.php");
  $user_code = $_SESSION["login"];
  include("card_input1_data_set.php");
?>
<main>
  <div class="pagetitle">
    <h3>【　資材部　一覧　】</h3>
    <?php include("common_card_input1.php"); ?>
  </div>
</main><!-- End #main -->
</body>
</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    //更新ボタンが押された場合、
    $(document).on('click', '#updateBtn', function() {
      var selectedId = $(this).data('card_no');      
      $('#card_no').val(selectedId);
    });
  });
</script>
<?php
include("footer.html");
?>