<?php
session_start();
header('Program-id: estimate_entry.php');
header('Content-type: text/html; charset=utf-8');
require_once('function.php');
$_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
include('zk_division_input1_data_set.php');
include("header1.php");

?>

<main>
  <div class="pagetitle">
    <h3>材工名仕様マスター保守</h3>
  </div>
  <div class="container">
    <form class="row g-3" action="zk_division_input2.php" method="POST" name="zk_division_form" enctype="multipart/form-data" id="zk_division_form">
      <div class="scrollable-table-container">
        <table class="tab1">
          <thead>
            <tr>
              <th>材工仕様</th>
              <th>区分1</th>
              <th>区分2</th>
              <th>材工名仕様詳細</th>
              <th>処理</th>
            </tr>
            <tr id="createBtnRow">
              <td colspan="5" style="text-align:left"><button class="createBtn" name="process" value="create">新規作成</button>
              </td>
            </tr>
          </thead> 
          <tbody>
            <?php          
            foreach ($zk_datas as $zk_data) {
            ?>
              <tr>
                <td><?= $zk_data['zk_div_name'] ?></td>
                <td><?= $zk_data['zk_tp'] ?></td>
                <td><?= $zk_data['zk_no'] ?></td>
                <td><?= $zk_data['zk_div_data'] ?></td>
                <td style="text-align:center">
                <button class="updateBtn" name="process" value="update" id="update"
                  data-division="<?= $zk_data['zk_division'] ?>" 
                  data-div_name="<?= $zk_data['zk_div_name'] ?>" 
                  data-tp="<?= $zk_data['zk_tp'] ?>" 
                  data-no="<?= $zk_data['zk_no'] ?>" 
                  data-data="<?= $zk_data['zk_div_data'] ?>">更新</button>
                </td>
                <input type="hidden" class="count" value="<?= $count ?>">
              </tr>
            <?php  } ?>
          </tbody>         
        </table>
        <!-- Hidden inputs to send data to the next page -->
        <input type="hidden" name="zk_division" id="zk_division" value="">
        <input type="hidden" name="zk_div_name" id="zk_div_name" value="">
        <input type="hidden" name="zk_tp" id="zk_tp" value="">
        <input type="hidden" name="zk_no" id="zk_no" value="">
        <input type="hidden" name="zk_div_data" id="zk_div_data" value="">
      </div>
    </form><!-- Vertical Form -->
  </div>
</main><!-- End #main -->

<script src="assets/js/inquiry_ent.js"></script>
<script src="assets/js/inquiry_ent_check.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $("#create").click(function() {
      $("#zk_division_form").attr("action", "zk_division_input2.php");
    });
    let count = $(".count").val();

    //一覧データがない場合、新規作成ボタンを非表示にする
    if (count == undefined) {
      $("#createBtnRow").hide();
    }
    //更新ボタンを押下場合
    $('.updateBtn').click(function(event) {
        $('#zk_division').val($(this).data('division'));
        $('#zk_div_name').val($(this).data('div_name'));
        $('#zk_tp').val($(this).data('tp'));
        $('#zk_no').val($(this).data('no'));
        $('#zk_div_data').val($(this).data('data'));

        $("#zk_division_form").attr("action", "zk_division_input2.php");
    });
  });
</script>

<style>
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