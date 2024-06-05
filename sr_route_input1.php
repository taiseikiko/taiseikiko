<?php
session_start();
header('Program-id: estimate_entry.php');
header('Content-type: text/html; charset=utf-8');
require_once('function.php');
$_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
include('sr_route_input1_data_set.php');
include("header1.php");

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
    <h3>部署ルートマスター保守</h3>
    <div class="container">
      <form class="row g-3" action="sr_route_input2.php" method="POST" name="inq_ent" enctype="multipart/form-data" id="sr_route_form">
        <div class="scrollable-table-container">
          <table class="tab1">
            <tr>
              <th>ルートID</th>
              <th>ルート1部署</th>
              <th>ルート2部署</th>
              <th>ルート3部署</th>
              <th>ルート4部署</th>
              <th>ルート5部署</th>
              <th>処理</th>
            </tr>
            <tr id="createBtnRow">
              <td colspan="7" style="text-align:left"><button class="createBtn" name="process" value="create">新規作成</button>
              </td>
            </tr>
            <?php

            foreach ($dept_datas as $dept_data) {
            ?>
              <tr>
                <td style="text-align: center;"><?= $dept_data['route_id'] ?></td>
                <td><?= $dept_data['route1_dept'] ?></td>
                <td><?= $dept_data['route2_dept'] ?></td>
                <td><?= $dept_data['route3_dept'] ?></td>
                <td><?= $dept_data['route4_dept'] ?></td>
                <td><?= $dept_data['route5_dept'] ?></td>
                <td style="text-align:center">
                  <button class="updateBtn" name="process" value="update" data-id="<?= $dept_data['route_id'] ?>">更新</button>
                  <input type="hidden" class="route_id" name="route_id" value="">
                </td>
                <input type="hidden" class="count" value="<?= $count ?>">
              </tr>
            <?php  } ?>
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
  $(document).ready(function() {
    //Handle return button click
    $("#create").click(function() {
      $("#sr_route_form").attr("action", "sr_route_input2.php");
    });
    let count = $(".count").val();

    //一覧データがない場合、新規作成ボタンを非表示にする
    if (count == undefined) {
      $("#createBtnRow").hide();
    }
    //更新ボタンを押下場合
    $('.updateBtn').click(function() {
      var selectedId = $(this).data('id');
      $('.route_id').val(selectedId);
    })
  });
</script>

<style>
  .scrollable-table-container {
    width: fit-content;
    height:700px;
    overflow: auto;
  }
  input[type=text],
  input[type=checkbox],
  select {
    /* width: 50%; */
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
  }

  #common_label {
    width: 100px;
    text-align: start;
  }

  input.readonlyText {
    background-color: #ffffe0;
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
    width: 110px;
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

  @media only screen and (max-width:800px) {

    .pagetitle,
    .container,
    .field-row {
      width: 80%;
      padding: 0;
    }

    .createBtn {
      width: 40px;
    }
  }

  @media only screen and (max-width:500px) {

    .pagetitle,
    .container,
    .field-row {
      width: 100%;
    }

    .createBtn {
      width: 40px;
    }
  }
</style>
<?php
// フッターセット
footer_set();
?>