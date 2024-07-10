<?php
  session_start();
  header('Program-id: estimate_entry.php');
  header('Content-type: text/html; charset=utf-8');
  require_once('function.php');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
  include("employee_ent2_data_set.php");
  include("header1.php");
   
?>

<main>
  <div class="pagetitle">
    <h3>社員マスター保守</h3>
  </div>
  <div class="container">
    <form class="row g-3" method="POST" name="inq_ent" enctype="multipart/form-data" id="emp_ent2">
      <input type="hidden" id="process" name="process" value="<?= $process ?>">
      <?php include("dialog.php") ?>
      <table>
        <tr>
          <td>
            <div class="field-row">
              <label class="common_label" for="employee_code">社員番号</label>
              <input type="text" id="employee_code" name="employee_code" value="<?= $employee_code ?>" 
              <?php if ($disabled_emp_code) { echo 'readonly class="readonlyText"';}  ?>>

              <label class="common_label" for="employee_name">社員名</label>
              <input type="text" id="employee_name" name="employee_name" value="<?= $employee_name ?>" class="input-res">

              <label class="common_label" for="kana">社員名カナ</label>
              <input type="text" id="kana" name="kana" value="<?= $kana ?>" class="input-res">
            </div>
          </td>                
        </tr>

        <tr>
          <td>
            <div class="field-row">
              <label class="common_label" for="department_code">部　署</label>
              <select name="department_code" class="dropdown-menu">
                <option value="">※選択して下さい。</option>
                <?php 
                if (isset($department_datas)) {
                  foreach($department_datas as $item) {
                    $code = $item['text1'];
                    $text = $item['text2'];
                    $selected = ($code == $department_code) ? 'selected' : '';
                    echo "<option value='$code' $selected>$text</option>";
                  }
                }
                ?>
              </select>
            </div>
          </td>
        </tr>

        <tr>
          <td>
            <div class="field-row">
              <label class="common_label" for="office_position_code">役　職</label>
              <select name="office_position_code" class="dropdown-menu">
                <option value="">※選択して下さい。</option>
                <?php 
                if (isset($office_position_datas)) {
                  foreach($office_position_datas as $item) {
                    $code = $item['code_no'];
                    $text = $item['text1'];
                    $selected = ($code == $office_position_code) ? 'selected' : '';
                    echo "<option value='$code' $selected>$text</option>";
                  }
                }
                ?>
              </select>

              <label class="common_label" for="class_name">等　級</label>
              <select name="qualifications_code" class="dropdown-menu">
                <option value="">※選択して下さい。</option>
                <?php 
                if (isset($qualification_datas)) {
                  foreach($qualification_datas as $item) {
                    $code = $item['code_no'];
                    $text = $item['text1'];
                    $selected = ($code == $qualifications_code) ? 'selected' : '';
                    echo "<option value='$code' $selected>$text</option>";
                  }
                }
                ?>
              </select>
            </div>
          </td>
        </tr>

        <tr>
          <td>
            <div class="field-row">
              <label class="common_label" for="class_name">給与区分</label>
              <select name="pay_division" class="dropdown-menu">
                <option value="">※選択して下さい。</option>
                <?php 
                if (isset($pay_division_datas)) {
                  foreach($pay_division_datas as $item) {
                    $code = $item['code_no'];
                    $text = $item['text1'];
                    $selected = ($code == $pay_division) ? 'selected' : '';
                    echo "<option value='$code' $selected>$text</option>";
                  }
                }
                ?>
              </select>

              <label class="common_label" for="goho">号　棒</label>
              <select name="goho" class="dropdown-menu">
                <option value="">※選択して下さい。</option>
                <?php 
                if (isset($company_codes)) {
                  foreach($company_codes as $item) {
                    $code = $item['text1'];
                    $text = $item['text2'];
                    $selected = ($code == $goho) ? 'selected' : '';
                    echo "<option value='$code' $selected>$text</option>";
                  }
                }
                ?>
              </select>
            </div>
          </td>
        </tr>

        <tr>
          <td>
            <div class="field-row">
              <label class="common_label" for="class_code" >システム権限</label>
              <select name="authorization" class="dropdown-menu">
                <option value="0">※選択して下さい。</option>
                <option value="0" <?php if ($authorization == '0') echo 'selected'; ?>>一般</option>
                <option value="3" <?php if ($authorization == '3') echo 'selected'; ?>>管理者</option>
                <option value="5" <?php if ($authorization == '5') echo 'selected'; ?>>最高権限</option>
              </select>

              <label class="common_label" for="pass">パスワード</label>
              <input type="text" id="pass" name="pass" value="<?= $pass ?>" class="input-res">
            </div>
          </td>
        </tr>

        <tr>
          <td>
            <div class="field-row">
              <label class="common_label" for="email">メールアドレス</label>
              <input type="text" id="email" name="email" value="<?= $email ?>" class="input-res" style="width:400px;">

              <label class="common_label" for="birthday">生年月日</label>
              <input type="date" min="2023-01-01" max="2028-12-31" id="birthday" name="birthday" value="<?= $birthday ?>" class="input-res">
            </div>
          </td>                
        </tr>

        <tr>
          <td>
            <div class="field-row">
              <label class="common_label" for="date_of_entry">入社日</label>
              <input type="date" min="2023-01-01" max="2028-12-31" id="date_of_entry" name="date_of_entry" value="<?= $date_of_entry ?>" class="input-res">

              <label class="common_label" for="company_code">会社コード</label>
              <select name="company_code" class="dropdown-menu">
                <option value="">※選択して下さい。</option>
                <?php 
                if (isset($company_datas)) {
                  foreach($company_datas as $item) {
                    $code = $item['text1'];
                    $text = $item['text2'];
                    $selected = ($code == $company_code) ? 'selected' : '';
                    echo "<option value='$code' $selected>$text</option>";
                  }
                }
                ?>
              </select>
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
</main><!-- End #main -->

