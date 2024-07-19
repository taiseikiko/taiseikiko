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
    <h3>物件情報入力</h3>
  </div>
  <div class="container">
    <form class="row g-3" method="POST" name="inq_ent" enctype="multipart/form-data" id="ec_form">
      <input type="hidden" id="key_number" class="key_number" name="key_number" value="">
      <table>
        <tr>
          <td>
            <div class="field-row">
              <label class="common_label" for="property_code">物件種別</label>
              <select name="property_code" id="property_code" onchange="loadPropertyData()">
                <option value="">※選択して下さい。</option>
                <option value="1">IV/IVT物件情報</option>
                <option value="2">穿孔工事物件情報</option>
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
              <th>工事件名</th>
              <th>管種</th>
              <th>サイズ</th>
              <th>発注者</th>
              <th>処理</th>
            </tr>
            <tr id="createBtnRow" style="display: none;">
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
      setFormAction("ec_article_input2.php");
    });

    //更新ボタンを押下した場合
    $(document).on('click', '.updateBtn', function() {
      var selectedId = $(this).data('key_number');
      $('#key_number').val(selectedId);
      setFormAction("ec_article_input2.php");
    });
  });

  function loadPropertyData() {
    var propertyCode = $("#property_code").val();
    $.ajax({
      url: 'ec_article_input1_data_set.php',
      type: 'POST',
      data: {
        property_code: propertyCode
      },
      success: function(data) {
        $("#propertyData").html(data);
        if (propertyCode) {
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