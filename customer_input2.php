<?php
  session_start();
  header('Program-id: estimate_entry.php');
  header('Content-type: text/html; charset=utf-8');
  require_once('function.php');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
  include("customer_input2_data_set.php");  
  
  // ヘッダーセット
  include("header1.php");  
?>
<main>
  <div class="pagetitle">
    <h3>得意先マスター保守</h3>
  </div>
  <div class="container">
    <form class="row g-3" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="customer_form" enctype="multipart/form-data">
      <input type="hidden" id="process" name="process" value="<?= $process ?>">
      <table style="width:auto;">
        <tr>
          <td>
            <div class="field-row">
              <label class="common_label" for="office_code">事業所コード</label>
              <input class="readonlyText" type="text" value="<?= $office_code ?>" id="office_code" name="office_code" readonly>
              
              <label class="common_label" for="office_name">事業所名称</label>
              <input class="readonlyText" type="text" value="<?= $office_name ?>" id="office_name" name="office_name" readonly>
            </div>
          </td>                
        </tr>
        <tr>
          <td>
            <div class="field-row">
              <label class="common_label" for="cust_code" >得意先コード</label>
              <input type="text" id="cust_code" name="cust_code" value="<?= $cust_code ?>">
              
              <label class="common_label" for="cust_name" >得意先名称</label>
              <input type="text" id="cust_name" name="cust_name" value="<?= $cust_name ?>">

              <label class="common_label" for="custmer_div" >区分</label>
              <select name="custmer_div" required>
                <option value="">※選択して下さい。</option>
                <?php foreach ($custmer_div_options as $option): 
                  $custmer_div_value = $option['custmer_div']; 
                  $text1_value = $option['text1']; ?>
                  <option value="<?= htmlspecialchars($custmer_div_value) ?>" <?= $custmer_div == $custmer_div_value ? 'selected' : '' ?>>
                  <?= htmlspecialchars($text1_value) ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          </td>                
        </tr>
        <tr>
          <td>
            <div class="field-row">
              <label class="common_label" for="employee_code" >担当者</label>
              <input type="text" id="contact_person" name="employee_code" value="<?= $employee_code ?>" onblur="onBlurEmpCode()">
              
              <button class="search_btn" style="margin-left: 11px;" onclick="emp_inq_open(event)">社員検索 </button>
              <input style="margin-left:30px" type="text" id="cp_name" name="employee_name" value="<?= $employee_name?>" class="readonlyText" readonly>
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
                <button class="updateBtn" id="upd_regBtn" name="submit"><?= $btn_name ?> </button>
              </div>
            </div>
          </td>
        </tr>
      </table>
    </form><!-- Vertical Form -->
  </div>
</main><!-- End #main -->

</html>
<script src="assets/js/inquiry_ent.js"></script>
<script src="assets/js/inquiry_ent_check.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="assets/js/customer_input2_check.js"></script>
<script type="text/javascript">

$(document).ready(function() {
  let process = '<?= $process ?? "create" ?>';
    $('#process').val(process);
    if (process == 'create') {
      $('#cust_code').prop('readonly', true);
      $('#cust_code').css({
        'background-color' : '#ffffe0'
      });
    }
    // Handle return button click
    $('#returnBtn').click(function(event) {
      event.preventDefault();  // Prevent the default form submission
      if (confirm('一覧画面に戻ります．よろしいですか？')) {
        window.location.href = 'customer_input1.php';
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
  
  function checkForm($this)
  {
      var str=$this.value;
      while(str.match(/[^A-Z^a-z\d\-\ \,]/))
      {
          str=str.replace(/[^A-Z^a-z\d\-\ \,]/,"");
      }
      $this.value=str;
  }
</script>
<?php

// フッターセット
footer_set();
?>
<style>
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

  .spacer {
    justify-content: right;
    margin-right: 264px;
  }
</style>