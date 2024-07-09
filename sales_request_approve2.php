<?php
  session_start();
  header('Program-id: estimate_entry.php');
  header('Content-type: text/html; charset=utf-8');
  require_once('function.php');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
  $title = isset($_GET['title']) ? $_GET['title'] : '';
  $dept_code = $_SESSION['department_code'];
  include("sales_request_input2_data_set.php");
  // ヘッダーセット
  include("header1.php");
?>

<main>
  <div class="pagetitle">
    <h3>【　営業依頼書：承認　】</h3>
    <?php include("common_sales_input2.php"); ?>
  </div>
</main><!-- End #main -->
</body>
</html>
<script src="assets/js/customer_ent.js"></script>
<script src="assets/js/public_office_ent.js"></script>
<script src="assets/js/sales_request_check.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    disableInput();

    //一覧から新規作成を押下する場合
    $(".createBtn").click(function(){

      //sales_request_approve3.phpへ移動する
      $("#input2").attr("action", "sales_request_approve3.php?title=<?= $title ?>");
    });

    //一覧画面から更新 or コピーボタンを押下する場合
    $(".updateBtn, .copyBtn").click(function(){
      //クリックされた行の営業依頼書行№	を取得する
      var selected = $(this).data('sq_line_no');

      //sales_request_approve3.phpへ移動する
      $("#input2").attr("action", "sales_request_approve3.php?line="+selected+"&title=<?= $title ?>");
    });

    //アップロードボタンを押下する場合
    $("#upload").click(function(){
      //sq_attach_upload1.phpへ移動する
      $("#input2").attr("action", "sq_attach_upload1.php?from=sr");
    })

    //確認ボタンを押下する場合
    $(".approveBtn").click(function(){
      event.preventDefault();
      var errMessage = checkValidationInput2();

      //エラーがある場合
      if (errMessage !== '') {
        //何の処理かを書く
        var process = "validate";
        //OKDialogを呼ぶ
        openOkModal(errMessage, process);
      } else {
        //確認メッセージを書く
        var msg = "営業依頼書を承認します？よろしいですか？";
        //何の処理科を書く
        var process = "update";
        //確認Dialogを呼ぶ
        openConfirmModal(msg, process);
      }   
    })

    //戻るボタンを押下する場合
    $("#returnBtn").click(function(){
      //確認メッセージを書く
      var msg = "前の画面に戻します。よろしいですか？";
      //何の処理科を書く
      var process = "return";
      //確認Dialogを呼ぶ
      openConfirmModal(msg, process);      
    })

    //確認BOXにはいボタンを押下する場合
    $("#confirm_okBtn").click(function(event) {
      var process = $("#btnProcess").val();
      //戻る処理の場合
      if (process == "return") {
        $("#input2").attr("action", "sales_request_approve1.php?title=<?= $title ?>");
      }
      //ヘッダ更新処理の場合
      else if (process == "update") {
        //submitしたいボタン名をセットする
        $("#confirm_okBtn").attr("name", "submit");
        //sales_request_update.phpへ移動する
        $("#input2").attr("action", "sales_request_update.php?title=<?= $title ?>");
      }
    });

    /**-------------------------------------------------------------------------------------------------------------- */

    //ALERT BOXに"はい"ボタンを押下する場合
    $("#ok_okBtn").click(function(event) {
      var process = $("#btnProcess").val();

      if (process == "errExec") {
        //sq_class_input1へ移動
        $('#input2').attr('action', 'sales_request_approve1.php?title=<?= $title ?>');
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

  function disableInput() {
    //Disabled Input 
    var inputs = document.getElementsByTagName('input');
    for (var i = 0; i < inputs.length; i++) {
      if (inputs[i].type.toLowerCase() !== 'hidden') {
        inputs[i].disabled = true;
      }
      if (inputs[i].type.toLowerCase() == 'text') {
        inputs[i].style.backgroundColor = '#e6e6e6';
      }
    }

    //Disabled textarea 
    var textareas = document.getElementsByTagName('textarea');
    for (var j = 0; j < textareas.length; j++) {
        textareas[j].disabled = true;
        textareas[j].style.backgroundColor = '#e6e6e6';
    }

    var selects = document.getElementsByTagName('select');
    const excludeSelect = ['otherProcess'];
    for (var k = 0; k < selects.length; k++) {
      if (!excludeSelect.includes(selects[k].id)) {
        selects[k].disabled = true;
      }
    }

    //Disabled select 
    var selects = document.getElementsByTagName('select');
    for (var k = 0; k < selects.length; k++) {
        
    }

    //Disabled button 
    var buttons = document.getElementsByTagName('button');
    const excludeButtons = ['returnBtn', 'updateBtn', 'okBtn', 'cancelBtn', 'copyBtn', 'createBtn'];
    for (var k = 0; k < buttons.length; k++) {
      if (!excludeButtons.includes(buttons[k].className)) {
        buttons[k].disabled = true;
      }
    }
  }
</script>
<?php
// フッターセット
footer_set();
?>
