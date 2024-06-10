<?php
  session_start();
  header('Program-id: estimate_entry.php');
  header('Content-type: text/html; charset=utf-8');
  require_once('function.php');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
  $title = isset($_GET['title']) ? $_GET['title'] : '';
  include("sales_request_input2_data_set.php");
  // ヘッダーセット
  include("header1.php");
?>

<main>
  <div class="pagetitle">
    <h3>【　営業依頼書：確認　】</h3>
    <?php include("common_sales_input2.php"); ?>
  </div>
</main><!-- End #main -->
</body>
</html>
<script src="assets/js/customer_ent.js"></script>
<script src="assets/js/public_office_ent.js"></script>
<script src="assets/js/sales_request_input_check.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    //一覧から新規作成を押下する場合
    $(".createBtn").click(function(){
      //localStorageにフォームデータを保存する
      saveFormData();

      //sales_request_check3.phpへ移動する
      $("#input2").attr("action", "sales_request_check3.php?title=<?= $title ?>");
    });

    //一覧画面から更新 or コピーボタンを押下する場合
    $(".updateBtn, .copyBtn").click(function(){
      //クリックされた行の営業依頼書行№	を取得する
      var selected = $(this).data('sq_line_no');

      //localStorageにフォームデータを保存する
      saveFormData();

      //sales_request_check3.phpへ移動する
      $("#input2").attr("action", "sales_request_check3.php?line="+selected+"&title=<?= $title ?>");
    });

    //アップロードボタンを押下する場合
    $("#upload").click(function(){
      //sq_attach_upload1.phpへ移動する
      $("#input2").attr("action", "sq_attach_upload1.php?from=sr");
    })

    //確認ボタンを押下する場合
    $(".approveBtn").click(function(){
      checkValidation(event);
      //sales_request_update.phpへ移動する
      $("#input2").attr("action", "sales_request_update.php?title=<?= $title ?>");
    })

    //戻るボタンを押下する場合
    $("#returnBtn").click(function(){
      $("#input2").attr("action", "sales_request_check1.php?title=<?= $title ?>");
    })
  });

  function getById(id) {
    return document.getElementById(id);
  }

  const myForm = getById("input2");
  //localStorageにフォームデータを保存する
  function saveFormData() {
    const formData = new FormData(myForm);
    const jsonData = JSON.stringify(Object.fromEntries(formData));
    localStorage.setItem('sales_request_form', jsonData);
  }

  //localStorageからフォームデータをセットする
  const formData = JSON.parse(localStorage.getItem('sales_request_form'));
  if (formData) {
    Object.keys(formData).forEach(key => {
      if (key !== 'uploaded_file') {
        myForm.elements[key].value = formData[key];
      }
    })
  }
</script>
<?php
// フッターセット
footer_set();
?>
