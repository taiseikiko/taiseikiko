<?php
  session_start();
  header('Program-id: estimate_entry.php');
  header('Content-type: text/html; charset=utf-8');
  require_once('function.php');
  include('sq_class_input2_data_set.php');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する                             */  
  include("header1.php");

  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

  // 初期設定 & データセット
  $process = '';  //処理
  $class_code = ''; //分類コード
  $class_name = $class_name_err = ''; //分類名
  $btn_name = '登録';  
  $datas = [];

  //一覧画面からPOSTを取得
  if (isset($_POST['process'])) {
    $process = $_POST['process'];
    
    //新規作成の場合
    if ($process == 'create') {
      //分類コード取得
      $class_code = getClassCode();
    } else {
      $btn_name = '更新';
      $class_code = $_POST['class_code'];

      //分類マスタからclass_nameを取得する
      $datas = getClassDatasByClassCode($class_code);
      if (!empty($datas)) {
        $class_name = $datas[0]['class_name'];
      }
    }
  }

  if (isset($_POST['submit'])) {
    $success = reg_or_upd_sq_class();
    if ($success) {
      echo "<script>
        window.location.href='sq_class_input1.php';
      </script>";
    } else {
      echo "<script>
        window.onload = function() { alert('失敗しました。'); }
      </script>";
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<main>
  <div class="pagetitle">
    <h3>分類マスター保守</h3>
    <div class="container">
      <form class="row g-3" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="sq_class_form" enctype="multipart/form-data">
        <input type="hidden" name="process" id="process" value="<?= $process ?>">
        <input type="hidden" name="success" id="success" value="<?= $success ?>">
        <table style="width:auto;">
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="class_code" >分類コード</label>
                <input type="text" id="class_code" name="class_code" value="<?= $class_code ?>" class="readonlyText" readonly>
              </div>
            </td>                
          </tr>
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="class_name" >分類名称</label>
                <input type="text" id="class_name" name="class_name" value="<?= $class_name ?>" maxlength="20">
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

</html>
<script src="assets/js/inquiry_ent.js"></script>
<script src="assets/js/inquiry_ent_check.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript">  
  $(document).ready(function(){
    // Handle return button click
    $('#returnBtn').click(function(event) {
      event.preventDefault();  // Prevent the default form submission
      if (confirm('一覧画面に戻ります．よろしいですか？')) {
        window.location.href = 'sq_class_input1.php';
      }
    });

    // Handle return button click
    // $("#returnBtn").click(function(){
    //     $("#sq_class_form").attr("action", "sq_class_input1.php");
    // });
    
    // Handle update button click
    // $("#upd_regBtn").click(function(){
    //     $("#sq_class_form").attr("action", "sq_class_input1_data_set.php");
    // });
  });

  //更新ボタンをクリックする時、チェックする
  document.getElementById('upd_regBtn').onclick = function(event) {
    var class_code = document.getElementById('class_code').value;
    var class_name = document.getElementById('class_name').value;
    var isErr = false;

    var sq_class = {
        class_code: class_code,
        class_name: class_name
    };

    if (class_name == '') {
      alert('「分類名称」を入力して下さい。');
      isErr = true;
    }

    if (class_name.length > 20) {
      alert('over 20');
      isErr = true;
    }

    if(!isErr) {
      //localStorageにデータ保存する
      //localStorage.setItem("sq_class", JSON.stringify(sq_class));
    } else {
      event.preventDefault();
    }
  }
  //
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
