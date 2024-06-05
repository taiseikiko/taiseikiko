<?php
  session_start();
  header('Program-id: estimate_entry.php');
  header('Content-type: text/html; charset=utf-8');
  require_once('function.php');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
  $msg = "";

  /* header.html                                        */  
  /*   ・ヘッダー記述                                    */  
  /*   ・メニュー記述                                    */  
  /*   ・サイドバー記述                                  */  
  include("header1.php");

  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

  // 初期設定 & データセット
  $btn_name = '';
  $zkm_code = '';
  $zkm_name = '';
  $size = '';
  $joint = '';
  $pipe = '';
  $inner_coating = '';
  $outer_coating = '';
  $fluid = '';
  $valve = '';
  $o_c_direction = '';
  $c_div = '';
  $sizeList = [];           //サイズ
  $jointList = [];          //接合形状
  $pipeList = [];           //管種
  $innerCoatingList = [];   //内面塗装
  $outerCoatingList = [];   //外面塗装
  $fluidList = [];          //管内流体
  $valveList = [];          //バルブ仕様
  $o_c_directionList = [];  //開閉方向
  $c_divList = [];          //一般・工事
  $datas = [];

  $sizeList = getDropdownData($pdo, 'size');                  //サイズ
  $jointList = getDropdownData($pdo, 'joint');                //接合形状
  $pipeList = getDropdownData($pdo, 'pipe');                  //管種
  $innerCoatingList = getDropdownData($pdo, 'inner_coating'); //内面塗装
  $outerCoatingList = getDropdownData($pdo, 'outer_coating'); //外面塗装
  $fluidList = getDropdownData($pdo, 'fluid');                //管内流体
  $valveList = getDropdownData($pdo, 'valve');                //バルブ仕様
  $o_c_directionList = getDropdownData($pdo, 'o_c_direction');//開閉方向
  $c_divList = getDropdownData($pdo, 'c_div');                //一般・工事

  //一覧画面からPOSTを取得
  if (isset($_POST['process'])) {
    $process = $_POST['process'];    

    //新規作成の場合
    if ($process == 'create') {
      $btn_name = '登録';
      //分類マスタからMAXデータ取得する
      $sql = "SELECT MAX(zkm_code) as max FROM sq_zaikoumei";
      $stmt = $pdo->prepare($sql);
      $stmt->execute();
      $max_zkm_code = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($max_zkm_code) {
        $zkm_code = $max_zkm_code['max'] + 1;
      } else {
        $zkm_code = 1;
      }
    } else {
      $btn_name = '更新';
      $zkm_code = $_POST['zkm_code'];

      //材工名マスタからデータを取得する
      $sql = "SELECT * FROM sq_zaikoumei WHERE zkm_code = '$zkm_code'";
      $stmt = $pdo->prepare($sql);
      $stmt->execute();
      $datas = $stmt->fetchAll();

      if (!empty($datas)) {
        $zkm_name = $datas[0]['zkm_name'];
        $size = $datas[0]['size'];
        $joint = $datas[0]['joint'];
        $pipe = $datas[0]['pipe'];
        $inner_coating = $datas[0]['inner_coating'];
        $outer_coating = $datas[0]['outer_coating'];
        $fluid = $datas[0]['fluid'];
        $valve = $datas[0]['valve'];
        $o_c_direction = $datas[0]['o_c_direction'];
        $c_div = $datas[0]['c_div'];
      }
    }
    $_SESSION['sq_zkm']['btn_name'] = $btn_name;
  }

  function getDropdownData($pdo, $code_id) {
    //sq_code テーブルからデータ取得する
    $sql = "SELECT text1, code_no FROM sq_code WHERE code_id='$code_id'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $datas = $stmt->fetchAll();
    return $datas;
  }

  if (isset($_GET['err_msg'])) {
   $class_name_err = $_GET['err_msg'];
   if ($class_name_err !== '') {
    showErrMsg($class_name_err);
   }
  }

  function showErrMsg($errMsg) {
    echo "<script type='text/javascript'>alert('$errMsg')</script>";
  }
