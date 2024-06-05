<?php
  session_start();
  header('Program-id: estimate_entry.php');
  header('Content-type: text/html; charset=utf-8');
  require_once('function.php');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
  include("header1.php");
  include("sq_zkm_input2_data_set.php");  
?>

<main>
  <div class="pagetitle">
    <h3>材工名マスター保守</h3>
  </div>
  <div class="container">
    <form class="row g-3" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="sq_zkm_form" enctype="multipart/form-data">
      <input type="hidden" name="class_code" value="<?= $class_code ?>">
      <input type="hidden" name="class_name" value="<?= $class_name ?>">
      <input type="hidden" name="process" id="process" value="<?= $process ?>">
      <table style="width:auto;">
        <tr>
          <td>
            <div class="field-row">
              <label class="common_label" for="class_code" >分類コード</label>
              <input style="width: 100px;" type="text" value="<?= $class_code ?>" id="class_code" name="class_code" class="readonlyText" readonly>
              
              <label style="padding-left: 20px; padding: right 0;" class="common_label" for="class_name" >分類名称</label>
              <input style="width: 60%;" type="text" value="<?= $class_name ?>" id="class_name" name="class_name" class="readonlyText" readonly>
            </div>
          </td>                
        </tr>
        <tr>
          <td>
            <div class="field-row">
              <label  class="common_label" for="zkm_code" >材工名コード</label>
              <input style="width: 100px;" type="text" id="zkm_code" name="zkm_code" value="<?= $zkm_code ?>" class="readonlyText" readonly>
              
              <label style="padding-left: 20px; padding: right 0;" class="common_label" for="zkm_name" >材工名名称</label>
              <input style="width: 60%;" type="text" id="zkm_name" name="zkm_name" value="<?= $zkm_name ?>" maxlength="40">
            </div>
          </td>
        </tr>
        <tr>
          <td>
            <div class="field-row">
              <label class="common_label" for="size">サイズ</label>
              <select name="size">
                <option value="">※選択して下さい。</option>
                <?php
                  if (!empty($sizeList)) {
                    foreach ($sizeList as $item) {
                      $size_text = $item['text1'];
                      $selectedSize = ($size_text == $size) ? 'selected' : '';
                      echo "<option value='$size_text' $selectedSize>$size_text</option>";
                    } 
                  }
                ?>
              </select>

              <label class="common_label" for="joint">接合形状</label>
              <select name="joint">
                <option value="">※選択して下さい。</option>
                <?php
                  if (!empty($jointList)) {
                    foreach ($jointList as $item) {
                      $joint_text = $item['text1'];
                      $selectedJoint = ($joint_text == $joint) ? 'selected' : '';
                      echo "<option value='$joint_text' $selectedJoint>$joint_text</option>";
                    } 
                  }
                ?>
              </select>

              <label class="common_label" for="pipe">管種 </label>
              <select name="pipe">
                <option value="">※選択して下さい。</option>
                <?php
                  if (!empty($pipeList)) {
                    foreach ($pipeList as $item) {
                      $pipe_text = $item['text1'];
                      $selectedPipe = ($pipe_text == $pipe) ? 'selected' : '';
                      echo "<option value='$pipe_text' $selectedPipe>$pipe_text</option>";
                    } 
                  }
                ?>
              </select>
            </div>
          </td>
        </tr>
        
        <tr>
          <td>
            <div class="field-row">
              
              <label class="common_label" for="inner_coating">内面塗装 </label>
              <select name="inner_coating">
                  <option value="">※選択して下さい。</option>
                <?php
                  if (!empty($innerCoatingList)) {
                    foreach ($innerCoatingList as $item) {
                      $innerCoating_text = $item['text1'];
                      $selectedSize = ($innerCoating_text == $inner_coating) ? 'selected' : '';
                      echo "<option value='$innerCoating_text' $selectedSize>$innerCoating_text</option>";
                    } 
                  }
                ?>
              </select>

              <label class="common_label" for="outer_coating">外面塗装 </label>
              <select name="outer_coating">
                <option value="">※選択して下さい。</option>
                <?php
                  if (!empty($outerCoatingList)) {
                    foreach ($outerCoatingList as $item) {
                      $outerCoating_text = $item['text1'];
                      $selectedOuterCoating = ($outerCoating_text == $outer_coating) ? 'selected' : '';
                      echo "<option value='$outerCoating_text' $selectedOuterCoating>$outerCoating_text</option>";
                    } 
                  }
                ?>
              </select>

              <label class="common_label" for="fluid">管内流体 </label>
              <select name="fluid">
                <option value="">※選択して下さい。</option>
                <?php
                  if (!empty($fluidList)) {
                    foreach ($fluidList as $item) {
                      $fluid_text = $item['text1'];
                      $selectedFluid = ($fluid_text == $fluid) ? 'selected' : '';
                      echo "<option value='$fluid_text' $selectedFluid>$fluid_text</option>";
                    } 
                  }
                ?>
              </select>
            </div>
          </td>
        </tr>
        
        <tr>
          <td>
            <div class="field-row">
              
              <label class="common_label" for="valve">バルブ仕様 </label>
              <select name="valve">
                  <option value="">※選択して下さい。</option>
                  <?php
                    if (!empty($valveList)) {
                      foreach ($valveList as $item) {
                        $valve_text = $item['text1'];
                        $selectedValve = ($valve_text == $valve) ? 'selected' : '';
                        echo "<option value='$valve_text' $selectedValve>$valve_text</option>";
                      } 
                    }
                  ?>
              </select>

              <label class="common_label" for="o_c_direction">開閉方向 </label>
              <select name="o_c_direction">
                <option value="">※選択して下さい。</option>
                <?php
                  if (!empty($o_c_directionList)) {
                    foreach ($o_c_directionList as $item) {
                      $o_c_direction_text = $item['text1'];
                      $selectedO_c_direction = ($o_c_direction_text == $o_c_direction) ? 'selected' : '';
                      echo "<option value='$o_c_direction_text' $selectedO_c_direction>$o_c_direction_text</option>";
                    } 
                  }
                ?>
              </select>

              <label class="common_label" for="c_div">一般・工事 </label>
              <select name="c_div">
                <option value="">※選択して下さい。</option>
                <?php
                  if (!empty($c_divList)) {
                    foreach ($c_divList as $item) {
                      $c_div_code = $item['code_no'];
                      $c_div_text = $item['text1'];
                      $selected_c_div = ($c_div_code == $c_div) ? 'selected' : '';
                      echo "<option value='$c_div_code' $selected_c_div>$c_div_text</option>";
                    } 
                  }
                ?>
              </select>
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
</main><!-- End #main -->


<script src="assets/js/inquiry_ent.js"></script>
<script src="assets/js/inquiry_ent_check.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    // Handle return button click
    $('#returnBtn').click(function(event) {
      event.preventDefault();  // Prevent the default form submission
      if (confirm('一覧画面に戻ります．よろしいですか？')) {
        window.location.href = 'sq_zkm_input1.php';
      }
    });
    // Handle return button click
    // $("#returnBtn").click(function(){
    //     $("#sq_zkm_form").attr("action", "sq_zkm_input1.php");
    // });
    
    // // Handle update button click
    // $("#upd_regBtn").click(function(){
    //     $("#sq_zkm_form").attr("action", "sq_zkm_input2_data_set.php");
    // });
  });

  //更新ボタンをクリックする時、チェックする
  document.getElementById('upd_regBtn').onclick = function(event) {
    var zkm_code = document.getElementById('zkm_code').value;
    var zkm_name = document.getElementById('zkm_name').value;
    var isErr = false;

    if (zkm_name == '') {
      alert('「材工名　名称」を入力して下さい。');
      isErr = true;
    }

    if (class_name.length > 40) {
      alert('over 40');
      isErr = true;
    }

    if(!isErr) {
    } else {
      event.preventDefault();
    }
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