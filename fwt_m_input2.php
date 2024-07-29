<?php
  session_start();
  header('Program-id: estimate_entry.php');
  header('Content-type: text/html; charset=utf-8');
  require_once('function.php');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
  // $title = $_GET['title'] ?? '';
  $dept_code = $_SESSION['department_code'];
  $user_code = $_SESSION["login"]?? '';
  $user_name = $_SESSION['user_name']?? '';      //登録者
  $office_name = $_SESSION['office_name']?? '';  //部署
  $office_position_name = $_SESSION['office_position_name']?? '';  //役職
  include("fwt_m_input2_data_set.php");
  // ヘッダーセット
  header_set1();
?>

<main>
  <div class="pagetitle">
    <table style="width: 100%;">
      <tr>
        <td style="width: 100%;">
          <div class="field-row" style="display: flex; justify-content: space-between; align-items: center;">
            <label for="title"><h3>見学、立会、研修仮予約、本予約入力</h3></label>
            <label class="common_label" for="add_date" style="margin-left: auto;">　　申請日</label>
            <input type="date" style="margin-left: 1rem;" min="2023-01-01" max="2028-12-31" name="add_date" value="" class="input-res"/>
          </div>
        </td>
      </tr>
    </table>
  </div>
  <?php include("common_fwt_m_input2.php"); ?>
</main><!-- End #main -->
</body>
</html>
<script src="assets/js/customer_ent.js"></script>
<script src="assets/js/public_office_ent.js"></script>
<script src="assets/js/sales_request_check.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
  });
</script>
<?php
// フッターセット
// footer_set();
?>
