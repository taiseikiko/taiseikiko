<?php
  session_start();
  require_once('function.php');
  header('Content-type: text/html; charset=utf-8');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する

  // ヘッダーセット
  header_set1();  

  include('skip_data_set.php');
?>
<!DOCTYPE html>
<html>
<body>
  <div class="window active">
    <div class="title-bar">
      <div class="title-bar-text">スキップ処理マスター</div>
      <div class="title-bar-controls">
      </div>
    </div>
    <div class="window-body has-space" style="min-height:150px; overflow:hidden;">
      <form method="POST" action="sq_detail_tr_engineering_update.php" id="skip_form">
        <input type="hidden" name="sq_no" value="<?= $sq_no ?>">
        <input type="hidden" name="sq_line_no" value="<?= $sq_line_no ?>">
        <input type="hidden" name="dept_id" id="dept_id" value="<?=$dept_id?>">
        <input type="hidden" name="title" id="title" value="<?=$title?>">
        <input type="hidden" name="route_pattern" id="route_pattern" value="<?=$route_pattern?>">
        <div class="field-row">
          <label class="common_label" for="other">スキップコメント </label>
          <textarea style="margin-left: 1rem;" name="comments" id="comments" rows="3" cols="120" class="textarea-res"><?= $comments ?></textarea>
        </div> 
        <!-- 現在の部署が「技術部と資材部」かつ、受付画面のみに、スキップ処理ボタンを表示。        -->
        <?php if (($dept_id == '02' && $e_title == 'receipt') || ($dept_id == '04' && $e_title == 'receipt')) {
          $disabled_btn = '';
        }?>
        <div class="field-row" style="margin-top: 20px; margin-left: 400px;">
          <button class="skipBtn" name="skip" id="skip" <?= $disabled_btn ?>>スキップ処理実行 </button>
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
    $('#skip').click(function() {
        $('#skip_form').attr('action', 'skip_update.php');
    })
  });
</script>
