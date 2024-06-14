<?php
  session_start();
  require_once('function.php');
  header('Content-type: text/html; charset=utf-8');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する

  // ヘッダーセット
  header_set1();  

  include('cancel_data_set.php');
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
    <div class="window-body has-space" style="min-height:150px; overflow:hidden;">
      <form method="POST" action="sq_detail_tr_engineering_update.php" id="cancel_form">
        <input type="hidden" name="sq_no" value="<?= $sq_no ?>">
        <input type="hidden" name="sq_line_no" value="<?= $sq_line_no ?>">
        <input type="hidden" name="dept_id" id="dept_id" value="<?=$dept_id?>">
        <input type="hidden" name="title" id="title" value="<?=$title?>">
        <div class="field-row">
          <label class="common_label" for="other">中止コメント</label>
          <textarea style="margin-left: 1rem;" name="comments" id="comments" rows="3" cols="120" class="textarea-res"><?= $comments ?></textarea>
        </div> 
        <!--         -->
        <?php if (($e_title == 'confirm') || ($e_title == 'approve')) {
          $disabled_btn = '';
        }?>
        <div class="field-row" style="margin-top: 20px; margin-left: 400px;">
          <button class="cancelBtn" name="cancel" id="cancel" <?= $disabled_btn ?>>中止処理実行 </button>
        </div>
        <br>
      </form>
    </div>
  </div>
</body>
<?php
  // フッターセット
  echo "Copyright <strong><span>情報システムグループ</span></strong>. All Rights Reserved<hr>";
?>
  </div>
</div>
</body>
</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    //スキップ処理実行を押下する場合
    $('#cancel').click(function() {
        $('#cancel_form').attr('action', 'cancel_update.php');
    })
  });
</script>
