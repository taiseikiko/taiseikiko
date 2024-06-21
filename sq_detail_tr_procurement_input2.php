<?php
  session_start();
  header('Program-id: estimate_entry.php');
  header('Content-type: text/html; charset=utf-8');
  require_once('function.php');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する

  // ヘッダーセット
  include("header1.php");
  $title = isset($_GET['title']) ? $_GET['title'] : '';
  $dept_code = $_SESSION['department_code'];
  include("sales_request_input2_data_set.php");
?>

<main>
  <div class="pagetitle">
    <h3>営業依頼書：依頼　情報ヘッダー</h3>
    <?php include("common_sales_input2.php"); ?>
  </div>
</main><!-- End #main -->
</body>
</html>

<script src="assets/js/customer_ent.js"></script>
<script src="assets/js/public_office_ent.js"></script>
<script src="assets/js/sales_request_input_check.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    disableInput();
    //一覧画面から更新 or コピーボタンを押下する場合
    $(".updateBtn").click(function(){
      //クリックされた行の営業依頼書行№	を取得する
      var selected = $(this).data('sq_line_no');
      
      //sales_request_input3.phpへ移動する
      $("#input2").attr("action", "sq_detail_tr_procurement_input3.php?line="+selected+"&title=<?= $title ?>");
    });

    //戻るボタンを押下する場合
    $(".returnBtn").click(function(){
      //確認メッセージを書く
      var msg = "前の画面に戻します。よろしいですか？";
      //何の処理科を書く
      var process = "return";
      //確認Dialogを呼ぶ
      openModal(msg, process);
    })

    //確認BOXにはいボタンを押下する場合
    $("#okBtn").click(function(event) {
      var process = $("#btnProcess").val();
      //戻る処理の場合
      if (process == "return") {
        $("#input2").attr("action", "sq_detail_tr_procurement_input1.php?title=<?= $title ?>");
      }
    });
  });

  function getById(id) {
    return document.getElementById(id);
  }

  function disableInput() {
    //Disabled Input 
    var inputs = document.getElementsByTagName('input');
    for (var i = 0; i < inputs.length; i++) {
      if (inputs[i].type.toLowerCase() !== 'hidden') {
        inputs[i].disabled = true;
      }
      if (inputs[i].type.toLowerCase() == 'text') {
        inputs[i].style.backgroundColor = '#e6e6e6';
      }
    }

    //Disabled textarea 
    var textareas = document.getElementsByTagName('textarea');
    for (var j = 0; j < textareas.length; j++) {
        textareas[j].disabled = true;
        textareas[j].style.backgroundColor = '#e6e6e6';
    }

    var selects = document.getElementsByTagName('select');
    const excludeSelect = ['otherProcess'];
    for (var k = 0; k < selects.length; k++) {
      if (!excludeSelect.includes(selects[k].id)) {
        selects[k].disabled = true;
      }
    }

    //Disabled select 
    var selects = document.getElementsByTagName('select');
    for (var k = 0; k < selects.length; k++) {
        
    }

    //Disabled button 
    var buttons = document.getElementsByTagName('button');
    const excludeButtons = ['returnBtn', 'updateBtn', 'okBtn', 'cancelBtn'];
    for (var k = 0; k < buttons.length; k++) {
      if (!excludeButtons.includes(buttons[k].className)) {
        buttons[k].disabled = true;
      }
    }
  }

  function openModal(msg, process) {
    event.preventDefault();
    //何の処理かをセットする
    $("#btnProcess").val(process);
    //確認メッセージをセットする
    $("#confirm-message").text(msg);
    //確認Dialogを呼ぶ
    $("#confirm").modal({backdrop: false});
  }
  
</script>