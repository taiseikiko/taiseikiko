<?php
session_start();
header('Program-id: estimate_entry.php');
header('Content-type: text/html; charset=utf-8');
require_once('function.php');
$_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
$title = $_GET['title'] ?? '';
$ec_division = $_GET['ec_division'] ?? '';
include("ec_work_input2_data_set.php");
// ヘッダーセット
include("header1.php");
$page_title = '';
$ec_divisionList = [
  '01' => 'IV実績',
  '02' => 'IVT実績',
  '03' => 'IVF実績',
  '04' => 'パイプリバース工事実績',
  '05' => 'ホースライニング工事実績',
  '06' => 'DC工事実績',
  '07' => 'HC工事実績',
  '08' => 'TC工事実績',
  '09' => 'STPφ700以上実績',
  '10' => '弁体離脱工事実績'
];

$page_title = $ec_divisionList[$ec_division];
?>

<main>
  <div class="pagetitle">
    <h3><?php echo htmlspecialchars($page_title); ?></h3>
    <div class="container">
      <form class="row g-3" method="POST" name="inq_ent" enctype="multipart/form-data" id="ec_work_form2">
        <?php include('dialog.php'); ?>
        <input type="hidden" name="process" value="<?= $process ?>">
        <input type="hidden" id="key_number" name="key_number" value="<?= $key_number ?>">
        <input type="hidden" id="ec_division" name="ec_division" value="<?= $ec_division ?>">
        <table style="width:auto;">
          <tr style="height:20px; margin-top:20px"></tr>      
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="bridge">出先 </label>
                <select style="margin-left: 1rem;" name="bridge" id="bridge" class="dropdown-menu">
                  <option value="">選択して下さい。</option>
                  <?php
                  if (isset($bridgeList) && !empty($bridgeList)) {
                    foreach ($bridgeList as $item) {
                      $code = $item['sq_dept_code'];
                      $text = $item['sq_dept_name'];
                      $selected = ($code == $bridge) ? 'selected' : '';
                      echo "<option value='$code' $selected>$text</option>";
                    }
                  }
                  ?>
                </select>

                <label class="common_label" for="government">官庁 </label>
                <input type="hidden" name="pf_code" id="pf_code" value="<?= $pf_code ?>">
                <input type="text" style="margin-left: 1rem;" id="pf_name" name="pf_name" value="<?= $pf_name?>" class="readonlyText input-res" readonly>
                <button class="search_btn" onclick="public_office_open(event)">官庁検索</button>

                <label class="common_label" for="customers">得意先</label>
                <input type="text" style="margin-left: 1rem;" id="cust_name" name="cust_name" value="<?= $cust_name ?>" class="readonlyText input-res" readonly>
                <input type="hidden" name="cust_code" id="cust_code" value="<?= $cust_code ?>">
                <button class="search_btn" onclick="customer_open(event)">得意先検索</button>                
              </div>
            </td>
          </tr>
          
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="ec_date">工事日</label>
                <input type="date" style="margin-left: 1rem;" min="2023-01-01" max="2028-12-31" name="ec_date" id="ec_date" value="<?= $ec_date ?>" class="input-res" />

                <label class="common_label" for="ec_number">工事番号</label>
                <input type="text" style="margin-left: 1rem;" id="ec_number" name="ec_number" value="<?= $ec_number ?>" class="input-res">
              </div>
            </td>
          </tr>

          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="ec_place">施工場所</label>
                <select name="ec_place" style="margin-left: 1rem;" id="ec_place" class="dropdown-menu">
                  <option value="0">選択して下さい。</option>
                  <?php
                  if (isset($ec_placeList) && !empty($ec_placeList)) {
                    foreach ($ec_placeList as $item) {
                      $code = $item['code_no'];
                      $text = $item['code_name'];
                      $selected = ($code == $ec_place) ? 'selected' : '';
                      echo "<option value='$code' $selected>$text</option>";
                    }
                  }
                  ?>
                </select>

                <label class="common_label" for="pipe">管種 </label>
                <select style="margin-left: 1rem;" name="pipe" id="pipe" class="dropdown-menu">
                  <option value="0">選択して下さい。</option>
                  <?php
                  if (isset($pipeList) && !empty($pipeList)) {
                    foreach ($pipeList as $item) {
                      $code = $item['code_no'];
                      $text = $item['code_name'];
                      $selected = ($code == $pipe) ? 'selected' : '';
                      echo "<option value='$code' $selected>$text</option>";
                    }
                  }
                  ?>
                </select>

                <label class="common_label" for="size">サイズ </label>
                <select style="margin-left: 1rem;" name="size" id="size" class="dropdown-menu">
                  <option value="0">選択して下さい。</option>
                  <?php
                  if (isset($sizeList) && !empty($sizeList)) {
                    foreach ($sizeList as $item) {
                      $code = $item['code_no'];
                      $text = $item['code_name'];
                      $selected = ($code == $size) ? 'selected' : '';
                      echo "<option value='$code' $selected>$text</option>";
                    }
                  }
                  ?>
                </select>

                <!-- 工事区分4と5の場合だけ -->
                <?php if (in_array($ec_division, array('04', '05'))) : ?>
                <label class="common_label" for="ec_extension">施工延長</label>
                <input type="text" style="margin-left: 1rem;" id="ec_extension" name="ec_extension" value="<?= $ec_extension ?>" class="input-res">
                <?php endif; ?>

                <!-- 工事区分１と１０の場合だけ -->
                <?php if (in_array($ec_division, array('01', '10'))) : ?>
                <label class="common_label" for="valve">バルブ種類 </label>
                <select style="margin-left: 1rem;" name="valve" id="valve" class="dropdown-menu">
                  <option value="0">選択して下さい。</option>
                  <?php
                  if (isset($valveList) && !empty($valveList)) {
                    foreach ($valveList as $item) {
                      $code = $item['code_no'];
                      $text = $item['code_name'];
                      $selected = ($code == $valve) ? 'selected' : '';
                      echo "<option value='$code' $selected>$text</option>";
                    }
                  }
                  ?>
                </select>
                <?php endif; ?>
              </div>
            </td>
          </tr>

          <!-- 工事区分4と5のじゃない場合 -->
          <?php if (!in_array($ec_division, array('04', '05'))) : ?>
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="specification_number">仕様書番号</label>
                <input type="text" style="margin-left: 1rem;" id="specification_number" name="specification_number" value="<?= $specification_number ?>" class="input-res">

                <label class="common_label" for="design_pressure">設計圧</label>
                <select style="margin-left: 10px;" name="design_pressure" id="design_pressure" class="dropdown-menu">
                  <option value="0">選択して下さい。</option>
                  <?php
                  if (isset($design_pressureList) && !empty($design_pressureList)) {
                    foreach ($design_pressureList as $item) {
                      $code = $item['code_no'];
                      $text = $item['code_name'];
                      $selected = ($code == $design_pressure) ? 'selected' : '';
                      echo "<option value='$code' $selected>$text</option>";
                    }
                  }
                  ?>
                </select>

                <label class="common_label" for="scene_water_pressure">現場水圧</label>
                <input type="text" style="margin-left: 1rem;" id="scene_water_pressure" name="scene_water_pressure" value="<?= $scene_water_pressure ?>" class="input-res">

                <label class="common_label" for="slant">傾斜</label>
                <input type="text" style="margin-left: 10px;" id="slant" name="slant" value="<?= $slant ?>" class="input-res">
              </div>
            </td>
          </tr>
          <?php endif; ?>

          <!-- 工事区分4と5のじゃない場合 -->
          <?php if (!in_array($ec_division, array('04', '05'))) : ?>
          <tr>
            <td>
              <div class="field-row">
                <!-- 工事区分１と２，３の場合だけ -->
                <?php if (in_array($ec_division, array('01', '02', '03'))) : ?>
                <label class="common_label" for="tank">タンク</label>
                <select style="margin-left: 1rem;" name="tank" id="tank" class="dropdown-menu">
                  <option value="0">選択して下さい。</option>
                  <?php
                  if (isset($tankList) && !empty($tankList)) {
                    foreach ($tankList as $item) {
                      $code = $item['code_no'];
                      $text = $item['code_name'];
                      $selected = ($code == $tank) ? 'selected' : '';
                      echo "<option value='$code' $selected>$text</option>";
                    }
                  }
                  ?>
                </select>
                <?php endif; ?>

                <!-- 工事区分１と２の場合だけ -->
                <?php if (in_array($ec_division, array('01', '02'))) : ?>
                <label class="common_label" for="cutter">切断機</label>
                <select style="margin-left: 1rem;" name="cutter" id="cutter" class="dropdown-menu">
                  <option value="0">選択して下さい。</option>
                  <?php
                  if (isset($cutterList) && !empty($cutterList)) {
                    foreach ($cutterList as $item) {
                      $code = $item['code_no'];
                      $text = $item['code_name'];
                      $selected = ($code == $cutter) ? 'selected' : '';
                      echo "<option value='$code' $selected>$text</option>";
                    }
                  }
                  ?>
                </select>
                <?php endif; ?>

                <!-- 工事区分１と１０の場合だけ -->
                <?php if (in_array($ec_division, array('01', '10'))) : ?>
                <label class="common_label" for="maker">メーカー</label>
                <select style="margin-left: 1rem;" name="maker" id="maker" class="dropdown-menu">
                  <option value="0">選択して下さい。</option>
                  <?php
                  if (isset($makerList) && !empty($makerList)) {
                    foreach ($makerList as $item) {
                      $code = $item['code_no'];
                      $text = $item['code_name'];
                      $selected = ($code == $maker) ? 'selected' : '';
                      echo "<option value='$code' $selected>$text</option>";
                    }
                  }
                  ?>
                </select>
                <?php endif; ?>

                <label class="common_label" for="coating">塗装</label>
                <select style="margin-left: 1rem;" name="coating" id="coating" class="dropdown-menu">
                  <option value="0">選択して下さい。</option>
                  <?php
                  if (isset($coatingList) && !empty($coatingList)) {
                    foreach ($coatingList as $item) {
                      $code = $item['code_no'];
                      $text = $item['code_name'];
                      $selected = ($code == $coating) ? 'selected' : '';
                      echo "<option value='$code' $selected>$text</option>";
                    }
                  }
                  ?>
                </select>
              </div>
            </td>
          </tr>
          <?php endif; ?>

          <tr>
            <td>
              <div class="field-row">
              <label class="common_label" for="m_cost">原価（材料）</label>
              <input type="text" style="margin-left: 1rem;" id="m_cost" name="m_cost" value="<?= $m_cost ?>" class="input-res" oninput="cal_t_cost(), cal_m_grossprofit()">

              <!-- 弁体離脱工事実績の場合 -->
              <?php if ($ec_division == '10') : ?>
              <label class="common_label" for="wt_cost">原価（割T）</label>
              <input type="text" style="margin-left: 1rem;" id="wt_cost" name="wt_cost" value="<?= $wt_cost ?>" class="input-res" oninput="cal_t_cost(), cal_wt_grossprofit()">
              
              <label class="common_label" for="valve_cost"> 原価（バルブ）</label>
              <input type="text" id="valve_cost" name="valve_cost" value="<?= $valve_cost ?>" class="input-res" oninput="cal_t_cost(), cal_valve_grossprofit()">
              <?php endif ; ?>

              <label class="common_label" for="con_cost"> 　原価（工事）</label>
              <input type="text" id="con_cost" name="con_cost" value="<?= $con_cost ?>" class="input-res" oninput="cal_t_cost(), cal_con_grossprofit()">

              <label class="common_label" for="t_cost">　　原価（計）</label>
              <input type="text" id="t_cost" name="t_cost" value="<?= $t_cost ?>" class="readonlyText input-res" readonly>
              </div>
            </td>
          </tr>

          <tr>
            <td>
              <div class="field-row">
              <label class="common_label" for="m_orders">受注（材料）</label>
              <input type="text" style="margin-left: 1rem;" id="m_orders" name="m_orders" value="<?= $m_orders ?>" class="input-res" oninput="cal_t_orders(), cal_m_grossprofit()">

              <!-- 弁体離脱工事実績の場合 -->
              <?php if ($ec_division == '10') : ?>
              <label class="common_label" for="wt_orders">受注（割T）</label>
              <input type="text" style="margin-left: 1rem;" id="wt_orders" name="wt_orders" value="<?= $wt_orders ?>" class="input-res" oninput="cal_t_orders(), cal_wt_grossprofit()">

              <label class="common_label" for="valve_orders">受注（バルブ）</label>
              <input type="text" id="valve_orders" name="valve_orders" value="<?= $valve_orders ?>" class="input-res" oninput="cal_t_orders(), cal_valve_grossprofit()">
              <?php endif ; ?>

              <label class="common_label" for="con_orders"> 　受注（工事）</label>
              <input type="text" id="con_orders" name="con_orders" value="<?= $con_orders ?>" class="input-res" oninput="cal_t_orders(), cal_con_grossprofit()">

              <label class="common_label" for="t_orders">　　受注（計）</label>
              <input type="text" id="t_orders" name="t_orders" value="<?= $t_orders ?>" class="readonlyText input-res" readonly>
              </div>
            </td>
          </tr>

          <tr>
            <td>
              <div class="field-row">
              <label class="common_label" for="m_grossprofit">粗利（材料）</label>
              <input type="text" style="margin-left: 1rem;" id="m_grossprofit" name="m_grossprofit" value="<?= $m_grossprofit ?>" class="readonlyText input-res" readonly>

              <!-- 弁体離脱工事実績の場合 -->
              <?php if ($ec_division == '10') : ?>
              <label class="common_label" for="wt_grossprofit">粗利（割T）</label>
              <input type="text" style="margin-left: 1rem;" id="wt_grossprofit" name="wt_grossprofit" value="<?= $wt_grossprofit ?>" class="readonlyText input-res" readonly>

              <label class="common_label" for="valve_grossprofit">粗利（バルブ）</label>
              <input type="text" id="valve_grossprofit" name="valve_grossprofit" value="<?= $valve_grossprofit ?>" class="readonlyText input-res" readonly>
              <?php endif ; ?>

              <label class="common_label" for="con_grossprofit"> 　粗利（工事）</label>
              <input type="text" id="con_grossprofit" name="con_grossprofit" value="<?= $con_grossprofit ?>" class="readonlyText input-res" readonly>

              <label class="common_label" for="t_grossprofit">　　粗利（計）</label>
              <input type="text" id="t_grossprofit" name="t_grossprofit" value="<?= $t_grossprofit ?>" class="readonlyText input-res" readonly>
              </div>
            </td>
          </tr>          

          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="trouble">トラブル</label>
                <input type="radio" id="trouble1" name="trouble" value="1" <?php if ($trouble == '1') { echo "checked"; } ?>>
                <label for="trouble1" style="margin-left:35px;">有</label>

                <input type="radio" id="trouble2" name="trouble" value="2" <?php if ($trouble == '2') { echo "checked"; } ?>>
                <label for="trouble2" style="margin-left:35px;">無</label>

                <label class="common_label" for="cause">原因</label>
                <input type="text" style="margin-left: 1rem; width: 500px;" id="cause" name="cause" value="<?= $cause ?>">
              </div>
            </td>
          </tr>

          <!-- 工事区分4と5の場合だけ -->
          <?php if (in_array($ec_division, array('04', '05'))) : ?>
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="ec_name">工事名称</label>
                <input type="text" style="margin-left: 1rem; width: 800px;" id="ec_name" name="ec_name" value="<?= $ec_name ?>">
              </div>
            </td>
          </tr>
          <tr style="height:10px;"></tr>

          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="ec_ready" style="width: 110px!important;">工事開始(From～To)</label>
                <input type="date" min="2023-01-01" max="2028-12-31" name="ec_ready_from" id="ec_ready_from" value="<?= $ec_ready_from ?>" class="input-res" />

                <label>～</label>
                <input type="date" min="2023-01-01" max="2028-12-31" name="ec_ready_to" id="ec_ready_to" value="<?= $ec_ready_to ?>" class="input-res" />
              </div>
            </td>
          </tr>
          <tr style="height:10px;"></tr>
          <?php endif ; ?>

          <!-- 工事区分１と２の場合だけ -->
          <?php if (in_array($ec_division, array('01', '02'))) : ?>
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="gross_footnote">粗利備考</label>
                <textarea id="gross_footnote" style="margin-left: 1rem;" name="gross_footnote" rows="3" cols="120" class="textarea-res"><?= $gross_footnote ?></textarea>
              </div>
            </td>
          </tr>
          <tr style="height:10px;"></tr>
          <?php endif ; ?>
          
          <tr>
            <td>
              <div class="field-row">
                <!-- 工事区分２と６、７，８の場合だけ -->
                <?php if (in_array($ec_division, array('02', '06', '07', '08'))) : ?>
                <label class="common_label" for="bifurcation">分岐形状</label>
                <select style="margin-left: 1rem;" name="bifurcation" id="bifurcation" class="dropdown-menu">
                  <option value="0">選択して下さい。</option>
                  <?php
                  if (isset($bifurcationList) && !empty($bifurcationList)) {
                    foreach ($bifurcationList as $item) {
                      $code = $item['code_no'];
                      $text = $item['code_name'];
                      $selected = ($code == $bifurcation) ? 'selected' : '';
                      echo "<option value='$code' $selected>$text</option>";
                    }
                  }
                  ?>
                </select>
                <?php endif ; ?>

                <!-- 工事区分２場合だけ -->
                <?php if ($ec_division == '02') : ?>
                <label class="common_label" for="shape">形</label>
                <input type="text" style="margin-left: 1rem;" id="shape" name="shape" value="<?= $shape ?>" class="input-res">
                <?php endif ; ?>

                <!-- 工事区分６、７，８、９，１０の場合だけ -->
                <?php if (in_array($ec_division, array('06', '07', '08', '09', '10'))) : ?>
                <label class="common_label" for="drill">穿孔機</label>
                <select style="margin-left: 1rem;" name="drill" id="drill" class="dropdown-menu">
                  <option value="0">選択して下さい。</option>
                  <?php
                  if (isset($drillList) && !empty($drillList)) {
                    foreach ($drillList as $item) {
                      $code = $item['code_no'];
                      $text = $item['code_name'];
                      $selected = ($code == $drill) ? 'selected' : '';
                      echo "<option value='$code' $selected>$text</option>";
                    }
                  }
                  ?>
                </select>
                <?php endif ; ?>

                <!-- 工事区分６場合だけ -->
                <?php if ($ec_division == '06') : ?>
                <label class="common_label" for="drill2">穿孔機2回目</label>
                <select style="margin-left: 1rem;" name="drill2" id="drill2" class="dropdown-menu">
                  <option value="0">選択して下さい。</option>
                  <?php
                  if (isset($drill2List) && !empty($drill2List)) {
                    foreach ($drill2List as $item) {
                      $code = $item['code_no'];
                      $text = $item['code_name'];
                      $selected = ($code == $drill2) ? 'selected' : '';
                      echo "<option value='$code' $selected>$text</option>";
                    }
                  }
                  ?>
                </select>
                <?php endif ; ?>
              </div>
            </td>
          </tr>
          
          <!-- 工事区分4と5のじゃない場合 -->
          <?php if (!in_array($ec_division, array('04', '05'))) : ?>
          <tr>
            <td>
              <div class="field-row">
                <!-- 工事区分６、７，８、９，１０の場合だけ -->
                <?php if (in_array($ec_division, array('06', '07', '08', '09', '10'))) : ?>
                <label class="common_label" for="water_pressure">水圧</label>
                <input type="text" style="margin-left: 1rem;" id="water_pressure" name="water_pressure" value="<?= $water_pressure ?>" class="input-res">
                <?php endif ; ?>

                <label class="common_label" for="quantity">数量</label>
                <input type="text" style="margin-left: 1rem;" id="quantity" name="quantity" value="<?= $quantity ?>" class="input-res">

                <!-- 工事区分１０場合だけ -->
                <?php if ($ec_division == '10') : ?>
                <label class="common_label" for="wt_bifurcation">割T字形状</label>
                <select style="margin-left: 1rem;" name="wt_bifurcation" id="wt_bifurcation" class="dropdown-menu">
                  <option value="0">選択して下さい。</option>
                  <?php
                  if (isset($wt_bifurcationList) && !empty($wt_bifurcationList)) {
                    foreach ($wt_bifurcationList as $item) {
                      $code = $item['code_no'];
                      $text = $item['code_name'];
                      $selected = ($code == $wt_bifurcation) ? 'selected' : '';
                      echo "<option value='$code' $selected>$text</option>";
                    }
                  }
                  ?>
                </select>
                <?php endif ; ?>
              </div>
            </td>
          </tr>
          <tr style="height:10px;"></tr>  
          <?php endif ; ?>                  

          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="footnote1">備考</label>
                <textarea id="footnote1" style="margin-left: 1rem;" name="footnote1" rows="3" cols="120" class="textarea-res"><?= $footnote1 ?></textarea>
              </div>
            </td>
          </tr>
          <tr style="height:10px;"></tr>  

          <!-- 工事区分4と5の場合だけ -->
          <?php if (in_array($ec_division, array('04', '05'))) : ?>
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="footnote2">備考２</label>
                <textarea id="footnote2" style="margin-left: 1rem;" name="footnote2" rows="3" cols="120" class="textarea-res"><?= $footnote2 ?></textarea>
              </div>
            </td>
          </tr>
          <?php endif ; ?>
          <tr style="height:20px;"></tr>
          <tr>
            <td>
            <div class="flex-container">
                <div>
                  <button id="returnBtn" class="returnBtn">前の画面に戻る </button>
                </div>
                <div>
                  <button id="updateBtn" class="updateBtn" name="submit"><?= $btn_name ?></button>
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
<script src="assets/js/ec_work_check.js"></script>
<script src="assets/js/customer_ent.js"></script> 
<script src="assets/js/public_office_ent.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    //戻るボタンを押下する場合
    $("#returnBtn").click(function(event) {
      //確認メッセージを書く
      var msg = "前の画面に戻します。よろしいですか？";
      //何の処理科を書く
      var process = "return";
      //確認Dialogを呼ぶ
      openConfirmModal(msg, process);
    });

    /**-------------------------------------------------------------------------------------------------------------- */

    //登録更新ボタンを押下場合
    $('#updateBtn').click(function() {
      event.preventDefault();
      var errMessage = '';
      var errMessage = checkValidation();

      //エラーがある場合
      if (errMessage !== '') {
        //何の処理かを書く
        var process = "validate";
        //change font color
        $('#ok-message').css('color', 'red');
        //OKDialogを呼ぶ
        openOkModal(errMessage, process);
      } else {
        var btnName = $('#updateBtn').text();
        //確認メッセージを書く
        var msg = btnName + "します？よろしいですか？";
        //何の処理科を書く
        var process = "update";
        
        //確認Dialogを呼ぶ
        openConfirmModal(msg, process);
      }
    });

    /**-------------------------------------------------------------------------------------------------------------- */

    //確認BOXにはいボタンを押下する場合
    $("#confirm_okBtn").click(function(event) {
      var process = $("#btnProcess").val();
      //戻る処理の場合
      if (process == "return") {
        $("#ec_work_form2").attr("action", "ec_work_input1.php");
      }
      //ヘッダ更新処理の場合
      else if (process == "update") {
        //submitしたいボタン名をセットする
        $("#confirm_okBtn").attr("name", "submit");
        //sales_request_update.phpへ移動する
        $("#ec_work_form2").attr("action", "ec_work_update.php");
      }
    });

    /**-------------------------------------------------------------------------------------------------------------- */

    //ALERT BOXに"はい"ボタンを押下する場合
    $("#ok_okBtn").click(function(event) {
      var process = $("#btnProcess").val();

      if (process == "errExec") {
        //sq_class_input1へ移動
        $('#ec_work_form2').attr('action', 'ec_work_input1.php');
      } else {
        //画面上変更なし
        $('#ok_okBtn').attr('data-dismiss', 'modal');
      }
    });

    /**-------------------------------------------------------------------------------------------------------------- */

    //エラーがあるかどうか確認する
    var err = '<?= $err ?>';
    //エラーがある場合
    if (err !== '') {
      //OKメッセージを書く
      var msg = "処理にエラーがありました。係員にお知らせください。";
      //OKDialogを呼ぶ
      openOkModal(msg, 'errExec');
    }
  });
  /**---------------------------------------------Javascript----------------------------------------------------------------- */
  function openConfirmModal(msg, process) {
    event.preventDefault();
    //何の処理かをセットする
    $("#btnProcess").val(process);
    //確認メッセージをセットする
    $("#confirm-message").text(msg);
    //確認Dialogを呼ぶ
    $("#confirm").modal({
      backdrop: false
    });
  }

  /**-------------------------------------------------------------------------------------------------------------- */

  function openOkModal(msg, process) {
    //何の処理かをセットする
    $("#btnProcess").val(process);
    //確認メッセージをセットする
    $("#ok-message").text(msg);
    //確認Dialogを呼ぶ
    $("#ok").modal({
      backdrop: false
    });
  }

  /**-------------------------------------------------------------------------------------------------------------- */

  // t_cost(原価(計))を計算する
  function cal_t_cost() {
    var ec_division = '<?= $ec_division ?>';          //工事区分    
    let con_cost = parseFloat($('#con_cost').val(), 10);  //原価(工事)

    //工事区分＝１０じゃない場合、
    if (ec_division !== '10') {
      let m_cost = parseFloat($('#m_cost').val(), 10);      //原価(材料)
      var t_cost = (m_cost + con_cost).toFixed(2);
    } else {
      let wt_cost = parseFloat($('#wt_cost').val(), 10);      //原価(割T)
      let valve_cost = parseFloat($('#valve_cost').val(), 10);//原画(バルブ)
      var t_cost =  (wt_cost + valve_cost + con_cost).toFixed(2);
    }
    $('#t_cost').val(t_cost); //原価(計)
    cal_t_grossprofit()
  }

  /**-------------------------------------------------------------------------------------------------------------- */

  // t_orders(受注(計))を計算する
  function cal_t_orders() {
    var ec_division = '<?= $ec_division ?>';              //工事区分    
    let con_orders = parseFloat($('#con_orders').val(), 10);  //受注(工事)

    //工事区分＝１０じゃない場合、
    if (ec_division !== '10') {
      let m_orders = parseFloat($('#m_orders').val(), 10);      //受注(材料)
      var t_orders = (m_orders + con_orders).toFixed(2);
    } else {
      let wt_orders = parseFloat($('#wt_orders').val(), 10);      //受注(割T)
      let valve_orders = parseFloat($('#valve_orders').val(), 10);//受注(バルブ)
      var t_orders =  (wt_orders + valve_orders + con_orders).toFixed(2);
    }
    $('#t_orders').val(t_orders); //受注(計)
    cal_t_grossprofit()
  }

  /**-------------------------------------------------------------------------------------------------------------- */

  // m_grossprofit(粗利(材料))を計算する
  function cal_m_grossprofit() {
    let m_orders = parseFloat($('#m_orders').val(), 10);  //受注(材料)
    let m_cost = parseFloat($('#m_cost').val(), 10);      //原価(材料)
    var m_grossprofit = ((m_orders - m_cost) / m_orders).toFixed(2);
    $('#m_grossprofit').val(m_grossprofit); //仕切(材料)
  }

  /**-------------------------------------------------------------------------------------------------------------- */

  // wt_grossprofit(粗利(割T))を計算する
  function cal_wt_grossprofit() {
    let wt_orders = parseFloat($('#wt_orders').val(), 10);  //受注（割T）
    let wt_cost = parseFloat($('#wt_cost').val(), 10);      //原価(割T)
    var wt_grossprofit = ((wt_orders - wt_cost) / wt_orders).toFixed(2);
    $('#wt_grossprofit').val(wt_grossprofit); //粗利(割T)
  }

  /**-------------------------------------------------------------------------------------------------------------- */

  // valve_grossprofit(粗利(バルブ))を計算する
  function cal_valve_grossprofit() {
    let valve_orders = parseFloat($('#valve_orders').val(), 10);  //受注(バルブ)
    let valve_cost = parseFloat($('#valve_cost').val(), 10);      //原画(バルブ)
    var valve_grossprofit = ((valve_orders - valve_cost) / valve_orders).toFixed(2);
    $('#valve_grossprofit').val(valve_grossprofit); //粗利(バルブ)
  }

  /**-------------------------------------------------------------------------------------------------------------- */

  // con_grossprofit(粗利(工事))を計算する
  function cal_con_grossprofit() {
    let con_orders = parseFloat($('#con_orders').val(), 10);  //受注(工事)
    let con_cost = parseFloat($('#con_cost').val(), 10);      //原価(工事)
    var con_grossprofit = ((con_orders - con_cost) / con_orders).toFixed(2);
    $('#con_grossprofit').val(con_grossprofit); //粗利(工事)
  }

  /**-------------------------------------------------------------------------------------------------------------- */

  // t_grossprofit(粗利(計))を計算する
  function cal_t_grossprofit() {
    let t_orders = parseFloat($('#t_orders').val(), 10);  //受注(計)
    let t_cost = parseFloat($('#t_cost').val(), 10);      //原価（計）
    var t_grossprofit = ((t_orders - t_cost) / t_orders).toFixed(2);
    $('#t_grossprofit').val(t_grossprofit); //粗利(計)
  }

    /**-------------------------------------------------------------------------------------------------------------- */
  document.addEventListener('DOMContentLoaded', function() {
    const chkList = ['m_listprice', 'wt_listprice', 'valve_listprice', 'con_listprice', 'm_cost', 'wt_cost', 'valve_cost', 'con_cost', 'm_orders',
                      'wt_orders', 'valve_orders', 'con_orders', 'quantity'
    ];
    chkList.forEach(ele => {
      var ele = document.getElementById(ele);

      if (ele) {
        ele.addEventListener('input', function(e) {
          var value = e.target.value;
        e.target.value = value.replace(/[^0-9.]/g, '');  
        });
      }
    });
  })

  
