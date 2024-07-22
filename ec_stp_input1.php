<?php
session_start();
header('Program-id: estimate_entry.php');
header('Content-type: text/html; charset=utf-8');
require_once('function.php');
$_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
include("ec_stp_input1_data_set.php");
include("header1.php");

?>

<main>
  <div class="pagetitle">
    <h3>STP施工資格認定者</h3>
  </div>
  <div class="container">
    <form class="row g-3" method="POST" name="inq_ent" enctype="multipart/form-data" id="ec_form">
      <input type="hidden" id="key_number" class="key_number" name="key_number" value="">
      <div class="scrollable-table-container">
        <table class="tab1">
          <thead>
            <tr>
              <th>出先</th>
              <th>更新年月日</th>
              <th>会社名</th>
              <th>氏名</th>
              <th>受講年</th>
              <th>備考</th>
              <th>処理</th>
            </tr>
            <tr id="createBtnRow">
              <td colspan="7" style="text-align:left"><button class="createBtn" id="create" name="process" value="new">新規作成</button></td>
            </tr>
          </thead>
          <tbody id="propertyData">
            <!-- Dataがここに表示されます -->
          </tbody>
        </table>
      </div>
    </form><!-- Vertical Form -->
  </div>
</main><!-- End #main -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    function setFormAction(action) {
      var propertyCode = $("#property_code").val();
      if (propertyCode) {
        $("#ec_form").attr("action", action + "?property_code=" + propertyCode);
      } else {
        $("#ec_form").attr("action", action);
      }
    }

    //新規ボタンを押下した場合
    $("#create").click(function() {
      setFormAction("ec_stp_input2.php");
    });

    //更新ボタンを押下した場合
    $(document).on('click', '.updateBtn', function() {
      var selectedId = $(this).data('key_number');
      $('#key_number').val(selectedId);
      setFormAction("ec_stp_input2.php");
    });
  });
</script>
<?php
// フッターセット
footer_set();
?>