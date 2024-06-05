<?php
session_start();
require_once('function.php');
$_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
header('Program-id: estimate_entry.php');
header('Content-type: text/html; charset=utf-8');
include("header1.php");

$office_name = $_SESSION['office_name'];
$department_code = $_SESSION['department_code'];

// DB接続
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

$dept_id = null;
$group_options = [];
$employee_options = [];
$entrant = '';
$confirmor = '';
$approver = '';
$group_id = null;

// 部門IDを取得する
$sql = "SELECT dept_id, dept2_id FROM sq_dept WHERE sq_dept_code = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$department_code]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
  $dept_id = $row['dept_id'];
  $dept2_id = $row['dept2_id'];

  // sq_default_roleに既存のデータがあるかどうかを確認する
  $sql = "SELECT entrant, confirmor, approver, group_id FROM sq_default_role WHERE dept_id = ?";
  if ($group_id) {
    $sql .= " AND group_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$dept_id, $group_id]);
  } else {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$dept_id]);
  }
  $existing_data = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($existing_data) {
    $group_id = $existing_data['group_id'];
    $entrant = $existing_data['entrant'];
    $confirmor = $existing_data['confirmor'];
    $approver = $existing_data['approver'];
  }
  // print_r($_SESSION);
}

$button_text =  '更新';
?>

<main>
  <div class="pagetitle">
    <h3>部署内初期ルート設定</h3>
    <div class="container">
      <form class="row g-3" action="sq_default_role_update.php" method="POST" id="sq_default_role_form" enctype="multipart/form-data">
        <table class="responsive-table" style="width:auto;">
          <tr id="firstRow">
            <td>
              <div class="field-row">
                <label class="common_label" for="office_name">部署</label>
                <input type="text" value="<?= htmlspecialchars($office_name) ?>" id="office_name" name="office_name" class="readonlyText" readonly>

                <label class="common_label" for="group">グループ</label>
                <select name="group" id="group">
                  <option value="">選択してください</option>
                </select>
                <input type="hidden" name="group_id" id="group_id">
                <input type="hidden" name="dept_id" value="<?= htmlspecialchars($dept_id) ?>">
              </div>
            </td>
          </tr>
          <tr id="secondRow" style="display:none;">
            <td>
              <div class="field-row">
                <label class="common_label" for="entrant">入力者</label>
                <select name="entrant" id="entrant">
                  <option value="">選択してください</option>
                </select>

                <label class="common_label" for="confirmor">確認者</label>
                <select name="confirmor" id="confirmor">
                  <option value="">選択してください</option>
                </select>

                <label class="common_label" for="approver">承認者</label>
                <select name="approver" id="approver">
                  <option value="">選択してください</option>
                </select>
              </div>
            </td>
          </tr>
          <tr id="thirdRow" style="display:none;">
            <td>
              <div class="flex-container" style="margin-left:3rem">
                <div>
                  <!-- <button class="copyBtn" type="reset" id="resetBtn">フォームリセット</button> -->
                  <button class="updateBtn" id="updateBtn"><?= htmlspecialchars($button_text) ?></button>
                </div>
              </div>
            </td>
          </tr>
        </table>
      </form><!-- Vertical Form -->
    </div>
  </div>
</main>

<script src="assets/js/inquiry_ent.js"></script>
<script src="assets/js/inquiry_ent_check.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    // ページの読み込み時にグループを読み込む
    fetchGroupData();

    $('#group').change(function() {
      if ($(this).val()) {
        fetchEmployeeData($(this).val()); // 選択したグループIDを渡す
        $('#secondRow').show();
        $('#thirdRow').show();
        $(this).prop('disabled', true); 
      } else {
        $('#secondRow').hide();
        $('#thirdRow').hide();
      }
    });

    $('#resetBtn').click(function() {
      $('#group').prop('disabled', false);
      $('#group').val('');
      $('#secondRow').hide();
      $('#thirdRow').hide();
    });

    $('#sq_default_role_form').submit(function(e) {
      $('#group_id').val($('#group').val());
      if (!$('#entrant').val() && !$('#confirmor').val() && !$('#approver').val()) {
        alert('入力項目に不備があります。');
        e.preventDefault();
      }　else {
        let buttonText = $('#updateBtn').text();
        if (!confirm(buttonText + 'します、よろしいでしょうか？')) {
          e.preventDefault();
        }
      }
    });
  });

  function fetchGroupData() {
    $.ajax({
      url: 'sq_default_role_data_set.php',
      method: 'POST',
      data: {
        department_code: '<?= $_SESSION['department_code'] ?>'
      },
      dataType: 'json',
      success: function(response) {
        if (response.success) {
          let options = '';
          if (response.groups.length === 0) {
            options = '<option value="なし" selected disabled>なし</option>'; // disabled を追加する
          } else {
            options = '<option value="">選択してください</option>';
            $.each(response.groups, function(index, group) {
              if (group.id && group.name) {
                options += '<option value="' + group.id + '">' + group.name + '</option>';
              }
            });
          }
          $('#group').html(options);
        }
      }
    });
  }

  function fetchEmployeeData(groupId) {
    $.ajax({
      url: 'sq_default_role_data_set.php',
      method: 'POST',
      data: {
        fetch_employees: true,
        department_code: '<?= $_SESSION['department_code'] ?>',
        group_id: groupId
      },
      dataType: 'json',
      success: function(response) {
        if (response.success) {
          let entrantOptions = '<option value="">選択してください</option>';
          let confirmorOptions = '<option value="">選択してください</option>';
          let approverOptions = '<option value="">選択してください</option>';

          $.each(response.entrants, function(index, employee) {
            entrantOptions += '<option value="' + employee.code + '"' + (employee.code == response.existingData.entrant ? ' selected' : '') + '>' + employee.name + '</option>';
          });

          $.each(response.confirmors, function(index, employee) {
            confirmorOptions += '<option value="' + employee.code + '"' + (employee.code == response.existingData.confirmor ? ' selected' : '') + '>' + employee.name + '</option>';
          });

          $.each(response.approvers, function(index, employee) {
            approverOptions += '<option value="' + employee.code + '"' + (employee.code == response.existingData.approver ? ' selected' : '') + '>' + employee.name + '</option>';
          });

          $('#entrant').html(entrantOptions);
          $('#confirmor').html(confirmorOptions);
          $('#approver').html(approverOptions);
        }
      }
    });
  }
</script>

<?php
// フッターセット
footer_set();
?>

<style>
  .updateBtn {
    margin: 2px 1px;
    width: 120px;
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
  .responsive-table {
    width: 100%;
    border-collapse: collapse;
  }
  @media only screen and (max-width: 800px) {
    .pagetitle,
    .container,
    .field-row {
      width: 100%;
      padding: 0;
    }

    .createBtn,
    .updateBtn {
      width: 120px;
    }

    .flex-container {
      flex-direction: column;
      align-items: flex-start;
    }

    .flex-container>div {
      width: 100%;
      text-align: center;
    }
  }

  @media only screen and (max-width: 500px) {
    .pagetitle,
    .container,
    .field-row {
      width: 100%;
    }

    .createBtn,
    .updateBtn {
      width: 120px;
    }

    .flex-container>div {
      width: 100%;
      text-align: center;
    }

    .field-row label,
    .field-row input,
    .field-row select {
      width: 100%;
      margin: 5px 0;
    }
  }
</style>