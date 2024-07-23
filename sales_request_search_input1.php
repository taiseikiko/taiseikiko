<?php
session_start();
header('Program-id: estimate_entry.php');
header('Content-type: text/html; charset=utf-8');
require_once('function.php');
$_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
$dept_code = $_SESSION['department_code'];
include("sales_request_search_input1_data_set.php");
include("header1.php");
$sq_datas = get_sq_datas("", ""); 
?>
<main>
  <h3>【　営業依頼書：検索　】</h3>

   <!-- PHP to display result if available -->

  <div class="container">
    <form id="searchForm" class="row g-3" action="sales_request_search_input2.php?title=search" method="POST" id="search1">
      
      <table style="width:auto;">
        <tr>
          <td>
            <div class="field-row">
              <label class="common_label" for="tokuisaki">依頼書№</label>
              <input type="text" id="sq_no" style="margin-left: 1rem;" name="sq_no" value="<?= $sq_no ?>">

              <label class="common_label" for="dept_name">事業所</label>
              <select class="dropdown-menu" style="margin-left: 1rem;" id="dept_name" name="dept_name">
                  <option value="">選択して下さい。</option>
                  <?php 
                    if (isset($officeList) && !empty($officeList)) {
                      foreach ($officeList as $item) {
                        $code = $item['office_code'];
                        $text = $item['office_name'];
                        $selected = ($code == $office_name) ? 'selected' : '';
                        echo "<option value='$code' $selected>$text</option>";
                      }
                    }
                  ?>
                </select>

              <label class="common_label" for="employee">担当者</label>
              <input type="text" id="employee" style="margin-left: 1rem;" name="employee" value="<?= $employee ?>">

              <label class="common_label" for="pf_name">事業体</label>
              <input type="text" id="pf_name" style="margin-left: 1rem;" name="pf_name" value="<?= $pf_name ?>">
            </div>
          </td>
        </tr>
        <tr>
          <td>
            <div class="field-row">
              <label class="common_label" for="zkm_name">材工名</label>
              <input type="text" style="margin-left: 1rem;" id="zkm_name" name="zkm_name" value="<?= $zkm_name ?>">

              <label class="common_label" for="size">サイズ</label>
              <input type="text" style="margin-left: 1rem;" id="size" name="size" value="<?= $size ?>">

              <label class="common_label" for="record_div">依頼内容</label>
              <select class="dropdown-menu" style="margin-left: 1rem;" id="record_div" name="record_div">
                <option value="">選択して下さい。</option>
                <option value="1">見積</option>
                <option value="2">図面</option>
              </select>

              <label class="common_label" for="status">処理状況</label>
              <select class="dropdown-menu" style="margin-left: 1rem;" id="status" name="status" <?= $status ?>>
                <option value="">選択して下さい。</option>
                <option value="1">受付</option>
                <option value="2">入力</option>
                <option value="3">確認</option>
                <option value="4">承認</option>
              </select>
            </div>
          </td>
        </tr>
      </table>
      <div class="scrollable-table-container">
        <table class="tab1" style="margin-top:20px;">
          <thead>
            <tr>
              <th>営業依頼書No</th>
              <th>事業所</th>
              <th>担当者</th>
              <th>事業体</th>
              <th>材工名</th>
              <th>サイズ</th>
              <th>依頼内容</th>
              <th>処理状況</th>
              <th>処理</th>
            </tr>
          </thead>
          <tbody id="sq_data_table">
          <?php foreach ($sq_datas as $item): 
            //print_r($sq_datas);?>
          <tr>
            <td><?= $item['sq_no'] . 'ー' . $item['sq_line_no'] ?></td>
            <td><?= $item['office_name'] ?></td>
            <td><?= $item['entrant_name'] ?></td>
            <td><?= $item['pf_name'] ?></td>
            <td><?= $item['zkm_name'] ?></td>
            <td><?= $item['size'] ?></td>
            <td><?= $item['record_div_nm'] ?></td>
            <td><?= $item['dept_name'] . $item['processing_status'] ?></td>
            <td style="text-align:center">
              <button type="submit" class="updateBtn" data-sq_no="<?= $item['sq_no'] ?>" data-sq_line_no="<?= $item['sq_line_no'] ?>" name="process2" value="detail">詳細</button>
            </td>
            <input type="hidden" class="sq_no" name="sq_no" value="">
            <input type="hidden" class="sq_line_no" name="sq_line_no" value="">
          </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </form>
  </div>
</main>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="assets/js/customer_ent.js"></script> 
<script src="assets/js/public_office_ent.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript">
  
$(document).ready(function(){
  $('#sq_no, #dept_name, #employee, #pf_name, #zkm_name, #size, #record_div, #status').on('input change', function() {
    var sq_no = $('#sq_no').val();
    var dept_name = $('#dept_name').val();
    var employee = $('#employee').val();
    var pf_name = $('#pf_name').val();
    var zkm_name = $('#zkm_name').val();
    var size = $('#size').val();
    var record_div = $('#record_div').val();
    var status = $('#status').val();
    
    $.ajax({
      type: 'POST',
      url: 'sales_request_search_input1_data_set.php',
      data: { 
        isReturn: false,
        sq_no: sq_no, 
        dept_name: dept_name,
        employee: employee,
        pf_name: pf_name,
        zkm_name: zkm_name,
        size: size,
        record_div: record_div,
        status: status,
      },
      success: function(response) {
        $('#sq_data_table').html(response);
      }      
    });    
  });

  $(document).on('click', '.updateBtn', function() {
    var sq_no = $(this).data('sq_no');
    var sq_line_no = $(this).data('sq_line_no');
    $('.sq_no').val(sq_no);
    $('.sq_line_no').val(sq_line_no);
  });

});

/**-------------------------------------------------------------------------------------------------------------- */
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
</style>
<?php
include("footer.html");
?>
