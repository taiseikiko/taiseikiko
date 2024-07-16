<?php
  session_start();
  require_once('function.php');
  header('Content-type: text/html; charset=utf-8');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する

  // ヘッダーセット
  header_set1();  

  include('dw_send_back_data_set.php');
?>
<!DOCTYPE html>
<html>
<body>
  <div class="window active">
    <div class="title-bar">
      <div class="title-bar-text">差し戻し</div>
      <div class="title-bar-controls">
      </div>
    </div>
    <div class="window-body has-space" style="min-height:210px; overflow:hidden;">
      <form method="POST" action="" id="return_back_form">
        <?php include("dialog.php") ?>
        <input type="hidden" name="dw_no" id="dw_no" value="<?= $dw_no ?>">
        <div class="field-row">
          <label class="common_label" for="other">差し戻し先担当者</label>
          <select class="dropdown-menu" id="send_back_to_person" name="send_back_to_person">
            <!-- <option value="" class="">選択して下さい。</option> -->
            <?php 
              if (isset($employee_datas) && !empty($employee_datas)) {
                foreach ($employee_datas as $item) {
                  $code = $item['employee_code'];
                  $text = $item['employee_name'];
                  $type = $item['type'];
                  echo "<option value='$code' id='$type'>$text</option>";
                }
              }
            ?>
          </select>
          <input type="hidden" name="type" id="type">
        </div>
        <div class="field-row">
          <label class="common_label" for="other">差し戻しコメント </label>
          <textarea name="restoration_comments" id="comments" rows="3" cols="120" class="textarea-res"><?= $restoration_comments ?></textarea>
        </div> 
        <div class="field-row" style="margin-top: 20px; margin-left: 400px;">
          <button class="skipBtn" name="send_back" id="send_back">差し戻し処理実行 </button>
        </div>
        <br>
      </form>
    </div>
  </div>
</body>
  </div>
</div>
</body>
</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){

    //差し戻し先担当者がCHANGEした場合
    var send_back = $('#send_back_to_person').val();
    if (send_back.length > 0) {
      setType();
    }

    $('#send_back_to_person').change(function() {
      setType();
    });

    function setType() {
      //選択されたOPTIONを検索する
      var selected = $('#send_back_to_person').find('option:selected');
      //選択されたOPTIONのIDを取得する
      var type = selected.attr('id');
      //選択されたユーザーがclientかentrantかを確認するためtypeにセットする
      $('#type').val(type);
    }

    /*----------------------------------------------------------------------------------------------- */
    
    //差し戻し処理実行を押下する場合
    $('#send_back').click(function() {
      //確認メッセージを書く
      var msg = "差し戻し処理を実行します。よろしいですか？";
      //何の処理科を書く
      var process = "returnProcess";
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
      if (process == "returnProcess") {
        //submitしたいボタン名をセットする
        $("#confirm_okBtn").attr("name", "send_back");
        //card_send_back_update.phpへ移動する
        $('#return_back_form').attr('action', 'dw_send_back_update.php');
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
    $("#confirm").modal({backdrop: false});
  }
</script>
