<?php
session_start();
header('Program-id: estimate_entry.php');
header('Content-type: text/html; charset=utf-8');
require_once('function.php');
$_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
$title = $_GET['title'] ?? '';
$user_code = $_SESSION['login'];
$user_name = $_SESSION['user_name'];      //登録者
$office_name = $_SESSION['office_name'];  //部署
$office_position_name = $_SESSION['office_position_name'];  //役職
include("receipt_data_set.php");
// ヘッダーセット
include("header1.php");
?>

<main>
  <div class="pagetitle">
    <h3>依頼書　<?=$header?>処理</h3>
    <?php include('common_request4.php'); ?>
  </div>
</main><!-- End #main -->
</body>

</html>
<script src="assets/js/request_form_check.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {

    disableInput();

    /**-------------------------------------------------------------------------------------------------------------- */

    //戻るボタンを押下する場合
    $("#returnBtn").click(function(event) {
      //確認メッセージを書く
      var msg = "前の画面に戻します。よろしいですか？";
      //何の処理科を書く
      var process = "return";
      //確認Dialogを呼ぶ
      openConfirmModal(msg, process);
    });

    /**-------------------------------------------------------------------------------------------------------------- */

    //登録更新ボタンを押下場合
    $('#updBtn').click(function() {
      event.preventDefault();
      var errMessage = '';
      // var errMessage = checkValidationInput2();

      //エラーがある場合
      if (errMessage !== '') {
        //何の処理かを書く
        var process = "validate";
        //OKDialogを呼ぶ
        openOkModal(errMessage, process);
      } else {
        var btnName = $('#updBtn').text().substr(3, 2);
        //確認メッセージを書く
        var msg = btnName + "します？よろしいですか？";
        //何の処理科を書く
        var process = "update";
        //確認Dialogを呼ぶ
        openConfirmModal(msg, process);
      }
    });

    /**-------------------------------------------------------------------------------------------------------------- */

    //確認BOXにはいボタンを押下する場合
    $("#confirm_okBtn").click(function(event) {
      var process = $("#btnProcess").val();
      //戻る処理の場合
      if (process == "return") {
        $("#req_rec_form4").attr("action", "receipt_input1.php?title=receipt");
      }
      //ヘッダ更新処理の場合
      else if (process == "update") {
        //submitしたいボタン名をセットする
        $("#confirm_okBtn").attr("name", "submit");
        //request_update.phpへ移動する
        $("#req_rec_form4").attr("action", "receipt_update.php");
      }
      //アプロード１処理の場合
      else if (process == "upload") {
        //submitしたいボタン名をセットする
        $("#confirm_okBtn").attr("name", "upload");
        //sales_request_update.phpへ移動する
        uploadFile("receipt_attach_upload1.php");
      }
    });

    /**-------------------------------------------------------------------------------------------------------------- */

    //ALERT BOXに"はい"ボタンを押下する場合
    $("#ok_okBtn").click(function(event) {
      var process = $("#btnProcess").val();

      if (process == "errExec") {
        //request_input1へ移動
        $('#req_rec_form4').attr('action', 'request_input1.php?title=request');
      } else {
        //画面上変更なし
        $('#ok_okBtn').attr('data-dismiss', 'modal');
      }
    });

    /*----------------------------------------------------------------------------------------------- */

    //エラーがあるかどうか確認する
    var err = '<?= $err ?>';
    //エラーがある場合
    if (err !== '') {
      //OKメッセージを書く
      var msg = "処理にエラーがありました。係員にお知らせください。";
      //OKDialogを呼ぶ
      openOkModal(msg, 'errExec');
    }

    /**-------------------------------------------------------------------------------------------------------------- */

    //差し戻しボタンを押下する場合
    $('#returnProcessBtn').click(function() {
      event.preventDefault();
      var request_form_number = document.getElementById('request_form_number').value;
      var from = 'receipt';
      var url = "request_send_back.php" + "?request_form_number=" + request_form_number + "&from=" + from;
      window.open(url, "popupWindow", "width=900,height=260,left=100,top=50");
    })

    /*----------------------------------------------------------------------------------------------- */

    //アプロードボタンを押下する場合
    $('#upload').click(function(event) {
      event.preventDefault();
      var errMessage = checkValidationFile();
      
      //エラーがある場合
      if (errMessage !== '') {
        //何の処理かを書く
        var process = "validate";
        //OKDialogを呼ぶ
        openOkModal(errMessage, process);
      } else {
        //何の処理かを書く
        var process = "upload";
        //エラーメッセージを書く
        var msg = "アプロードします。よろしいですか？";
        //確認Dialogを呼ぶ
        openConfirmModal(msg, process);
      }
    });

    /*----------------------------------------------------------------------------------------------- */

    //localStorageからフォームデータをセットする
    const formData = JSON.parse(localStorage.getItem('req_rec_form2'));
    if (formData) {
      var myForm = document.getElementById('req_rec_form2');
      Object.keys(formData).forEach(key => {
        const exceptId = ['uploaded_file'];
        if (!exceptId.includes(key)) {
          myForm.elements[key].value = formData[key];
        }
      })

      //フォームにセット後、クリアする
      localStorage.removeItem('req_rec_form2');
    }

    /*----------------------------------------------------------------------------------------------- */



  });


  /**-------------------------------------------------------------------------------------------------------------- */

  function openConfirmModal(msg, process) {
    event.preventDefault();
    //何の処理かをセットする
    $("#btnProcess").val(process);
    //確認メッセージをセットする
    $("#confirm-message").text(msg);
    //確認Dialogを呼ぶ
    $("#confirm").modal({
      backdrop: false
    });
  }

  /**-------------------------------------------------------------------------------------------------------------- */

  function openOkModal(msg, process) {
    //何の処理かをセットする
    $("#btnProcess").val(process);
    //確認メッセージをセットする
    $("#ok-message").text(msg);
    //確認Dialogを呼ぶ
    $("#ok").modal({
      backdrop: false
    });
  }


  /*----------------------------------------------------------------------------------------------- */

  function disableInput() {
    //Disabled Input 
    var inputs = document.getElementsByTagName('input');
    const excludeInput = ['file', 'hidden', 'submit'];
    for (var i = 0; i < inputs.length; i++) {
      if (!excludeInput.includes(inputs[i].type.toLowerCase())) {
        inputs[i].disabled = true;
      }
      if (inputs[i].type.toLowerCase() == 'text') {
        inputs[i].style.backgroundColor = '#e6e6e6';
      }
    }

    //Disabled textarea 
    var textareas = document.getElementsByTagName('textarea');
    const excludeTextareas = ['recipi_comment'];
    for (var j = 0; j < textareas.length; j++) {
      if (!excludeTextareas.includes(textareas[j].id)) {
        textareas[j].disabled = true;
        textareas[j].style.backgroundColor = '#e6e6e6';
      }
    }

    //Disabled select 
    var selects = document.getElementsByTagName('select');
    for (var k = 0; k < selects.length; k++) {
      selects[k].disabled = true;
    }

    //Disabled button 
    var buttons = document.getElementsByTagName('button');
    const excludeButtons = ['returnBtn', 'okBtn', 'cancelBtn', 'returnProcessBtn', 'updRegBtn'];
    for (var k = 0; k < buttons.length; k++) {
      if (!excludeButtons.includes(buttons[k].className)) {
        buttons[k].disabled = true;
      }
    }
  }

  /**-------------------------------------------------------------------------------------------------------------- */

  function uploadFile(url) {
    event.preventDefault();
    var request_form_number = document.getElementById('request_form_number').value;
    var uploaded_file = document.getElementById('uploaded_file').files[0];
    var upload_comments = document.getElementById('request_comment').value;

    var formData = new FormData();
    formData.append('request_form_number', request_form_number);
    formData.append('uploaded_file', uploaded_file);
    formData.append('upload_comments', upload_comments);

    $.ajax({
      type: "POST",
      url: url,
      data: formData,
      processData: false, // Important: prevent jQuery from processing the data
      contentType: false, // Important: ensure jQuery does not add a content-type header
      success: function(response) {
        //reload page
        location.reload();
      },
      error: function(xhr, status, error) {
      }
    })

  }

  /**-------------------------------------------------------------------------------------------------------------- */

</script>
<style>
  .dropdown-menu {
    width: 180px;
  }

  .clearfix:after {
    clear: both;
    content: "";
    display: block;
    height: 0;
  }

  .updateBtn {
    margin: 2px 1px;
  }

  .copyBtn {
    margin: 2px 1px;
  }

  .createBtn {
    width: 120px;
    margin-top: 2px;
    margin-bottom: 2px;
    margin-right: 8px;
  }

  .flex-container {
    display: flex;
  }

  .flex-container>div {
    margin: 20px 5px;
  }

  .business_daily_report {
    width: 630px;
  }

  .updRegBtn {
    background: #80dfff;
  }
</style>
<?php

// フッターセット
footer_set();
?>