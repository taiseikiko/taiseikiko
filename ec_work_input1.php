<?php
session_start();
header('Program-id: estimate_entry.php');
header('Content-type: text/html; charset=utf-8');
require_once('function.php');
$_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
include("ec_article_input1_data_set.php");
include("header1.php");

?>

<main>
  <div class="pagetitle">
    <h3>工事実績</h3>
  </div>
  <div class="container">
    <form class="row g-3" method="POST" name="inq_ent" enctype="multipart/form-data" id="ec_work_form">
      <input type="hidden" id="key_number" class="key_number" name="key_number" value="">
      <table>
        <tr>
          <td>
            <div class="field-row">
              <label class="common_label" for="ec_division">工事区分</label>
              <select name="ec_division" id="ec_division" onchange="loadDivisionData()">
                <option value="">※選択して下さい。</option>
                <option value="01">IV実績</option>
                <option value="02">IVT実績</option>
                <option value="03">IVF実績</option>
                <option value="04">パイプリバース工事実績</option>
                <option value="05">ホースライニング工事実績</option>
                <option value="06">DC工事実績</option>
                <option value="07">HC工事実績</option>
                <option value="08">TC工事実績</option>
                <option value="09">STPφ700以上実績</option>
                <option value="10">弁体離脱工事実績</option>
              </select>
            </div>
          </td>
        </tr>
      </table>
      <div class="scrollable-table-container">
        <table class="tab1">
          <thead>
            <tr>
              <th>出先</th>
              <th>日付</th>
              <th>官庁</th>
              <th>得意先</th>
              <th>施工場所</th>
              <th>管種</th>
              <th>処理</th>
            </tr>
            <tr id="createBtnRow" style="display: none;">
              <td colspan="7" style="text-align:left"><button class="createBtn" id="create" name="process" value="new">新規作成</button></td>              
            </tr>
          </thead>
          <tbody id="divisionData">
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
      var ec_division = $("#ec_division").val();
      if (ec_division) {
        $("#ec_work_form").attr("action", action + "?ec_division=" + ec_division);
      } else {
        $("#ec_work_form").attr("action", action);
      }
    }

    //新規ボタンを押下した場合
    $("#create").click(function() {
      var ec_division = $("#ec_division").val();
      $("#ec_work_form").attr("action", "ec_work_input2.php?ec_division=" + ec_division);
    });

    //更新ボタンを押下した場合
    $(document).on('click', '.updateBtn', function() {
      var selectedId = $(this).data('key_number');
      $('#key_number').val(selectedId);
      setFormAction("ec_work_input2.php");
    });
  });

  function loadDivisionData() {
    var ec_division = $("#ec_division").val();
    $.ajax({
      url: 'ec_work_input1_data_set.php',
      type: 'POST',
      data: {
        ec_division: ec_division
      },
      success: function(data) {
        $("#divisionData").html(data);
        if (ec_division) {
          $("#createBtnRow").show();
        } else {
          $("#createBtnRow").hide();
        }
      }
    });
  }
</script>
<?php
// フッターセット
footer_set();
?>