<?php
  session_start();
  require_once('function.php');
  header('Content-type: text/html; charset=utf-8');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
  $msg = "";
  include("inq_sales_request_data_set.php");

  // ヘッダーセット
  header_set1();
?>
<!DOCTYPE html>
<html>
<body>
  <div class="window active">
    <div class="title-bar">
      <div class="title-bar-text">営業依頼書検索</div>
      <div class="title-bar-controls">
      </div>
    </div>
    <div class="window-body has-space" style="min-height:760px; over-flow:hidden;">
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
            
            </tbody>
          </table>
        </div>
      </form>
      <?php
        //Json Encode
        // $employee_cd = json_encode($employee_code);  
        // $employee_nm = json_encode($employee_name);  
        // $dept_cd = json_encode($department_code);  
        // $dept_nm = json_encode($dept_name);  
        // $op_cd = json_encode($office_position_code);  
        // $op_nm = json_encode($op_name);  
      ?>
  </div>
</div>
</body>
</html>
<script src="assets/js/sales_request_ent.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
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
      url: 'inq_sales_request_data_set.php',
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

  $(document).on('click', '.selectBtn', function() {
    var sq_no = $(this).data('sq_no');
    var sq_line_no = $(this).data('sq_line_no');
    inq_ent(sq_no, sq_line_no);
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
</style>


