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
include("request_item_input2_data_set.php");
// ヘッダーセット
include("header1.php");
?>

<main>
  <div class="pagetitle">
    <h3>依頼書　<?= $header ?></h3>
    <div class="container">
      <form class="row g-3" method="POST" name="inq_ent" enctype="multipart/form-data" id="request_item_form2">
        <input type="hidden" name="process" value="<?= $process ?>">
        <?php include('dialog.php'); ?>
        <table style="width:auto;">
          <tr style="height:20px; margin-top:20px"></tr>
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="user_name">登録者</label>
                <input type="text" style="margin-left: 1rem;" class="readonlyText input-res" name="user_name" value="<?= $user_name ?>" readonly>
                <input type="hidden" name="user_code" value="<?= $_SESSION["login"] ?>">

                <label class="common_label" for="office_name">　　部署</label>
                <input type="text" style="width:370px;" name="office_name" class="readonlyText input-res" value="<?= $office_name ?>" readonly>

                <label class="common_label" for="office_position_name">　　役職</label>
                <input type="text" style="width:100px;" class="readonlyText input-res" name="office_position_name" value="<?= $office_position_name ?>" readonly>
              </div>
            </td>
          </tr>
          <tr style="margin-top:10px"></tr>
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="request_item_id">案件No </label>
                <input type="text" style="margin-left: 1rem;" name="request_item_id" class="readonlyText input-res" value="<?= $request_item_id ?>" readonly>
                <label class="common_label" for="request_item_name">案件名</label>
                <input type="text" style="width:370px;" name="request_item_name" class="input-res" value="<?= $request_item_name ?>">
              </div>
            </td>
          </tr>
        </table>  
        <table>
          <tr>
            <td>
              <div class="flex-container">
                <div>
                  <button id="returnBtn" class="returnBtn">前の画面に戻る </button>
                </div>
                <div>
                  <button id="updBtn" class="<?= $btn_class ?>" name="submit"><?= $btn_name ?></button>
                </div>
              </div>
            </td>            
          </tr>
        </table>
      </form><!-- Vertical Form -->
    </div>
  </div>
</main><!-- End #main -->
</body>

</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
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
        var btnName = $('#updBtn').text().substr(2, 2);
        //確認メッセージを書く
        var msg = btnName + "します？よろしいですか？";
        //何の処理科を書く        
        var process = "new";
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
        $("#request_item_form2").attr("action", "request_item_input1.php");
      }
      //ヘッダ更新処理の場合
      else if (process == "new") {
        //submitしたいボタン名をセットする
        $("#confirm_okBtn").attr("name", "submit");
        //request_update.phpへ移動する
        $("#request_item_form2").attr("action", "request_item_update.php");
      }
    });

    /**-------------------------------------------------------------------------------------------------------------- */

    //ALERT BOXに"はい"ボタンを押下する場合
    $("#ok_okBtn").click(function(event) {
      var process = $("#btnProcess").val();

      if (process == "errExec") {
        //request_item_input1へ移動
        $('#request_item_form2').attr('action', 'request_item_input1.php');
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

  });

  /**------------------------------------------------FUNCTION-------------------------------------------------------------- */

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