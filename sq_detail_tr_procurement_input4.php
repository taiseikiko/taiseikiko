<table style="width:auto;">
  <tr>
    <hr>
  </tr>
  <tr><h3>【資材部入力画面・見積処理】</h3></tr>
  <tr>
    <td>
      <div class="field-row" style="margin-top: 10px;">
        <label class="common_label" for="mitsumori">通知メール送信先 </label>

        <input type="checkbox" class="mail" id="mail_to1" name="mail_to1" value="1" <?php if ($mail_to1 == '1') {echo 'checked';} ?>>
        <label for="mail_to1">品質管理課</label>

        <input type="checkbox" class="mail" id="mail_to2" name="mail_to2" value="1" <?php if ($mail_to2 == '1') {echo 'checked';} ?>>
        <label for="mail_to2">関西工事センター </label>

        <input type="checkbox" class="mail" id="mail_to3" name="mail_to3" value="1" <?php if ($mail_to3 == '1') {echo 'checked';} ?>>
        <label for="mail_to3">東京工事センター </label>

        <input type="checkbox" class="mail" id="mail_to4" name="mail_to4" value="1" <?php if ($mail_to4 == '1') {echo 'checked';} ?>>
        <label for="mail_to4">工事資材部</label>

        <input type="checkbox" class="mail" id="mail_to5" name="mail_to5" value="1" <?php if ($mail_to5 == '1') {echo 'checked';} ?>>
        <label for="mail_to5">資材部</label>
      </div>
    </td>
  </tr>

  <tr>
    <td>
      <div class="field-row" style="margin-top: 10px;">
        <label class="common_label" style="text-align:center;white-space:pre-wrap" for="mitsumori">
          見積図面
        （技術）
        </label>
        <table class="tab1" style="margin-top:10px;">
          <tr>
            <th> 添付された資料 </th>
          </tr>
          <?php
            $files = glob('document/engineering/quotation/*.*');
            foreach ($files as $key => $value) {
              $cut = str_replace('document/engineering/quotation/', '', $value);
              $chk = substr($cut,0,strlen($sq_no)); //get sq_no from file name
              $type = mb_substr($cut,(strlen($sq_no)+1),4);
              if($sq_no == $chk && $type == '見積図面'){
                echo "<tr><td><a href=".$value." target='_blank'>".$value."</a></td></tr>";
              }
            }
          ?>
          <tr style="height:10px;"></tr>
        </table>
      </div>
    </td>
  </tr>

  <tr>
    <td>
      <div class="field-row" style="margin-top: 10px;">
        <label class="common_label" style="text-align:center;white-space:pre-wrap" for="mitsumori">
          資料
        （技術） 
        </label>
        <table class="tab1" style="margin-top:10px;">
          <tr>
            <th> 添付された資料 </th>
          </tr>
          <?php
            $files = glob('document/engineering/quotation/*.*');
            foreach ($files as $key => $value) {
              $cut = str_replace('document/engineering/quotation/', '', $value);
              $chk = substr($cut,0,strlen($sq_no)); //get sq_no from file name
              $type = mb_substr($cut,(strlen($sq_no)+1),2);
              if($sq_no == $chk && $type == '資料'){
                echo "<tr><td><a href=".$value." target='_blank'>".$value."</a></td></tr>";
              }
            }
          ?>
          <tr style="height:10px;"></tr>
        </table>
      </div>
    </td>
  </tr>  

  <tr>
    <td>
      <div class="field-row" style="margin-top: 10px;">
        <label class="common_label" style="text-align:center;" for="mitsumori">原価計算書 </label>
        <label for="upload">アップロードするファイル ⇒  </label>
        <input type="file" name="uploaded_file1">
        <input type="submit" name="submit_entrant" id="submit_upload1" value="アップロード">
      </div>
    </td>
  </tr>
  <table class="tab1" style="margin-left:120px;margin-top:10px;">
    <tr>
      <th> 添付された資料 </th>
    </tr>
    <?php
      $files = glob('document/procurement/*.*');
      foreach ($files as $key => $value) {
        $cut = str_replace('document/procurement/', '', $value);
        $chk = substr($cut,0,strlen($sq_no)); //get sq_no from file name
        $type = mb_substr($cut,(strlen($sq_no)+1),5);
        if($sq_no == $chk && $type == '原価計算書'){
          echo "<tr><td><a href=".$value." target='_blank'>".$value."</a></td></tr>";
        }
      }
    ?>
    <tr style="height:10px;"></tr>
  </table>
      
  <tr>
    <td>
      <div class="field-row" style="margin-top: 10px;">
        <label class="common_label" style="text-align:center;" for="mitsumori">資料 </label>
        <label for="upload">アップロードするファイル ⇒  </label>
        <input type="file" name="uploaded_file2">
        <input type="submit" name="submit_entrant" id="submit_upload2" value="アップロード">
      </div>
    </td>
  </tr>
  <table class="tab1" style="margin-left:120px; margin-top:10px;">
    <tr>
      <th> 添付された資料 </th>
    </tr>
    <?php
      $files = glob('document/procurement/*.*');
      foreach ($files as $key => $value) {
        $cut = str_replace('document/procurement/', '', $value);
        $chk = substr($cut,0,strlen($sq_no)); //get sq_no from file name
        $type = mb_substr($cut,(strlen($sq_no)+1),2);
        if($sq_no == $chk && $type == '資料'){
          echo "<tr><td><a href=".$value." target='_blank'>".$value."</a></td></tr>";
        }
      }
    ?>
    <tr style="height:10px;"></tr>
  </table>

  <tr>
    <td>
      <div class="field-row" style="margin-top: 10px;">
        <label class="common_label" for="entrant_comments">作成者コメント</label>
        <textarea id="entrant_comments" name="entrant_comments" rows="3" cols="120" class="textarea-res"
        <?php if ($title !== 'pc_entrant') { echo 'disabled style="background-color: #e6e6e6;"'; } ?>><?= $entrant_comments ?></textarea>
      </div>
    </td>
  </tr>

  <?php
  if ($title !== 'pc_entrant') { ?>
  <tr>
    <td>
      <div class="field-row" style="margin-top: 10px;">
        <label class="common_label" for="confirmer_comments">確認者コメント</label>
        <textarea id="confirmer_comments" name="confirmer_comments" rows="3" cols="120" class="textarea-res"
        <?php if ($title !== 'pc_confirm') { echo 'disabled style="background-color: #e6e6e6;"'; } ?>><?= $confirmer_comments ?></textarea>
      </div>
    </td>
  </tr>
  <?php
  }
  ?>

  <?php
  if ($title == 'pc_approve') { ?>
  <tr>
    <td>
      <div class="field-row" style="margin-top: 10px;">
        <label class="common_label" for="approver_comments">承認者コメント</label>
        <textarea id="approver_comments" name="approver_comments" rows="3" cols="120" class="textarea-res"><?= $approver_comments ?></textarea>
      </div>
    </td>
  </tr>
  <?php
  }
  ?>

  <tr>
    <div class="flex-container">
      <div>            
        <button id="returnBtn" class="returnBtn">前の画面に戻る </button>
      </div>
      <div>
        <button id="updBtn" style="background:#80dfff;" class="update" name="submit_entrant" value="update">更新 </button>
      </div>
      <?php
      if ($title == 'pc_confirm' || $title == 'pc_approve') { ?>
      <div style="margin-top:13px; margin-left:435px">            
        <label class="common_label" for="other">その他処理 </label>
        <select class="dropdown-menu" id="otherProcess" name="otherProcess" onchange="other_process(event)">
          <option value="" class="">選択して下さい。</option>
          <option value="1" class="">差し出し</option>
          <option value="2" class="">中止</option>
          <option value="3" class="">スキップ</option>
        </select>
      </div>
      <?php
      }
      ?>
    </div>
  </tr>          
