<div class="container">
      <form class="row g-3" method="POST" name="inq_ent" enctype="multipart/form-data" id="request_input2">
        <input type="hidden" id="process" name="process" value="<?= $process ?>">
        <input type="hidden" name="request_no" id="request_no" value="<?php echo htmlspecialchars($request_no); ?>">
        <input type="hidden" name="client" id="client" value="<?= $user_code ?>">
        <?php include('dialog.php'); ?>
        <table style="width:auto;">
          <input type="hidden" name="sq_no" id="sq_no" value="<?= $sq_no ?>">
          <tr style="height:10px; margin-top:20px"></tr>
          <tr style="height:10px;"></tr>
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="user_name">登録者</label>
                <input type="text" style="margin-left: 1rem;" class="readonlyText input-res" name="user_name" value="<?= $user_name ?>" readonly>
                <input type="hidden" name="user_code" value="<?= $_SESSION["login"] ?>">

                <label class="common_label" for="office_name">　　部署</label>
                <input type="text" style="width:370px;" name="office_name" class="readonlyText input-res" value="<?= $office_name ?>" readonly>

                <label class="common_label" for="office_position_name">　　役職</label>
                <input type="text" style="width:100px;" class="readonlyText input-res" name="office_position_name" value="<?= $office_position_name ?>" readonly>
              </div>
            </td>
          </tr>
          <tr style="margin-top:10px"></tr>
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="class">分類 </label>
                <select style="margin-left: 1rem;" class="dropdown-menu" id="classList" name="class_code">
                  <option value="">選択して下さい。</option>
                  <?php
                  if (isset($class_datas) && !empty($class_datas)) {
                    foreach ($class_datas as $item) {
                      $code = $item['class_code'];
                      $text = $item['class_name'];
                      $selected = ($code == $class_code) ? 'selected' : '';
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
                <label class="common_label" for="comments">コメント</label>
                <textarea id="comments" style="margin-left: 1rem;" name="comments" rows="3" cols="120" class="textarea-res"></textarea>
              </div>
            </td>
          </tr>
        </table>

        <table>
          <tr style="height:20px;"></tr>
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
                <input type="file" name="uploaded_file1" id="uploaded_file1">
                <input type="submit" name="upload" id="upload1" value="アップロード">
              </td>
            </div>
          </tr>
        </table>
        <table class="tab1" style="margin-left:120px; margin-top:10px;width: auto;">
          <tr>
            <th> 添付された資料 </th>
          </tr>
          <tr style="height:10px;"></tr>
        </table>
        <table>
          <tr style="height:20px;"></tr>
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="comments">確認者コメント</label>
                <textarea id="comments" style="margin-left: 1rem;" name="comments" rows="3" cols="120" class="textarea-res"></textarea>
              </div>
            </td>
          </tr>
          <tr style="height:20px;"></tr>
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="comments">承認者コメント</label>
                <textarea id="comments" style="margin-left: 1rem;" name="comments" rows="3" cols="120" class="textarea-res"></textarea>
              </div>
            </td>
          </tr>
        </table>
        <table>
          <tr>
            <td>
              <div class="flex-container">
                <div>
                  <button id="returnBtn" class="returnBtn">前の画面に戻る </button>
                </div>
                <div>
                  <button id="updBtn" class="<?= $btn_class ?>" name="submit">依頼書承認</button>
                </div>
              </div>
            </td>
            <td>
              <div class="flex-container" style="margin-left: 50rem;">
                <div>
                  <button id="returnProcessBtn" class="returnProcessBtn">差し戻し </button>
                </div>
              </div>
            </td>
          </tr>
        </table>
      </form><!-- Vertical Form -->
    </div>