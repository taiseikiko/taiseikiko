<div class="container">
  <form class="row g-3" method="POST" name="inq_ent" enctype="multipart/form-data" id="input2">
    <input type="hidden" name="process" value="<?= $process ?>">
    <input type="hidden" name="dept_id" value="<?= $dept_id ?>">
    <input type="hidden" name="client" value="<?= $client ?>">
    <input type="hidden" name="title" value="<?= $title ?>">
    <table style="width:auto;">
      <input type="hidden" name="sq_no" value="<?= $sq_no ?>">
      <tr style="height:10px; margin-top:20px"></tr>
      <tr style="height:10px;"></tr>
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
            <label class="common_label" for="item_name" >件名</label>
            <textarea style="margin-left: 1rem;" name="item_name" id="item_name" rows="3" cols="120" class="readonlyText textarea-res"><?= $item_name ?></textarea>
          </div>
        </td>
      </tr>
      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="text26">提出先</label>
            <input type="text" style="margin-left: 1rem;" id="cust_name" name="cust_name" value="<?= $cust_name ?>" class="readonlyText input-res">
            <input type="hidden" name="cust_code" id="cust_code" value="<?= $cust_code ?>">
            <button class="search_btn" onclick="customer_open(event)">提出先検索</button>
            
            <label class="common_label" for="cust_dept">　　担当部署</label>
            <input type="text" id="cust_dept" name="cust_dept" value="<?= $cust_dept ?>" class="input-res">

            <label class="common_label" for="text3">　　担当者</label>
            <input type="text" id="cust_pic" name="cust_pic" value="<?= $cust_pic ?>" class="input-res">
          </div>
        </td>      
      </tr>
      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="text26">事業体 </label>
            <input type="text" style="margin-left: 1rem;" id="pf_name" name="pf_name" value="<?= $pf_name ?>" class="readonlyText input-res">
            <input type="hidden" name="pf_code" id="pf_code" value="<?= $pf_code ?>">
            <button class="search_btn" onclick="public_office_open(event)">事業体検索</button>

            <label class="common_label" for="pf_dept">　　担当部署</label>
            <input type="text" id="pf_dept" name="pf_dept" value="<?= $pf_dept ?>" class="input-res">

            <label class="common_label" for="pf_pic">　　担当者</label>
            <input type="text" id="pf_pic" name="pf_pic" value="<?= $pf_pic ?>" class="input-res">
          </div>
        </td>
      </tr>

      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="planned_order_date">発注時期</label>
            <input type="date" style="margin-left: 1rem;" min="2023-01-01" max="2028-12-31" name="planned_order_date" value="<?= $planned_order_date ?>" class="input-res"/>

            <label class="common_label" for="text26">　　発注確度 </label>
            <select name="degree_of_order" class="input-res">
              <option value="">選択して下さい。</option>
              <?php 
                if (isset($degree_of_order_list)) {
                  foreach($degree_of_order_list as $item) {
                    $code = $item['code_no'];
                    $text = $item['text1'];
                    $selected = ($code == $degree_of_order) ? 'selected' : '';
                    echo "<option value='$code' $selected>$text</option>";
                  }
                }
              ?>
            </select>

            <label class="common_label" for="text26">　　受注確度 </label>
            <select name="order_accuracy" class="input-res">
              <option value="">選択して下さい。</option>
              <?php 
                if (isset($order_accuracy_list)) {
                  foreach($order_accuracy_list as $item) {
                    $code = $item['code_no'];
                    $text = $item['text1'];
                    $selected = ($code == $order_accuracy) ? 'selected' : '';
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
            <label class="common_label" for="planned_construction_date">施工時期</label>
            <input type="date" style="margin-left: 1rem;" min="2023-01-01" max="2028-12-31" name="planned_construction_date" value="<?= $planned_construction_date ?>" class="input-res"/>
          </div>
        </td>
      </tr>

      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="case_div">案件区分</label>
            <input type="radio" id="case_div1" name="case_div" value="1" <?php if ($case_div == '1') { echo "checked"; } ?>>
            <label class="common_label" for="case_div1" style="margin-left:35px; margin-top:17px">新規案件</label>
            <input type="radio" id="case_div2" name="case_div" value="2" <?php if ($case_div == '2') { echo "checked"; } ?>>
            <label class="common_label" for="case_div2" style="margin-top:17px">継続案件</label>
          </div>
        </td>
      </tr>

      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="daily_report_url">営業日報</label>
            <input type="text" style="margin-left: 1rem;" class="business_daily_report" name="daily_report_url" id="daily_report_url" value="<?= $daily_report_url ?>">
          </div>
        </td>
      </tr>
      <tr style="height:10px;"></tr>

      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="note">備考</label>
            <textarea id="note" style="margin-left: 1rem;" name="note" rows="3" cols="120" class="textarea-res"><?= $note ?></textarea>
          </div>
        </td>
      </tr>

      <tr>
        <td>
          <div class="field-row">
            <label for="text26" >技術担当者への事前連絡</label>
            <input type="checkbox" id="prior_notice_div" name="prior_notice_div" value="1" <?php if ($prior_notice_div == '1') { echo "checked"; } ?>>
            <label class="common_label" for="prior_notice_div">事前連絡済 </label>
            <input type="date" min="2023-01-01" max="2028-12-31" name="prior_notice_date" value="<?= $prior_notice_date ?>" class="input-res"/>
          </div>
        </td>
      </tr>
      <tr style="height:10px;"></tr>
    </table>
    
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
        if (!empty($sq_no)) {
          $files = glob('document/sales_management/*.*');
          foreach ($files as $key => $value) {
            $cut = str_replace('document/sales_management/', '', $value);
            $chk = substr($cut,0,strlen($sq_no));
            if($sq_no == $chk){
              echo "<tr><td><a href=".$value." target='_blank'>".$value."</a></td></tr>";
            }
          }
        }
      ?>
      </table>
                
      <tr>
        <td>
          <div class="field-row" style="margin-top: 20px;">
            <label class="common_label" for="text26">関連依頼書</label>
            <input type="text" style="margin-left: 1rem;" id="" name="kanrenirai" class="input-res">                
            <button onclick="">依頼書検索</button>
          </div>
        </td>
      </tr>
      <table class="tab1" style="width:fit-content; margin-top:20px; overflow-x:auto">
        <tr>
          <th>行</th>
          <th>材工名</th>
          <th>サイズ</th>
          <th>接合形状</th>
          <th>管種</th>
          <th>内面塗装</th>
          <th>外面塗装</th>
          <th>管内流体</th>
          <th>バルブ仕様</th>
          <th>区分</th>
          <?php if ($process == 'detail') { echo '<th>担当者</th>'; } ?>
          <th <?php if ($process !== 'detail' && $title !== 'check' && $title !== 'approve') { echo 'width="160px"'; } ?>>処理</th>
        </tr>
        <tr>
          <?php 
            if ($sq_no == '') {
              $regBtnDisabled = 'disabled';
            }
          ?>
          <?php if ($process !== 'detail' && $title !== 'check' && $title !== 'approve') { ?>
            <td colspan="11" style="text-align:left"><button class="createBtn" name="process2" value="new" <?= $regBtnDisabled ?>>新規作成</button></td>
          <?php } ?>
        </tr>
        <?php 
          $i = 1;
          if (isset($sq_detail_list) && !empty($sq_detail_list)) {
            foreach ($sq_detail_list as $item) {
              if ($process == 'detail' && $title !=='set_route') { $employee_name = $item['employee_name']; } else { $employee_name = ''; }
        ?>
        <tr>
          <td><?= $i ?></td>
          <td><?= $item['zkm_name'] ?></td>
          <td><?= $item['size'] ?></td>
          <td><?= $item['joint'] ?></td>
          <td><?= $item['pipe'] ?></td>
          <td><?= $item['inner_coating'] ?></td>
          <td><?= $item['outer_coating'] ?></td>
          <td><?= $item['fluid'] ?></td>
          <td><?= $item['valve'] ?></td>
          <td><?= $item['record_div_nm'] ?></td>
          <?php if ($process == 'detail') { echo '<td>'.$employee_name.'</td>'; } ?>
          <td>
            <?php if ($process == 'update') { ?>
              <button class="updateBtn" name="process2" value="update" data-sq_line_no="<?= $item['sq_line_no'] ?>">更新</button>
              <?php if ($title !== 'check' && $title !== 'approve') { ?>
              <button class="copyBtn" name="process2" value="copy" data-sq_line_no="<?= $item['sq_line_no'] ?>">コピー</button>
              <?php } ?>
            <?php } else { ?>
              <button class="updateBtn" name="process2" value="detail" data-sq_line_no="<?= $item['sq_line_no'] ?>">明細画面</button>
            <?php } ?>
          </td>
        </tr>
        <?php
              $i++;
            }
          }
        ?>
      </table>

      <tr>
        <div class="flex-container">
          <div>            
            <button id="returnBtn" class="returnBtn">前の画面に戻る </button>
          </div>
          <?php 
          if ($title == 'input') { 
            $btn_name = '営業依頼書の入力'; 
          } else if ($title == 'check') {
            $btn_name = '営業依頼書の確認';
          } else {
            $btn_name = '営業依頼書の承認';
          }
          ?>
          <div>
            <button class="approveBtn" name="submit" value="update"><?= $btn_name ?> </button>
          </div>
        </div>
      </tr>          
    </table>        
  </form><!-- Vertical Form -->
</div>

<style>
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
<?php

// フッターセット
footer_set();
?>