</table>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    $("#updBtn").click(function() {
      //確認メッセージを書く
      var msg = "更新します。よろしいですか？";
      //何の処理科を書く
      var process = "update";
      //確認Dialogを呼ぶ
      openModal(msg, process);
    })

    //見積原価のアップロードボタンを押下する場合
    $("#submit_upload1").click(function(){
      //sq_attach_upload1.phpへ移動する
      $("#input3").attr("action", "sq_attach_upload1.php?from=pc1");
    })
    //見積定価のアップロードボタンを押下する場合
    $("#submit_upload2").click(function(){
      //sq_attach_upload1.phpへ移動する
      $("#input3").attr("action", "sq_attach_upload1.php?from=pc2");
    })
    //確認BOXにはいボタンを押下する場合
    $("#confirm_okBtn").click(function(event) {
      var process = $("#btnProcess").val();
      //ヘッダ更新処理の場合
      if (process == "update") {
        //submitしたいボタン名をセットする
        $("#confirm_okBtn").attr("name", "submit_entrant");
        //sales_request_update.phpへ移動する
        $("#input3").attr("action", "sq_detail_tr_procurement_update.php");
      }
    });
  });

  function openModal(msg, process) {
    event.preventDefault();
    //何の処理かをセットする
    $("#btnProcess").val(process);
    //確認メッセージをセットする
    $("#confirm-message").text(msg);
    //確認Dialogを呼ぶ
    $("#confirm").modal({backdrop: false});
  }

</script>