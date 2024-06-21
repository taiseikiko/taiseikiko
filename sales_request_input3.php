<?php
  session_start();
  header('Program-id: estimate_entry.php');
  header('Content-type: text/html; charset=utf-8');
  require_once('function.php');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
  include("header1.php");

  include("sales_request_input3_data_set.php");
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

    $("#updBtn").click(function() {
      //確認メッセージを書く
      var msg = "営業依頼書 明細を作成．更新します。よろしいですか？";
      //何の処理科を書く
      var process = "update";
      //確認Dialogを呼ぶ
      openModal(msg, process);
    })

    //はいボタンを押下する場合
    $("#okBtn").click(function(event) {
      var process = $("#btnProcess").val();
      //戻る処理の場合
      if (process == "return") {
        $("#input3").attr("action", "sales_request_input2.php?sq_no="+<?= $sq_no ?>+"&process=update&title=<?= $title ?>");
      }
      //明細更新処理の場合
      else if (process == "update") {
        checkValidation(event);
        
        //submitしたいボタン名をセットする
        $("#okBtn").attr("name", "submit");
        //sales_request_update.phpへ移動する
        $("#input3").attr("action", "sales_request_update2.php?title=<?= $title ?>");
      }
    });

    function openModal(msg, process) {
      event.preventDefault();
      //何の処理かをセットする
      $("#btnProcess").val(process);
      //確認メッセージをセットする
      $("#confirm-message").text(msg);
      //確認Dialogを呼ぶ
      $("#confirm").modal({backdrop: false});
    }
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

</script>

<?php

// フッターセット
footer_set();
?>