<script src="assets/js/inquiry_ent.js"></script>
<script src="assets/js/inquiry_ent_check.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
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

    /**-------------------------------------------------------------------------------------------------------------- */

    //登録更新ボタンを押下場合
    $('#upd_regBtn').click(function() {
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
        var btnName = $('#upd_regBtn').text();
        //確認メッセージを書く
        var msg = btnName + "します？よろしいですか？";
        //何の処理科を書く
        var process = "update";        
        //確認Dialogを呼ぶ
        openConfirmModal(msg, process);
      }
    });

    /*----------------------------------------------------------------------------------------------- */
    //更新ボタンを押下場合
    $("#update").click(function(){
      var selectedId = $(this).data('id');
      $('.zkm_code').val(selectedId);
      $("#sq_zkm_form").attr("action", "sq_zkm_input2.php");
    });
    
    /**---------------------------------------------------------------------------------------------------------------------- */

    //確認BOXに"はい"ボタンを押下する場合
    $("#confirm_okBtn").click(function(event) {
      var process = $("#btnProcess").val();
      //戻る処理の場合
      if (process == "return") {
        $('#emp_ent2').attr('action', 'employee_ent1.php');
      }
      //ヘッダ更新処理の場合
      else if (process == "update") {
        //submitしたいボタン名をセットする
        $("#confirm_okBtn").attr("name", "submit");
        //sales_request_update.phpへ移動する
        $("#emp_ent2").attr("action", "employee_update.php");
      }
    });

    /**---------------------------------------------------------------------------------------------------------------------- */

    //ALERT BOXに"はい"ボタンを押下する場合
    $("#ok_okBtn").click(function(event) {
      var process = $("#btnProcess").val();
      //エラーがある場合
      if (process == "exceErr" || process == "duplicate") {
        //dw_route_in_dept_input1へ移動
        $('#emp_ent2').attr('action', 'employee_ent1.php');
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
      if (err == 'exceErr') {
        //OKメッセージを書く
        var msg = "処理にエラーがありました。係員にお知らせください。";
      } else {
        //OKメッセージを書く
        var msg = "重複の登録があります。";
      }
      //OKDialogを呼ぶ
      openOkModal(msg, err);
    }

  /**------------------------------------------------------JS---------------------------------------------------------------- */

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

  /**---------------------------------------------------------------------------------------------------------------------- */

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

  /**---------------------------------------------------------------------------------------------------------------------- */
</script>
<?php
// フッターセット
footer_set();
?>
<style>
  .scrollable-table-container {
    width: fit-content;
    height:700px;
    overflow: auto;
  }

  thead th {
    position: sticky;
    top: 0; 
    z-index: 1;
  }

  .dropdown-menu {
    width: 180px;
  }

  .updateBtn {
    margin: 2px 1px;
  }

  .createBtn {
    width: 110px;
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