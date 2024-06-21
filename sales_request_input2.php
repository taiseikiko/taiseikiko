<?php
  session_start();
  header('Program-id: estimate_entry.php');
  header('Content-type: text/html; charset=utf-8');
  require_once('function.php');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
  $title = $_GET['title'] ?? '';
  $dept_code = $_SESSION['department_code'];
  include("sales_request_input2_data_set.php");
  // ヘッダーセット
  include("header1.php");
?>

<main>
  <div class="pagetitle">
    <h3>営業依頼書：依頼入力</h3>
    <?php include("common_sales_input2.php"); ?>
  </div>
</main><!-- End #main -->
</body>
</html>
<script src="assets/js/customer_ent.js"></script>
<script src="assets/js/public_office_ent.js"></script>
<script src="assets/js/sales_request_input_check.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    //一覧から新規作成を押下する場合
    $(".createBtn").click(function(){
      //localStorageにフォームデータを保存する
      // saveFormData();

      //sales_request_input3.phpへ移動する
      $("#input2").attr("action", "sales_request_input3.php?title=<?= $title ?>");
    });

    //一覧画面から更新 or コピーボタンを押下する場合
    $(".updateBtn, .copyBtn").click(function(){
      //クリックされた行の営業依頼書行№	を取得する
      var selected = $(this).data('sq_line_no');

      //localStorageにフォームデータを保存する
      // saveFormData();

      //sales_request_input3.phpへ移動する
      $("#input2").attr("action", "sales_request_input3.php?line="+selected+"&title=<?= $title ?>");
    });

    //アップロードボタンを押下する場合
    $("#upload").click(function(){
      //sq_attach_upload1.phpへ移動する
      $("#input2").attr("action", "sq_attach_upload1.php?from=sr");
    })

    //確認ボタンを押下する場合
    $(".approveBtn").click(function(event){
      var title = $('#title').val();
      var name;
      if (title == "input") {
        name = '入力';
      } else if (title == "check") {
        name = '確認';
      } else {
        name = '承認';
      }
      //確認メッセージを書く
      var msg = "営業依頼書を"+name+"します？よろしいですか？";
      //何の処理科を書く
      var process = "update";
      //確認Dialogを呼ぶ
      openModal(msg, process);
    })
    
    //戻るボタンを押下する場合
    $("#returnBtn").click(function(event) {
      //確認メッセージを書く
      var msg = "前の画面に戻します。よろしいですか？";
      //何の処理科を書く
      var process = "return";
      //確認Dialogを呼ぶ
      openModal(msg, process);
    });

    //はいボタンを押下する場合
    $("#okBtn").click(function(event) {
      var process = $("#btnProcess").val();
      //戻る処理の場合
      if (process == "return") {
        $("#input2").attr("action", "sales_request_input1.php?title=<?= $title ?>");
      }
      //ヘッダ更新処理の場合
      else if (process == "update") {
        checkValidation(event);
        
        $("#okBtn").attr("name", "submit");
        //sales_request_update.phpへ移動する
        $("#input2").attr("action", "sales_request_update.php?title=<?= $title ?>");
      }
    });

    function openModal(msg, process) {
      event.preventDefault();
      //何の処理かをセットする
      $("#btnProcess").val(process);
      //確認メッセージをセットする
      $("#confirm-message").text(msg);
      //確認Dialogを呼ぶ
      $("#confirm").modal({backdrop: false});
    }
  });

  function getById(id) {
    return document.getElementById(id);
  }

  // const myForm = getById("input2");
  //localStorageにフォームデータを保存する
  // function saveFormData() {
  //   const formData = new FormData(myForm);
  //   const jsonData = JSON.stringify(Object.fromEntries(formData));
  //   localStorage.setItem('sales_request_form', jsonData);
  // }

  //localStorageからフォームデータをセットする
  // const formData = JSON.parse(localStorage.getItem('sales_request_form'));
  // if (formData) {
  //   Object.keys(formData).forEach(key => {
  //     if (key !== 'uploaded_file') {
  //       myForm.elements[key].value = formData[key];
  //     }
  //   })
  // }
</script>
<?php
// フッターセット
footer_set();
?>
