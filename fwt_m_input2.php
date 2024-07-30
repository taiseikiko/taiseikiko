<?php
  session_start();
  header('Program-id: estimate_entry.php');
  header('Content-type: text/html; charset=utf-8');
  require_once('function.php');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
  $dept_code = $_SESSION['department_code'];
  $user_code = $_SESSION["login"]?? '';
  $user_name = $_SESSION['user_name']?? '';      //登録者
  $office_name = $_SESSION['office_name']?? '';  //部署
  $office_position_name = $_SESSION['office_position_name']?? '';  //役職
  $title = $_GET['title']?? '';
  include("fwt_m_input2_data_set.php");
  include("header1.php");
?>

<main>
  <div class="pagetitle">
    <table style="width: 100%;">      
      <tr>
        <td style="width: 100%;">
          <div class="field-row" style="display: flex; justify-content: space-between; align-items: center;">
            <label for="title"><h3>見学、立会、研修仮予約、本予約入力</h3></label>
            <label class="common_label" for="add_date" style="margin-left: auto;">　　申請日</label>
            <input type="date" style="margin-left: 1rem;" min="2023-01-01" max="2028-12-31" name="add_date" value="<?= $add_date ?>" class="input-res"/>
          </div>
        </td>
      </tr>
    </table>
  </div>
  <?php include("common_fwt_m_input2.php"); ?>
</main><!-- End #main -->
</body>
</html>
<script src="assets/js/customer_ent.js"></script>
<script src="assets/js/public_office_ent.js"></script>
<script src="assets/js/sales_request_check.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    
    var status = "<?= $status ?>";
    disableInput(status);

    /**-------------------------------------------------------------------------------------------------------------- */
    
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

    //日程調整ボタンを押下する場合
    $(".approveBtn").click(function(event){
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
        var btn_name = "<?= $btn_name ?>";
        //確認メッセージを書く
        var msg = btn_name + "します？よろしいですか？";
        //何の処理科を書く
        var process = "update";
        //確認Dialogを呼ぶ
        openConfirmModal(msg, process);
      }      
    })

    /**-------------------------------------------------------------------------------------------------------------- */

    //確認BOXにはいボタンを押下する場合
    $("#confirm_okBtn").click(function(event) {
      var process = $("#btnProcess").val();
      //戻る処理の場合
      if (process == "return") {
        $("#input2").attr("action", "fwt_m_input1.php?title=<?= $title ?>");
      }
      //更新処理の場合
      else if (process == "update") {
        //submitしたいボタン名をセットする
        $("#confirm_okBtn").attr("name", "submit");
        //sales_request_update.phpへ移動する
        $("#input2").attr("action", "fwt_m_update.php");
      }
      // //アプロード１処理の場合
      // else if (process == "upload") {
      //   //submitしたいボタン名をセットする
      //   $("#confirm_okBtn").attr("name", "upload");
      //   //sales_request_update.phpへ移動する
      //   uploadFile("sq_attach_upload1.php?from=sr");
      // }
    });

     /*----------------------------------------------------------------------------------------------- */

    //ALERT BOXに"はい"ボタンを押下する場合
    $("#ok_okBtn").click(function(event) {
      var process = $("#btnProcess").val();
      //エラーがある場合
      if (process == "errExec") {
        //fwt_m_input1へ移動
        $('#input2').attr('action', 'fwt_m_input1.php?title=<?= $title ?>');
      } else {
        //画面上変更なし
        $('#ok_okBtn').attr('data-dismiss', 'modal');
      }
    });

    /*----------------------------------------------------------------------------------------------- */

    //中止ボタンを押下する場合
    $('#reject').click(function () {
      event.preventDefault();
      var fwt_m_no = document.getElementById('fwt_m_no').value;

      var url = "fwt_cancel_division_input1.php" + "?fwt_m_no=" + fwt_m_no;
      window.open(url, "popupWindow", "width=900,height=250,left=100,top=50");
    })

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

    /*----------------------------------------------------------------------------------------------- */
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

  function disableInput(status) {
    //Disabled Input 
    let excludeInput;
    if (status !== '1') {
      excludeInput = [];
    } else {
      excludeInput = ['fixed_date', 'fixed_start', 'fixed_end'];
    }
    var inputs = document.getElementsByTagName('input');
    for (var i = 0; i < inputs.length; i++) {
      if (inputs[i].type.toLowerCase() !== 'hidden') {
        if (!excludeInput.includes(inputs[i].id)) {
          inputs[i].disabled = true;
        }
      }
      if (inputs[i].type.toLowerCase() == 'text') {
        inputs[i].style.backgroundColor = '#e6e6e6';
      }
    }

    //Disabled textarea 
    var textareas = document.getElementsByTagName('textarea');
    let excludeTA;
    if (status !== '3') {
      excludeTA = ['note'];
    } else {
      excludeTA = [];
    }
    for (var j = 0; j < textareas.length; j++) {
      if (!excludeTA.includes(textareas[j].id)) {
        textareas[j].disabled = true;
        textareas[j].style.backgroundColor = '#e6e6e6';
      }
    }

    //Disabled select
    var selects = document.getElementsByTagName('select');
    const excludeSelect = [''];
    for (var k = 0; k < selects.length; k++) {
      if (!excludeSelect.includes(selects[k].id)) {
        selects[k].disabled = true;
      }
    }

    //Disabled button 
    var buttons = document.getElementsByTagName('button');
    const excludeButtons = ['returnBtn', 'updateBtn', 'okBtn', 'cancelBtn', 'approveBtn', 'skipBtn'];
    for (var k = 0; k < buttons.length; k++) {
      if (!excludeButtons.includes(buttons[k].className)) {
        buttons[k].disabled = true;
      }
    }
  }

  /**-------------------------------------------------------------------------------------------------------------- */

</script>
<?php
// フッターセット
// footer_set();
?>
