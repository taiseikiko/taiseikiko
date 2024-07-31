<?php
  session_start();
  header('Program-id: estimate_entry.php');
  header('Content-type: text/html; charset=utf-8');
  require_once('function.php');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
  include('sq_class_input1_data_set.php');
  include("header1.php");

  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

  // 初期設定 & データセット
  $class_datas = array();

  //分類マスタからデータ取得する
  $class_datas = getClassDatas();
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
      <form class="row g-3" action="sq_class_input2.php" method="POST" name="inq_ent" enctype="multipart/form-data">
        <div class="scrollable-table-container">
          <table class="tab1">
            <thead>
              <tr>
                <th>分類コード</th>
                <th>分類名称</th>
                <th>処理</th>
              </tr>
              <tr>
                <td colspan="3" style="text-align:right"><button class="createBtn" name="process" value="create">新規作成</button></td>
              </tr>
            </thead>
            <tbody>
              <?php
                foreach ($class_datas as $class_data) {
              ?>
              <tr>
                <td><?php echo $class_data['class_code']; ?></td>
                <td><?php echo $class_data['class_name']; ?></td>
                <td style="text-align:center">
                  <button class="updateBtn" data-id="<?= $class_data['class_code'] ?>" name="process" value="update">更新</button></td>
                <input type="hidden" class="class_code" name="class_code" value="">
              </tr>
              <?php 
                }
              ?>
            </tbody>
          </table>
        </div>
      </form><!-- Vertical Form -->
    </div>
  </main><!-- End #main -->

</html>
<script src="assets/js/inquiry_ent.js"></script>
<script src="assets/js/inquiry_ent_check.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $('.updateBtn').click(function() {
      var selectedId = $(this).data('id');
      $('.class_code').val(selectedId);
    })  
  });

</script>

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
  
  .clearfix:after {
    clear: both;
    content: "";
    display: block;
    height: 0;
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
</style>
<?php
// フッターセット
footer_set();
?>
