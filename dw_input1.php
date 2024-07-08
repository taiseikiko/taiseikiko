<?php
session_start();
header('Program-id: estimate_entry.php');
header('Content-type: text/html; charset=utf-8');
require_once('function.php');
$_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
$dept_code = $_SESSION['department_code'];
include("dw_input1_data_set.php");
include("header1.php");
?>
<main>
  <h3>【　図面管理　一覧　】</h3>

   <!-- PHP to display result if available -->

  <div class="container">
    <form class="row g-3" method="POST" id="dw_input_form">
      <table style="width:auto;">
        <tr>
          <td>
            <div class="field-row">
              <label class="common_label" for="class_code">分類</label>
              <input type="text" id="class_code" name="class_code" value="<?= isset($class_code) ? htmlspecialchars($class_code) : '' ?>">

              <label class="common_label" for="zkm_code">材工名</label>
              <input type="text" id="zkm_code" name="zkm_code" value="<?= isset($zkm_code) ? htmlspecialchars($zkm_code) : '' ?>">

              <label class="common_label" for="size">サイズ</label>
              <input type="text" id="size" name="size" value="<?= isset($size) ? htmlspecialchars($size) : '' ?>">

              <label class="common_label" for="joint">接合形状</label>
              <input type="text" id="joint" name="joint" value="<?= isset($joint) ? htmlspecialchars($joint) : '' ?>">

              <button type="submit" style="margin-left:20px;" id="searchBtn" name="process" value="search" class="search_btn">検索</button>
            </div>
          </td>
        </tr>
      </table>
      
      <table class="tab1" style="margin-top:20px;">
        <tr>
          <th>分類</th>
          <th>材工名</th>
          <th>サイズ</th>
          <th>接合形状</th>
          <th>仕様</th>
          <th>工事関連図</th>
          <th>図面</th>
          <th>最終図面更新日</th>
          <th>処理</th>
        </tr>
        <tr>
          <td colspan="9">
            <button type="submit" name="process" id="regBtn" value="new">新規登録</button>
          </td>
        </tr>
        <tbody>
        <?php foreach ($dw_datas as $item): ?>
        <tr>
          <td><?= $item['class_code'] ?></td>
          <td><?= $item['zkm_code'] ?></td>
          <td><?= $item['size'] ?></td>
          <td><?= $item['joint'] ?></td>
          <td><?= $item['specification'] ?></td>
          <td></td>
          <td></td>
          <td><?= $item['upd_date'] ?></td>
          <td style="text-align:center">
            <button type="submit" class="updateBtn" data-dw_no="<?= $item['dw_no'] ?>" name="process" value="update">更新</button>
          </td>
          <input type="hidden" class="dw_no" name="dw_no" value="">
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <?php 
        if (count($dw_datas) <= 0) {
          echo "<div><h4 style='font-size: 12px;'>表示するデータがございません。</h4></div>";
        }
      ?>
    </form>
  </div>
</main>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="assets/js/customer_ent.js"></script> 
<script src="assets/js/public_office_ent.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript">
  
$(document).ready(function(){
  $('#regBtn').click(function() {
    $('#dw_input_form').attr('action', 'dw_input2.php');
  });

  $(document).on('click', '.updateBtn', function() {
    var selectedId = $(this).data('sq_no');
    $('.sq_no').val(selectedId);
  });

});

/**-------------------------------------------------------------------------------------------------------------- */

function handleWindowClose() {
  $.ajax({
    type: 'POST',
    url: 'sales_request_input1_data_set.php',
    data: { 
      return: false,
      cust_name: cust_name, 
      pf_name: pf_name },
    success: function(response) {
      $('#sq_data_table').html(response);
    }
    
  });
}

/**-------------------------------------------------------------------------------------------------------------- */
</script>
<?php
include("footer.html");
?>