?>
<style>

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

  /* Responsive Arrow Progress Bar */

  .container {
    font-family: 'Lato', sans-serif;
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
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<main>
  <div class="pagetitle">
    <h3>材工名マスター保守</h3>
    <div class="container">
      <form class="row g-3" action="sq_zkm_input1.php" method="POST" id="sq_zkm_form" enctype="multipart/form-data">
        <input type="hidden" name="process" id="process" value="<?= $process ?>">
        <table style="width:auto;">
          <tr style="height:10px; margin-top:20px"></tr>
            <tr>
                <td>
                    <div class="field-row">
                        <label id="common_label" for="class_code" >分類コード</label>
                        <input style="width: 100px;" type="text" id="class_code" name="class_code" class="readonlyText" readonly>
                        
                        <label style="padding-left: 20px; padding: right 0;" id="common_label" for="class_name" >分類名称</label>
                        <input style="width: 60%;" type="text" id="class_name" name="class_name" class="readonlyText" readonly>
                    </div>
                </td>                
            </tr>
          <tr style="height:20px;"></tr>
            <tr>
              <td>
                <div class="field-row">
                  <label  id="common_label" for="zkm_code" >材工名コード</label>
                  <input style="width: 100px;" type="text" id="zkm_code" name="zkm_code" value="<?= ($zkm_code) ? $zkm_code : $_SESSION['sq_zkm']['zkm_code'] ?>">
                  
                  <label style="padding-left: 20px; padding: right 0;" id="common_label" for="zkm_name" >材工名名称</label>
                  <input style="width: 60%;" type="text" id="zkm_name" name="zkm_name" value="<?= $zkm_name ?>">
                </div>
              </td>               
            </tr>
          <tr style="height:10px;"></tr>

          <tr>
            <td>
              <div class="field-row">
                
                <label id="common_label" for="text26">　　サイズ </label>
                <select name="size">
                  <option value="">※選択して下さい。</option>
                  <?php
                    if (!empty($sizeList)) {
                      foreach ($sizeList as $item) {
                        $size_code = $item['code_no'];
                        $size_text = $item['text1'];
                        $selectedSize = ($size_code == ($size ? $size : $_SESSION['sq_zkm']['size'])) ? 'selected' : '';
                        echo "<option value='$size_code' $selectedSize>$size_text</option>";
                      } 
                    }
                  ?>
                </select>

                <label id="common_label" for="text26">　　接合形状 </label>
                <select name="joint">
                  <option value="">※選択して下さい。</option>
                  <?php
                    if (!empty($jointList)) {
                      foreach ($jointList as $item) {
                        $joint_code = $item['code_no'];
                        $joint_text = $item['text1'];
                        $selectedJoint = ($joint_code == ($joint ? $joint : $_SESSION['sq_zkm']['joint'])) ? 'selected' : '';
                        echo "<option value='$joint_code' $selectedJoint>$joint_text</option>";
                      } 
                    }
                  ?>
                </select>

                <label id="common_label" for="text26">　　管種 </label>
                <select name="pipe">
                  <option value="">※選択して下さい。</option>
                  <?php
                    if (!empty($pipeList)) {
                      foreach ($pipeList as $item) {
                        $pipe_code = $item['code_no'];
                        $pipe_text = $item['text1'];
                        $selectedPipe = ($pipe_code == ($pipe ? $pipe : $_SESSION['sq_zkm']['pipe'])) ? 'selected' : '';
                        echo "<option value='$pipe_code' $selectedPipe>$pipe_text</option>";
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
                
                <label id="common_label" for="text26">　　内面塗装 </label>
                <select name="inner_coating">
                    <option value="">※選択して下さい。</option>
                  <?php
                    if (!empty($innerCoatingList)) {
                      foreach ($innerCoatingList as $item) {
                        $innerCoating_code = $item['code_no'];
                        $innerCoating_text = $item['text1'];
                        $selectedSize = ($innerCoating_code == ($inner_coating ? $inner_coating : $_SESSION['sq_zkm']['inner_coating'])) ? 'selected' : '';
                        echo "<option value='$innerCoating_code' $selectedSize>$innerCoating_text</option>";
                      } 
                    }
                  ?>
                </select>

                <label id="common_label" for="text26">　　外面塗装 </label>
                <select name="outer_coating">
                  <option value="">※選択して下さい。</option>
                  <?php
                    if (!empty($outerCoatingList)) {
                      foreach ($outerCoatingList as $item) {
                        $outerCoating_code = $item['code_no'];
                        $outerCoating_text = $item['text1'];
                        $selectedOuterCoating = ($outerCoating_code == ($outer_coating ? $outer_coating : $_SESSION['sq_zkm']['outer_coating'])) ? 'selected' : '';
                        echo "<option value='$outerCoating_code' $selectedOuterCoating>$outerCoating_text</option>";
                      } 
                    }
                  ?>
                </select>

                <label id="common_label" for="text26">　　管内流体 </label>
                <select name="fluid">
                  <option value="">※選択して下さい。</option>
                  <?php
                    if (!empty($fluidList)) {
                      foreach ($fluidList as $item) {
                        $fluid_code = $item['code_no'];
                        $fluid_text = $item['text1'];
                        $selectedFluid = ($fluid_code == ($fluid ? $fluid : $_SESSION['sq_zkm']['fluid'])) ? 'selected' : '';
                        echo "<option value='$fluid_code' $selectedFluid>$fluid_text</option>";
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
                
                <label id="common_label" for="text26">　　バルブ仕様 </label>
                <select name="valve">
                    <option value="">※選択して下さい。</option>
                    <?php
                      if (!empty($valveList)) {
                        foreach ($valveList as $item) {
                          $valve_code = $item['code_no'];
                          $valve_text = $item['text1'];
                          $selectedValve = ($valve_code == ($valve ? $valve : $_SESSION['sq_zkm']['valve'])) ? 'selected' : '';
                          echo "<option value='$valve_code' $selectedValve>$valve_text</option>";
                        } 
                      }
                    ?>
                </select>

                <label id="common_label" for="text26">　　開閉方向 </label>
                <select name="o_c_direction">
                  <option value="">※選択して下さい。</option>
                  <?php
                    if (!empty($o_c_directionList)) {
                      foreach ($o_c_directionList as $item) {
                        $o_c_direction_code = $item['code_no'];
                        $o_c_direction_text = $item['text1'];
                        $selectedO_c_direction = ($o_c_direction_code == ($o_c_direction ? $o_c_direction : $_SESSION['sq_zkm']['o_c_direction'])) ? 'selected' : '';
                        echo "<option value='$o_c_direction_code' $selectedO_c_direction>$o_c_direction_text</option>";
                      } 
                    }
                  ?>
                </select>

                <label id="common_label" for="text26">　　一般・工事 </label>
                <select name="c_div">
                  <option value="">※選択して下さい。</option>
                  <?php
                    if (!empty($c_divList)) {
                      foreach ($c_divList as $item) {
                        $c_div_code = $item['code_no'];
                        $c_div_text = $item['text1'];
                        $selected_c_div = ($c_div_code == ($c_div ? $c_div : $_SESSION['sq_zkm']['c_div'])) ? 'selected' : '';
                        echo "<option value='$c_div_code' $selected_c_div>$c_div_text</option>";
                      } 
                    }
                  ?>
                </select>
                <input type="hidden" value="<?= $c_div ?>">
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
                  <button class="updateBtn" id="upd_regBtn" name="submit"><?= ($btn_name) ? $btn_name : $_SESSION['sq_zkm']['btn_name'] ?></button>
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
<script type="text/javascript">
  $(document).ready(function(){
    let process = $('#process').val();
    if (process == 'create') {
      $('#zkm_code').prop('readonly', true);
      $('#zkm_code').css({
        'background-color' : '#ffffe0'
      });
    }
    // Handle return button click
    $("#returnBtn").click(function(){
        $("#sq_zkm_form").attr("action", "sq_zkm_input1.php");
    });
    
    // Handle update button click
    $("#upd_regBtn").click(function(){
        $("#sq_zkm_form").attr("action", "sq_zkm_input2_data_set.php");
    });
  });

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
