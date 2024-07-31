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
      <input type="hidden" name="private" value="<?= $private ?>">
      <table class="responsive-table">
        <tr>
          <td>
            <div class="field-row">
              <label class="common_label" for="class_name">分類</label>
              <input type="text" id="class_name" name="class_name" value="<?= $class_name ?>">

              <label class="common_label" for="zkm_name">材工名</label>
              <input type="text" id="zkm_name" name="zkm_name" value="<?= $zkm_name ?>">

            </div>
          </td>
        </tr>
        <tr>
          <td>
            <div class="field-row">
              <label class="common_label" for="size">サイズ</label>
              <input type="text" id="size" name="size_name" value="<?= $size_name ?>">

              <label class="common_label" for="joint">接合形状</label>
              <input type="text" id="joint" name="joint_name" value="<?= $joint_name ?>">

            </div>
          </td>
        </tr>
        <tr style="height:10px;"></tr>
        <tr>
          <td class="button-container">
            <div class="field-row text-center">
              <button type="submit" id="searchBtn" name="process" value="search" class="search_btn">検索</button>

              <button type="submit" id="clearBtn" name="process" value="clear" class="clear_btn">クリア</button>
            </div>
          </td>
        </tr>
      </table>

      <div class="scrollable-table-container">
        <table class="tab1">
          <thead>
            <tr>
              <th>図面No</th>
              <th>登録者</th>
              <th>ステータス</th>
              <th>分類</th>
              <th>材工名</th>
              <th>サイズ</th>
              <th>接合形状</th>
              <th>種類</th>
              <th>最終図面更新日</th>
              <th>処理</th>
            </tr>
            <?php if (!$private) { ?>
              <tr>
                <td colspan="10">
                  <button type="submit" name="process" id="regBtn" value="new">新規登録</button>
                </td>
              </tr>
            <?php } ?>
          </thead>
          <tbody id="resultsTableBody">
            <?php foreach ($dw_datas as $item) : ?>
              <tr>
                <td><?= $item['dw_no'] ?></td>
                <td><?= $item['client_name'] ?></td>
                <td><?= $item['status'] ?></td>
                <td><?= $item['class_name'] ?></td>
                <td><?= $item['zkm_name'] ?></td>
                <td><?= $item['size'] ?></td>
                <td><?= $item['joint'] ?></td>
                <td><?= $item['dw_div2'] ?></td>
                <td><?= $item['upd_date'] ?></td>
                <td style="text-align:center">
                  <button type="submit" class="updateBtn" data-dw_no="<?= $item['dw_no'] ?>" name="process" value="update">更新</button>
                </td>
                <input type="hidden" class="dw_no" name="dw_no" value="">
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <!-- 検索後データがない場合 -->
        <?= $search_result?> 
      </div>
        
    </form>
  </div>
</main>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="assets/js/customer_ent.js"></script>
<script src="assets/js/public_office_ent.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#regBtn').click(function() {
      $('#dw_input_form').attr('action', 'dw_input2.php');
    });

    $(document).on('click', '.updateBtn', function() {
      var selectedId = $(this).data('dw_no');
      $('.dw_no').val(selectedId);
      $('#dw_input_form').attr('action', 'dw_input2.php');
    });

    $('#clearBtn').click(function() {
      $('#class_name').val('');
      $('#zkm_name').val('');
      $('#size').val('');
      $('#joint').val('');
      $('#resultsTableBody').empty();
      // process field を 'clear' に設定して form　submit
      // $('<input>').attr({
      //   type: 'hidden',
      //   name: 'process',
      //   value: 'clear'
      // }).appendTo('#dw_input_form');
      // $('#dw_input_form').submit();
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
        pf_name: pf_name
      },
      success: function(response) {
        $('#sq_data_table').html(response);
      }

    });
  }

  /**-------------------------------------------------------------------------------------------------------------- */
</script>
<style>
  .responsive-table {
    width: auto;
  }  
  .button-container {
    display: flex;
    justify-content: center;
  }

  .scrollable-table-container {
    width: fit-content;
    height:550px;
    overflow: auto;
  }

  thead th {
    position: sticky;
    top: 0; 
    z-index: 1;
  }

  .search_btn,
  .clear_btn {
    margin: 0 10px;
  }

  .search_btn {
    background: #80dfff;
  }

  @media (max-width: 768px) {
  .field-row {
    flex-direction: column;
  }

  .field-row input {
    flex: 1 1 100%;
  }

  .search_btn,
  .clear_btn {
    flex: 1 1 100%;
  }
}

@media (max-width: 480px) {
  .common_label {
    font-size: 0.9em;
  }

  .search_btn,
  .clear_btn {
    font-size: 0.9em;
  }
}
</style>
<?php
include("footer.html");
?>