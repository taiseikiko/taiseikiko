<?php
session_start();
header('Program-id: estimate_entry.php');
header('Content-type: text/html; charset=utf-8');
require_once('function.php');
$_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
$dept_code = $_SESSION['department_code'];

include("sales_request_input1_data_set.php");
include("header1.php");
$title = isset($_GET['title']) ? $_GET['title'] : '';
$sq_datas = get_sq_datas("", ""); 
?>
<main>
  <h3>【　営業依頼書：依頼承認　】</h3>
  <div class="container">
    <form id="searchForm" class="row g-3" action="sales_request_approve2.php?title=<?= $title ?>" method="POST">
      <table style="width:auto;">
        <tr>
          <td>
            <div class="field-row">
              <label class="common_label" for="tokuisaki">得意先</label>
              <input type="text" id="cust_name" name="cust_name">
              <input type="hidden" name="cust_code" id="cust_code">
              <input type="hidden" name="dept_id" value="<?= $dept_id ?>">
              <button type="button" class="search_btn" onclick="customer_open(event)">得意先検索</button>
            </div>
          </td>
        </tr>
        <tr>
          <td>
            <div class="field-row">
              <label class="common_label" for="tokuisaki">事業体</label>
              <input type="text" id="pf_name" name="pf_name">
              <input type="hidden" name="pf_code" id="pf_code">
              <button type="button" class="search_btn" onclick="public_office_open(event)">事業体検索</button>
            </div>
          </td>
        </tr>
      </table>
      
      <table class="tab1" style="margin-top:20px;">
        <tr>
          <th>得意先</th>
          <th>事業体</th>
          <th>件名</th>
          <th>処理状況</th>
          <th>担当者</th>
          <th>処理</th>
        </tr>

        <tbody id="sq_data_table">
        <?php foreach ($sq_datas as $item): ?>
        <tr>
          <td><?= $item['cust_name'] ?></td>
          <td><?= $item['pf_name'] ?></td>
          <td><?= $item['item_name'] ?></td>
          <td></td>
          <td><?= $item['employee_name'] ?></td>
          <td style="text-align:center">
            <button type="submit" class="updateBtn" data-sq_no="<?= $item['sq_no'] ?>" name="process" value="update">更新</button>
          </td>
          <input type="hidden" class="sq_no" name="sq_no" value="">
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </form>
  </div>
</main>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="assets/js/customer_ent.js"></script> 
<script src="assets/js/public_office_ent.js"></script>
<script type="text/javascript">
  
$(document).ready(function(){
  $('#cust_name, #pf_name').on('input change', function() {
    var cust_name = $('#cust_name').val();
    var pf_name = $('#pf_name').val();
    var dept_code = <?= $dept_code ?>;
    var title = "<?= $title ?>";
    
    $.ajax({
      type: 'POST',
      url: 'sales_request_input1_data_set.php',
      data: { 
        isReturn: false,
        cust_name: cust_name, 
        pf_name: pf_name,
        dept_code: dept_code,
        title: title
      },
      success: function(response) {
        $('#sq_data_table').html(response);
      }      
    });
  });

  $(document).on('click', '.updateBtn', function() {
    var selectedId = $(this).data('sq_no');
    $('.sq_no').val(selectedId);
  });
});

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

// localStorage.removeItem('sales_request_form');
</script>
<?php
include("footer.html");
?>
