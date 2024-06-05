<?php
  session_start();
  header('Program-id: estimate_entry.php');
  header('Content-type: text/html; charset=utf-8');
  require_once('function.php');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
  include('public_office_input1_data_set.php');
  // ヘッダーセット
  include("header1.php");  
?>

<main>
  <div class="pagetitle">
    <h3>官庁マスター保守</h3>
  </div>
  <div class="container">
    <form class="row g-3" method="POST" name="inq_ent" enctype="multipart/form-data" id="public_office_form">
      <table>
        <tr>
          <td>
            <div class="field-row">
              <label class="common_label" for="office_code" >事業所コード</label>
              <select name="office_category" onchange="submit()" required>
                <option value="" <?php if (empty($office_code_filter)) echo 'selected'; ?>>※選択して下さい。</option>
                <?php 
                  $previous_code = null;
                  foreach ($office_datas as $office_code) {
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
              <th style="width:10%">官庁コード</th>
              <th>官庁　名称</th>
              <th style="width:140px;">担当者</th>
              <th style="width:140px;">処理</th>
            </tr>
            <tr>
              <td colspan="4" style="text-align:left"><button class="createBtn" name="process" value="create">新規作成</button></td>
            </tr>
          </thead>
          <tbody>
            <?php 
              $i = 1;
              foreach ($pf_datas as $pf_data) { 
            ?>
            <tr>
              <td><?= $pf_data['pf_code'] ?></td>
              <td><?= $pf_data['pf_name'] ?></td>
              <td><?= $pf_data['employee_name'] ?></td>
              <td style="text-align:center"><button class="updateBtn" name="process" value="update" id='update<?= $i ?>' data-id="<?= $pf_data['pf_code'] ?>">更新</button></td>
              <input type="hidden" class="pf_code" name="pf_code" value="">
              <input type="hidden" class="count" value="<?= $count ?>">
            </tr>
            <?php 
                $i++;
              }
            ?>
            <input type="hidden" id="hid_office_code" class="office_code" name="office_code" value="<?= $office_code_filter ?>">
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
      $("#public_office_form").attr("action", "public_office_input2.php");
    });    
    
    let count = $(".count").val();

    //Handle update button click
    for (let index = 1; index <= count; index++) {
      $("#update"+index).click(function(){
        var selectedId = $(this).data('id');
        $('.pf_code').val(selectedId);
        $("#public_office_form").attr("action", "public_office_input2.php");
      });
    }

    if ($("#hid_office_code").val() == '') {
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