<?php
  session_start();
  header('Program-id: estimate_entry.php');
  header('Content-type: text/html; charset=utf-8');
  require_once('function.php');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
  include("header1.php");

  $dept_code = $_SESSION['department_code'];
  $card_no = $_POST['card_no'] ?? '';
  $_SESSION['card_no'] = $card_no;
  $user_code = $_SESSION["login"];
  $user_name = $_SESSION['user_name'];      //登録者
  $office_name = $_SESSION['office_name'];  //部署
  $office_position_name = $_SESSION['office_position_name'];  //役職
  
  // ヘッダーセット
  include("card_input2_data_set.php");
?>

<main>
  <div class="pagetitle">
    <h3>資材部：入力</h3>
    <?php include("common_card_input2.php"); ?>
  </div>
</main><!-- End #main -->
</body>
<script src="assets/js/public_office_ent.js"></script>
<script src="assets/js/inquiry_ent.js"></script>
<script src="assets/js/inquiry_ent_check.js"></script>
<script src="assets/js/sales_request_input3_check.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    // 4つの部署のCHANGEを扱う関数
    function handleClassListChange(selector, index) {
      $(selector).change(function() {
        let class_code = $(this).val();
        $('#zkm_code' + index).val('');
        fetchData(class_code, index);
      })
    }
    
    // Using async/await to handle fetchData asynchronously
    async function processClassCodes() {
      // Loop through each class list and set up change handlers
      for (let i = 1; i <= 4; i++) {
        let selector = `#classList${i}`;
        let class_code = $(selector).val();
        
        if (class_code !== '') {
          await fetchData(class_code, i.toString());
        }
        
        handleClassListChange(selector, i);
      }
    }

    processClassCodes();

    /*----------------------------------------------------------------------------------------------- */

    $("#returnBtn").click(function() {
      //確認メッセージを書く
      var msg = "前の画面に戻します。よろしいですか？";
      //何の処理科を書く
      var process = "return";
      //確認Dialogを呼ぶ
      openConfirmModal(msg, process);
    });

    /*----------------------------------------------------------------------------------------------- */

    //登録／承認ボタンが押された場合
    $('#reg_updBtn').click(function() {
      //確認メッセージを書く
      var msg = $(this).text() + "します。よろしいですか？";
      //何の処理科を書く
      var process = "new";
      //確認Dialogを呼ぶ
      openConfirmModal(msg, process);
    });

    /*----------------------------------------------------------------------------------------------- */

    //アプロードボタンを押下場合
    $('#upload').click(function(event) {     
          //何の処理かを書く
          var process = "upload";
          //エラーメッセージを書く
          var msg = "アプロードします。よろしいですか？";
          //確認Dialogを呼ぶ
          openConfirmModal(msg, process);
       
    });

    /*----------------------------------------------------------------------------------------------- */

    //確認BOXに"はい"ボタンを押下する場合
    $("#confirm_okBtn").click(function(event) {
      var process = $("#btnProcess").val();
      //戻る処理の場合
      if (process == "return") {
        $('#card_input2').attr('action', 'card_input1.php');
      }
      //ヘッダ更新処理の場合
      else if (process == "new") {
        //submitしたいボタン名をセットする
        $("#confirm_okBtn").attr("name", "submit");
        //sales_request_update.phpへ移動する
        $('#card_input2').attr('action', 'card_update.php');
      }
      //アプロード処理の場合
      else if (process == "upload"){
        //submitしたいボタン名をセットする
        $("#confirm_okBtn").attr("name", "submit");
        //sales_request_update.phpへ移動する
        $('#card_input2').attr('action', 'card_attach_upload1.php?from=input2');
      }
    });

    /*----------------------------------------------------------------------------------------------- */

    //ALERT BOXに"はい"ボタンを押下する場合
    $("#ok_okBtn").click(function(event) {
      var process = $("#btnProcess").val();
      //エラーがある場合
      if (process == "error") {
        //card_input1へ移動
        $('#card_input2').attr('action', 'card_input1.php');
        //選択したファイルをクリアする
        $('#uploaded_file').val('');
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
      var msg = "登録処理にエラーがありました。係員にお知らせください。";
      //OKDialogを呼ぶ
      openOkModal(msg, 'error');
    }

    /*----------------------------------------------------------------------------------------------- */
    
  });

  function fetchData(class_code, no) {
    $('#zaikoumeiList' + no + 'option:not(:first-child)').remove();

    $.ajax({
      url: "card_input2_data_set.php",
      type: "POST",
      data: {
        function_name: "get_zaikoumei_datas",
        class_code: class_code
      },
      success: function(response){
        var zaikoumeiList = JSON.parse(response);
        var selected = $('#zkm_code' + no).val();
        let i = 1;
        $.each(zaikoumeiList, function(index, item) {
          $('#zaikoumeiList' + no).append($('<option>', {
            value: item.zkm_code,
            text: item.zkm_name,
            id: 'val_' + no + '_' + i,
          }));
          if (item.zkm_code == selected) {
            $('#val_' + no + '_' + i).prop('selected', true);
          }
          i++;
        });
      },
      error: function(xhr, status, error) {
        console.log('error');
      }
    });
  }

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
<style>
  .dropdown-menu {
    width: 180px;
  }
</style>