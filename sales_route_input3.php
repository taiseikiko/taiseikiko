<?php
  session_start();
  header('Program-id: estimate_entry.php');
  header('Content-type: text/html; charset=utf-8');
  require_once('function.php');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
  include("header1.php");
  include("sales_request_input3_data_set.php");
  include("sales_route_input3_data_set.php");

  $route_pattern_list = [];
  $route_pattern_list = get_route_pattern_list();  

  $title = $_GET['title'] ?? '';
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
<script src="assets/js/sales_route_check.js"></script>
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

    /**-------------------------------------------------------------------------------------------------------------- */

    $("#classList").change(function() {
      let class_code = $(this).val();
      $('#zkm_code').val('');
      fetchData(class_code, function(response) {
        console.log(response);
      }, function(error) {
        console.log(error);
      })
    });

    /**-------------------------------------------------------------------------------------------------------------- */

    //材工名のプルダウンがCHANGEされた場合、区分TEXTBOXにデータセット
    $("#zaikoumeiList").change(function() {
      set_c_div();
    })

    /**-------------------------------------------------------------------------------------------------------------- */

    $("#returnBtn").click(function() {
      //確認メッセージを書く
      var msg = "前の画面に戻します。よろしいですか？";
      //何の処理科を書く
      var process = "return";
      //確認Dialogを呼ぶ
      openConfirmModal(msg, process);      
    })

    /**-------------------------------------------------------------------------------------------------------------- */

    $("#updBtn").click(function() {      
      event.preventDefault();
      var errMessage = checkValidation();

      //エラーがある場合
      if (errMessage !== '') {
        //何の処理かを書く
        var process = "validate";
        //OKDialogを呼ぶ
        openOkModal(errMessage, process);
      } else {
        //確認メッセージを書く
        var msg = "ルート設定します？よろしいですか？";
        //何の処理科を書く
        var process = "update";
        //確認Dialogを呼ぶ
        openConfirmModal(msg, process);
      }      
    })

    /**-------------------------------------------------------------------------------------------------------------- */

    //確認BOXにはいボタンを押下する場合
    $("#confirm_okBtn").click(function(event) {
      var process = $("#btnProcess").val();
      //戻る処理の場合
      if (process == "return") {
        $("#input3").attr("action", "sales_route_input2.php?sq_no="+<?= $sq_no ?>+"&process=detail&title=<?= $title ?>");
      }
      //ヘッダ更新処理の場合
      else if (process == "update") {
        //submitしたいボタン名をセットする
        $("#confirm_okBtn").attr("name", "submit");
        //sales_request_update.phpへ移動する
        $("#input3").attr("action", "sq_route_setting.php");
      }
    });

    /**-------------------------------------------------------------------------------------------------------------- */

    //ALERT BOXに"はい"ボタンを押下する場合
    $("#ok_okBtn").click(function(event) {
      var process = $("#btnProcess").val();

      if (process == "errExec") {
        //sq_class_input1へ移動
        $('#input3').attr('action', 'sales_route_input1.php?title=<?= $title ?>');
      } else {
        //画面上変更なし
        $('#ok_okBtn').attr('data-dismiss', 'modal');
      }
    });

    /**-------------------------------------------------------------------------------------------------------------- */

    //エラーがあるかどうか確認する
    var err = '<?= $err ?>';
    //エラーがある場合
    if (err !== '') {
      //OKメッセージを書く
      var msg = "処理にエラーがありました。係員にお知らせください。";
      //OKDialogを呼ぶ
      openOkModal(msg, 'errExec');
    }
  });

  /**---------------------------------------------Javascript----------------------------------------------------------------- */
  function openConfirmModal(msg, process) {
    event.preventDefault();
    //何の処理かをセットする
    $("#btnProcess").val(process);
    //確認メッセージをセットする
    $("#confirm-message").text(msg);
    //確認Dialogを呼ぶ
    $("#confirm").modal({backdrop: false});
  }

  /**-------------------------------------------------------------------------------------------------------------- */

  function openOkModal(msg, process) {
    //何の処理かをセットする
    $("#btnProcess").val(process);
    //確認メッセージをセットする
    $("#ok-message").text(msg);
    //確認Dialogを呼ぶ
    $("#ok").modal({backdrop: false});
  }

  /**-------------------------------------------------------------------------------------------------------------- */

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

  /**-------------------------------------------------------------------------------------------------------------- */

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

  /**-------------------------------------------------------------------------------------------------------------- */

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

  /**-------------------------------------------------------------------------------------------------------------- */

  //Disabled Input 
  var inputs = document.getElementsByTagName('input');
  const excludeInputs = ['hidden', 'submit', 'file']
  for (var i = 0; i < inputs.length; i++) {
    if (!excludeInputs.includes(inputs[i].type.toLowerCase()) && inputs[i].className !== 'mail') {
      inputs[i].disabled = true;
    }
    if (inputs[i].type.toLowerCase() == 'text') {
      inputs[i].style.backgroundColor = '#e6e6e6';
    }
  }

  //Disabled textarea 
  var textareas = document.getElementsByTagName('textarea');
  const excludeTextareas = ['entrant_comments', 'confirmer_comments', 'approver_comments'];
  for (var j = 0; j < textareas.length; j++) {
    if (!excludeTextareas.includes(textareas[j].id)) {
      textareas[j].disabled = true;
      textareas[j].style.backgroundColor = '#e6e6e6';
    }
  }

  //Disabled select 
  var selects = document.getElementsByTagName('select');
  const excludeSelects = ['otherProcess', 'route_no'];
  for (var k = 0; k < selects.length; k++) {
    if (!excludeSelects.includes(selects[k].id)) {
      selects[k].disabled = true;
    }
  }

  //Disabled button 
  var buttons = document.getElementsByTagName('button');
  const excludeButtons = ['returnBtn', 'setEmp', 'update', 'setRoute', 'okBtn', 'cancelBtn'];
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