</script>
<?php
// フッターセット
footer_set();
?>
<style>
  .dropdown-menu {
    width: 180px;
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

  .flex-container>div {
    margin: 20px 5px;
  }

  @media only screen and (max-width:1300px) {
    .input-res {
      width: 100px;
    }

    .textarea-res {
      width: 625px;
    }

    .createBtn {
      width: 80px;
      margin-right: 60px;
    }

    .business_daily_report {
      width: 625px !important;
    }
  }

  @media only screen and (max-width:1000px) {
    .common_label {
      width: 80px;
    }

    .input-res {
      width: 80px;
    }

    .business_daily_report {
      width: 524px !important;
    }

    .textarea-res {
      width: 524px;
    }

    .createBtn {
      width: 80px;
      margin-right: 60px;
    }
  }

  @media only screen and (max-width:822px) {

    input[type="checkbox"]+label,
    input[type="file"],
    input[type="submit"] {
      font-size: 8pt;
    }

    main,
    button {
      font-size: 8pt;
    }

    .common_label {
      width: 70px;
    }

    .input-res {
      width: 70px;
    }

    .business_daily_report {
      width: 470px !important;
    }

    .textarea-res {
      width: 470px;
      height: 40px;
    }

    .createBtn {
      width: 80px;
      margin-right: 60px;
    }

    .search_btn {
      font-size: 8pt;
      width: 80px;
    }
  }

  @media only screen and (max-width:734px) {

    input[type="checkbox"]+label,
    input[type="file"],
    input[type="submit"] {
      font-size: 8pt;
    }

    main,
    button {
      font-size: 8pt;
    }

    .common_label {
      width: 50px;
    }

    .input-res {
      width: 55px;
    }

    .business_daily_report {
      width: 380px !important;
    }

    .textarea-res {
      width: 380px;
      height: 40px;
    }

    .createBtn {
      width: 80px;
      margin-right: 60px;
    }

    .search_btn {
      font-size: 8pt;
      width: 80px;
    }
  }

  @media only screen and (max-width:430px) {

    input[type="checkbox"]+label,
    input[type="file"],
    input[type="submit"],
    .copyBtn,
    .updateBtn {
      font-size: 5pt;
    }

    main,
    button {
      font-size: 5pt;
    }

    .common_label {
      width: 15px;
    }

    .input-res {
      width: 15px;
    }

    .textarea-res {
      width: 250px;
    }

    .search_btn {
      font-size: smaller;
      width: 45px;
    }

    button {
      min-width: 20px !important;
    }

    .business_daily_report {
      width: 250px !important;
    }

    .foot {
      width: max-content;
      margin: 0 !important;
    }
  }

  .business_daily_report {
    width: 630px;
  }
</style>