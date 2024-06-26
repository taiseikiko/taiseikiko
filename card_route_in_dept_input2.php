<?php
  session_start();
  header('Program-id: estimate_entry.php');
  header('Content-type: text/html; charset=utf-8');
  require_once('function.php');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
  include("header1.php");
  include("card_route_in_dept_input2_data_set.php");
?>
<main>
  <div class="pagetitle">
    <h3>部署内ルートマスター保守</h3>
    <div class="container">
      <form class="row g-3" method="POST" id="set-busyo-route" enctype="multipart/form-data">
        <?php include("dialog.php") ?>
        <input type="hidden" name="process" id="process" value="<?= $process ?>">
        <table style="width:auto;">
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="dept_code" >部署</label>
                <select name="dept" id="dept" disabled>
                  <option value="">※選択して下さい。</option>
                  <?php
                    if (!empty($deptList)) {
                      foreach ($deptList as $item) {
                        $dept_cd = $item['text1'];
                        $dept_nm = $item['text2'];
                        $selectedDept = ($dept_cd == $dept_id) ? 'selected' : '';
                        echo "<option value='$dept_cd' $selectedDept>$dept_nm</option>";
                      } 
                    }
                  ?>
                </select>
                <input type="hidden" name="hid_dept_id" value="<?= $dept_id ?>">
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="employee_code" >担当者</label>
                <input type="text" id="contact_person" name="employee_code" value="<?= $employee_code ?>" class="readonlyText" readonly>
                <input type="hidden" name="hid_employee_cd" value="<?= $employee_code ?>">
                
                <button class="search_btn" style="margin-left: 11px;" onclick="emp_inq_open(event)">社員検索 </button>
                <input style="margin-left:30px" type="text" id="cp_name" name="employee_name" value="<?= $employee_name ?>" class="readonlyText" readonly>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <div class="field-row spacer">
                <label class="common_label" for="role" >役割</label>
                <select name="role" id="role" style="margin-right: 40px">
                  <option value="" <?= ("" == $role) ? 'selected' : '' ?>>※選択して下さい。</option>
                  <!-- 資材部は1と3しかありません -->
                  <?php if ($dept_id != '04') { ?>
                    <option value="0" <?= (0 == $role) ? 'selected' : '' ?>>受付</option>
                  <?php } ?>
                  <option value="1" <?= (1 == $role) ? 'selected' : '' ?>>入力</option>
                  <?php if ($dept_id != '04') { ?>
                    <option value="2" <?= (2 == $role) ? 'selected' : '' ?>>確認</option>
                  <?php } ?>
                  <option value="3" <?= (3 == $role) ? 'selected' : '' ?>>承認</option>
                  
                </select>
                <input type="hidden" name="hid_role" value="<?= $role ?>">

                <label class="common_label" for="dept_code" >部署</label>
                <input type="hidden" name="dept_code" id="dept_code">
                <input type="text" id="dept_name" name="department_name" value="<?= $department_name ?>" class="readonlyText" readonly>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <div class="field-row spacer">
                <label class="common_label" for="office_position_code" >役職</label>
                <input type="hidden" name="office_position_code" id="office_position_code">
                <input type="text" id="op_name" name="office_position_name" value="<?= $office_position_name ?>" class="readonlyText" readonly>
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
                  <button class="updateBtn" id="upd_regBtn" name="submit" value="update"><?= $btn_name ?></button>
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
<script src="assets/js/inquiry_ent.js"></script>
<script src="assets/js/inquiry_ent_check.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="assets/js/card_route_in_dept_input2_check.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    //戻るボタンを押下する場合
    $('#returnBtn').click(function(event) {
      //確認メッセージを書く
      var msg = "前の画面に戻します。よろしいですか？";
      //何の処理科を書く
      var process = "return";
      //確認Dialogを呼ぶ
      openConfirmModal(msg, process);
    });

    //更新ボタンを押下場合
    $('#upd_regBtn').click(function(event) {
      //チェックバリデーション
      var errMsg = checkValidation(event);
      //エラーがある場合
      if (errMsg !== undefined) {
        //何の処理かを書く
        var process = "validate";
        //OKDialogを呼ぶ
        openOkModal(errMsg, process);
      } else {
        var btnname = "<?= $btn_name ?>";
        //確認メッセージを書く
        var msg = btnname + "します。よろしいですか？";
        //何の処理かを書く
        var process = "update";
        //確認Dialogを呼ぶ
        openConfirmModal(msg, process);
      }
    });

    //エラーがあるかどうか確認する
    var err = '<?= $err ?>';
    //エラーがある場合
    if (err !== '') {
      //何の処理かを書く
      var process = "error";
      //重複エラーの場合
      if (err == 'dupErr') {
        //OKメッセージを書く
        var msg = "重複の登録があります。";        
      } else {
        //OKメッセージを書く
        var msg = "登録処理にエラーがありました。係員にお知らせください。";
      }
      //OKDialogを呼ぶ
      openOkModal(msg, process);
    }

    //確認BOXに"はい"ボタンを押下する場合
    $("#confirm_okBtn").click(function(event) {
      var process = $("#btnProcess").val();
      //戻る処理の場合
      if (process == "return") {
        $('#set-busyo-route').attr('action', 'card_route_in_dept_input1.php');
      }
      //ヘッダ更新処理の場合
      else if (process == "update") {
        //submitしたいボタン名をセットする
        $("#confirm_okBtn").attr("name", "submit");
        //sales_request_update.phpへ移動する
        $("#set-busyo-route").attr("action", "card_route_in_dept_update.php");
      }
    });

    //ALERT BOXに"はい"ボタンを押下する場合
    $("#ok_okBtn").click(function(event) {
      var process = $("#btnProcess").val();
      //エラーがある場合
      if (process == "error") {
        //card_route_in_dept_input1へ移動
        $('#set-busyo-route').attr('action', 'card_route_in_dept_input1.php');
      } else {
        //画面上変更なし
        $('#ok_okBtn').attr('data-dismiss', 'modal');
      }
    });

  });

  function openConfirmModal(msg, process) {
    event.preventDefault();
    //何の処理かをセットする
    $("#btnProcess").val(process);
    //確認メッセージをセットする
    $("#confirm-message").text(msg);
    //確認Dialogを呼ぶ
    $("#confirm").modal({backdrop: false});
  }

  function openOkModal(msg, process) {
    //何の処理かをセットする
    $("#btnProcess").val(process);
    //確認メッセージをセットする
    $("#ok-message").text(msg);
    //確認Dialogを呼ぶ
    $("#ok").modal({backdrop: false});
  }
</script>
<?php

// フッターセット
footer_set();
?>
<style>
  .spacer {
    justify-content: right;
  }

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

  .flex-container > div {
    margin: 20px 5px;
  }

  @media only screen and (max-width:800px) {
    .pagetitle, .container, .field-row {
      width: 80%;
      padding: 0;
    }
    .createBtn {
      width: 40px;
    }
  }
  @media only screen and (max-width:500px) {
    .pagetitle, .container, .field-row {
      width: 100%;
    }
    .createBtn {
      width: 40px;
    }
  }
</style>