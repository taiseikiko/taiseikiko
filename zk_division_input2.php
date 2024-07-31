  <?php
  session_start();
  header('Program-id: estimate_entry.php');
  header('Content-type: text/html; charset=utf-8');
  require_once('function.php');
  include('zk_division_input2_data_set.php');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する                               
  include("header1.php");

  //一覧画面からPOSTを取得  
  $process = $_POST['process'];
  $zk_division = $_POST['zk_division'] ?? '';
  $zk_div_name = $_POST['zk_div_name'] ?? '';
  $zk_tp = $_POST['zk_tp'] ?? '';
  $zk_no = $_POST['zk_no'] ?? '';
  $zk_div_data = $_POST['zk_div_data'] ?? '';
  $btn_name = ($process === 'create') ? '登録' : '更新';
?>
<main>
<div class="pagetitle">
  <h3>材工名仕様マスター保守</h3>
</div>
  <div class="container">
    <form class="row g-3" method="POST" id="zk_division_form" enctype="multipart/form-data">
      <?php include("dialog.php") ?>
      <input type="hidden" name="process" value="<?= htmlspecialchars($process) ?>">
      <input type="hidden" name="original_zk_division" value="<?= htmlspecialchars($zk_division) ?>">
      <input type="hidden" name="original_zk_div_name" value="<?= htmlspecialchars($zk_div_name) ?>">
      <input type="hidden" name="original_zk_tp" value="<?= htmlspecialchars($zk_tp) ?>">
      <input type="hidden" name="original_zk_no" value="<?= htmlspecialchars($zk_no) ?>">
      <input type="hidden" name="original_zk_div_data" value="<?= htmlspecialchars($zk_div_data) ?>">
      <input type="hidden" name="csrf_token" value="<?= $_SESSION['token'] ?>">
      <table style="width:auto;">
        <tr>
          <td>
            <div class="field-row">
              <label class="common_label" for="zk_div_name" >材工仕様</label><i class="fa fa-asterisk" style="font-size:10px;color:red;margin-left:-8px;"></i>
              <select type="text" id="zk_div_name" name="zk_div_name" value="" style="width: 140px;margin-left:1rem;">
                <option value="">※選択して下さい。</option>
                <?php foreach ($zk_div_names as $name) { ?>
                  <option value="<?= $name['zk_div_name'] ?>" <?= ($zk_div_name == $name['zk_div_name']) ? 'selected' : '' ?>><?= $name['zk_div_name'] ?></option>
                <?php } ?>
              </select>
              <label class="common_label" for="zk_tp" >区分１</label><i class="fa fa-asterisk" style="font-size:10px;color:red;margin-left:-8px;"></i>
              <select type="text" id="zk_tp" name="zk_tp" value="" style="width: 140px;margin-left:1rem;">
                <option value="">※選択して下さい。</option>
                <?php foreach ($zk_tp_values as $tp_value) { ?>
                  <option value="<?= $tp_value['zk_tp'] ?>" <?= ($zk_tp == $tp_value['zk_tp']) ? 'selected' : '' ?>><?= $tp_value['zk_tp'] ?></option>
                <?php } ?>
              </select>
              
              <label class="common_label" for="zk_no" >区分２</label><i class="fa fa-asterisk" style="font-size:10px;color:red;margin-left:-8px;"></i>
              <select type="text" id="zk_no" name="zk_no" value="" style="width: 140px;margin-left:1rem;">
                <option value="">※選択して下さい。</option>
                <?php foreach ($zk_no_values as $no_value) { ?>
                  <option value="<?= $no_value['zk_no'] ?>" <?= ($zk_no == $no_value['zk_no']) ? 'selected' : '' ?>><?= $no_value['zk_no'] ?></option>
                <?php } ?>
              </select>
            </div>
          </td>                               
        </tr>
        <tr>
          <td>
            <div class="field-row">
              <label class="common_label" for="zk_div_data" >材工名仕様詳細</label><i class="fa fa-asterisk" style="font-size:10px;color:red;margin-left:-8px;"></i>
              <input type="text" id="zk_div_data" name="zk_div_data" style="width: 700px;margin-left:1rem;" value="<?= $zk_div_data?>" maxlength="50">
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
                <button class="updateBtn" id="upd_regBtn" name="submit" value="update"><?= $btn_name?></button>
              </div>
            </div>
          </td>
        </tr>
      </table>
    </form><!-- Vertical Form -->
  </div>
</main><!-- End #main -->
<script src="assets/js/zk_division_check.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript">  
  $(document).ready(function(){
    
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
        $('#zk_division_form').attr('action', 'zk_division_input1.php');
      }
      //戻る処理の場合
      else if (process == "update") {
        //submitしたいボタン名をセットする
        $("#confirm_okBtn").attr("name", "submit");
        $('#zk_division_form').attr('action', 'zk_division_update.php');
      }
    });

  /**-------------------------------------------------------------------------------------------------------------- */

    //ALERT BOXに"はい"ボタンを押下する場合
    $("#ok_okBtn").click(function(event) {
      var process = $("#btnProcess").val();

      if (process == "errExec") {
        //zk_division_input1へ移動
        $('#zk_division_form').attr('action', 'zk_division_input1.php');
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
<style>
  .container {
    font-family: 'Lato', sans-serif;
  }

  .updateBtn {
    margin: 2px 1px;
    background-color:red;
  }

  .copyBtn {
    margin: 2px 1px;
    background-color:blue;
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

  #class_name {
    width: 630px;
  }
</style>
<?php
// フッターセット
footer_set();
?>
