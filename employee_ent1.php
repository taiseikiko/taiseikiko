<?php
  session_start();
  header('Program-id: estimate_entry.php');
  header('Content-type: text/html; charset=utf-8');
  require_once('function.php');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
  include('employee_ent1_data_set.php');
  include("header1.php");

  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
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
    <h3>社員マスター保守</h3>
    <div class="container">
      <form class="row g-3" method="POST" name="inq_ent" enctype="multipart/form-data" id="emp_ent1">
        <table style="width:auto;">
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="emp_nm">社員名</label>
                <input type="text" id="emp_nm" name="emp_nm" value="<?= $emp_nm ?>">

                <button type="submit" id="search_btn" class="search_btn" style="margin-left: 11px;" name="process" value="search">社員検索 </button>
              </div>
            </td>
          </tr>
        </table>

        <div class="scrollable-table-container">
          <table class="tab1">
            <thead>
              <tr>
                <th>社員コード</th>
                <th>社　員　名</th>
                <th>部　署</th>
                <th>役　職</th>
                <th>処理</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td colspan="6">
                  <button type="submit" name="process" id="regBtn" value="new">新規登録</button>
                </td>
              </tr>
              <?php
              if (!empty($emp_datas) && isset($emp_datas)) {
                foreach ($emp_datas as $item) {
              ?>
              <tr>
                <td><?= $item['employee_code'] ?></td>
                <td><?= $item['employee_name'] ?></td>
                <td><?= $item['dept_name'] ?></td>
                <td><?= $item['op_name'] ?></td>
                <td><button type="submit" class="updateBtn" data-employee_code="<?= $item['employee_code'] ?>" name="process" value="update">更新</button></td>
                <input type="hidden" class="employee_code" name="employee_code" value="">
              </tr>
              <?php 
                } } else { ?>
                  <td colspan="15"><b>表示するデータがございません。</b></td>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $('#regBtn').click(function() {
      $('#emp_ent1').attr('action', 'employee_ent2.php');
    });

    $(document).on('click', '.updateBtn', function() {
      var selectedId = $(this).data('employee_code');
      $('.employee_code').val(selectedId);
      $('#emp_ent1').attr('action', 'employee_ent2.php');
    });
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
