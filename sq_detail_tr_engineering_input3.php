<?php
  session_start();
  header('Program-id: estimate_entry.php');
  header('Content-type: text/html; charset=utf-8');
  require_once('function.php');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
  include("header1.php");
  $dept_code = $_SESSION['department_code'];
  include("sales_request_input3_data_set.php");
  include("sq_detail_tr_engineering_input4_data_set.php");

  $title = isset($_GET['title']) ? $_GET['title'] : '';
?>

<main>
<div class="pagetitle">
  <h3>営業依頼書：依頼入力（詳細）</h3>
  <?php include("common_sales_input3.php"); ?>
</div>
</main><!-- End #main -->
</body>
</html>
<script type="text/javascript"></script>
<script src="assets/js/inquiry_ent.js"></script>
<script src="assets/js/inquiry_ent_check.js"></script>
<script src="assets/js/sales_request_input3_check.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    let class_code = $('#classList').val();
    if (class_code != '') {
      fetchData(class_code, function(response) {
      }, function(error) {
        console.log(error);
      })
    }

    $("#classList").change(function() {
      let class_code = $(this).val();
      $('#zkm_code').val('');
      fetchData(class_code, function(response) {
        console.log(response);
      }, function(error) {
        console.log(error);
      })
    });

    //材工名のプルダウンがCHANGEされた場合、区分TEXTBOXにデータセット
    $("#zaikoumeiList").change(function() {
      set_c_div();
    })

    $("#returnBtn").click(function() {
      //確認メッセージを書く
      var msg = "前の画面に戻します。よろしいですか？";
      //何の処理科を書く
      var process = "return";
      //確認Dialogを呼ぶ
      openModal(msg, process);
    })

    //確認BOXにはいボタンを押下する場合
    $("#confirm_okBtn").click(function(event) {
      var process = $("#btnProcess").val();
      //戻る処理の場合
      if (process == "return") {
        $("#input3").attr("action", "sq_detail_tr_engineering_input2.php?sq_no="+<?= $sq_no ?>+"&process=detail&title=<?= $title ?>");
      }
    });
  });

  function fetchData(class_code) {
    $('#zaikoumeiList option:not(:first-child)').remove();
    $.ajax({
      url: "sales_request_input3_data_set.php",
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
        set_c_div();
      },
      error: function(xhr, status, error) {
        console.log('error')
      }
    });
  }

  function set_c_div() {
    let c_div = $('#zaikoumeiList option:selected').attr('class');

    if (c_div !== '') {
      //カンマを区切り文字として使用して文字列を配列に分割します
      var c_div_array = c_div.split(',');
      let code = c_div_array[0];
      let text = c_div_array[1];
      $('#c_div').val(text);
      $('#c_div_code').val(code);
    } else {
      $('#c_div').val('');
      $('#c_div_code').val('');
    }
  }

  document.addEventListener("DOMContentLoaded", function() {
    var quantity = document.getElementById('quantity');
    var right_quantity = document.getElementById('right_quantity');
    var left_quantity = document.getElementById('left_quantity');

    //数字チェック
    quantity.addEventListener("input", function(event) {
      var value = event.target.value;
      event.target.value = value.replace(/\D/g, '');
    });

    right_quantity.addEventListener("input", function(event) {
      var value = event.target.value;
      event.target.value = value.replace(/\D/g, '');
    });

    left_quantity.addEventListener("input", function(event) {
      var value = event.target.value;
      event.target.value = value.replace(/\D/g, '');
    });
  });

  //担当者設定子画面呼び出し
  function person_in_charge(event) {
    var sq_no = document.getElementById('sq_no').value;
    var sq_line_no = document.getElementById('sq_line_no').value;
    var record_div = document.getElementById('record_div').value;
    var route_pattern = document.getElementById('route_pattern').value;
    var dept_id = document.getElementById('dept_id').value;
    var title = document.getElementById('title').value;

    event.preventDefault();
    var url = "sq_person_in_charge_input.php" + "?sq_no=" + sq_no + 
    "&sq_line_no=" + sq_line_no + 
    "&record_div=" + record_div +
    "&route_pattern=" + route_pattern +
    "&dept_id=" + dept_id +
    "&title=" + title;
    window.open(url,"popupWindow","width=500,height=200,left=100,top=50");
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

  //Disabled Input 
  var inputs = document.getElementsByTagName('input');
  for (var i = 0; i < inputs.length; i++) {
    if (inputs[i].type.toLowerCase() !== 'hidden' && inputs[i].type.toLowerCase() !== 'submit' && inputs[i].type.toLowerCase() !== 'file' &&
      inputs[i].className !== 'mail') {
      inputs[i].disabled = true;
    }
    if (inputs[i].type.toLowerCase() == 'text') {
      inputs[i].style.backgroundColor = '#e6e6e6';
    }
  }

  //Disabled textarea 
  var textareas = document.getElementsByTagName('textarea');
  for (var j = 0; j < textareas.length; j++) {
    if (textareas[j].id !== 'entrant_comments' && textareas[j].id !== 'confirmer_comments' && textareas[j].id !== 'approver_comments') {
      textareas[j].disabled = true;
      textareas[j].style.backgroundColor = '#e6e6e6';
    }
  }

  //Disabled select 
  var selects = document.getElementsByTagName('select');
  for (var k = 0; k < selects.length; k++) {
    if (selects[k].id !== 'otherProcess') {
      selects[k].disabled = true;
    }
  }

  //Disabled button 
  var buttons = document.getElementsByTagName('button');
  const excludeButtons = ['returnBtn', 'setEmp', 'update', 'okBtn', 'cancelBtn'];
  for (var k = 0; k < buttons.length; k++) {
    if (!excludeButtons.includes(buttons[k].className)) {
      buttons[k].disabled = true;
    }
  }

</script>
<style>
  .dropdown-menu {
    width: 180px;
  }

  #checkbox_label {
    width: 70px;
    text-align: start;
  }
  
  input.readonlyText {
    background-color: #e6e6e6;
  }

  .flex-container {
    display: flex;
  }

  .flex-container > div {
    margin: 20px 5px;
  }

  #preassure2 {
      margin-left: 0px; 
      margin-right: 50px; 
  }



</style>
<?php

// フッターセット
footer_set();
?>
