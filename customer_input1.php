<?php
  session_start();
  header('Program-id: estimate_entry.php');
  header('Content-type: text/html; charset=utf-8');
  require_once('function.php');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
  
  include('customer_input1_data_set.php');
  // ヘッダーセット
  include("header1.php");
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

  // 初期設定 & データセット
  $office_codes = [];
  $customer_datas = [];
  $count = 0;

  // 事業所コードを取得する
  $office_codes = getOfficeDatas();

  //選択された事業所コードを取得する
  $office_code_filter = isset($_POST['office_category']) ? $_POST['office_category'] : '';

  // 得意先からデータ取得する
  if($office_code_filter !== '') {
    $customer_datas = getCustDatas($office_code_filter);
    if(!empty($customer_datas)) {
      $count = count($customer_datas);
    }
  }
  
?>
<main>
  <div class="pagetitle">
    <h3>得意先マスター保守</h3>
  </div>
  <div class="container">
    <form class="row g-3" action="" method="POST" name="inq_ent" enctype="multipart/form-data" id="customer_form">
      <table>
        <tr>
          <td>
            <div class="field-row">
              <label class="common_label" for="class_code" >事業所コード</label>
              <select name="office_category" onchange="submit()" required>
                <option value="" <?php if (empty($office_code_filter)) echo 'selected'; ?>>※選択して下さい。</option>
                  <?php 
                    $previous_code = null;
                    if(isset($office_codes)) {
                      foreach ($office_codes as $office_code) {
                        // Check if the current code is different from the previous one
                        if ($office_code['office_code'] != $previous_code) {
                        // If different, display it as an option
                  ?>
                <option value="<?= $office_code['office_code'] ?>" 
                  <?php if ($office_code_filter === $office_code['office_code']) echo 'selected'; ?>>
                  <?= $office_code['office_name'] ?>
                </option>
                <?php 
                  // Update the previous code to the current one
                  $previous_code = $office_code['office_code'];
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
              <th style="width:10%">得意先コード</th>
              <th>得意先　名称</th>
              <th style="width:140px;">担当者</th>
              <th style="width:140px;">処理</th>
            </tr>
            <tr id="createBtnRow">
              <td colspan="4" style="text-align:left"><button class="createBtn" id="create" name="process" value="create">新規作成</button></td>
            </tr>
          </thead>
          <tbody>
            <tr>
              <?php 
                $i = 1;      
                foreach ($customer_datas as $customer_data) { 
              ?>
              <td><?= $customer_data['cust_code'] ?></td>
              <td><?= $customer_data['cust_name'] ?></td>
              <td><?= $customer_data['employee_name'] ?></td>
              <td style="text-align:center"><button class="updateBtn" name="process" value="update" id='update<?= $i ?>' data-id="<?= $customer_data['cust_code'] ?>">更新</button></td>
              <input type="hidden" class="cust_code" name="cust_code" value="">
              <input type="hidden" class="count" value="<?= $count ?>">
            </tr>
            <?php $i++; }  ?>
            <input type="hidden" id="hide_create" class="office_code" name="office_code" value="<?= $office_code_filter ?>">
          </tbody>
        </table>
      </div>
    </form><!-- Vertical Form -->
  </div>
</main><!-- End #main -->

<script src="assets/js/inquiry_ent.js"></script>
<script src="assets/js/inquiry_ent_check.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    //Handle return button click
    $(".createBtn").click(function(){
      $("#customer_form").attr("action", "customer_input2.php");
    });
    
    let count = $(".count").val();

    //更新ボタンを押下場合
    for (let index = 1; index <= count; index++) {
      $("#update"+index).click(function(){
        var selectedId = $(this).data('id');
        $('.cust_code').val(selectedId);
        $("#customer_form").attr("action", "customer_input2.php");
      });
    }
    if($("#hide_create").val() == ''){
      $(".createBtn").hide();
    }   
  });
</script>
<?php

// フッターセット
footer_set();
?>
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