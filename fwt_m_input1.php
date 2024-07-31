<?php
session_start();
header('Program-id: estimate_entry.php');
header('Content-type: text/html; charset=utf-8');
require_once('function.php');
$_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
$dept_code = $_SESSION['department_code'];
$title = $_GET['title']?? '';
include("fwt_m_input1_data_set.php");
include("header1.php");

$fwt_datas = get_fwt_datas($title, "", "");

// Mapping for class
$class_map = [
  1 => '工場見学',
  2 => '立会検査',
  3 => '技術研修'
];

// Mapping for status
$status_map = [
  1 => '仮予約済',
  2 => '日程入力済',
  3 => '本予約済',
  4 => '関係部署確認済',
  5 => '営業管理確認済',
  6 => '完了',
  7 => '却下'
];

// Apply mappings to data
foreach ($fwt_datas as &$item) {
  $item['class'] = $class_map[$item['class']] ?? $item['class'];
  $item['status'] = $status_map[$item['status']] ?? $item['status'];
}
unset($item); // break the reference with the last element

$result = $_GET['result'] ?? '';
?>
<main>
  <h3>【　見学、立会、研修依頼一覧　】</h3>

  <!-- PHP to display result if available -->

  <div class="container">
    <form id="searchForm" class="row g-3" action="fwt_m_input2.php?title=<?= $title ?>" method="POST" id="fwt_m_input1_form">
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
          <th>申請者</th>
          <th>種類</th>
          <th>受注官庁</th>
          <th>第１候補日</th>
          <th>時間</th>
          <th>第２候補日</th>
          <th>時間</th>
          <th>第３候補日</th>
          <th>時間</th>
          <th>状況</th>
          <th>詳細</th>
        </tr>
        <!-- <tr>
          <td colspan="11">
            <button type="submit" name="process" value="new">新規登録</button>
          </td>
        </tr> -->
        <tbody id="fwt_data_table">
          <?php foreach ($fwt_datas as $item) : ?>
            <tr>
              <td><?= htmlspecialchars($item['employee_name']) ?></td>
              <td><?= htmlspecialchars($item['class']) ?></td>
              <td><?= htmlspecialchars($item['pf_name']) ?></td>
              <td><?= htmlspecialchars($item['candidate1_date']) ?></td>
              <td><?= htmlspecialchars($item['candidate1_start']) ?><?= htmlspecialchars($item['candidate1_end']) ?></td>
              <td><?= htmlspecialchars($item['candidate2_date']) ?></td>
              <td><?= htmlspecialchars($item['candidate2_start']) ?><?= htmlspecialchars($item['candidate2_end']) ?></td>
              <td><?= htmlspecialchars($item['candidate3_date']) ?></td>
              <td><?= htmlspecialchars($item['candidate3_start']) ?><?= htmlspecialchars($item['candidate3_end']) ?></td>
              <td><?= htmlspecialchars($item['status']) ?></td>
              <td style="text-align:center">
                <button type="submit" class="updateBtn" name="process" value="update" data-fwt_m_no=<?= $item['fwt_m_no'] ?>>詳細</button>
              </td>
              <input type="hidden" class="fwt_m_no" name="fwt_m_no">
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php
      if (count($fwt_datas) <= 0) {
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
  $(document).ready(function() {
    $('#cust_name, #pf_name').on('input change', function() {
      var cust_name = $('#cust_name').val();
      var pf_name = $('#pf_name').val();
      var dept_code = <?= $dept_code ?>;


      $.ajax({
        type: 'POST',
        url: 'fwt_m_input1_data_set.php',
        data: {
          isReturn: false,
          cust_name: cust_name,
          pf_name: pf_name,
          dept_code: dept_code,
        },
        success: function(response) {
          $('#fwt_data_table').html(response);
        }
      });
    });

    $(document).on('click', '.updateBtn', function() {
      var selectedId = $(this).data('fwt_m_no');
      $('.fwt_m_no').val(selectedId);
    });

  });

  /**-------------------------------------------------------------------------------------------------------------- */

  function handleWindowClose() {
    $.ajax({
      type: 'POST',
      url: 'fwt_m_input1_data_set.php',
      data: {
        return: false,
        cust_name: cust_name,
        pf_name: pf_name
      },
      success: function(response) {
        $('#fwt_data_table').html(response);
      }

    });
  }

  /**-------------------------------------------------------------------------------------------------------------- */
</script>
<?php
include("footer.html");
?>