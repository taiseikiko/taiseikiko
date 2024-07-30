<?php
  session_start();
  require_once('function.php');
  header('Content-type: text/html; charset=utf-8');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
  // ヘッダーセット
  header_set1(); 
  include('fwt_cancel_data_set.php');
?>
<!DOCTYPE html>
<html>
<body>
  <div class="window active">
    <div class="title-bar">
      <div class="title-bar-text">中止処理マスター</div>
      <div class="title-bar-controls">
      </div>
    </div>
    <div class="window-body has-space" style="min-height:210px; overflow:hidden;">
      <form method="POST" id="cancel_form">
        <?php include("dialog.php") ?>
        <input type="hidden" name="fwt_m_no" value="<?= $fwt_m_no ?>">
        <div class="field-row">
          <label class="common_label" for="other">中止コメント</label>
          <textarea style="margin-left: 1rem;" name="comments" id="comments" rows="3" cols="120" class="textarea-res"><?= $comments ?></textarea>
        </div>         
        <div class="field-row" style="margin-top: 20px; margin-left: 400px;">
          <button class="cancelProcessBtn" name="cancel" id="cancel">中止処理実行（処理中です。） </button>
        </div>
        <br>
      </form>
    </div>
  </div>
</body>
</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    //スキップ処理実行を押下する場合
    $('#cancel').click(function() {
        // $('#cancel_form').attr('action', 'cancel_update.php');
        //確認メッセージを書く
      var msg = "中止処理を実行します。よろしいですか？";
      //何の処理科を書く
      var process = "cancel";
      //確認Dialogを呼ぶ
      openConfirmModal(msg, process);
    })
     /*----------------------------------------------------------------------------------------------- */

    //エラーがあるかどうか確認する
    var err = '<?= $err ?>';
    //エラーがある場合
    if (err !== '') {
      //OKメッセージを書く
      var msg = "登録処理にエラーがありました。係員にお知らせください。";
      //OKDialogを呼ぶ
      openOkModal(msg, 'error');
    }

    /*----------------------------------------------------------------------------------------------- */

    //確認BOXに"はい"ボタンを押下する場合
    $("#confirm_okBtn").click(function(event) {
      var process = $("#btnProcess").val();
      //ヘッダ更新処理の場合
      if (process == "cancel") {
        //submitしたいボタン名をセットする
        $("#confirm_okBtn").attr("name", "cancel");
        //cancel_update.phpへ移動する
        $('#cancel_form').attr('action', 'fwt_cancel_update.php');
      }
    });
  });
  /*--------------------------------------FUNCTION--------------------------------------------------------- */
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
</script>
