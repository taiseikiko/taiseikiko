<div class="container">
  <form class="row g-3" method="POST" enctype="multipart/form-data" id="card_input2">
    <input type="hidden" name="card_no" id="card_no" value="<?= $card_no ?>">
    <?php include("dialog.php") ?>
    <input type="hidden" name="process" value="<?= $process ?>">
    <table style="width:auto;">
      <tr style="height:10px; margin-top:20px"></tr>
      <tr style="height:10px;"></tr>
      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="card_no" >依頼書№</label>
            <input type="text" style="margin-left: 1rem;" class="readonlyText input-res" name="card_no" value="<?= $card_no ?>" readonly>
          </div>
        </td>
      </tr>
      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="user_name" >登録者</label>
            <input type="text" style="margin-left: 1rem;" class="readonlyText input-res" name="user_name" value="<?= $_SESSION['user_name'] ?>" readonly>
            <input type="hidden" name="user_code" value="<?= $_SESSION["login"] ?>">
            
            <label class="common_label" for="office_name">　　部署</label>
            <input type="text" style="width:370px;" name="office_name" class="readonlyText input-res" value="<?= $_SESSION['office_name'] ?>" readonly>

            <label class="common_label" for="office_position_name" >　　役職</label>
            <input type="text" style="width:100px;" class="readonlyText input-res" name="office_position_name" value="<?= $_SESSION['office_position_name'] ?>" readonly>
          </div>
        </td>      
      </tr>
      <tr>
        <td>
          <div class="field-row">
          <label class="common_label" for="text26">事業体 </label>
            <input type="text" style="margin-left: 1rem;" id="pf_name" name="pf_name" value="<?= $pf_name ?>" class="readonlyText input-res" readonly>
            <input type="hidden" name="pf_code" id="pf_code" value="<?= $pf_code ?>">
            <button class="search_btn" onclick="public_office_open(event)">事業体検索</button>

            <label class="common_label" for="preferred_date"> 出図希望日</label>
            <input type="date" style="margin-left: 1rem;" min="2023-01-01" max="2028-12-31" name="preferred_date" id="preferred_date" value="<?= $preferred_date ?>" class="input-res"/>

            <label class="common_label" for="deadline"> 納期</label>
            <input type="date" style="margin-left: 1rem;" min="2023-01-01" max="2028-12-31" name="deadline" id="deadline" value="<?= $deadline ?>" class="input-res"/>
          </div>
        </td>
      </tr>
      <tr>
        <td>
          <div class="field-row" style="margin-top: 20px;">
            <font size=3>
              <b>受注カード</b>
            </font>
          </div>
        </td>
      </tr>
      <tr>
        <div class="field-row">
          <td>
            アップロードするファイル ⇒ 
            <input type="file" name="uploaded_file">
            <input type="submit" name="submit" id="upload" value="アップロード">
          </td>
        </div>
      </tr>
      <tr style="height:10px;"></tr>
      <table class="tab1">
        <tr>
          <th> 添付された資料 </th>
        </tr>
        <?php        
        if (!empty($card_no)) {
          $files = glob('document/sales_management/*.*');
          foreach ($files as $key => $value) {
            $cut = str_replace('document/sales_management/', '', $value);
            $chk = substr($cut,0,strlen($card_no));
            if($card_no == $chk){
              echo "<tr><td><a href=".$value." target='_blank'>".$value."</a></td></tr>";
            }
          }
        }
        ?>
        <tr style="height:10px;"></tr>
      </table>
    </table>
    <table style="width:auto;">
      <tr style="height:10px;"></tr>
      <tr>
        <hr>
      </tr>
      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="procurement_no1" >資材部№</label>
            <input type="text" style="margin-left: 1rem;" class="input-res" name="procurement_no1" value="<?= $procurement_no1 ?>">
            <label class="common_label" for="maker1">製造メーカー</label>
              <input type="text" style="margin-left: 1rem;" class="business_daily_report" name="maker1" id="" value="<?= $maker1 ?>">
          </div>
        </td>
      </tr>

      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="class1">分類 </label>
            <select style="margin-left: 1rem;" class="dropdown-menu" id="classList1" name="class_code1">
              <option value="">選択して下さい。</option>
              <?php 
                if (isset($class_datas) && !empty($class_datas)) {
                  foreach($class_datas as $item) {
                    $code = $item['class_code'];
                    $text = $item['class_name'];
                    $selected = ($code == $class_code1) ? 'selected' : '';
                    echo "<option value='$code' $selected>$text</option>";
                  }
                }
              ?>
            </select>
            <label class="common_label" for="zaikoumei1">　　材工名</label>
            <select class="dropdown-menu" id="zaikoumeiList1" name="zaikoumei1">
              <option value="" class="">選択して下さい。</option>
            </select>
            <input type="hidden" name="zkm_code" id="zkm_code1" value="<?= $zkm_code1 ?>">

            <label class="common_label" for="pipe1">管種 </label>
            <select class="dropdown-menu" name="pipe1">
              <option value="">選択して下さい。</option>
              <?php 
                if (isset($pipeList) && !empty($pipeList)) {
                  foreach ($pipeList as $item) {
                    $code = $item['zk_div_data'];
                    $text = $item['zk_div_data'];
                    $selected = ($text == $pipe1 ) ? 'selected' : '';
                    echo "<option value='$code' $selected>$text</option>";
                  }
                }
              ?>
            </select>
            <label class="common_label" for="size1">　　サイズ </label>
            <input type="text" style="margin-left: 1rem; width:80px;" name="sizeA1" value="<?= $sizeA1 ?>">mm　　✖
            <input type="text" style="margin-left: 1rem; width:80px;" name="sizeB1" value="<?= $sizeB1 ?>">mm　
            <button class="approveBtn" name="detail" id="detailBtn1" style="margin-left: 3rem;" value="1" <?= $disabled_detail_btn1 ?>>詳細</button>
          </div>
        </td>
      </tr>
      <tr style="height:10px;"></tr>

      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="specification_no1" >仕様書№</label>
            <input type="text" style="margin-left: 1rem;" class="input-res" name="specification_no1" value="<?= $specification_no1 ?>">
            <label class="common_label" for="special_note1">特記事項</label>
              <input type="text" style="margin-left: 1rem;" class="business_daily_report" name="special_note1" id="" value="<?= $special_note1 ?>">
          </div>
        </td>
      </tr>
      <tr style="height:10px;"></tr>
    </table>
    <table style="width:auto;">
      <tr style="height:10px;"></tr>
      <tr>
        <hr>
      </tr>
      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="procurement_no2" >資材部№</label>
            <input type="text" style="margin-left: 1rem;" class="input-res" name="procurement_no2" value="<?= $procurement_no2 ?>">
            <label class="common_label" for="maker">製造メーカー</label>
              <input type="text" style="margin-left: 1rem;" class="business_daily_report" name="maker2" id="" value="<?= $maker2 ?>">
          </div>
        </td>
      </tr>

      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="class2">分類 </label>
            <select style="margin-left: 1rem;" class="dropdown-menu" id="classList2" name="class_code2">
              <option value="">選択して下さい。</option>
              <?php 
                if (isset($class_datas) && !empty($class_datas)) {
                  foreach($class_datas as $item) {
                    $code = $item['class_code'];
                    $text = $item['class_name'];
                    $selected = ($code == $class_code2) ? 'selected' : '';
                    echo "<option value='$code' $selected>$text</option>";
                  }
                }
              ?>
            </select>
            <label class="common_label" for="zaikoumei2">　　材工名 </label>
            <select class="dropdown-menu" id="zaikoumeiList2" name="zaikoumei2">
              <option value="" class="">選択して下さい。</option>
            </select>
            <input type="hidden" name="zkm_code" id="zkm_code2" value="<?= $zkm_code2 ?>">

            <label class="common_label" for="pipe2">管種 </label>
            <select name="pipe2" class="dropdown-menu">
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
            <label class="common_label" for="size2">　　サイズ </label>
            <input type="text" style="margin-left: 1rem; width:80px;" name="sizeA2" value="<?= $sizeA2 ?>">mm　　✖
            <input type="text" style="margin-left: 1rem; width:80px;" name="sizeB2" value="<?= $sizeB2 ?>">mm　
            <button class="approveBtn" name="detail" id="detailBtn2" style="margin-left: 3rem;" value="2" <?= $disabled_detail_btn2 ?>>詳細</button>
          </div>
        </td>
      </tr>
      <tr style="height:10px;"></tr>

      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="specification_no2" >仕様書№</label>
            <input type="text" style="margin-left: 1rem;" class="input-res" name="specification_no2" value="<?= $specification_no2 ?>">
            <label class="common_label" for="special_note2">特記事項</label>
              <input type="text" style="margin-left: 1rem;" class="business_daily_report" name="special_note2" id="" value="<?= $special_note2 ?>">
          </div>
        </td>
      </tr>
      <tr style="height:10px;"></tr>
    </table>
    <table style="width:auto;">
      <tr style="height:10px;"></tr>
      <tr>
        <hr>
      </tr>
      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="procurement_no3" >資材部№</label>
            <input type="text" style="margin-left: 1rem;" class="input-res" name="procurement_no3" value="<?= $procurement_no3 ?>">
            <label class="common_label" for="maker">製造メーカー</label>
              <input type="text" style="margin-left: 1rem;" class="business_daily_report" name="maker3" id="" value="<?= $maker3 ?>">
          </div>
        </td>
      </tr>

      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="class3">分類 </label>
            <select style="margin-left: 1rem;" class="dropdown-menu" id="classList3" name="class_code3">
              <option value="">選択して下さい。</option>
              <?php 
                if (isset($class_datas) && !empty($class_datas)) {
                  foreach($class_datas as $item) {
                    $code = $item['class_code'];
                    $text = $item['class_name'];
                    $selected = ($code == $class_code3) ? 'selected' : '';
                    echo "<option value='$code' $selected>$text</option>";
                  }
                }
              ?>
            </select>
            <label class="common_label" for="zaikoumei3">　　材工名 </label>
            <select class="dropdown-menu" id="zaikoumeiList3" name="zaikoumei3">
              <option value="" class="">選択して下さい。</option>
            </select>
            <input type="hidden" name="zkm_code" id="zkm_code3" value="<?= $zkm_code3 ?>">

            <label class="common_label" for="pipe3">管種 </label>
            <select name="pipe3" class="dropdown-menu">
              <option value="">選択して下さい。</option>
              <?php 
                if (isset($pipeList) && !empty($pipeList)) {
                  foreach ($pipeList as $item) {
                    $code = $item['zk_div_data'];
                    $text = $item['zk_div_data'];
                    $selected = ($code == $pipe3) ? 'selected' : '';
                    echo "<option value='$code' $selected>$text</option>";
                  }
                }
              ?>
            </select>
            <label class="common_label" for="size3">　　サイズ </label>
            <input type="text" style="margin-left: 1rem; width:80px;" name="sizeA3" value="<?= $sizeA3 ?>">mm　　✖
            <input type="text" style="margin-left: 1rem; width:80px;" name="sizeB3" value="<?= $sizeB3 ?>">mm　
            <button class="approveBtn" name="detail" id="detailBtn3" style="margin-left: 3rem;" value="3" <?= $disabled_detail_btn3 ?>>詳細</button>
          </div>
        </td>
      </tr>
      <tr style="height:10px;"></tr>

      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="specification_no3" >仕様書№</label>
            <input type="text" style="margin-left: 1rem;" class="input-res" name="specification_no3" value="<?= $specification_no3 ?>">
            <label class="common_label" for="special_note3">特記事項</label>
              <input type="text" style="margin-left: 1rem;" class="business_daily_report" name="special_note3" id="" value="<?= $special_note3 ?>">
          </div>
        </td>
      </tr>
      <tr style="height:10px;"></tr>
    </table>
    <table style="width:auto;">
      <tr style="height:10px;"></tr>
      <tr>
        <hr>
      </tr>
      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="procurement_no4" >資材部№</label>
            <input type="text" style="margin-left: 1rem;" class="input-res" name="procurement_no4" value="<?= $procurement_no4 ?>">
            <label class="common_label" for="maker">製造メーカー</label>
              <input type="text" style="margin-left: 1rem;" class="business_daily_report" name="maker4" id="" value="<?= $maker4 ?>">
          </div>
        </td>
      </tr>

      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="class4">分類 </label>
            <select style="margin-left: 1rem;" class="dropdown-menu" id="classList4" name="class_code4">
              <option value="">選択して下さい。</option>
              <?php 
                if (isset($class_datas) && !empty($class_datas)) {
                  foreach($class_datas as $item) {
                    $code = $item['class_code'];
                    $text = $item['class_name'];
                    $selected = ($code == $class_code4) ? 'selected' : '';
                    echo "<option value='$code' $selected>$text</option>";
                  }
                }
              ?>
            </select>
            <label class="common_label" for="zaikoumei4">　　材工名 </label>
            <select class="dropdown-menu" id="zaikoumeiList4" name="zaikoumei4">
              <option value="" class="">選択して下さい。</option>
            </select>
            <input type="hidden" name="zkm_code" id="zkm_code4" value="<?= $zkm_code4 ?>">

            <label class="common_label" for="pipe4">管種 </label>
            <select name="pipe4" class="dropdown-menu">
              <option value="">選択して下さい。</option>
              <?php 
                if (isset($pipeList) && !empty($pipeList)) {
                  foreach ($pipeList as $item) {
                    $code = $item['zk_div_data'];
                    $text = $item['zk_div_data'];
                    $selected = ($code == $pipe4) ? 'selected' : '';
                    echo "<option value='$code' $selected>$text</option>";
                  }
                }
              ?>
            </select>
            <label class="common_label" for="size4">　　サイズ </label>
            <input type="text" style="margin-left: 1rem; width:80px;" name="sizeA4" value="<?= $sizeA4 ?>">mm　　✖
            <input type="text" style="margin-left: 1rem; width:80px;" name="sizeB4" value="<?= $sizeB4 ?>">mm　
            <button class="approveBtn" name="detail" id="detailBtn4" style="margin-left: 3rem;" value="4" <?= $disabled_detail_btn4 ?>>詳細</button>
          </div>
        </td>
      </tr>
      <tr style="height:10px;"></tr>

      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="specification_no4" >仕様書№</label>
            <input type="text" style="margin-left: 1rem;" class="input-res" name="specification_no4" value="<?= $specification_no4 ?>">
            <label class="common_label" for="special_note4">特記事項</label>
              <input type="text" style="margin-left: 1rem;" class="business_daily_report" name="special_note4" id="" value="<?= $special_note4 ?>">
          </div>
        </td>
      </tr>
      <tr style="height:10px;"></tr>
    </table>    
    
    <table style="width:auto;">
      <tr>
        <hr>
      </tr>
      <tr style="height:10px;"></tr>
      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="approver" style="margin-left: 1rem;">　　承認者 </label>
            <select name="approver" class="input-res">
              <option value="">選択して下さい。</option>
              <?php 
                if (isset($approverList) && !empty($approverList)) {
                  foreach ($approverList as $item) {
                    $code = $item['employee_code'];
                    $text = $item['employee_name'];
                    $selected = ($code == $approver) ? 'selected' : '';
                    echo "<option value='$code' $selected>$text</option>";
                  }
                }
              ?>
            </select>
          </div>
        </td>
      </tr>
      <tr style="height:10px;"></tr>
      <tr>
        <td>
          <div class="flex-container">
            <div>            
              <button id="returnBtn" class="returnBtn">前の画面に戻る </button>
              <button type="submit" class="approveBtn" id="reg_updBtn" name="submit" value="update"><?= $btn_name ?></button>
            </div>     
          </div>
        </td>
        <td>
          <div class="flex-container">
            <div>            
              <button id="remandBtn" class="returnProcessBtn" style="margin-left: 50rem;">差し戻し </button>
              <button class="cancelProcessBtn" name="submit" value="cancel">中止</button>
            </div> 
          </div>
        </td>
      </tr>          
    </table>        
  </form><!-- Vertical Form -->
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript">
  $(document).ready(function (){
    //詳細ボタンを押下場合
    $("#detailBtn1, #detailBtn2").click(function(){
      $("#card_input2").attr("action", "card_input3.php");
    });
  });
</script>
<style>
  /* .flex-container {
    display: flex;    
  }

  .flex-container > div {
    margin: 20px 5px;
  } */
</style>
<?php
// フッターセット
footer_set();
?>
