<div class="container">
  <form class="row g-3" method="POST" name="inq_ent" enctype="multipart/form-data" id="req_rec_form4">
    <input type="hidden" name="request_form_number" id="request_form_number" value="<?= $request_form_number ?>">
    <?php include('dialog.php'); ?>
    <table style="width:auto;">
      <tr style="height:20px; margin-top:20px"></tr>
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
            <select style="margin-left: 1rem;" class="dropdown-menu" id="classList" name="request_class">
              <option value="">選択して下さい。</option>
              <?php
              if (isset($class_datas) && !empty($class_datas)) {
                foreach ($class_datas as $item) {
                  $code = $item['request_dept'] . ',' . $item['request_item_id'];
                  $text = $item['text2'] . '　:　' . $item['request_item_name'];
                  $selected = ($code == $recipent_dept . ',' . $request_class) ? 'selected' : '';
                  echo "<option value='$code' $selected>$text</option>";
                }
              }
              ?>
            </select>
            <input type="hidden" name="recipent_dept" value="<?= $recipent_dept ?>">
          </div>
        </td>
      </tr>
      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="request_comment">コメント</label>
            <textarea id="request_comment" style="margin-left: 1rem;" name="request_comment" rows="3" cols="120" class="textarea-res"><?= $request_comment ?></textarea>
          </div>
        </td>
      </tr>
    </table>

    <!-- 依頼書入力の場合だけ表示する -->
    <?php if ($title !== 'receipt') : ?>
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
              <input type="file" name="uploaded_file" id="uploaded_file" <?= $disabled ?>>
              <input type="submit" name="upload" id="upload" value="アップロード" <?= $disabled ?>>
            </td>
          </div>
        </tr>
      </table>
    <?php endif; ?>

    <table class="tab1" style="margin-left:120px; margin-top:10px;width: auto;">
      <tr>
        <th> 添付された資料 </th>
      </tr>
      <?php
      $files = glob('document/request/*.*');
      foreach ($files as $key => $value) {
        if ($value == $request_form_url) {
          echo "
                <tr>
                  <td>
                    <a href=" . $value . " target='_blank'>" . $value . "</a>
                  </td>
                </tr>";
        }
      }
      ?>
      <tr style="height:10px;"></tr>
    </table>
    <table>
      <tr style="height:20px;"></tr>
      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="comfirmor_comment">（依頼部署）<br />確認者コメント</label>
            <textarea id="comfirmor_comment" style="margin-left: 1rem;" name="comfirmor_comment" rows="3" cols="120" class="textarea-res"><?= $comfirmor_comment ?></textarea>
          </div>
        </td>
      </tr>

      <tr>
        <td>
          <div class="field-row">
            <label class="common_label" for="approval_comment">（依頼部署）<br />承認者コメント</label>
            <textarea <?= $style ?> id="approval_comment" name="approval_comment" rows="3" cols="120" class="textarea-res" <?= $disabled ?>><?= $approval_comment ?></textarea>
          </div>
        </td>
      </tr>
      <tr style="height:20px;"></tr>
    </table>

    <!-- 依頼受付～承認の場合だけに表示する -->
    <?php if ($title == 'receipt') : ?>
      <table>
        <tr>
          <hr>
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
              <input type="file" name="uploaded_file" id="uploaded_file">
              <input type="submit" name="upload" id="upload" value="アップロード">
            </td>
          </div>
        </tr>
      </table>

      <table class="tab1" style="margin-left:120px; margin-top:10px;width: auto;">
        <tr>
          <th> 添付された資料 </th>
        </tr>
        <?php
        $files = glob('document/receipt/*.*');
        foreach ($files as $key => $value) {
          if ($value == $recipt_form_url) {
            echo "
                <tr>
                  <td>
                    <a href=" . $value . " target='_blank'>" . $value . "</a>
                  </td>
                </tr>";
          }
        }
        ?>
        <tr style="height:10px;"></tr>
      </table>

      <table>
        <tr style="height:20px;"></tr>
        <tr>
          <td>
            <div class="field-row">
              <label class="common_label" for="recipi_comment">処理者コメント</label>
              <textarea id="recipi_comment" style="margin-left: 1rem;" name="recipi_comment" rows="3" cols="120" class="textarea-res"><?= $recipi_comment ?></textarea>
            </div>
          </td>
        </tr>
        <!-- 確認と承認の場合だけに表示する -->
        <?php if ($status == '4' || $status == '5') : ?>
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="recipt_comfirmor_comment">確認者コメント</label>
                <textarea id="recipt_comfirmor_comment" style="margin-left: 1rem;" name="recipt_comfirmor_comment" rows="3" cols="120" class="textarea-res"><?= $recipt_comfirmor_comment ?></textarea>
              </div>
            </td>
          </tr>
        <?php endif; ?>

        <!-- 承認の場合だけに表示する -->
        <?php if ($status == '5') : ?>
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="recipt_approval_comment">承認者コメント</label>
                <textarea id="recipt_approval_comment" style="margin-left: 1rem;" name="recipt_approval_comment" rows="3" cols="120" class="textarea-res"><?= $recipt_approval_comment ?></textarea>
              </div>
            </td>
          </tr>
          <tr style="height:20px;"></tr>
      </table>
    <?php endif; ?>

  <?php endif; ?>

  <table>
    <tr>
      <td>
        <div class="flex-container">
          <div>
            <button id="returnBtn" class="returnBtn">前の画面に戻る </button>
          </div>
          <div>
            <button id="updBtn" class="<?= $btn_class ?>" name="submit" <?= $disabled ?>><?= $btn_name ?></button>
            <input type="hidden" name="process2" value="approve">
          </div>
        </div>
      </td>
      <td>
        <div class="flex-container" style="margin-left: 50rem;">
          <div>
            <button id="returnProcessBtn" class="returnProcessBtn" <?= $disabled ?>>差し戻し </button>
          </div>
        </div>
      </td>
    </tr>
  </table>
  </form><!-- Vertical Form -->
</div>