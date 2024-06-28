<?php
  session_start();
  require_once('function.php');
  header('Content-type: text/html; charset=utf-8');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する

  // ヘッダーセット
  header_set1();  

  include('sq_send_back_data_set.php');
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
    <div class="window-body has-space" style="min-height:150px; overflow:hidden;">
      <form method="POST" action="sq_detail_tr_engineering_update.php" id="return_back_form">        
        <input type="hidden" name="sq_no" id="sq_no" value="<?= $sq_no ?>">
        <input type="hidden" name="sq_line_no" id="sq_line_no" value="<?= $sq_line_no ?>">
        <input type="hidden" name="dept_id" id="dept_id" value="<?=$dept_id?>">
        <input type="hidden" name="title" id="title" value="<?=$title?>">
        <input type="hidden" name="route_pattern" id="route_pattern" value="<?=$route_pattern?>">
        <div class="field-row">
          <label class="common_label" for="other">差し戻し先部署</label>
          <select class="dropdown-menu" id="dept" name="dept">
            <option value="" class="">選択して下さい。</option>
            <option value="00" >営業部</option>
            <?php 
              if (isset($dept_datas) && !empty($dept_datas)) {
                foreach ($dept_datas as $item) {
                  $code = $item['dept_id'];
                  $text = $item['dept_name'];
                  $selected = ($code === $selected_dept) ? 'selected' : '';
                  echo "<option value='$code' $selected>$text</option>";
                }
              }
            ?>
          </select>
        </div>
        <div class="field-row">
          <label class="common_label" for="other">差し戻し先担当者</label>
          <select class="dropdown-menu" id="send_back_to_person" name="send_back_to_person">
            <option value="" class="">選択して下さい。</option>
          </select>
        </div>
        <div class="field-row">
          <label class="common_label" for="other">差し戻しコメント </label>
          <textarea name="restoration_comments" id="comments" rows="3" cols="120" class="textarea-res"><?= $restoration_comments ?></textarea>
        </div> 
        <!-- 差し戻しを行うのは各部の受付者、確認者、承認者        -->
        <?php if ($e_title !== 'entrant') {
          $disabled_btn = '';
        }?>
        <div class="field-row" style="margin-top: 20px; margin-left: 400px;">
          <button class="skipBtn" name="send_back" id="send_back" <?= $disabled_btn ?>>差し戻し処理実行 </button>
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
<script type="text/javascript">
  $(document).ready(function(){
    $('#send_back').prop('disabled', true);

    $('#dept').change(function() {
      var dept = $(this).val();
      fetchData(dept, function(response) {
        console.log(response);
      }, function(error) {
        console.error(error);
      });
    });

    $('#send_back_to_person').change(function() {
      var employee = $(this).val();
      if (employee == '') {
        $('#send_back').prop('disabled', true);
      } else {
        $('#send_back').prop('disabled', false);
      }
    });     

    //スキップ処理実行を押下する場合
    $('#send_back').click(function() {
        $('#return_back_form').attr('action', 'sq_send_back_update.php');
    })
  });

  function fetchData(dept) {
    $('#send_back_to_person option:not(:first-child)').remove();
    var route_pattern = document.getElementById('route_pattern').value;
    var sq_no = document.getElementById('sq_no').value;
    var sq_line_no = document.getElementById('sq_line_no').value;
    var title = document.getElementById('title').value;
    var log_in_dept_id = document.getElementById('dept_id').value;
    $.ajax({
      url: "sq_send_back_data_set.php",
      type: "POST",
      data: {
        dept: dept,
        route_pattern: route_pattern,
        sq_no: sq_no,
        sq_line_no: sq_line_no,
        title: title,
        log_in_dept_id: log_in_dept_id,
        functionName: "getDropdownData"
      },
      success: function(response) {
        var personList = JSON.parse(response);
        console.log(personList);
        if (personList) {
          $.each(personList, function(index, item) {
            $('#send_back_to_person').append($('<option>', {
              value: item.employee_code,
              text: item.employee_name
            }));
          });
        }
      },
      error: function(xhr, status, error) {
        console.log(xhr.responseText);
      }
    });
  }
</script>
