<?php
  session_start();
  header('Program-id: estimate_entry.php');
  header('Content-type: text/html; charset=utf-8');
  require_once('function.php');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
  include("sq_zkm_input1_data_set.php");
  include("header1.php");
   
?>

<main>
  <div class="pagetitle">
    <h3>材工名マスター保守</h3>
  </div>
  <div class="container">
    <form class="row g-3" method="POST" name="inq_ent" enctype="multipart/form-data" id="sq_zkm_form">
    
    <table>
        <tr>
          <td>
            <div class="field-row">
              <label class="common_label" for="class_code" >分類コード</label>
              <select name="class_category" onchange="submit()" required>
                <option value="">※選択して下さい。</option>
                <?php 
                  $previous_code = null;
                  if (isset($class_datas)) {
                    foreach ($class_datas as $class_item) {
                      // Check if the current code is different from the previous one
                      if ($class_item['class_code'] != $previous_code) {
                      // If different, display it as an option
                ?>
                <option value="<?= $class_item['class_code'] ?>" 
                  <?php if ($class_code_filter === $class_item['class_code']) echo 'selected'; ?>>
                  <?= $class_item['class_name'] ?>
                </option>
                <?php 
                  // Update the previous code to the current one
                  $previous_code = $class_item['class_code'];
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
              <th>材工名コード</th>
              <th>材工名　名称</th>
              <th>区分</th>
              <th>処理</th>
            </tr>
            <tr id="createBtnRow">
              <td colspan="4" style="text-align:left"><button class="createBtn" id="create" name="process" value="create">新規作成</button></td>
              <input type="hidden" id="hid_class_code" class="class_code" name="class_code" value="<?= $class_code_filter ?>">              
            </tr>
          </thead>
          <tbody>
            <?php
              $i = 1;
              if(isset($material_datas)) {
                foreach ($material_datas as $material_data) {
            ?>
            <tr>
              <td><?= $material_data['zkm_code'] ?></td>
              <td><?= $material_data['zkm_name']?></td>
              <td><?= $material_data['text1']?></td>
              <td style="text-align:center"><button class="updateBtn" id='update<?= $i ?>' data-id="<?= $material_data['zkm_code'] ?>" name="process" value="update">更新</button></td>
              <input type="hidden" class="zkm_code" name="zkm_code" value="">
              <input type="hidden" class="class_code" name="class_code" value="<?= $class_code_filter ?>">
              
            </tr>
            <?php $i++; } }?>
            <input type="hidden" class="count" value="<?= $count ?>">
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
    $("#create").click(function(){
      $("#sq_zkm_form").attr("action", "sq_zkm_input2.php");
    });    
    
    let count = $(".count").val();

    //一覧データがない場合、新規作成ボタンを非表示にする
    if ($("#hid_class_code").val() == '') {
      $("#createBtnRow").hide();
    }

    //更新ボタンを押下場合
    for (let index = 1; index <= count; index++) {
      $("#update"+index).click(function(){
        var selectedId = $(this).data('id');
        $('.zkm_code').val(selectedId);
        $("#sq_zkm_form").attr("action", "sq_zkm_input2.php");
      });
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
    width: 110px;
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