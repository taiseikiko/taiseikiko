<?php
  session_start();
  header('Program-id: estimate_entry.php');
  header('Content-type: text/html; charset=utf-8');
  require_once('function.php');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
  $title = $_GET['title'] ?? '';
  $dept_code = $_SESSION['department_code'];
  // ヘッダーセット
  include("header1.php");
  include("card_input2_data_set.php");
?>

<main>
  <div class="pagetitle">
    <h3>資材部：入力</h3>
    <?php include("common_card_input2.php"); ?>
  </div>
</main><!-- End #main -->
</body>
<script src="assets/js/public_office_ent.js"></script>
<script src="assets/js/inquiry_ent.js"></script>
<script src="assets/js/inquiry_ent_check.js"></script>
<script src="assets/js/sales_request_input3_check.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    let class_code = $('#classList').val();
    if (class_code != '') {
      fetchData(class_code);
    }

    $("#classList").change(function() {
      let class_code = $(this).val();
      $('#zkm_code').val('');
      fetchData(class_code);
    });

    $("#returnBtn").click(function() {
      openModal("前の画面に戻します。よろしいですか？", "return");
    });

    $("#updBtn").click(function() {
      openModal("営業依頼書 明細を作成．更新します。よろしいですか？", "update");
    });

    function openModal(msg, process) {
      event.preventDefault();
      $("#btnProcess").val(process);
      $("#confirm-message").text(msg);
      $("#confirm").modal({backdrop: false});
    }
  });

  function fetchData(class_code) {
    $('#zaikoumeiList option:not(:first-child)').remove();
    $.ajax({
      url: "card_input2_data_set.php",
      // url: "sales_request_input3_data_set.php",
      type: "POST",
      data: {
        function_name: "get_zaikoumei_datas",
        class_code: class_code
      },
      success: function(response){
        var zaikoumeiList = JSON.parse(response);
        var selected = $('#zkm_code').val();
        let i = 1;
        $.each(zaikoumeiList, function(index, item) {
          $('#zaikoumeiList').append($('<option>', {
            value: item.zkm_code,
            text: item.zkm_name,
            id: 'val'+i,
            class: item.code_no+','+item.text1
          }));
          if (item.zkm_code == selected) {
            $('#val'+i).prop('selected', true);
          }
          i++;
        });
      },
      error: function(xhr, status, error) {
        console.log('error');
      }
    });
  }
</script>