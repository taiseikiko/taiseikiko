<?php
  session_start();
  header('Program-id: estimate_entry.php');
  header('Content-type: text/html; charset=utf-8');
  require_once('function.php');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
  include("header1.php");
  include("sr_route_in_dept_input2_data_set.php");
?>
<main>
  <div class="pagetitle">
    <h3>部署内ルートマスター保守</h3>
    <div class="container">
      <form class="row g-3" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="sq_zkm_form" enctype="multipart/form-data">
        <input type="hidden" name="process" id="process" value="<?= $process ?>">
        <table style="width:auto;">
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="dept_code" >部署</label>
                <select name="dept" id="dept" readonly>
                  <option value="">※選択して下さい。</option>
                  <?php
                    if (!empty($deptList)) {
                      foreach ($deptList as $item) {
                        $dept_cd = $item['text1'];
                        $dept_nm = $item['text2'];
                        $selectedDept = ($dept_cd == $dept_id) ? 'selected' : '';
                        echo "<option value='$dept_cd' $selectedDept>$dept_nm</option>";
                      } 
                    }
                  ?>
                </select>
                <input type="hidden" name="hid_dept_id" value="<?= $dept_id ?>">

                <label class="common_label" for="group" >グループ</label>
                <select name="group" id="group">
                  <option value="">※選択して下さい。</option>
                  <option value="" id="test">test</option>
                </select>
                <input type="hidden" name="hid_group_id" id="group_id" value="<?= $group_id ?>">
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="employee_code" >担当者</label>
                <input type="text" id="contact_person" name="employee_code" value="<?= $employee_code ?>" class="readonlyText" readonly>
                <input type="hidden" name="hid_employee_cd" value="<?= $employee_code ?>">
                
                <button class="search_btn" style="margin-left: 11px;" onclick="emp_inq_open(event)">社員検索 </button>
                <input style="margin-left:30px" type="text" id="cp_name" name="employee_name" value="<?= $employee_name ?>" class="readonlyText" readonly>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <div class="field-row spacer">
                <label class="common_label" for="role" >役割</label>
                <select name="role" id="role" style="margin-right: 40px">
                  <option value="" <?= ("" == $role) ? 'selected' : '' ?>>※選択して下さい。</option>
                  <option value="0" <?= (0 == $role) ? 'selected' : '' ?>>受付</option>
                  <option value="1" <?= (1 == $role) ? 'selected' : '' ?>>入力</option>
                  <option value="2" <?= (2 == $role) ? 'selected' : '' ?>>確認</option>
                  <option value="3" <?= (3 == $role) ? 'selected' : '' ?>>承認</option>
                <?php  ?>
                </select>
                <input type="hidden" name="hid_role" value="<?= $role ?>">

                <label class="common_label" for="dept_code" >部署</label>
                <input type="hidden" name="dept_code" id="dept_code">
                <input type="text" id="dept_name" name="department_name" value="<?= $department_name ?>" class="readonlyText" readonly>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <div class="field-row spacer">
                <label class="common_label" for="office_position_code" >役職</label>
                <input type="hidden" name="office_position_code" id="office_position_code">
                <input type="text" id="op_name" name="office_position_name" value="<?= $office_position_name ?>" class="readonlyText" readonly>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <div class="flex-container">
                <div>            
                  <button id="returnBtn" name="return">戻る </button>
                </div>
                <div>
                  <button class="updateBtn" id="upd_regBtn" name="submit" value="update"><?= $btn_name ?></button>
                </div>
              </div>
            </td>
          </tr>
        </table>
      </form><!-- Vertical Form -->
    </div>
  </div>
</main><!-- End #main -->
</body>
</html>
<script src="assets/js/inquiry_ent.js"></script>
<script src="assets/js/inquiry_ent_check.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="assets/js/sr_route_in_dept_input2_check.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    // Handle return button click
    $('#returnBtn').click(function(event) {
      event.preventDefault();  // Prevent the default form submission
      if (confirm('一覧画面に戻ります．よろしいですか？')) {
        window.location.href = 'sr_route_in_dept_input1.php';
      }
    });

    //部署プルダウンに選択されたデータがあるかどうかチェックする
    let chk_dept = $('#dept').val();
    if (chk_dept != '') {
      fetchData(chk_dept, function(response) {
        console.log(response);
      }, function(error) {
        console.log(error);
      })
    }

    //部署プルダウンに選択されたデータによってグループのプルダウンを有効にする
    $('#dept').change(function() {
      let dept = $(this).val();
      fetchData(dept, function(response) {
        console.log(response);
      }, function(error) {
        console.error(error);
      });
    });

  });

  function fetchData(dept) {
    $('#group option:not(:first-child)').remove();
    $.ajax({
      url: "sr_route_in_dept_input2_data_set.php",
      type: "POST",
      data: {
        function_name: "getDropdownDataOfGroup",
        dept_code: dept,
        code_id: 'dept_group'
      },
      success: function(response){
        var groupList = JSON.parse(response);
        var selected = $('#group_id').val();
        let i = 1;
        $.each(groupList, function(index, item) {
          $('#group').append($('<option>', {
            value: item.text2,
            text: item.text3,
            id: 'val'+i
          }));
          if ($.inArray(item.text2, selected) !== -1) {
            $('#val'+i).prop('selected', true);
          }
          i++;
        });
      },
      error: function (xhr, status, error) {
        console.log('error');
      }
    });
  }

  //更新ボタンをクリックする時、チェックする
  document.getElementById('upd_regBtn').onclick = function(event) {
    checkValidation(event);
  }
</script>
<?php

// フッターセット
footer_set();
?>
<style>
  .spacer {
    justify-content: right;
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