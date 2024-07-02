<?php
session_start();
require_once('function.php');
$_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
header('Program-id: estimate_entry.php');
header('Content-type: text/html; charset=utf-8');
include("header1.php");
include("sq_default_role_data_set.php");

$office_name = $_SESSION['office_name'];
$department_code = $_SESSION['department_code'];

// dept_id を取得
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
$sql = "SELECT dept_id FROM sq_dept WHERE sq_dept_code = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$department_code]);
$dept_id = $stmt->fetchColumn();

$button_text = '更新';
?>

<main>
  <div class="pagetitle">
    <h3>部署内初期ルート設定</h3>
    <div class="container">
      <form class="row g-3" action="sq_default_role_update.php" method="POST" id="sq_default_role_form" enctype="multipart/form-data">
        <input type="hidden" name="department_code" id="department_code" value="<?= $department_code ?>">
        <input type="hidden" name="dept_id" id="dept_id" value="<?= htmlspecialchars($dept_id) ?>">
        <table class="responsive-table" style="width:auto;">
          <tr id="firstRow">
            <td>
              <div class="field-row">
                <label class="common_label" for="office_name">部署</label>
                <input type="text" value="<?= htmlspecialchars($office_name) ?>" id="office_name" name="office_name" class="readonlyText" readonly>
                <label class="common_label" for="group">グループ </label>
                <select class="dropdown-menu" id="group" name="group">
                  <option value="" class="">選択して下さい。</option>
                  <?php
                  if (isset($group_datas) && !empty($group_datas)) {
                    foreach ($group_datas as $item) {
                      $code = $item['text2'];
                      $text = $item['text3'];
                      $selectedGroup = ($code == $group) ? 'selected' : '';
                      echo "<option value='$code' $selectedGroup>$text</option>";
                    }
                  } else {
                    echo "<option value='' class=''>なし</option>";
                  }
                  ?>
                </select>
              </div>
              <input type="hidden" name="group_id" id="group_id">
            </td>
          </tr>
          <tr id="secondRow" style="display:none;">
            <td>
              <div class="field-row">
                <label class="common_label" for="entrant">入力者</label>
                <select name="entrant" id="entrant">
                  <option value="" class="">選択して下さい。</option>
                </select>

                <label class="common_label" for="confirmer">確認者</label>
                <select name="confirmer" id="confirmer">
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    $('#group').change(function() {
      var group = $(this).val();
      var dept_id = $('#dept_id').val();

      $('#secondRow').show();
      $('#thirdRow').show();
      $(this).prop('disabled', true);

      fetchInitialData(dept_id, group);
      fetchData(group); // Load the dropdown data as before
    });

    function fetchInitialData(dept_id, group_id) {
      $.ajax({
        url: "sq_default_role_data_set.php",
        type: "POST",
        data: {
          dept_id: dept_id,
          group_id: group_id,
          functionName: "getInitialData"
        },
        success: function(response) {
          try {
            var data = JSON.parse(response);
            if (data) {
              loadExistingRoleData(data);
            }
          } catch (e) {
            console.error("Invalid JSON response: ", response);
          }
        },
        error: function(xhr, status, error) {
          console.error("AJAX Error: ", error);
        }
      });
    }

    function fetchData(group) {
      $('#entrant option:not(:first-child)').remove();
      $('#confirmer option:not(:first-child)').remove();
      $('#approver option:not(:first-child)').remove();

      var dept_id = document.getElementById('dept_id').value;

      $.ajax({
        url: "sq_default_role_data_set.php",
        type: "POST",
        data: {
          group_id: group,
          dept_id: dept_id,
          functionName: "getDropdownData"
        },
        success: function(response) {
          try {
            var data = JSON.parse(response);
            if (data) {
              var dropdownData = data;
              if (dropdownData.entrant) {
                $.each(dropdownData.entrant, function(index, item) {
                  $('#entrant').append($('<option>', {
                    value: item.employee_code,
                    text: item.employee_name
                  }));
                });
              }
              if (dropdownData.confirmer) {
                $.each(dropdownData.confirmer, function(index, item) {
                  $('#confirmer').append($('<option>', {
                    value: item.employee_code,
                    text: item.employee_name
                  }));
                });
              }
              if (dropdownData.approver) {
                $.each(dropdownData.approver, function(index, item) {
                  $('#approver').append($('<option>', {
                    value: item.employee_code,
                    text: item.employee_name
                  }));
                });
              }
            }
          } catch (e) {
            console.error("Invalid JSON response: ", response);
          }
        },
        error: function(xhr, status, error) {
          console.error("AJAX Error: ", error);
        }
      });
    }

    $('#entrant').change(function() {
      var entrant = $(this).val();
      var dept_id = $('#dept_id').val();
      var group_id = $('#group').val();

      checkExistingData(dept_id, group_id, entrant);
    });

    function checkExistingData(dept_id, group_id, entrant) {
      $.ajax({
        url: "sq_default_role_data_set.php",
        type: "POST",
        data: {
          dept_id: dept_id,
          group_id: group_id,
          entrant: entrant,
          functionName: "checkExistingData"
        },
        success: function(response) {
          try {
            var data = JSON.parse(response);
            if (data) {
              $('#confirmer').val(data.confirmer);
              $('#approver').val(data.approver);
            } else {
              $('#confirmer').val('');
              $('#approver').val('');
            }
          } catch (e) {
            console.error("Invalid JSON response: ", response);
          }
        },
        error: function(xhr, status, error) {
          console.error("AJAX Error: ", error);
        }
      });
    }

    function loadExistingRoleData(data) {
      if (data) {
        $('#entrant').val(data.entrant);
        $('#confirmer').val(data.confirmer);
        $('#approver').val(data.approver);
      }
    }
    $('#sq_default_role_form').submit(function(e) {
      $('#group_id').val($('#group').val());
      if (!$('#entrant').val() && !$('#confirmer').val() && !$('#approver').val()) {
        alert('入力項目に不備があります。');
        e.preventDefault();
      } else {
        let buttonText = $('#updateBtn').text();
        if (!confirm(buttonText + 'します、よろしいでしょうか？')) {
          e.preventDefault();
        }
      }
    });
  });
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