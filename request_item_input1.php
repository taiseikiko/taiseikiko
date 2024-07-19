<?php
session_start();
header('Program-id: estimate_entry.php');
header('Content-type: text/html; charset=utf-8');
require_once('function.php');
$_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
$dept_code = $_SESSION['department_code'];
include("request_item_input1_data_set.php");
include("header1.php");
?>
<main>
  <h3>【　依頼案件マスター保守　一覧　】</h3>
  <div class="container">
    <form class="row g-3" method="POST" id="request_item_form1">
      <table style="width:auto;">
        <tr>
            <div class="field-row">
            <td>
              <label class="common_label" for="request_dept">分類</label>
              <input type="text" id="kw_request_dept" name="kw_request_dept" value="<?= $kw_request_dept ?>">

              <button type="submit" style="margin-left:20px;background:#80dfff;" id="searchBtn" name="process" value="search" class="search_btn">検索</button>
            </td>
        </div>
        </tr>
      </table>
      
      <table class="tab1" style="margin-top:20px;">
        <tr>
          <th>案件№</th>
          <th>依頼案件名</th>
          <th>処理</th>
        </tr>
        <tr>
          <td colspan="8">
            <button type="submit" name="process" id="regBtn" value="new">新規登録</button>
          </td>
        </tr>
        <tbody>
          <?php if (!empty($request_datas) && isset($request_datas)) : 
            foreach ($request_datas as $item) :
          ?>
          <tr>
<<<<<<< HEAD
            <td><?= $item['request_dept_name'] ?></td>
            <td><?= $item['request_case_item_id'] ?></td>
            <td><?= $item['request_person_name'] ?></td>
            <td><?= $item['request_item_name'] ?></td>
            <td style="text-align:center">
              <button type="submit" class="updateBtn" 
              data-request_case_dept="<?= $item['request_case_dept'] ?>" 
              data-request_case_item_id="<?= $item['request_case_item_id'] ?>" 
=======
            <td><?= $item['request_item_id'] ?></td>
            <td><?= $item['request_item_name'] ?></td>
            <td style="text-align:center">
              <button type="submit" class="updateBtn" 
              data-request_dept="<?= $item['request_dept'] ?>" 
              data-request_item_id="<?= $item['request_item_id'] ?>" 
>>>>>>> main
              name="process" value="update">更新</button>
            </td>
          </tr>
          <?php 
            endforeach;
            endif; 
          ?>
<<<<<<< HEAD
          <input type="hidden" class="request_case_dept" name="request_case_dept" value="">
          <input type="hidden" class="request_case_item_id" name="request_case_item_id" value="">
=======
          <input type="hidden" class="request_dept" name="request_dept" value="">
          <input type="hidden" class="request_item_id" name="request_item_id" value="">
>>>>>>> main
        </tbody>
      </table>
    </form>
  </div>
</main>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript">
  
$(document).ready(function(){
  //新規登録の場合
  $('#regBtn').click(function() {
    $('#request_item_form1').attr('action', 'request_item_input2.php');
  });

  $(document).on('click', '.updateBtn', function() {
<<<<<<< HEAD
    var selectedId = $(this).data('request_case_dept');
    $('.request_case_dept').val(selectedId);
    var request_case_item_id = $(this).data('request_case_item_id');
    $('.request_case_item_id').val(request_case_item_id);
=======
    var request_dept = $(this).data('request_dept');
    var request_item_id = $(this).data('request_item_id');
    $('.request_dept').val(request_dept);
    $('.request_item_id').val(request_item_id);
>>>>>>> main

    $('#request_item_form1').attr('action', 'request_item_input2.php');
  });

});

/**-------------------------------------------------------------------------------------------------------------- */
</script>
<?php
include("footer.html");
<<<<<<< HEAD
?>
=======
?>
>>>>>>> main
