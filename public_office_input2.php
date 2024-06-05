<?php
  session_start();
  header('Program-id: estimate_entry.php');
  header('Content-type: text/html; charset=utf-8');
  require_once('function.php');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
  // ヘッダーセット
  include("header1.php");
  include("public_office_input2_data_set.php");

  
?>
<main>
  <div class="pagetitle">
    <h3>官庁マスター保守</h3>
  </div>
  <div class="container">
    <form class="row g-3" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" name="" enctype="multipart/form-data">
      <input type="hidden" name="process" id="process" value="<?= $process ?>">
      <table style="width:auto;">
        <tr>
          <td>
            <div class="field-row">
              <label class="large_label" for="office_code" >事業所コード</label>
              <input type="text" id="office_code" name="office_code" value="<?= $office_code ?>" class="readonlyText" readonly>
              
              <label class="common_label" for="office_name" >事業所名称</label>
              <input type="text" id="office_name" name="office_name" value="<?= $office_name ?>" class="readonlyText" readonly>
            </div>
          </td>                
        </tr>
        <tr>
          <td>
            <div class="field-row">
              <label class="common_label" for="pf_code" >官庁コード</label>
              <input type="text" id="pf_code" name="pf_code" value="<?= $pf_code ?>" class="readonlyText" readonly>
              
              <label class="common_label" for="pf_name" >官庁名称</label>
              <input type="text" id="pf_name" name="pf_name" value="<?= $pf_name ?>">
            </div>
          </td>                
        </tr>
        <tr>
          <td>
            <div class="field-row">
              <label class="common_label" for="employee_cd" >担当者</label>
              <input type="text" id="contact_person" name="employee_code" value="<?=$employee_code ?>" onblur="onBlurEmpCode()">
              
              <button style="margin-left: 11px;" class="search_btn" onclick="emp_inq_open(event)">社員検索 </button>
              <input style="margin-left:30px" type="text" id="cp_name" name="employee_name" value="<?=$employee_name ?>" class="readonlyText" readonly>
            </div>
          </td>               
        </tr>
        <tr>           
          <td>
            <div class="field-row spacer">
              <label class="common_label" for="dept_name" >部署</label>
              <input type="hidden" name="dept_code" id="dept_code">
              <input type="text" id="dept_name" name="department_name" value="<?=$department_name ?>" class="readonlyText" readonly>
            </div>
            </div>
          </td>               
        </tr>
        <tr>           
          <td>
            <div class="field-row spacer">
              <label class="common_label" for="op_name" >役職</label>
              <input type="hidden" name="office_position_code" id="office_position_code">
              <input type="text" id="op_name" name="office_position_name" value="<?=$office_position_name ?>" class="readonlyText" readonly>
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
                <button class="updateBtn" id="upd_regBtn" name="submit"><?= $btn_name ?></button>
              </div>
            </div>
          </td>
        </tr>
      </table>
    </form><!-- Vertical Form -->
  </div>
</main><!-- End #main -->

<script src="assets/js/inquiry_ent.js"></script>
<script src="assets/js/inquiry_ent_check.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="assets/js/public_office_input2_check.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    // Handle return button click
    $('#returnBtn').click(function(event) {
      event.preventDefault();  // Prevent the default form submission
      if (confirm('一覧画面に戻ります．よろしいですか？')) {
        window.location.href = 'public_office_input1.php';
      }
    });
  });

  //更新ボタンをクリックする時、チェックする
  document.getElementById('upd_regBtn').onclick = function(event) {
    checkValidation(event);
  }

  function onBlurEmpCode() {
    let emp_cd = document.getElementById('contact_person');
    let emp_nm = document.getElementById('cp_name');
    let dept_nm = document.getElementById('dept_name');
    let op_nm = document.getElementById('op_name');
    if (emp_cd.value == '') {
      emp_nm.value = '';
      dept_nm.value = '';
      op_nm.value = '';
    }
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
  
  input[type=text], input[type=checkbox], select {
    /* width: 50%; */
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
  }

  #common_label {
    width: 100px;
    text-align: start;
  }

  input.readonlyText {
    background-color: #ffffe0;
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

  .copyBtn {
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

  .business_daily_report {
    width: 630px;
  }

  button .btn {
    background-color: red;
  }
</style>