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
<script type="text/javascript">
  $(document).ready(function(){
    //localStorageにフォームデータを保存する
    // saveFormData();
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
      $("#input2").attr("action", "sq_detail_tr_procurement_input1.php?title=<?= $title ?>");
    })
  });

  function getById(id) {
    return document.getElementById(id);
  }

  // const myForm = getById("input2");
  // //localStorageにフォームデータを保存する
  // function saveFormData() {
  //   const formData = new FormData(myForm);
  //   const jsonData = JSON.stringify(Object.fromEntries(formData));
  //   localStorage.setItem('detail_procurement', jsonData);
  // }

  // //localStorageからフォームデータをセットする
  // const formData = JSON.parse(localStorage.getItem('detail_procurement'));
  // if (formData) {
  //   Object.keys(formData).forEach(key => {
  //     if (key !== 'uploaded_file') {
  //       myForm.elements[key].value = formData[key];
  //     }
  //   })
  // }

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

    //Disabled select 
    var selects = document.getElementsByTagName('select');
    for (var k = 0; k < selects.length; k++) {
        selects[k].disabled = true;
    }

    //Disabled button 
    var buttons = document.getElementsByTagName('button');
    for (var k = 0; k < buttons.length; k++) {
      if (buttons[k].className !== 'returnBtn' && buttons[k].className !== 'updateBtn') {
        buttons[k].disabled = true;
      }
    }
  }
  
</script>