<?php
session_start();
header('Program-id: estimate_entry.php');
header('Content-type: text/html; charset=utf-8');
require_once('function.php');
$_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
include("ec_stp_input2_data_set.php");
// ヘッダーセット
include("header1.php");
?>

<main>
  <div class="pagetitle">
    <h3>STP施工資格認定者</h3>
    <div class="container">
      <form class="row g-3" method="POST" name="inq_ent" enctype="multipart/form-data" id="ec_form2">
        <?php include('dialog.php'); ?>
        <input type="hidden" name="process" value="<?= $process ?>">
        <input type="hidden" id="key_number" name="key_number" value="<?= $key_number ?>">
        <table style="width:auto;">
          <tr style="height:10px; margin-top:20px"></tr>
          <tr style="height:10px;"></tr>
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="bridge">出先 </label><i class="fa fa-asterisk" style="font-size:10px;color:red;margin-left:-8px;"></i>
                <select style="margin-left: 1rem;" name="bridge" id="bridge" class="dropdown-menu">
                  <option value="">選択して下さい。</option>
                  <?php
                  if (isset($bridgeList) && !empty($bridgeList)) {
                    foreach ($bridgeList as $item) {
                      $code = $item['sq_dept_code'];
                      $text = $item['sq_dept_name'];
                      $selected = ($code == $bridge) ? 'selected' : '';
                      echo "<option value='$code' $selected>$text</option>";
                    }
                  }
                  ?>
                </select>

                <label class="common_label" for="company">会社名 </label><i class="fa fa-asterisk" style="font-size:10px;color:red;margin-left:-8px;"></i>
                <select style="margin-left: 1rem;" name="company" id="company" class="dropdown-menu">
                  <option value="">選択して下さい。</option>
                  <?php
                  if (isset($companyList) && !empty($companyList)) {
                    foreach ($companyList as $item) {
                      $code = $item['code_no'];
                      $text = $item['code_name'];
                      $selected = ($code == $company) ? 'selected' : '';
                      echo "<option value='$code' $selected>$text</option>";
                    }
                  }
                  ?>
                </select>

                <label class="common_label" for="name">氏名 </label><i class="fa fa-asterisk" style="font-size:10px;color:red;margin-left:-8px;"></i>
                <input type="text" style="margin-left:1rem" id="name" name="name" value="<?= $name ?>" class="input-res">

              </div>
            </td>
          </tr>

          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="birthday">生年月日 </label><i class="fa fa-asterisk" style="font-size:10px;color:red;margin-left:-8px;"></i>
                <input type="date" style="margin-left:1rem" min="1900-01-01" max="2028-12-31" name="birthday" id="birthday" value="<?= $birthday ?>" class="input-res" />

                <label class="common_label" for="attendance_year">受講年 </label>
                <input type="text" id="attendance_year" name="attendance_year" value="<?= $attendance_year ?>" class="input-res">

                <label class="common_label" for="elementary_number">初級№ </label>
                <input type="text" id="elementary_number" name="elementary_number" value="<?= $elementary_number ?>" class="input-res">

                <label class="common_label" for="advance_number">上級No. </label>
                <input type="text" id="advance_number" name="advance_number" value="<?= $advance_number ?>" class="input-res">
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="con_qualification">施工資格. </label>
                <input type="text" style="margin-left:1rem;" id="con_qualification" name="con_qualification" value="<?= $con_qualification ?>" class="input-res">

                <label class="common_label" for="renewal_date">更新年月日 </label><i class="fa fa-asterisk" style="font-size:10px;color:red;margin-left:-8px;"></i>
                <input type="date" style="margin-left:1rem;" min="2023-01-01" max="2028-12-31" name="renewal_date" id="renewal_date" value="<?= $renewal_date ?>" class="input-res" />

                <label class="common_label" for="expiration_date">有効期限 </label><i class="fa fa-asterisk" style="font-size:10px;color:red;margin-left:-8px;"></i>
                <input type="date" style="margin-left:1rem;" min="2023-01-01" max="2028-12-31" name="expiration_date" id="expiration_date" value="<?= $expiration_date ?>" class="input-res" />
              </div>
            </td>
          </tr>


          <tr style="height:10px;"></tr>

          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="footnote">備考</label>
                <textarea id="footnote" style="margin-left: 1rem;" name="footnote" rows="3" cols="120" class="textarea-res"><?= $footnote ?></textarea>
              </div>
            </td>
          </tr>
          <tr style="height:20px;"></tr>
          <tr>
            <td>
              <div class="flex-container">
                <div>
                  <button id="returnBtn" class="returnBtn">前の画面に戻る </button>
                </div>
                <div>
                  <button id="updateBtn" class="<?= $btn_class?>" name="submit"><?= $btn_name ?></button>
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
<script src="assets/js/ec_request_input2_check.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    //戻るボタンを押下する場合
    $("#returnBtn").click(function(event) {
      //確認メッセージを書く
      var msg = "前の画面に戻します。よろしいですか？";
      //何の処理科を書く
      var process = "return";
      //確認Dialogを呼ぶ
      openConfirmModal(msg, process);
    });

    /**-------------------------------------------------------------------------------------------------------------- */

    //登録更新ボタンを押下場合
    $('#updateBtn').click(function() {
      event.preventDefault();
      var errMessage = '';
      var errMessage = checkValidation();

      //エラーがある場合
      if (errMessage !== '') {
        //何の処理かを書く
        var process = "validate";
        //change font color
        // $('#ok-message').css('color', 'red');
        //OKDialogを呼ぶ
        openOkModal(errMessage, process);
      } else {
        var btnName = $('#updateBtn').text();
        //確認メッセージを書く
        var msg = btnName + "します？よろしいですか？";
        //何の処理科を書く
        var process = "update";

        //確認Dialogを呼ぶ
        openConfirmModal(msg, process);
      }
    });

    /**-------------------------------------------------------------------------------------------------------------- */

    //確認BOXにはいボタンを押下する場合
    $("#confirm_okBtn").click(function(event) {
      var process = $("#btnProcess").val();
      //戻る処理の場合
      if (process == "return") {
        $("#ec_form2").attr("action", "ec_stp_input1.php");
      }
      //ヘッダ更新処理の場合
      else if (process == "update") {
        //submitしたいボタン名をセットする
        $("#confirm_okBtn").attr("name", "submit");
        //sales_request_update.phpへ移動する
        $("#ec_form2").attr("action", "ec_stp_update.php");
      }
    });

    /**-------------------------------------------------------------------------------------------------------------- */

    //ALERT BOXに"はい"ボタンを押下する場合
    $("#ok_okBtn").click(function(event) {
      var process = $("#btnProcess").val();

      if (process == "errExec") {
        //sq_class_input1へ移動
        $('#ec_form2').attr('action', 'ec_stp_input1.php');
      } else {
        //画面上変更なし
        $('#ok_okBtn').attr('data-dismiss', 'modal');
      }
    });

    /**-------------------------------------------------------------------------------------------------------------- */

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
</script>
<?php
// フッターセット
footer_set();
?>
<style>
  .dropdown-menu {
    width: 180px;
  }

  .clearfix:after {
    clear: both;
    content: "";
    display: block;
    height: 0;
  }

  .updateBtn {
    margin: 2px 1px;
  }

  .copyBtn {
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

  @media only screen and (max-width:1300px) {
    .input-res {
      width: 100px;
    }

    .textarea-res {
      width: 625px;
    }

    .createBtn {
      width: 80px;
      margin-right: 60px;
    }

    .business_daily_report {
      width: 625px !important;
    }
  }

  @media only screen and (max-width:1000px) {
    .common_label {
      width: 80px;
    }

    .input-res {
      width: 80px;
    }

    .business_daily_report {
      width: 524px !important;
    }

    .textarea-res {
      width: 524px;
    }

    .createBtn {
      width: 80px;
      margin-right: 60px;
    }
  }

  @media only screen and (max-width:822px) {

    input[type="checkbox"]+label,
    input[type="file"],
    input[type="submit"] {
      font-size: 8pt;
    }

    main,
    button {
      font-size: 8pt;
    }

    .common_label {
      width: 70px;
    }

    .input-res {
      width: 70px;
    }

    .business_daily_report {
      width: 470px !important;
    }

    .textarea-res {
      width: 470px;
      height: 40px;
    }

    .createBtn {
      width: 80px;
      margin-right: 60px;
    }

    .search_btn {
      font-size: 8pt;
      width: 80px;
    }
  }

  @media only screen and (max-width:734px) {

    input[type="checkbox"]+label,
    input[type="file"],
    input[type="submit"] {
      font-size: 8pt;
    }

    main,
    button {
      font-size: 8pt;
    }

    .common_label {
      width: 50px;
    }

    .input-res {
      width: 55px;
    }

    .business_daily_report {
      width: 380px !important;
    }

    .textarea-res {
      width: 380px;
      height: 40px;
    }

    .createBtn {
      width: 80px;
      margin-right: 60px;
    }

    .search_btn {
      font-size: 8pt;
      width: 80px;
    }
  }

  @media only screen and (max-width:430px) {

    input[type="checkbox"]+label,
    input[type="file"],
    input[type="submit"],
    .copyBtn,
    .updateBtn {
      font-size: 5pt;
    }

    main,
    button {
      font-size: 5pt;
    }

    .common_label {
      width: 15px;
    }

    .input-res {
      width: 15px;
    }

    .textarea-res {
      width: 250px;
    }

    .search_btn {
      font-size: smaller;
      width: 45px;
    }

    button {
      min-width: 20px !important;
    }

    .business_daily_report {
      width: 250px !important;
    }

    .foot {
      width: max-content;
      margin: 0 !important;
    }
  }

  .business_daily_report {
    width: 630px;
  }
</style>