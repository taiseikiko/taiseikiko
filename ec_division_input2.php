<?php
session_start();
header('Program-id: estimate_entry.php');
header('Content-type: text/html; charset=utf-8');
require_once('function.php');
include('ec_division_input2_data_set.php');
$_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する                             */  
include("header1.php");
?>

<main>
  <div class="pagetitle">
    <h3>既存工事実績マスター保守</h3>
    <div class="container">
      <form class="row g-3" method="POST" id="ec_division_form" enctype="multipart/form-data">
        <?php include("dialog.php") ?>
        <input type="hidden" name="process" id="process" value="<?= $process ?>">
        <table style="width:auto;">
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="spec_name">既存工事仕様</label>
                <input type="text" style="margin-left:1rem" id="spec_name" name="spec_name" value="<?= $spec_name ?>" class="readonlyText" readonly>
                <input type="hidden" id="code_key" name="code_key" value="<?= $code_key ?>" class="readonlyText" readonly>
                <input type="hidden" id="code_no" name="code_no" value="<?= $code_no ?>" class="readonlyText" readonly>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="code_name">コード名</label><i class="fa fa-asterisk" style="font-size:10px;color:red;margin-left:-8px;"></i>
                <input type="text" style="margin-left:1rem" id="code_name" name="code_name" value="<?= $code_name ?>" maxlength="50">
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <div class="flex-container">
                <div>
                  <button id="returnBtn" name="return">戻る </button>
                </div>
                <div>
                  <button class="<?= $btn_class?>" id="upd_regBtn" name="submit" value="update"><?= $btn_name ?></button>
                </div>
              </div>
            </td>
          </tr>
        </table>
      </form><!-- Vertical Form -->
    </div>
</main><!-- End #main -->

<script src="assets/js/ec_check.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    // Handle return button click
    $('#returnBtn').click(function(event) {
      //確認メッセージを書く
      var msg = "前の画面に戻します。よろしいですか？";
      //何の処理科を書く
      var process = "return";
      //確認Dialogを呼ぶ
      openConfirmModal(msg, process);
    });

    /**-------------------------------------------------------------------------------------------------------------- */
    //更新ボタンを押下場合
    $('#upd_regBtn').click(function(event) {
      event.preventDefault();
      var errMessage = checkValidation();

      //エラーがある場合
      if (errMessage !== '') {
        //何の処理かを書く
        var process = "validate";
        //OKDialogを呼ぶ
        openOkModal(errMessage, process);
      } else {
        var btnName = '<?= $btn_name ?>';
        //確認メッセージを書く
        var msg = btnName + "します。よろしいですか？";
        //何の処理科を書く
        var process = "update";
        //確認Dialogを呼ぶ
        openConfirmModal(msg, process);
      }
    });

    /**-------------------------------------------------------------------------------------------------------------- */

    //確認BOXに"はい"ボタンを押下する場合
    $("#confirm_okBtn").click(function(event) {
      var process = $("#btnProcess").val();
      //戻る処理の場合
      if (process == "return") {
        $('#ec_division_form').attr('action', 'ec_division_input1.php');
      }
      //戻る処理の場合
      else if (process == "update") {
        //submitしたいボタン名をセットする
        $("#confirm_okBtn").attr("name", "submit");
        $('#ec_division_form').attr('action', 'ec_division_update.php');
      }
    });

    /**-------------------------------------------------------------------------------------------------------------- */

    //ALERT BOXに"はい"ボタンを押下する場合
    $("#ok_okBtn").click(function(event) {
      var process = $("#btnProcess").val();

      if (process == "errExec") {
        //ec_division_input1へ移動
        $('#ec_division_form').attr('action', 'ec_division_input1.php');
      } else {
        //画面上変更なし
        $('#ok_okBtn').attr('data-dismiss', 'modal');
      }
    });
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

  /**---------------------------------------------Javascript----------------------------------------------------------------- */
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
  .container {
    font-family: 'Lato', sans-serif;
  }

  .updateBtn {
    margin: 2px 1px;
    background-color: red;
  }

  .copyBtn {
    margin: 2px 1px;
    background-color: blue;
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

  @media only screen and (max-width:800px) {

    .pagetitle,
    .container,
    .field-row {
      width: 80%;
      padding: 0;
    }

    .createBtn {
      width: 40px;
    }
  }

  @media only screen and (max-width:500px) {

    .pagetitle,
    .container,
    .field-row {
      width: 100%;
    }

    .createBtn {
      width: 40px;
    }
  }

  #code_name {
    width: 630px;
  }
</style>
<?php
// フッターセット
footer_set();
?>