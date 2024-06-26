<?php
  session_start();
  header('Program-id: estimate_entry.php');
  header('Content-type: text/html; charset=utf-8');
  require_once('function.php');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
  include('card_route_in_dept_input1_data_set.php');
  include("header1.php");  
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<main>
  <div class="pagetitle">
    <h3>部署内ルートマスター保守</h3>
    <div class="container">
      <form class="row g-3" method="POST" name="inq_ent" enctype="multipart/form-data" id="route_dept_form">
        <table>
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="dept_code" >部署</label>
                <select name="dept" id="dept" onchange="submit()" required>
                  <option value="">※選択して下さい。</option>
                    <?php
                    $previous_code = null;
                    if (!empty($deptList) && isset($deptList)) {
                      foreach ($deptList as $item) {
                        $dept_cd = $item['text1'];
                        $dept_nm = $item['text2'];

                        if ($dept_cd != $previous_code) {
                    ?>
                    <option value="<?= $dept_cd ?>" <?php if ($dept_filter === $dept_cd) echo 'selected'; ?>><?= $dept_nm ?></option>
                  <?php 
                    // Update the previous code to the current one
                    $previous_code = $dept_cd;
                        }
                      }
                    } 
                  ?>
                </select>
              </div>
            </td>                
          </tr>
        </table>
        <div class="scrollable-table-container">
          <table class="tab1">
            <thead>
              <tr>
                <th>部署ID</th>
                <th>部署名</th>
                <th>社員番号</th>
                <th>社員名</th>
                <th>役割区分</th>
                <th>役割名</th>
                <th>処理</th>
              </tr>
              <tr>
                <td colspan="9" style="text-align:left"><button class="createBtn" name="process" value="create">新規作成</button></td>
                <input type="hidden" id="hid_dept" class="hid_dept" name="hid_dept" value="<?= $dept_filter ?>">
              </tr>
            </thead>
            <tbody>
              <?php
                $i = 1;
                foreach ($route_dept_datas as $item) {
              ?>
              <tr>
                <td><?= $item['department_code']; ?></td>
                <td><?= $item['dept_name']; ?></td>
                <td><?= $item['employee_code']; ?></td>
                <td><?= $item['employee_name']; ?></td>
                <td><?= $item['role']; ?></td>
                <td><?= $item['role_name']; ?></td>
                <td style="text-align:center"><button id="updateBtn<?= $i ?>" class="updateBtn" 
                data-dept="<?= $item['department_code'] ?>" 
                data-emp="<?= $item['employee_code'] ?>" 
                data-role="<?= $item['role'] ?>" 
                name="process" value="update">更新</button></td>
                <input type="hidden" class="employee_code" name="employee_code" value="">
                <input type="hidden" class="role" name="role" value="">
              </tr>
              <?php 
                  $i++;
                }
              ?>
              <input type="hidden" class="count" value="<?= $count ?>">
            </tbody>
          </table>
        </div>
      </form><!-- Vertical Form -->
    </div>
  </main><!-- End #main -->

</html>
<script src="assets/js/inquiry_ent.js"></script>
<script src="assets/js/inquiry_ent_check.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    //Handle return button click
    $(".createBtn").click(function(){
      $("#route_dept_form").attr("action", "card_route_in_dept_input2.php");
    });

    let count = $(".count").val();

    //一覧データがない場合、新規作成ボタンを非表示にする
    if ($("#hid_dept").val() == '') {
      $(".createBtn").hide();
    }

    //更新ボタンを押下場合
    for (let index = 1; index <= count; index++) {
      $('#updateBtn'+index).click(function() {
        var selectedDept = $(this).data('dept');
        var selectedEmp = $(this).data('emp');
        var selectedRole = $(this).data('role');
        $('.department_code').val(selectedDept);
        $('.employee_code').val(selectedEmp);
        $('.role').val(selectedRole);
        $("#route_dept_form").attr("action", "card_route_in_dept_input2.php");
      })
    }
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

  .flex-container > div {
    margin: 20px 5px;
  }

  @media only screen and (max-width:800px) {
    .pagetitle, .container, .field-row {
      width: 80%;
      padding: 0;
    }
    .createBtn {
      width: 40px;
    }
  }
  @media only screen and (max-width:500px) {
    .pagetitle, .container, .field-row {
      width: 100%;
    }
    .createBtn {
      width: 40px;
    }
  }
</style>
<?php
// フッターセット
footer_set();
?>
