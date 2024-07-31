<?php
  session_start();
  require_once('function.php');
  header('Content-type: text/html; charset=utf-8');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
  include('sq_person_in_charge_input_data_set.php');

  // ヘッダーセット
  header_set1();  
?>
<!DOCTYPE html>
<html>
<body>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <div class="window active">
    <div class="title-bar">
      <div class="title-bar-text">担当者設定</div>
      <div class="title-bar-controls">
      </div>
    </div>
    <div class="window-body has-space" style="min-height:150px; overflow:hidden;">
      <form method="POST" action="sq_detail_tr_engineering_update.php" id="setEmployee">
        <?php include('dialog.php'); ?>
        <input type="hidden" name="sq_no" value="<?= $sq_no ?>">
        <input type="hidden" name="sq_line_no" value="<?= $sq_line_no ?>">
        <input type="hidden" name="record_div" value="<?= $record_div ?>">
        <input type="hidden" name="route_pattern" value="<?= $route_pattern ?>">
        <input type="hidden" name="dept_id" id="dept_id" value="<?=$dept_id?>">
        <input type="hidden" name="title" id="title" value="<?=$title?>">
        <div class="field-row">
          <label class="common_label" for="other">グループ </label><i class="fa fa-asterisk" style="font-size:10px;color:red;margin-left:-8px;"></i>       
          <select style="margin-left:1rem;" class="dropdown-menu" id="group" name="group" <?php if ($title == 'sm_receipt' || $title == 'pc_receipt') { echo 'disabled'; } ?>>
            <option value="" class="">選択して下さい。</option>
            <?php 
              if (isset($group_datas) && !empty($group_datas)) {
                foreach ($group_datas as $item) {
                  $code = $item['text2'];
                  $text = $item['text3'];
                  $selectedGroup = ($code == $group) ? 'selected' : '';
                  echo "<option value='$code' $selectedGroup>$text</option>";
                }
              }
            ?>
          </select>
        </div>
        <?php if ($title == 'sm_receipt' || $title == 'pc_receipt') { 
          echo '<font style="color: red"><label class="common_label" style="margin-left:50px;" for="other">※グループの選択は不要です。 </label></font>'; }
        ?>
        <div class="field-row">
          <label class="common_label" for="other">担当者 </label><i class="fa fa-asterisk" style="font-size:10px;color:red;margin-left:-8px;"></i>
          <select style="margin-left:1rem;" class="dropdown-menu" id="entrant" name="entrant">
            <option value="" class="">選択して下さい。</option>
            <?php 
              if (isset($employee_datas) && !empty($employee_datas)) {
                foreach ($employee_datas as $item) {
                  $code = $item['employee_code'];
                  $text = $item['employee_name'];
                  $selectedGroup = ($code == $group) ? 'selected' : '';
                  echo "<option value='$code' $selectedGroup>$text</option>";
                }
              }
            ?>
          </select>
        </div>
        <div class="field-row">
          <button class="approveBtn" name="submit_receipt" id="select">担当者設定 </button>
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
<script src="assets/js/sq_person_in_charge_check.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $('#group').change(function() {
      var group = $(this).val();
      fetchData(group, function(response) {
        console.log(response);
      }, function(error) {
        console.error(error);
      });
    });

    //担当者設定を押下する場合
    $('#select').click(function() {
      event.preventDefault();
      var errMessage = checkValidation();

      //エラーがある場合
      if (errMessage !== '') {
        //何の処理かを書く
        var process = "validate";
        //OKDialogを呼ぶ
        openOkModal(errMessage, process);
      } else {
        //確認メッセージを書く
        var msg = "担当者設定します。よろしいですか？";
        //何の処理科を書く
        var process = "setRoute";
        //確認Dialogを呼ぶ
        openConfirmModal(msg, process);
      }      
    })

    //確認BOXにはいボタンを押下する場合
    $("#confirm_okBtn").click(function(event) {
      var process = $("#btnProcess").val();
      //担当者設定処理の場合
      if (process == "setRoute") {
        //submitしたいボタン名をセットする
        $("#confirm_okBtn").attr("name", "submit_receipt");

        var title = $('#title').val();
        //技術部の場合
        if (title == 'td_receipt') {
          $('#setEmployee').attr('action', 'sq_detail_tr_engineering_update.php');
        } 
        //営業管理部の場合
        else if (title == 'sm_receipt') {
          $('#setEmployee').attr('action', 'sq_detail_tr_sales_management_update.php');
        }
        //工事管理部の場合
        else if (title == 'cm_receipt') {
          $('#setEmployee').attr('action', 'sq_detail_tr_const_management_update.php');
        }
        //資材部の場合
        else if (title == 'pc_receipt') {
          $('#setEmployee').attr('action', 'sq_detail_tr_procurement_update.php');
        }
      }
    });

     /**-------------------------------------------------------------------------------------------------------------- */

    //ALERT BOXに"はい"ボタンを押下する場合
    $("#ok_okBtn").click(function(event) {
      var process = $("#btnProcess").val();

      if (process == "errExec") {
        //sq_class_input1へ移動
        $('#input2').attr('action', 'sales_request_input1.php?title=<?= $title ?>');
      } else {
        //画面上変更なし
        $('#ok_okBtn').attr('data-dismiss', 'modal');
      }
    });
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

  function fetchData(group) {
    $('#entrant option:not(:first-child)').remove();
    var dept_id = document.getElementById('dept_id').value;
    $.ajax({
      url: "sq_person_in_charge_input_data_set.php",
      type: "POST",
      data: {
        group_id: group,
        dept_id: dept_id,
        functionName: "getDropdownData"
      },
      success: function(response) {
        var personList = JSON.parse(response);
        if (personList) {
          $.each(personList, function(index, item) {
            $('#entrant').append($('<option>', {
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
