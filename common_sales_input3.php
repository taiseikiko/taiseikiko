<?php 
  $e_title = substr($title, 3);
?>
<div class="container">
  <form class="row g-3" method="POST" name="inq_ent" enctype="multipart/form-data" id="input3">
    <?php include('dialog.php'); ?>
    <input type="hidden" name="process2" id="process2" value="<?= $process2 ?>">
    <input type="hidden" name="sq_no" id="sq_no" value="<?= $sq_no ?>">
    <input type="hidden" name="sq_line_no" id="sq_line_no" value="<?= $sq_line_no ?>">
    <input type="hidden" name="record_div" id="record_div" value="<?= $record_div ?>">
    <input type="hidden" name="route_pattern" id="route_pattern" value="<?= $route_pattern?>">
    <input type="hidden" name="dept_id" id="dept_id" value="<?= $dept_id ?>">
    <input type="hidden" name="title" id="title" value="<?= $title ?>">
    <input type="hidden" name="user_code" value="<?= $_SESSION["login"] ?>">
    <input type="hidden" name="processing_status" id="processing_status" value="<?= $processing_status ?>">
    <!-- <?= 'prcessing status = '. $processing_status?> -->
    <table style="width:auto;">
      <tr style="height:10px;"></tr>
      <tr style="height:10px; margin-top:20px"></tr>
      <tr>
        <td>
          <?php include('progress_bar.php'); ?>
          <div class="field-row" style="float:left;">
            <label style="width:120px;" for="text1"><b>部署ステータス</b></label>
          </div>
          <div class="container">
            <!-- Responsive Arrow Progress Bar 部署ステータス arrow steps -->
            <div class="arrow-steps clearfix">
              <?= $dept_steps_html ?>
            </div>
          </div>
        </td>
      </tr>
      <tr style="height:20px;"></tr>
      <tr>
        <td>
          <div class="field-row" style="float:left;">
            <label style="width:120px;" for="text2"><b>処理ステータス</b></label>
          </div>
          <div class="container">
            <!-- Responsive Arrow Progress Bar 処理ステータス arrow steps-->
            <div class="arrow-steps clearfix">
              <?= $status_steps_html ?>
            </div>
          </div>
        </td>
      </tr>
      <tr style="height:20px;"></tr>
      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="estimate_div">見積区分</label>
            <input type="checkbox" id="estimate_div1" name="estimate_div1" value="1" <?php if ($estimate_div1 == '1') {echo 'checked';} if ($record_div == '2') {echo 'disabled';}?> >
            <label class="common_label" for="estimate_div1">材料費</label>

            <input type="checkbox" id="estimate_div2" name="estimate_div2" value="1" <?php if ($estimate_div2 == '1') {echo 'checked';} if ($record_div == '2') {echo 'disabled';}?>>
            <label class="common_label" for="estimate_div2">工事費</label>

            <label class="common_label" for="deadline_estimate_date">見積提出期限</label>
            <input type="date" min="2023-01-01" max="2028-12-31" name="deadline_estimate_date" value="<?= $deadline_estimate_date ?>" />
          </div>
        </td>
      </tr>
      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="specification">納入仕様書</label>
            <input type="checkbox" id="specification_div" name="specification_div" value="1" <?php if ($specification_div == '1') {echo 'checked';} if ($record_div == '1') {echo 'disabled';}?>>
            <label class="common_label" for="specification_div">仕様書必要</label>

            <label for="check_type" style="width:40px">（検査</label>
            <input type="radio" name="check_type" id="check_type1" value="1" <?php if ($check_type == '1') {echo 'checked';} ?>>
            <label id="common_label" for="check_type1" style="margin-left:35px;">日水協</label>
            <input type="radio" name="check_type" id="check_type2" value="2" <?php if ($check_type == '2') {echo 'checked';} ?>>
            <label id="common_label" for="check_type2" style="margin-left:35px;">社内証）</label>
          </div>
        </td>
      </tr>
      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="drawing">参考図面</label>
            <input type="checkbox" id="drawing_div" name="drawing_div" value="1" <?php if ($drawing_div == '1') {echo 'checked';} if ($record_div == '1') {echo 'disabled';}?>>
            <label class="common_label" for="drawing_div">参考図面必要</label>
          </div>
        </td>
      </tr>
      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="document">資料</label>
            <input type="checkbox" id="document_div" name="document_div" value="1" <?php if ($document_div == '1') {echo 'checked';} if ($record_div == '1') {echo 'disabled';}?>>
            <label class="common_label" for="document_div">資料必要</label>

            <label class="common_label" for="deadline_drawing_date">図面等提出期限</label>
            <input type="date" min="2023-01-01" max="2028-12-31" name="deadline_drawing_date" value="<?= $deadline_drawing_date ?>">
          </div>
        </td>
      </tr>
      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="cad_data">CADデータ</label>
            <input type="checkbox" id="cad_data_div" name="cad_data_div" value="1" <?php if ($cad_data_div == '1') {echo 'checked';} ?>>
            <label for="cad_data_div">CADデータ必要</label>
          </div>  
        </td>
      </tr>
      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="class">分類 </label>
            <select class="dropdown-menu" id="classList" name="class">
              <option value="">選択して下さい。</option>
              <?php 
                if (isset($class_datas) && !empty($class_datas)) {
                  foreach($class_datas as $item) {
                    $code = $item['class_code'];
                    $text = $item['class_name'];
                    $selected = ($code == $class_code) ? 'selected' : '';
                    echo "<option value='$code' $selected>$text</option>";
                  }
                }
              ?>
            </select>

            <label class="common_label" for="zaikoumei">材工名 </label>
            <select class="dropdown-menu" id="zaikoumeiList" name="zaikoumei">
              <option value="" class="">選択して下さい。</option>
            </select>
            <input type="hidden" name="zkm_code" id="zkm_code" value="<?= $zkm_code ?>">

            <label class="common_label" for="c_div">区分 </label>
            <input type="text" id="c_div" name="c_div" value="<?= $c_div ?>" class="readonlyText" readonly style="width:100px;">
            <input type="hidden" name="c_div_code" id="c_div_code">
          </div>
        </td>
      </tr>
      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="size">サイズ </label>
            <?php 
              if (!isset($sizeList) || empty($sizeList)) {
                $sizeDisabled = 'disabled';
              }
            ?>
            <select class="dropdown-menu" name="size" <?= $sizeDisabled ?>>
              <option value="">選択して下さい。</option>
              <?php 
                if (isset($sizeList) && !empty($sizeList)) {
                  foreach($sizeList as $item) {
                    $code = $item['zk_div_data'];
                    $text = $item['zk_div_data'];
                    $selected = ($code == $size) ? 'selected' : '';
                    echo "<option value='$code' $selected>$text</option>";
                  }
                }
              ?>
            </select>

            <label class="common_label" for="joint">接合形状 </label>
            <?php 
              if (!isset($jointList) || empty($jointList)) {
                $jointDisabled = 'disabled';
              }
            ?>
            <select class="dropdown-menu" name="joint" <?= $jointDisabled ?>>
              <option value="">選択して下さい。</option>
              <?php 
                if (isset($jointList) && !empty($jointList)) {
                  foreach ($jointList as $item) {
                    $code = $item['zk_div_data'];
                    $text = $item['zk_div_data'];
                    $selected = ($code == $joint) ? 'selected' : '';
                    echo "<option value='$code' $selected>$text</option>";
                  }
                }
              ?>
            </select>

            <label class="common_label" for="pipe">管種 </label>
            <?php 
              if (!isset($pipeList) || empty($pipeList)) {
                $pipeDisabled = 'disabled';
              }
            ?>
            <select class="dropdown-menu" name="pipe" <?= $pipeDisabled ?>>
              <option value="">選択して下さい。</option>
              <?php 
                if (isset($pipeList) && !empty($pipeList)) {
                  foreach ($pipeList as $item) {
                    $code = $item['zk_div_data'];
                    $text = $item['zk_div_data'];
                    $selected = ($code == $pipe) ? 'selected' : '';
                    echo "<option value='$code' $selected>$text</option>";
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
            <label class="common_label" for="size2">サイズ２ </label>
            <?php 
              if (!isset($sizeList) || empty($sizeList)) {
                $sizeDisabled = 'disabled';
              }
            ?>
            <select class="dropdown-menu" name="size2" <?= $sizeDisabled ?>>
              <option value="">選択して下さい。</option>
              <?php 
                if (isset($sizeList) && !empty($sizeList)) {
                  foreach($sizeList as $item) {
                    $code = $item['zk_div_data'];
                    $text = $item['zk_div_data'];
                    $selected = ($code == $size2) ? 'selected' : '';
                    echo "<option value='$code' $selected>$text</option>";
                  }
                }
              ?>
            </select>

            <label class="common_label" for="joint2">接合形状２ </label>
            <?php 
              if (!isset($jointList) || empty($jointList)) {
                $jointDisabled = 'disabled';
              }
            ?>
            <select class="dropdown-menu" name="joint2" <?= $jointDisabled ?>>
              <option value="">選択して下さい。</option>
              <?php 
                if (isset($jointList) && !empty($jointList)) {
                  foreach ($jointList as $item) {
                    $code = $item['zk_div_data'];
                    $text = $item['zk_div_data'];
                    $selected = ($code == $joint2) ? 'selected' : '';
                    echo "<option value='$code' $selected>$text</option>";
                  }
                }
              ?>
            </select>

            <label class="common_label" for="pipe2">管種２ </label>
            <?php 
              if (!isset($pipeList) || empty($pipeList)) {
                $pipeDisabled = 'disabled';
              }
            ?>
            <select class="dropdown-menu" name="pipe2" <?= $pipeDisabled ?>>
              <option value="">選択して下さい。</option>
              <?php 
                if (isset($pipeList) && !empty($pipeList)) {
                  foreach ($pipeList as $item) {
                    $code = $item['zk_div_data'];
                    $text = $item['zk_div_data'];
                    $selected = ($code == $pipe2) ? 'selected' : '';
                    echo "<option value='$code' $selected>$text</option>";
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
            <label class="common_label" for="special_tube_od">特殊管外径 </label>
            <input style="width:60px" type="text" id="special_tube_od1" name="special_tube_od1" value="<?= $special_tube_od1 ?>">
            <label for="special_tube_od1">mm</label>

            <input style="width:60px; margin-left:13px;" type="text" id="special_tube_od2" name="special_tube_od2" value="<?= $special_tube_od2 ?>">
            <label for="special_tube_od2">mm</label>

            <label class="common_label" for="fluid">管内流体 </label>
            <?php 
              if (!isset($fluidList) || empty($fluidList)) {
                $fluidDisabled = 'disabled';
              }
            ?>
            <select class="dropdown-menu" name="fluid" <?= $fluidDisabled ?>>
              <option value="">選択して下さい。</option>
              <?php 
                if (isset($fluidList) && !empty($fluidList)) {
                  foreach ($fluidList as $item) {
                    $code = $item['zk_div_data'];
                    $text = $item['zk_div_data'];
                    $selected = ($code == $fluid) ? 'selected' : '';
                    echo "<option value='$code' $selected>$text</option>";
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
            <label class="common_label" for="design_water_pressure">設計水圧 </label>
            <input type="text" id="design_water_pressure" name="design_water_pressure" value="<?= $design_water_pressure ?>">
            
            <input type="checkbox" id="water_outage" name="water_outage" value="1" <?php if ($water_outage == '1') {echo 'checked';} ?>>
            <label class="common_label" for="water_outage">断水</label>
            
            <label style="width: 34px;" class="common_label" for="normal_water_puressure">常圧</label>
            <input type="text" id="normal_water_puressure" name="normal_water_puressure" style="width:100px" value="<?= $normal_water_puressure ?>">
            
            <label class="common_label" for="reducing_pressure_div">施工時減圧</label>              
            <input type="radio" id="reducing_pressure_div1" name="reducing_pressure_div" value="1" <?php if ($reducing_pressure_div == '1') {echo 'checked';} ?>>
            <label style="width: auto;margin-left:35px;" class="reducing_pressure_div1" for="reducing_pressure_div1">可</label>

            <input type="radio" id="reducing_pressure_div2" name="reducing_pressure_div" value="2" <?php if ($reducing_pressure_div == '2') {echo 'checked';} ?>>
            <label style="width: auto;margin-left:35px;" class="reducing_pressure_div2" for="reducing_pressure_div2">不可</label>
          </div>
        </td>
      </tr>
      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="inner_coating">内面塗装 </label>
            <?php 
              if (!isset($inner_coatingList) || empty($inner_coatingList)) {
                $inner_coatingDisabled = 'disabled';
              }
            ?>
            <select class="dropdown-menu" name="inner_coating" <?= $inner_coatingDisabled ?>>
              <option value="">選択して下さい。</option>
              <?php 
                if (isset($inner_coatingList) && !empty($inner_coatingList)) {
                  foreach ($inner_coatingList as $item) {
                    $code = $item['zk_div_data'];
                    $text = $item['zk_div_data'];
                    $selected = ($code == $inner_coating) ? 'selected' : '';
                    echo "<option value='$code' $selected>$text</option>";
                  }
                }
              ?>
            </select>

            <label class="common_label" for="inner_film">膜厚 </label>
            <input type="text" id="inner_film" name="inner_film" value="<?= $inner_film ?>">
          </div>
        </td>
      </tr>
      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="outer_coating">外面塗装  </label>
            <?php 
              if (!isset($outer_coatingList) || empty($outer_coatingList)) {
                $outer_coatingDisabled = 'disabled';
              }
            ?>
            <select class="dropdown-menu" name="outer_coating" <?= $outer_coatingDisabled ?>>
              <option value="">選択して下さい。</option>
              <?php 
                if (isset($outer_coatingList) && !empty($outer_coatingList)) {
                  foreach ($outer_coatingList as $item) {
                    $code = $item['zk_div_data'];
                    $text = $item['zk_div_data'];
                    $selected = ($code == $outer_coating) ? 'selected' : '';
                    echo "<option value='$code' $selected>$text</option>";
                  }
                }
              ?>
            </select>

            <label class="common_label" for="outer_film">膜厚 </label>
            <input type="text" id="outer_film" name="outer_film" value="<?= $outer_film ?>">
          </div>
        </td>
      </tr>
      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="valve">バルブ仕様 </label>
            <?php 
              if (!isset($valveList) || empty($valveList)) {
                $valveDisabled = 'disabled';
              }
            ?>
            <select class="dropdown-menu" name="valve" <?= $valveDisabled ?>>
              <option value="">選択して下さい。</option>
              <?php 
                if (isset($valveList) && !empty($valveList)) {
                  foreach ($valveList as $item) {
                    $code = $item['zk_div_data'];
                    $text = $item['zk_div_data'];
                    $selected = ($code == $valve) ? 'selected' : '';
                    echo "<option value='$code' $selected>$text</option>";
                  }
                }
              ?>
            </select>

            <label class="common_label" for="o_c_direction">開閉方向 </label>
            <?php 
              if (!isset($o_c_directionList) || empty($o_c_directionList)) {
                $o_c_directionDisabled = 'disabled';
              }
            ?>
            <select class="dropdown-menu" name="o_c_direction" <?= $o_c_directionDisabled ?>>
              <option value="">選択して下さい。</option>
              <?php 
                if (isset($o_c_directionList) && !empty($o_c_directionList)) {
                  foreach ($o_c_directionList as $item) {
                    $code = $item['zk_div_data'];
                    $text = $item['zk_div_data'];
                    $selected = ($code == $valve) ? 'selected' : '';
                    echo "<option value='$code' $selected>$text</option>";
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
            <label class="common_label" for="quantity">数量  </label>
            <input type="text" id="quantity" name="quantity" value="<?= $quantity ?>">

            <label class="common_label" for="right_quantity">（ 右用</label>
            <input type="text" id="right_quantity" name="right_quantity" value="<?= $right_quantity ?>">
            
            <label class="common_label" for="left_quantity">左用</label>   
            <input type="text" id="left_quantity" name="left_quantity" value="<?= $left_quantity ?>">
            <label for="left_quantity">）</label>   
          </div>
        </td>
      </tr>
      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="const_div">工事区分</label>
            <input type="checkbox" id="const_div1" name="const_div1" value="1" <?php if ($const_div1 == '1') {echo 'checked';} ?>>
            <label class="common_label" for="const_div1">昼間</label>

            <input type="checkbox" id="const_div2" name="const_div2" value="1" <?php if ($const_div2 == '1') {echo 'checked';} ?>>
            <label class="common_label" for="const_div2">夜間 </label>

            <input type="checkbox" id="const_div3" name="const_div3" value="1" <?php if ($const_div3 == '1') {echo 'checked';} ?>>
            <label class="common_label" for="const_div3">昼間・夜間 </label>

            <input type="checkbox" id="const_div4" name="const_div4" value="1" <?php if ($const_div4 == '1') {echo 'checked';} ?>>
            <label class="common_label" for="const_div4">昼夜通し</label>
          </div>
        </td>
      </tr>
      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="special_note">特記仕様</label>
            <textarea type="text" id="special_note" name="special_note" rows="3" cols="120"><?= $special_note ?></textarea>
          </div>
        </td>
      </tr>
      <!-- 登録、確認、承認画面の場合だけに表示させる -->
      <?php
        if ($title == 'input' || $title == 'check' || $title == 'approve') { 
      ?>
      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="entrant_comments">作成者コメント</label>
            <textarea id="entrant_comment" name="entrant_comments" rows="3" cols="120" class="textarea-res"
            <?php if ($processing_status == '0') {echo 'disabled style="background-color: #e6e6e6;"';}?>><?= $entrant_comments ?></textarea>
          </div>
        </td>
      </tr>
      <?php } ?>

      <!-- 確認画面の場合だけに表示させる -->
      <?php
        if ($title == 'check' || $title == 'approve') { 
      ?>
      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="confirmer_comments">確認者コメント</label>
            <textarea id="confirmer_comment" name="confirmer_comments" rows="3" cols="120" class="textarea-res"
            <?php if ($processing_status == '0') {echo 'disabled style="background-color: #e6e6e6;"';}?>><?= $confirmer_comments ?></textarea>
          </div>
        </td>
      </tr>
      <?php } ?>

      <!-- 承認画面の場合だけに表示させる -->
      <?php 
        if ($title == 'approve') { 
      ?>
      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="approver_comments">承認者コメント</label>
            <textarea id="approver_comment" name="approver_comments" rows="3" cols="120" class="textarea-res"
            <?php if ($processing_status == '0') {echo 'disabled style="background-color: #e6e6e6;"';}?>><?= $approver_comments ?></textarea>
          </div>
        </td>
      </tr>
      <?php } ?>

      <?php
      //各部署の受付画面、と営業部のすべて画面の場合
      if ($e_title == 'receipt' || $title == 'input' || $title == 'check' || $title == 'approve') { ?>
      <tr>
        <td>
          <div class="flex-container">
            <div>
              <button id="returnBtn" class="returnBtn">前の画面に戻る </button>
            </div>
              <!-- 入力画面の場合だけに表示させる -->
              <?php if($e_title == 'receipt') { ?>
                <div>
                  <button id="updBtn" class="setEmp" style="background:#80dfff;" name="submit" onclick="person_in_charge(event)" <?php if ($processing_status == '0') {echo 'disabled';}?>>担当者設定</button>
                </div>                
              <?php } else {
                // 確認画面の場合、ボタン名を「営業依頼書　明細の確認」,
                //　承認画面の場合、「営業依頼書　明細の承認」とする
                if ($title == 'check') { $btn_name = '営業依頼書　明細の確認'; }
                else if ($title == 'approve') { $btn_name = '営業依頼書　明細の承認'; }
                else { $btn_name = '営業依頼書 明細の作成・更新'; }?>
                <div>
                  <button id="updBtn" class="createOrUpdate" style="background:#80dfff;" name="submit" <?php if ($processing_status == '0') {echo 'disabled';}?> ><?= $btn_name ?></button>
                </div>
              <?php } 
                if ($title !== 'input') { ?>
              <div style="margin-top:13px; margin-left:435px">            
                <label class="common_label" for="other">その他処理 </label>
                <select class="dropdown-menu" id="otherProcess" name="otherProcess" onchange="other_process(event)" <?php if ($processing_status == '0') {echo 'disabled';}?>>
                  <option value="" class="">選択して下さい。</option>
                  <option value="1" class="">差し戻し</option>
                  <option value="2" class="">中止</option>
                  <?php if ($showSkip) echo'<option value="3" class="">スキップ</option>'; ?>
                </select>
              </div>
              <?php } ?>
            </div>
          </div>
        </td>
      </tr>
      <?php } 
      // ルート設定画面の場合だけに表示させる
      if ($title == 'set_route') {
      ?>
      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="route_pattern">ルート設定 </label>
            <select class="dropdown-menu" id="route_no" name="route_pattern" <?php if (!empty($route_pattern)) { echo 'disabled'; } ?> <?php if ($processing_status == '0') {echo 'disabled';}?>>
              <option value="">選択して下さい。</option>
              <?php 
                if (isset($route_pattern_list) && !empty($route_pattern_list)) {
                  foreach ($route_pattern_list as $item) {
                    $code = $item['route_id'];
                    $name = isset($route_names[$code]) ? $route_names[$code] : '';
                    $selected = ($code == $route_pattern) ? 'selected' : '';
                    echo "<option value='$code' $selected>$name</option>";
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
              <button id="returnBtn" class="returnBtn">前の画面に戻る </button>
            </div>
            <div>
              <button id="updBtn" class="setRoute" style="background:#80dfff;" name="submit" <?php if ($processing_status == '0') {echo 'disabled';}?>>ルート設定 </button>
            </div>
            <div style="margin-top:13px; margin-left:435px">            
              <label class="common_label" for="other">その他処理 </label>
              <select class="dropdown-menu" id="otherProcess" name="otherProcess" onchange="other_process(event)" <?php if ($processing_status == '0') {echo 'disabled';}?>>
                <option value="" class="">選択して下さい。</option>
                <option value="1" class="">差し戻し</option>
                <option value="2" class="">中止</option>
                <?php if ($showSkip) echo'<option value="3" class="">スキップ</option>'; ?>
              </select>
            </div>
          </div>
        </td>
      </tr>
      <?php } 
      // 検索画面(sales_request_search_input)の場合だけに表示させる
      if ($title == 'search') { ?>
      <tr>
        <td>
          <div class="flex-container">
            <div>
              <button id="returnBtn" class="returnBtn">前の画面に戻る </button>
            </div>
          <div>
      </td>
      </tr>
      <?php } ?>
      <tr style="height:10px;"></tr>
    </table>
    <!-- /*..................................................................................................*/ -->
    <?php 
    $s_title = substr($title, 0, 2);
    //営業管理部の場合
    if ($s_title == 'sm') {
      if ($title !== 'sm_receipt') {
        include('sq_detail_tr_sales_management_input4.php');
      }
    } 
    //技術部の場合
    else if ($s_title == 'td') {
      if ($title !== 'td_receipt') {
        if ($record_div == '1') {
          include('sq_detail_tr_engineering_input4.php');
        } else if ($record_div == '2') {
          include('sq_detail_tr_engineering_input5.php');
        }
      }
    }
    //工事管理部の場合
    else if ($s_title == 'cm') {
      if ($title !== 'cm_receipt') {
        include('sq_detail_tr_const_management_input4.php');
      }
    } 
    //資材部の場合
    else if ($s_title == 'pc') {
      if ($title !== 'pc_receipt') {
        include('sq_detail_tr_procurement_input4.php');
      }
    } 
    ?>
  </form><!-- Vertical Form -->
</div>
<script type="text/javascript">
  //その他処理が変わる場合
  function other_process(event) {
    event.preventDefault();
    var sq_no = document.getElementById('sq_no').value;
    var sq_line_no = document.getElementById('sq_line_no').value;
    var dept_id = document.getElementById('dept_id').value;
    var title = document.getElementById('title').value;
    var route_pattern = document.getElementById('route_pattern').value;

    var process = document.getElementById('otherProcess').value;
    //スキップ処理の場合
    if (process == 3) {
      var url = "skip_division_input1.php" + "?sq_no=" + sq_no + 
      "&sq_line_no=" + sq_line_no +
      "&dept_id=" + dept_id +
      "&route_pattern=" + route_pattern +
      "&title=" + title;
    }
    //差し戻し処理の場合
    else if (process == 1) {
      var url = "sq_send_back.php" + "?sq_no=" + sq_no + 
      "&sq_line_no=" + sq_line_no +
      "&dept_id=" + dept_id +
      "&route_pattern=" + route_pattern +
      "&title=" + title;
    }
    //中止処理の場合
    if (process == 2) {
      var url = "cancel_division_input1.php" + "?sq_no=" + sq_no + 
      "&dept_id=" + dept_id +
      "&route_pattern=" + route_pattern +
      "&sq_line_no=" + sq_line_no +
      "&title=" + title;
    }
    window.open(url, "popupWindow", "width=900,height=260,left=100,top=50");
    
  }
</script>
<style>
  .dropdown-menu {
    width: 180px;
  }
/* Responsive Arrow Progress Bar */

.container {
    font-family: 'Lato', sans-serif;
    
  }

  .arrow-steps {
    --d: 1rem;
    /* arrow depth */
    --gap: 0.4rem;
    /* arrow thickness, gap */

    display: flex;
    margin-right: var(--d);
    width: 50%;

  }

  .step {
    flex: 0.25;
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
    padding: 0.6rem var(--d);
    margin-right: calc(var(--d) * -1 + var(--gap));
    background: #deebf7;
    color: #000000;
    clip-path: polygon(0% 0%,
        calc(100% - var(--d)) 0%,
        100% 50%,
        calc(100% - var(--d)) 100%,
        0% 100%,
        var(--d) 50%);
  }

  .step:first-child {
    clip-path: polygon(0% 0%,
        calc(100% - var(--d)) 0%,
        100% 50%,
        calc(100% - var(--d)) 100%,
        0% 100%);
  }

  .step.current {
    flex: 0.25;
    background: #5b9bd5;
    color: #fff;
  }

  .step.current a {
    color: #fff;
  }

  .step a {
    color: #000000;
    text-decoration: none;
  }

  #checkbox_label {
    width: 70px;
    text-align: start;
  }
  
  input.readonlyText {
    background-color: #ffffe0;
  }

  .flex-container {
    display: flex;
  }

  .flex-container > div {
    margin: 20px 5px;
  }

  #preassure2 {
      margin-left: 0px; 
      margin-right: 50px; 
  }



</style>