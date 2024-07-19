<?php
session_start();
header('Program-id: estimate_entry.php');
header('Content-type: text/html; charset=utf-8');
require_once('function.php');
$_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
include('ec_division_input1_data_set.php');
include("header1.php");

// DB接続
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

// 初期設定 & データセット
$ec_datas = array();
$selected_code_key = '';

// Get selected value from dropdown
if (isset($_POST['spec_name'])) {
    $selected_code_key = $_POST['spec_name'];
    $ec_datas = getEcDatasByCodeKey($selected_code_key);
}

// Function to get filtered data based on code key
function getEcDatasByCodeKey($code_key) {
    global $pdo;
    $sql = "SELECT * FROM ec_code_master WHERE code_key = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$code_key]);
    return $stmt->fetchAll();
}
?>

<main>
  <div class="pagetitle">
    <h3>既存工事実績マスター保守</h3>
  </div>
  <div class="container">
    <form class="row g-3" method="POST" name="inq_ent" enctype="multipart/form-data" id="ec_division_form">
      <div class="field-row">
        <label class="common_label" for="spec_name">仕様名</label>
        <select name="spec_name" onchange="submit()" required>
          <option value="">※選択して下さい。</option>
          <?php 
          foreach ($ec_names as $key => $name) {
              $selected = ($selected_code_key == $key) ? 'selected' : '';
              echo "<option value='" . htmlspecialchars($key, ENT_QUOTES, 'UTF-8') . "' $selected>" . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . "</option>";
          }
          ?>
        </select>
      </div>
    </form>
    
    <div class="scrollable-table-container">
      <table class="tab1">
        <thead>
          <tr>
            <th>仕様名</th>
            <th>コードNo</th>
            <th>コード名</th>
            <th>処理</th>
          </tr>
          <tr id="createBtnRow">
            <td colspan="4" style="text-align:left"><button type="button" class="createBtn" id="create" name="process" value="create">新規作成</button></td>
            <input type="hidden" id="hid_spec_name" class="spec_name" name="spec_name" value="<?= $selected_code_key ?>">
          </tr>
        </thead>
        <tbody>
          <?php
          if (isset($ec_datas)) {
            foreach ($ec_datas as $ec_data) {
              $code_key = $ec_data['code_key'];
              $spec_name = isset($ec_names[$code_key]) ? $ec_names[$code_key] : '';
          ?>
          <tr>
            <td><?= htmlspecialchars($spec_name, ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($ec_data['code_no'], ENT_QUOTES, 'UTF-8') ?></td>
            <td><?= htmlspecialchars($ec_data['code_name'], ENT_QUOTES, 'UTF-8') ?></td>
            <td style="text-align:center"><button type="button" class="updateBtn" data-id="<?= htmlspecialchars($ec_data['code_key'], ENT_QUOTES, 'UTF-8') ?>" name="process" value="update">更新</button></td>
            <input type="hidden" class="code_key" name="code_key" value="">
          </tr>
          <?php } } ?>
        </tbody>
      </table>
    </div>
  </div>
</main><!-- End #main -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $("#create").click(function(){
      $("#ec_division_form").append('<input type="hidden" name="process" value="create">');
      $("#ec_division_form").attr("action", "ec_division_input2.php");
      $("#ec_division_form").submit();
    });

    //一覧データがない場合、新規作成ボタンを非表示にする
    if ($("#hid_spec_name").val() == '') {
      $("#createBtnRow").hide();
    }
    
    $(".updateBtn").click(function(){
      var selectedId = $(this).data('id');
      $('.code_key').val(selectedId);
      $("#ec_division_form").append('<input type="hidden" name="process" value="update">');
      $("#ec_division_form").append('<input type="hidden" name="code_key" value="' + selectedId + '">');
      $("#ec_division_form").attr("action", "ec_division_input2.php");
      $("#ec_division_form").submit();
    });
  });
</script>

<style>
  .scrollable-table-container {
    width: fit-content;
    height: 700px;
    overflow: auto;
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
    width: 120px;
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
</style>
<?php
// フッターセット
footer_set();
?>
