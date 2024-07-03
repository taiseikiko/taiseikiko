<?php
  session_start();
  require_once('function.php');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
  include("sr_route_input2_data_set.php");  
  header('Program-id: estimate_entry.php');
  header('Content-type: text/html; charset=utf-8');
  include("header1.php");
?>

<main>
  <div class="pagetitle">
    <h3>部署ルートマスター保守</h3>
    <div class="container">
      <form class="row g-3" method="POST" id="sr_route_form" enctype="multipart/form-data">
        <?php include("dialog.php") ?>
        <input type="hidden" name="process" id="process" value="<?= htmlspecialchars($process) ?>">
        <table style="width:auto;">
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="route_id">ルートID</label>
                <input style="width: 100px;" type="text" value="<?= htmlspecialchars($route_id) ?>" id="route_id" name="route_id" class="readonlyText" readonly>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <div class="field-row" style="margin: 1rem;">
                <font size=3>
                  <b>ルート部署を1～5までプルダウンから選択して下さい。</b>
                </font>
              </div>
            </td>
          </tr>
          <?php for ($i = 1; $i <= 5; $i++) : ?>
            <tr>
              <td>
                <div class="field-row">
                  <label class="common_label" for="route<?= $i ?>_dept">ルート<?= $i ?>部署</label>
                  <select name="route<?= $i ?>_dept" id="route<?= $i ?>_dept">
                    <?= generateOptions($dept_list, $route_depts[$i - 1]) ?>
                  </select>
                </div>
              </td>
            </tr>
          <?php endfor; ?>
          <tr>
            <td>
              <div class="flex-container" style="margin-left:3rem">
                <div>
                  <button id="returnBtn" name="return">戻る</button>
                </div>
                <div>
                  <button class="updateBtn" id="upd_regBtn" name="submit" value="update"><?= htmlspecialchars($btn_name) ?></button>
                </div>
              </div>
            </td>
          </tr>
        </table>
      </form><!-- Vertical Form -->
    </div>
  </div>
</main>

<script src="assets/js/sr_route_check.js"></script>
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
        $('#sr_route_form').attr('action', 'sr_route_input1.php');
      }
      //戻る処理の場合
      else if (process == "update") {
        //submitしたいボタン名をセットする
        $("#confirm_okBtn").attr("name", "submit");
        $('#sr_route_form').attr('action', 'sr_route_update.php');
      }
    });

    /**-------------------------------------------------------------------------------------------------------------- */

    //ALERT BOXに"はい"ボタンを押下する場合
    $("#ok_okBtn").click(function(event) {
      var process = $("#btnProcess").val();

      if (process == "errExec") {
        //sr_route_input1へ移動
        $('#sr_route_form').attr('action', 'sr_route_input1.php');
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

</script>

<?php
// フッターセット
footer_set();
?>

<style>
  .updateBtn {
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
</style>