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
include("request_input2_data_set.php");
// ヘッダーセット
include("header1.php");
?>

<main>
  <div class="pagetitle">
    <h3>依頼書　<?= $header ?></h3>
    <?php include('common_request2.php'); ?>    
  </div>
</main><!-- End #main -->
</body>

</html>
<script src="assets/js/customer_ent.js"></script>
<script src="assets/js/public_office_ent.js"></script>
<script src="assets/js/sales_request_check.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {

    let class_code = $('#classList').val();
    if (class_code != '') {
      fetchData(class_code, function(response) {}, function(error) {
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
        var btnName = $('#updBtn').text();
        //確認メッセージを書く
        var msg = btnName + "します？よろしいですか？";
        //何の処理科を書く
        if ($.trim(btnName) == '更新') {
          var process = "update";
        } else {
          var process = "approve";
        }
        $('#process').val(process);
        //確認Dialogを呼ぶ
        openConfirmModal(msg, process);
      }
    });

    

    //確認BOXにはいボタンを押下する場合
    $("#confirm_okBtn").click(function(event) {
      var process = $("#btnProcess").val();
      //戻る処理の場合
      if (process == "return") {
        $("#request_input2").attr("action", "request_input1.php");
      }
      //ヘッダ更新処理の場合
      else if (process == "update" || process == "approve") {
        //submitしたいボタン名をセットする
        $("#confirm_okBtn").attr("name", "submit");
        //request_update.phpへ移動する
        $("#request_input2").attr("action", "request_update.php");
      }
    });

    /**-------------------------------------------------------------------------------------------------------------- */

    //ALERT BOXに"はい"ボタンを押下する場合
    $("#ok_okBtn").click(function(event) {
      var process = $("#btnProcess").val();

      if (process == "errExec") {
        //request_input1へ移動
        $('#request_input2').attr('action', 'request_input1.php');
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
      var request_no = document.getElementById('request_no').value;

      var url = "request_send_back.php" + "?request_no=" + request_no;
      window.open(url, "popupWindow", "width=900,height=260,left=100,top=50");
    })

    


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