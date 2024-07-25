<?php
session_start();
header('Program-id: estimate_entry.php');
header('Content-type: text/html; charset=utf-8');
require_once('function.php');
$_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
$dept_code = $_SESSION['department_code'];
include("fwt_m_input1_data_set.php");
include("header1.php");
include("dialog.php");
$title = $_GET['title'] ?? '';
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
        <tr>
          <td colspan="11">
            <button type="submit" name="process" value="new">新規登録</button>
          </td>
        </tr>
        <tbody id="fwt_data_table">
        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
          <td style="text-align:center">
            <button type="submit" class="updateBtn" name="process" value="update">詳細</button>
          </td>
          <input type="hidden" class="" name="" value="">
        </tr>
        </tbody>
      </table>
    </form>
  </div>
</main>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="assets/js/customer_ent.js"></script> 
<script src="assets/js/public_office_ent.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript">

/**-------------------------------------------------------------------------------------------------------------- */
</script>
<?php
include("footer.html");
?>
