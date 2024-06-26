<div class="container">
  <form class="row g-3" method="POST" enctype="multipart/form-data" id="card_input2">
    <table style="width:auto;">
      <tr style="height:10px; margin-top:20px"></tr>
      <tr style="height:10px;"></tr>
      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="sq_no" >依頼書№</label>
            <input type="text" style="margin-left: 1rem;" class="readonlyText input-res" name="sq_no" value="" readonly>
            <input type="hidden" name="" value="">
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
            <label class="common_label" for="p_office_no">事業体 </label>
            <input type="text" style="margin-left: 1rem;" id="p_office_no" name="p_office_no" value="" class="readonlyText input-res">
            <!-- <input type="hidden" name="p_office_no" id="p_office_no" value="<?= $p_office_no ?>"> -->
            <button class="search_btn" onclick="public_office_open(event)">事業体検索</button>

            <label class="common_label" for="preferred_date">　出図希望日</label>
            <input type="date" style="margin-left: 1rem;" min="2023-01-01" max="2028-12-31" name="preferred_date" value="" class="input-res"/>

            <label class="common_label" for="deadline">　納期</label>
            <input type="date" style="margin-left: 1rem;" min="2023-01-01" max="2028-12-31" name="deadline" value="" class="input-res"/>
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
            <label class="common_label" for="procurement_no" >資材部№</label>
            <input type="text" style="margin-left: 1rem;" class="readonlyText input-res" name="procurement_no" value="">
            <label class="common_label" for="maker">製造メーカー</label>
              <input type="text" style="margin-left: 1rem;" class="business_daily_report" name="maker" id="" value="">
          </div>
        </td>
      </tr>

      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="zkm_code" style="margin-left: 1rem;">　　材工名 </label>
            <select name="zkm_code" class="input-res">
              <option value="">選択して下さい。</option>
            </select>
            <label class="common_label" for="pipe">　　管種 </label>
            <select name="pipe" class="input-res">
              <option value="">選択して下さい。</option>
            </select>
            <label class="common_label" for="size">　　サイズ </label>
            <input type="text" style="margin-left: 1rem; width:40px;" name="sizeA">mm　　✖
            <input type="text" style="margin-left: 1rem; width:40px;" name="sizeB">mm　
            <button class="approveBtn" name="detail" id="detailBtn" style="margin-left: 3rem;" value="">詳細</button>
            <!-- NEED TO ASSIGN WITH DB DATAS LATER -->
            <input type="hidden" name="sq_card_no" id="sq_card_no" value="20240624">
            <input type="hidden" name="sq_card_line_no" id="sq_card_line_no" value="1">
            <input type="hidden" name="client" id="client" value="seven02">
            <!-- NEED TO ASSIGN WITH DB DATAS LATER -->
          </div>
        </td>
      </tr>
      <tr style="height:10px;"></tr>

      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="specification_no" >仕様書№</label>
            <input type="text" style="margin-left: 1rem;" class="readonlyText input-res" name="specification_no" value="">
            <label class="common_label" for="special_note">特記事項</label>
              <input type="text" style="margin-left: 1rem;" class="business_daily_report" name="special_note" id="" value="">
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
            <label class="common_label" for="procurement_no" >資材部№</label>
            <input type="text" style="margin-left: 1rem;" class="readonlyText input-res" name="procurement_no" value="">
            <label class="common_label" for="maker">製造メーカー</label>
              <input type="text" style="margin-left: 1rem;" class="business_daily_report" name="maker" id="" value="">
          </div>
        </td>
      </tr>

      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="zkm_code" style="margin-left: 1rem;">　　材工名 </label>
            <select name="zkm_code" class="input-res">
              <option value="">選択して下さい。</option>
            </select>
            <label class="common_label" for="pipe">　　管種 </label>
            <select name="pipe" class="input-res">
              <option value="">選択して下さい。</option>
            </select>
            <label class="common_label" for="size">　　サイズ </label>
            <input type="text" style="margin-left: 1rem; width:40px;" name="sizeA">mm　　✖
            <input type="text" style="margin-left: 1rem; width:40px;" name="sizeB">mm　
            <button class="approveBtn" name="detail" id="detailBtn" style="margin-left: 3rem;" value="">詳細</button>
          </div>
        </td>
      </tr>
      <tr style="height:10px;"></tr>

      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="specification_no" >仕様書№</label>
            <input type="text" style="margin-left: 1rem;" class="readonlyText input-res" name="specification_no" value="">
            <label class="common_label" for="special_note">特記事項</label>
              <input type="text" style="margin-left: 1rem;" class="business_daily_report" name="special_note" id="" value="">
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
            <label class="common_label" for="procurement_no" >資材部№</label>
            <input type="text" style="margin-left: 1rem;" class="readonlyText input-res" name="procurement_no" value="">
            <label class="common_label" for="maker">製造メーカー</label>
              <input type="text" style="margin-left: 1rem;" class="business_daily_report" name="maker" id="" value="">
          </div>
        </td>
      </tr>

      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="zkm_code" style="margin-left: 1rem;">　　材工名 </label>
            <select name="zkm_code" class="input-res">
              <option value="">選択して下さい。</option>
            </select>
            <label class="common_label" for="pipe">　　管種 </label>
            <select name="pipe" class="input-res">
              <option value="">選択して下さい。</option>
            </select>
            <label class="common_label" for="size">　　サイズ </label>
            <input type="text" style="margin-left: 1rem; width:40px;" name="sizeA">mm　　✖
            <input type="text" style="margin-left: 1rem; width:40px;" name="sizeB">mm　
            <button class="approveBtn" name="detail" id="detailBtn" style="margin-left: 3rem;" value="">詳細</button>
          </div>
        </td>
      </tr>
      <tr style="height:10px;"></tr>

      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="specification_no" >仕様書№</label>
            <input type="text" style="margin-left: 1rem;" class="readonlyText input-res" name="specification_no" value="">
            <label class="common_label" for="special_note">特記事項</label>
              <input type="text" style="margin-left: 1rem;" class="business_daily_report" name="special_note" id="" value="">
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
            <label class="common_label" for="procurement_no" >資材部№</label>
            <input type="text" style="margin-left: 1rem;" class="readonlyText input-res" name="procurement_no" value="">
            <label class="common_label" for="maker">製造メーカー</label>
              <input type="text" style="margin-left: 1rem;" class="business_daily_report" name="maker" id="" value="">
          </div>
        </td>
      </tr>

      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="zkm_code" style="margin-left: 1rem;">　　材工名 </label>
            <select name="zkm_code" class="input-res">
              <option value="">選択して下さい。</option>
            </select>
            <label class="common_label" for="pipe">　　管種 </label>
            <select name="pipe" class="input-res">
              <option value="">選択して下さい。</option>
            </select>
            <label class="common_label" for="size">　　サイズ </label>
            <input type="text" style="margin-left: 1rem; width:40px;" name="sizeA">mm　　✖
            <input type="text" style="margin-left: 1rem; width:40px;" name="sizeB">mm　
            <button class="approveBtn" name="detail" id="detailBtn" style="margin-left: 3rem;" value="">詳細</button>
          </div>
        </td>
      </tr>
      <tr style="height:10px;"></tr>

      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="specification_no" >仕様書№</label>
            <input type="text" style="margin-left: 1rem;" class="readonlyText input-res" name="specification_no" value="">
            <label class="common_label" for="special_note">特記事項</label>
              <input type="text" style="margin-left: 1rem;" class="business_daily_report" name="special_note" id="" value="">
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
          <div class="field-row">
            <label class="common_label" for="approver" style="margin-left: 1rem;">　　承認者 </label>
            <select name="approver" class="input-res">
              <option value="">選択して下さい。</option>
            </select>
          </div>
        </td>
      </tr>   
      <tr>
        <td>
          <div class="flex-container">
            <div>            
              <button id="returnBtn" class="returnBtn">前の画面に戻る </button>
              <button class="approveBtn" name="submit" value="update">登録</button>
            </div>            
            <div>            
              <button id="remandBtn" class="remandBtn" style="margin-left: 50rem;">差し戻し </button>
              <button class="cancelBtn" name="submit" value="cancel">中止</button>
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
    $("#detailBtn").click(function(){
      $("#card_input2").attr("action", "card_input3.php");
    });
  });
</script>
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
