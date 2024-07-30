<div class="container">
  <form class="row g-3" method="POST" name="inq_ent" enctype="multipart/form-data" id="input2">
    <?php include('dialog.php'); ?>
    <input type="hidden" name="status" id="status" value="<?= $status ?>">
    <table style="width:auto;">
      <tr style="height:10px; margin-top:20px"></tr>
      <tr style="height:10px;"></tr>
      <tr>
        <td>
          <div class="field-row">
            <label class="common_cal_label" for="user_name" >登録者</label>
            <input type="text" style="margin-left: 1rem;" class="readonlyText input-res" name="user_name" value="<?= $user_name ?>" readonly>
            <input type="hidden" name="fwt_m_no" id="fwt_m_no" value="<?= $fwt_m_no ?>">
            <input type="hidden" name="title" id="title" value="<?= $title ?>">
            
            <label class="common_cal_label" for="office_name">　　部署</label>
            <input type="text" style="width:370px;" name="office_name" class="readonlyText input-res" value="<?= $office_name ?>" readonly>

            <label class="common_cal_label" for="office_position_name" >　　役職</label>
            <input type="text" style="width:100px;" class="readonlyText input-res" name="office_position_name" value="<?= $office_position_name ?>" readonly>
          </div>
          <div class="field-row">
            <label class="common_cal_label" for="class">種類 </label>
            <select id="class" name="class" class="dropdown-menu" style="margin-left: 1rem;">
              <option value="">選択して下さい。</option>
              <option value="1" <?php if ($class == '1') echo 'selected'; ?>>工場見学</option>
              <option value="2" <?php if ($class == '2') echo 'selected'; ?>>立会検査</option>
              <option value="3" <?php if ($class == '3') echo 'selected'; ?>>技術研修</option>
            </select>            
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
            <label class="common_cal_label" for="candidate1_date">第１候補日</label>
            <input type="date" id="candidate1_date" style="margin-left: 1rem;" min="2023-01-01" max="2028-12-31" name="candidate1_date" value="<?= $candidate1_date ?>" class="input-res"/>
            
            <label class="common_cal_label" for="candidate1_start">　　時間</label>
            <input type="time" id="candidate1_start" name="candidate1_start" value="<?= $candidate1_start ?>" class="input-res">

            <label for="candidate1_end">　　～</label>
            <input type="time" id="candidate1_end" style="margin-left: 1rem;" name="candidate1_end" value="<?= $candidate1_end ?>" class="input-res">
          </div>
        </td>  
      </tr>
      <tr style="height:10px;"></tr>
      <tr>
        <td>
          <div class="field-row">
            <label class="common_cal_label" for="candidate2_date">第２候補日</label>
            <input type="date" id="candidate2_date" style="margin-left: 1rem;" min="2023-01-01" max="2028-12-31" name="candidate2_date" value="<?= $candidate2_date ?>" class="input-res"/>
            
            <label class="common_cal_label" for="candidate2_start">　　時間</label>
            <input type="time" id="candidate2_start" name="candidate2_start" value="<?= $candidate2_start ?>" class="input-res">

            <label for="candidate2_end">　　～</label>
            <input type="time" id="candidate2_end" style="margin-left: 1rem;"  name="candidate2_end" value="<?= $candidate2_end ?>" class="input-res">
          </div>
        </td>  
      </tr>
      <tr style="height:10px;"></tr>
      <tr>
        <td>
          <div class="field-row">
            <label class="common_cal_label" for="candidate3_date">第３候補日</label>
            <input type="date" id="candidate3_date" style="margin-left: 1rem;" min="2023-01-01" max="2028-12-31" name="candidate3_date" value="<?= $candidate3_date ?>" class="input-res"/>
            
            <label class="common_cal_label" for="candidate3_start">　　時間</label>
            <input type="time" id="candidate3_start" name="candidate3_start" value="<?= $candidate3_start ?>" class="input-res">

            <label for="candidate3_end">　　～</label>
            <input type="time" id="candidate3_end" style="margin-left: 1rem;" name="candidate3_end" value="<?= $candidate3_end ?>" class="input-res">
          </div>
        </td>  
      </tr>
      <tr style="height:10px;"></tr>
      <tr>
        <td>
          <div class="field-row">
            <label class="common_cal_label" for="fixed_date"><b>確定日程</b></label>
            <input type="date" id="fixed_date" style="margin-left: 1rem;" min="2023-01-01" max="2028-12-31" name="fixed_date" value="<?= $fixed_date ?>" class="input-res"/>
            
            <label class="common_cal_label" for="fixed_start">　　時間</label>
            <input type="time" id="fixed_start" name="fixed_start" value="<?= $fixed_start ?>" class="input-res">

            <label  for="fixed_end">　　～</label>
            <input type="time" id="fixed_end" style="margin-left: 1rem;" name="fixed_end" value="<?= $fixed_end ?>" class="input-res">
          </div>
        </td>  
      </tr>
      <tr style="height:10px;"></tr>
      <tr>
        <td>
          <div class="field-row">            
            <label class="common_cal_label" for="p_office_no">受注官庁 </label>
              <input type="hidden" name="pf_code" id="pf_code" value="<?= $p_office_no ?>">
              <input type="text" style="margin-left: 1rem;" id="pf_name" name="pf_name" value="" class="readonlyText input-res" readonly>
              <button id="search_pf" class="search_btn">官庁検索</button>

            <label class="common_cal_label" for="cust_no" style="margin-left: 4rem;">来客社名</label>
              <input type="text" style="margin-left: 1rem;" id="cust_name" name="cust_name" value="" class="readonlyText input-res" readonly>
              <input type="hidden" name="cust_code" id="cust_code" value="<?= $cust_no ?>">
              <button id="search_cus" class="search_btn">社名検索</button>
          </div>
        </td>
      </tr>
      <tr style="height:10px;"></tr>
      <tr>
        <td>
          <div class="field-row">
            <label class="common_cal_label" for="post_name">役職・氏名　等</label>
            <textarea id="post_name" style="margin-left: 1rem;" name="post_name" rows="3" cols="90" class="textarea-res"><?= $post_name ?></textarea>

            <label class="common_cal_label" for="p_number">人　数</label>
            <input type="text" id="p_number" name="p_number" value="<?= $p_number ?>">
          </div>
        </td>
      </tr>
      <tr>
        <td>
          <div class="field-row">
            <label class="common_cal_label" for="companion">当社同行者</label>
            <input type="text" style="margin-left: 1rem;" id="companion" name="companion" value="<?= $companion ?>">
          </div>
        </td>
      </tr>
      <tr>
        <td>
          <div class="field-row">
          <label class="common_cal_label" for="purpose">目　的</label>
          <textarea id="purpose" style="margin-left: 1rem;" name="purpose" rows="3" cols="120" class="textarea-res" 
          placeholder="※目的は、詳細に明記して下さい！"><?= $purpose ?></textarea>
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
            <input type="checkbox" id="qm_visit" name="qm_visit" value="1">
            <label class="common_cal_label" for="qm_visit">品質管理見学 </label>
            
            <input type="checkbox" id="fb_visit" name="fb_visit" value="1">
            <label class="common_cal_label" for="fb_visit">工場棟見学 </label>
            
            <input type="checkbox" id="er_visit" name="er_visit" value="1">
            <label class="common_cal_label" for="er_visit" style="width: auto;">展示ルーム見学 </label>
          </div>
        </td>  
      </tr>
      <!-- 技術研修（class=3）の時は非表示 -->
      <?php if ($class !== '3') : ?>
      <tr id="class1">
        <td>
          <div class="field-row">
            <input type="checkbox" id="p_demo" name="p_demo" value="1">
            <label class="common_cal_label" for="p_demo">製品デモ </label>
            
            <textarea id="p_demo_note" style="margin-left: 1rem;" name="p_demo_note" rows="3" cols="60" class="textarea-res"></textarea>
            
            <input type="checkbox" id="dvd_gd" name="dvd_gd" value="1">
            <label class="common_cal_label" for="dvd_gd">DVD案内 </label>
            
            <textarea id="dvd_gd_note" style="margin-left: 1rem;" name="dvd_gd_note" rows="3" cols="60" class="textarea-res"></textarea>
          </div>
        </td>  
      </tr>
      <?php endif ;?>
      <tr style="height:10px;"></tr>
      <tr>
        <td>
          <div class="field-row">
            <label class="common_cal_label" for="">配布資料 </label>

            <input type="radio" id="d_document1" name="d_document" value="1">
            <label class="common_label" for="d_document1" style="margin-left:35px;">無し</label>

            <input type="radio" id="d_document2" name="d_document" value="2">
            <label class="common_label" for="d_document2">営業手配</label>

            <input type="radio" id="d_document3" name="d_document" value="3">
            <label class="common_label" for="d_document3">工場手配</label>
            
            <label class="common_cal_label" for="">内　容 </label>
            <textarea id="d_document_note" style="margin-left: 1rem;" name="d_document_note" rows="3" cols="60" class="textarea-res"></textarea>
          </div>
        </td>  
      </tr>
      <tr style="height:10px;"></tr>
      <tr>
        <td>
          <div class="field-row">
            <label class="common_cal_label" for="">来場方法 </label>
            <input type="radio" id="ht_visit1" name="ht_visit" value="1">            
            <label class="common_label" for="ht_visit1" style="margin-left:35px;">社用車</label>

            <input type="radio" id="ht_visit2" name="ht_visit" value="2">
            <label class="common_label" for="ht_visit2">バス</label>

            <input type="radio" id="ht_visit3" name="ht_visit" value="3">
            <label class="common_label" for="ht_visit3">送迎必要</label>

          </div>
        </td>  
      </tr>
      <tr style="height:10px;"></tr>
      <tr>
        <td>
          <div class="field-row">
            <label class="common_cal_label" for="">昼食手配 </label>

            <input type="radio" id="lunch1" name="lunch" value="1">            
            <label class="common_label" for="lunch1" style="margin-left:35px;">弁当</label>

            <input type="radio" id="lunch2" name="lunch" value="2">
            <label class="common_label" for="lunch2">外食</label>

            <input type="radio" id="lunch3" name="lunch" value="3">
            <label class="common_label" for="lunch3">不要</label>        
          </div>
        </td>  
      </tr>
      <tr style="height:10px;"></tr>
      <tr>
        <td>
          <div class="field-row">            
            <label class="common_cal_label" for="other_req">その他客先要望 </label>            
            <textarea id="other_req" style="margin-left: 1rem;" name="other_req" rows="3" cols="90" class="textarea-res"><?= $other_req ?></textarea>
          </div>
        </td>  
      </tr>
      <tr style="height:10px;"></tr>
      <tr>
        <td>
          <div class="field-row">            
            <label class="common_cal_label" for="note">備　考 </label>            
            <textarea id="note" style="margin-left: 1rem;" name="note" rows="3" cols="90" class="textarea-res" 
            placeholder="※Welcomeボードへ記載する社名等記入して下さい"><?= $note ?></textarea>
          </div>
        </td>  
      </tr>      
      <tr style="height:10px;"></tr>
    </table>
    <!-- 立会検査（class=2）の時のみ表示 -->
    <?php if ($class == '2') : ?>
    <table id="class2" style="width:auto;">
      <tr>
        <hr>
      </tr>
      <tr>
        <td>
          <div class="field-row" style="margin-top: 20px;">
            <font size=3>
              <b>立会検査</b>
            </font>
          </div>
        </td>
      </tr>
      <tr style="height:10px;"></tr>
      <tr>
        <td>
          <div class="field-row">
            <label class="common_cal_label" for="name">品名</label>
            <input type="text" style="margin-left: 1rem;" id="name" name="name" value="<?= $name ?>">

            <label class="common_cal_label" for="size">サイズ</label>
            <input type="text" style="margin-left: 1rem;" id="size" name="size" value="<?= $size ?>">

            <label class="common_cal_label" for="quantity">数量</label>
            <input type="text" style="margin-left: 1rem;" id="quantity" name="quantity" value="<?= $quantity ?>">         
            
          </div>
        </td>  
      </tr>
      <tr>
        <td>
          <div class="field-row">
            <label class="common_cal_label" for="card_no">カード番号</label>
            <input type="text" style="margin-left: 1rem;" id="card_no" name="card_no" value="<?= $card_no ?>"> 
          </div>
        </td>  
      </tr>
      <tr style="height:10px;"></tr>
      <tr>
        <td>
          <div class="field-row">
            <label class="common_cal_label" for="">検査内容 </label>
            <input type="checkbox" id="inspection1" name="inspection" value="1" <?php if (in_array('1', $inspection_arr)) echo 'checked' ?>>
            <label class="common_cal_label" for="inspection1">水圧検査 </label>            
          </div>
        </td>  
      </tr>
      <tr>
        <td>
          <div class="field-row">
            <input type="checkbox" id="inspection2" name="inspection" value="2" <?php if (in_array('2', $inspection_arr)) echo 'checked' ?>>
            <label class="common_cal_label" for="inspection2">水圧検査 </label>
            
            <input type="checkbox" id="inspection3" name="inspection" value="3" <?php if (in_array('3', $inspection_arr)) echo 'checked' ?>>
            <label class="common_cal_label" for="inspection3">寸法検査 </label>
            
            <input type="checkbox" id="inspection4" name="inspection" value="4" <?php if (in_array('4', $inspection_arr)) echo 'checked' ?>>
            <label class="common_cal_label" for="inspection4">塗装検査 </label>
            
            <input type="checkbox" id="inspection5" name="inspection" value="5" <?php if (in_array('5', $inspection_arr)) echo 'checked' ?>>
            <label class="common_cal_label" for="inspection5">材料試験 </label>
            
          </div>
        </td>  
      </tr>
      <tr>
        <td>
          <div class="field-row">
            <input type="checkbox" id="inspection6" name="inspection" value="6" <?php if (in_array('6', $inspection_arr)) echo 'checked' ?>>
            <label class="common_cal_label" for="inspection6">性能検査 </label>
          </div>
        </td>  
      </tr>
      <tr>
        <td>
          <div class="field-row">
            <input type="checkbox" id="inspection8" name="inspection" value="7" <?php if (in_array('7', $inspection_arr)) echo 'checked' ?>>
            <label class="common_cal_label" for="inspection8">その他 </label>
            <textarea id="inspection_note" style="margin-left: 1rem;" name="inspection_note" rows="1" cols="90" class="textarea-res"></textarea>
          </div>
        </td>  
      </tr>
      <tr style="height:10px;"></tr>
      
    </table>    
    <?php endif; ?>    
    <!-- 技術研修（class=3）の時のみ表示 -->
    <?php if ($class == '3') : ?>
    <table id="class3" style="width:auto;">
      <tr>
        <hr>
      </tr>
      <tr>
        <td>
          <div class="field-row" style="margin-top: 20px;">
            <font size=3>
              <b>技術研修</b>
            </font>
          </div>
        </td>
      </tr>
      <tr style="height:10px;"></tr>
      <tr>
        <td>
          <div class="field-row">
          <label class="common_cal_label" for="training_plan">研修内容項目 </label>
            <select id="training_plan" name="training_plan" class="dropdown-menu" style="margin-left: 1rem;">
              <option value="<?= $training_plan ?>">選択して下さい。</option>
              <?php
              if (isset($training_plan_list) && !empty($training_plan_list)) {
                foreach ($training_plan_list as $item) {
                  $code = $item['training_plan'];
                  $text = $item['training_plan'];
                  
                  $selected = ($code == $training_plan) ? 'selected' : '';
                  echo "<option value='$code' $selected>$text</option>";
                }
              }
              ?>
            </select>   

            <input type="checkbox" id="lecture" name="lecture" value="1" <?php if ($lecture == '1') echo 'checked' ?>>
            <label class="common_cal_label" for="lecture">座学 </label> 

            <input type="checkbox" id="demonstration" name="demonstration" value="1" <?php if ($demonstration == '1') echo 'checked' ?>>
            <label class="common_cal_label" for="demonstration">実演 </label> 
            
            <input type="checkbox" id="experience" name="experience" value="1" <?php if ($experience == '1') echo 'checked' ?>>
            <label class="common_cal_label" for="experience">体験 </label>             
            
            <input type="hidden" name="hid_dvd" id="hid_dvd" value="">
            <pre id='dvd' for="dvd" style="width: auto;">
              <?= $dvd ?>
            </pre>             
          </div>
        </td>  
      </tr>
      <tr style="height:10px;"></tr>
      
      
    </table>  
    <?php endif; ?>
    <table style="width:auto;">
      <tr>
        <hr>
      </tr>
      <tr>
        <td>
          <div class="field-row" style="margin-top: 20px;">
            <font size=3>
              <b>資料の添付</b>
            </font>
          </div>
        </td>
      </tr>
      <tr>
        <div class="field-row">
          <td>
            アップロードするファイル ⇒ 
            <input type="file" name="uploaded_file" id="uploaded_file">
            <input type="submit" name="submitFile" id="upload" value="アップロード">
          </td>
        </div>
      </tr>
      <tr style="height:10px;"></tr>
      <table class="tab1">
        <tr>
          <th> 添付された資料 </th>
        </tr>
        <?php        
        if (!empty($fwt_m_no)) {
          $files = glob('document/fwt/*.*');
          foreach ($files as $key => $value) {
            $cut = str_replace('document/fwt/', '', $value);
            $chk = substr($cut,0,strlen($fwt_m_no));
            if($fwt_m_no == $chk){
              echo "<tr><td><a href=".$value." target='_blank'>".$value."</a></td></tr>";
            }
          }
        }
      ?>
      </table>     

      <tr>
        <div class="flex-container">
          <div>            
            <button id="returnBtn" class="returnBtn">前の画面に戻る </button>
          </div>          
          
          <?php if ($btn_name !== '') : ?>
          <div>
            <button class="approveBtn" name="submit" value="update"><?= $btn_name ?></button>
          </div>    
          <?php endif; ?>  
          
          <?php if (in_array($status, array('3', '4', '5'))) : ?>
          <div>
            <button class="skipBtn" id="reject" style="margin-left:50rem" name="submit" value="update">却下</button>
          </div>    
          <?php endif; ?> 
        </div>
      </tr>
    </table>        
  </form><!-- Vertical Form -->
</div>
<?php
  // フッターセット
  footer_set();
?>
<style>
  .dropdown-menu {
    width: 180px;
  }

  .hide {
    display : none;
  }

  pre {
    font-size: medium;
    text-align: left;
    border: none; /* Removes the border */
    padding: 0;   /* Optional: Remove padding */
    margin: 0;    /* Optional: Remove margin */
    background: none; /* Optional: Remove background color */
    white-space: pre-line;
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
      width: 625px!important;
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
      width: 524px!important;
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
    input[type="file"], input[type="submit"] {
      font-size: 8pt;
    }
    main, button {
      font-size: 8pt;
    }
    .common_label {
      width: 70px;
    }

    .input-res {
      width: 70px;
    }

    .business_daily_report {
      width: 470px!important;
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
    input[type="file"], input[type="submit"] {
      font-size: 8pt;
    }
    main, button {
      font-size: 8pt;
    }
    .common_label {
      width: 50px;
    }

    .input-res {
      width: 55px;
    }

    .business_daily_report {
      width: 380px!important;
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
    input[type="file"], input[type="submit"], .copyBtn, .updateBtn {
      font-size: 5pt;
    }
    main, button {
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
      min-width: 20px!important;
    }

    .business_daily_report {
      width: 250px!important;
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


