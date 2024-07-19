<?php
session_start();
header('Program-id: estimate_entry.php');
header('Content-type: text/html; charset=utf-8');
require_once('function.php');
$_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
$title = $_GET['title'] ?? '';
$property_code = isset($_GET['property_code']) ? $_GET['property_code'] : '';
include("ec_article_input2_data_set.php");
// ヘッダーセット
include("header1.php");
$page_title = '';
if ($property_code == '1') {
    $page_title = 'IV/IVT物件情報';
} elseif ($property_code == '2') {
    $page_title = '穿孔工事物件情報';
}
?>

<main>
  <div class="pagetitle">
    <h3><?php echo htmlspecialchars($page_title); ?></h3>
    <div class="container">
      <form class="row g-3" method="POST" name="inq_ent" enctype="multipart/form-data" id="ec_form2">
        <?php include('dialog.php'); ?>
        <input type="hidden" name="process" value="<?= $process ?>">
        <input type="hidden" id="property_code" name="ec_property" value="<?= $property_code ?>">
        <input type="hidden" id="key_number" name="key_number" value="<?= $key_number ?>">
        <table style="width:auto;">
          <tr style="height:10px; margin-top:20px"></tr>
          <tr style="height:10px;"></tr>         
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

                <label class="common_label" for="sq_no" style="margin-left: 4rem;">営業依頼書№ </label>
                <input type="text"  id="sq_no" name="sq_no" value="" class="readonlyText input-res" readonly>
                <button class="search_btn" onclick="">営業依頼書検索</button>

                <label class="common_label" for="add_date" style="margin-left: 5rem;">登録日 </label>
                <input type="date" min="2023-01-01" max="2028-12-31" name="add_date" id="add_date" value="<?= $add_date ?>" class="input-res" />
              </div>
            </td>
          </tr>
          
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="ec_name">工事件名 </label>
                <select style="margin-left: 1rem;" name="ec_name" id="ec_name" class="dropdown-menu">
                  <option value="0">選択して下さい。</option>
                  <?php
                  if (isset($ec_nameList) && !empty($ec_nameList)) {
                    foreach ($ec_nameList as $item) {
                      $code = $item['code_no'];
                      $text = $item['code_name'];
                      $selected = ($code == $ec_name) ? 'selected' : '';
                      echo "<option value='$code' $selected>$text</option>";
                    }
                  }
                  ?>
                </select>

                <label class="common_label" for="pipe">管種 </label>
                <select name="pipe" id="pipe" class="dropdown-menu">
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
                <select name="size" id="size" class="dropdown-menu">
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

                <label class="common_label" for="valve">バルブ種類 </label>
                <select name="valve" id="valve" class="dropdown-menu">
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
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="maker">メーカー </label>
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

                <label class="common_label" for="bifurcation">分岐形状 </label>
                <select name="bifurcation" id="bifurcation" class="dropdown-menu">
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

                <!-- IV/IVT物件情報の場合 -->
                <?php if ($property_code == '1' ) : ?>
                <label class="common_label" for="tank">使用タンク </label>
                <select name="tank" id="tank" class="dropdown-menu">
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

                <!-- 穿孔工事物件情報の場合 -->
                <?php if ($property_code == '2' ) : ?>
                <label class="common_label" for="wt_bifurcation">割T字形状 </label>
                <select name="wt_bifurcation" id="wt_bifurcation" class="dropdown-menu">
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
                <?php endif; ?>

                <label class="common_label" for="mpa">仕様(Mpa) </label>
                <select name="mpa" id="mpa" class="dropdown-menu">
                  <option value="0">選択して下さい。</option>
                  <?php
                  if (isset($mpaList) && !empty($mpaList)) {
                    foreach ($mpaList as $item) {
                      $code = $item['code_no'];
                      $text = $item['code_name'];
                      $selected = ($code == $mpa) ? 'selected' : '';
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
                <label class="common_label" for="supplier">発注者 </label>
                <select style="margin-left: 1rem;" name="supplier" id="supplier" class="dropdown-menu">
                  <option value="0">選択して下さい。</option>
                  <?php
                  if (isset($supplierList) && !empty($supplierList)) {
                    foreach ($supplierList as $item) {
                      $code = $item['code_no'];
                      $text = $item['code_name'];
                      $selected = ($code == $supplier) ? 'selected' : '';
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
              <div class="field-row">
              <!-- IV/IVT物件情報の場合 -->
              <?php if ($property_code == '1' ) : ?>
              <label class="common_label" for="m_listprice">定価（材料）</label>
              <input type="text" style="margin-left: 1rem;" id="m_listprice" name="m_listprice" value="<?= $m_listprice ?>" class="input-res" oninput="cal_t_listprice()">              
              <?php endif; ?>

              <!-- 穿孔工事物件情報 -->
              <?php if ($property_code == '2' ) : ?>
              <label class="common_label" for="wt_listprice">定価（割T）</label>
              <input type="text" style="margin-left: 1rem;" id="wt_listprice" name="wt_listprice" value="<?= $wt_listprice ?>" class="input-res" oninput="cal_t_listprice()">
              
              <label class="common_label" for="valve_listprice"> 定価（バルブ）</label>
              <input type="text" id="valve_listprice" name="valve_listprice" value="<?= $valve_listprice ?>" class="input-res" oninput="cal_t_listprice()">
              <?php endif; ?>

              <label class="common_label" for="con_listprice"> 　定価（工事）</label>
              <input type="text" id="con_listprice" name="con_listprice" value="<?= $con_listprice ?>" class="input-res" oninput="cal_t_listprice()">

              <label class="common_label" for="t_listprice">　　定価（計）</label>
              <input type="text" id="t_listprice" name="t_listprice" value="<?= $t_listprice ?>" class="readonlyText input-res" readonly>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <div class="field-row">
              <!-- IV/IVT物件情報の場合 -->
              <?php if ($property_code == '1' ) : ?>
              <label class="common_label" for="m_cost">原価（材料）</label>
              <input type="text" style="margin-left: 1rem;" id="m_cost" name="m_cost" value="<?= $m_cost ?>" class="input-res" oninput="cal_t_cost(), cal_m_partition(), cal_m_grossprofit()">
              <?php endif; ?>

              <!-- 穿孔工事物件情報 -->
              <?php if ($property_code == '2' ) : ?>
              <label class="common_label" for="wt_cost">原価（割T）</label>
              <input type="text" style="margin-left: 1rem;" id="wt_cost" name="wt_cost" value="<?= $wt_cost ?>" class="input-res" oninput="cal_t_cost(), cal_wt_partition(), cal_wt_grossprofit()">
              
              <label class="common_label" for="valve_cost"> 原価（バルブ）</label>
              <input type="text" id="valve_cost" name="valve_cost" value="<?= $valve_cost ?>" class="input-res" oninput="cal_t_cost(), cal_valve_partition(), cal_valve_grossprofit()">
              <?php endif; ?>
              
              <label class="common_label" for="con_cost"> 　原価（工事）</label>
              <input type="text" id="con_cost" name="con_cost" value="<?= $con_cost ?>" class="input-res" oninput="cal_t_cost(), cal_con_partition(), cal_con_grossprofit()">

              <label class="common_label" for="t_cost">　　原価（計）</label>
              <input type="text" id="t_cost" name="t_cost" value="<?= $t_cost ?>" class="readonlyText input-res" readonly>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <div class="field-row">
              <label class="common_label" for="card_no">カード№</label>
              <input type="text" style="margin-left: 1rem;" id="card_no" name="card_no" value="<?= $card_no ?>" class="input-res">
              
              <label class="common_label" for="ec_no"> 　工事番号</label>
              <input type="text" id="ec_no" name="ec_no" value="<?= $ec_no ?>" class="input-res">

              <label class="common_label" for="contact">　　契約先</label>
              <input type="text" id="contact" name="contact" value="<?= $contact ?>" class="input-res">
              </div>
            </td>
          </tr>

          <tr style="height:10px;"></tr>
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="estimate_date">見積返答日 </label>
                <input type="date" min="2023-01-01" max="2028-12-31" name="estimate_date" id="estimate_date" value="<?= $estimate_date ?>" class="input-res" style="margin-left: 1rem;"/>
                
                <label class="common_label" for="cost_date" style="margin-left: 3rem;">原価返答日 </label>
                <input type="date" min="2023-01-01" max="2028-12-31" name="cost_date" id="cost_date" value="<?= $cost_date ?>" class="input-res" />
                
                <label class="common_label" for="card_date" style="margin-left: 3rem;">カード計上日 </label>
                <input type="date" min="2023-01-01" max="2028-12-31" name="card_date" id="card_date" value="<?= $card_date ?>" class="input-res" />
                
                <label class="common_label" for="construction_date" style="margin-left: 3rem;">施工予定日 </label>
                <input type="date" min="2023-01-01" max="2028-12-31" name="construction_date" id="construction_date" value="<?= $construction_date ?>" class="input-res" />
              </div>
            </td>
          </tr>
          <tr style="height:10px;"></tr>
          <tr>
            <td>
              <div class="field-row">
              <!-- IV/IVT物件情報の場合 -->
              <?php if ($property_code == '1' ) : ?>
              <label class="common_label" for="m_orders">受注（材料）</label>
              <input type="text" style="margin-left: 1rem;" id="m_orders" name="m_orders" value="<?= $m_orders ?>" class="input-res" oninput="cal_t_orders(), cal_m_partition(), cal_m_grossprofit()">
              <?php endif; ?>

              <!-- 穿孔工事物件情報 -->
              <?php if ($property_code == '2' ) : ?>
              <label class="common_label" for="wt_orders">受注（割T）</label>
              <input type="text" style="margin-left: 1rem;" id="wt_orders" name="wt_orders" value="<?= $wt_orders ?>" class="input-res" oninput="cal_t_orders(), cal_wt_partition(), cal_wt_grossprofit()">

              <label class="common_label" for="valve_orders">受注（バルブ）</label>
              <input type="text" id="valve_orders" name="valve_orders" value="<?= $valve_orders ?>" class="input-res" oninput="cal_t_orders(), cal_valve_partition(), cal_valve_grossprofit()">
              <?php endif; ?>
              
              <label class="common_label" for="con_orders"> 　受注（工事）</label>
              <input type="text" id="con_orders" name="con_orders" value="<?= $con_orders ?>" class="input-res" oninput="cal_t_orders(), cal_con_partition(), cal_con_grossprofit()">

              <label class="common_label" for="t_orders">　　受注（計）</label>
              <input type="text" id="t_orders" name="t_orders" value="<?= $t_orders ?>" class="readonlyText input-res" readonly>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <div class="field-row">
              <!-- IV/IVT物件情報の場合 -->
              <?php if ($property_code == '1' ) : ?>
              <label class="common_label" for="m_partition">仕切（材料）</label>
              <input type="text" style="margin-left: 1rem;" id="m_partition" name="m_partition" value="<?= $m_partition ?>" class="readonlyText input-res" readonly>
              <?php endif; ?>

              <!-- 穿孔工事物件情報 -->
              <?php if ($property_code == '2' ) : ?>
              <label class="common_label" for="wt_partition">仕切（割T）</label>
              <input type="text" style="margin-left: 1rem;" id="wt_partition" name="wt_partition" value="<?= $wt_partition ?>" class="readonlyText input-res" readonly>

              <label class="common_label" for="valve_partition">仕切（バルブ）</label>
              <input type="text" id="valve_partition" name="valve_partition" value="<?= $valve_partition ?>" class="readonlyText input-res" readonly>
              <?php endif; ?>
              
              <label class="common_label" for="con_partition"> 　仕切（工事）</label>
              <input type="text" id="con_partition" name="con_partition" value="<?= $con_partition ?>" class="readonlyText input-res" readonly>

              <label class="common_label" for="t_partition">　　仕切（計）</label>
              <input type="text" id="t_partition" name="t_partition" value="<?= $t_partition ?>" class="readonlyText input-res" readonly>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <div class="field-row">
              <!-- IV/IVT物件情報の場合 -->
              <?php if ($property_code == '1' ) : ?>
              <label class="common_label" for="m_grossprofit">粗利（材料）</label>
              <input type="text" style="margin-left: 1rem;" id="m_grossprofit" name="m_grossprofit" value="<?= $m_grossprofit ?>" class="readonlyText input-res" readonly>
              <?php endif; ?>

              <!-- 穿孔工事物件情報 -->
              <?php if ($property_code == '2' ) : ?>
              <label class="common_label" for="wt_grossprofit">粗利（割T）</label>
              <input type="text" style="margin-left: 1rem;" id="wt_grossprofit" name="wt_grossprofit" value="<?= $wt_grossprofit ?>" class="readonlyText input-res" readonly>

              <label class="common_label" for="valve_grossprofit">粗利（バルブ）</label>
              <input type="text" id="valve_grossprofit" name="valve_grossprofit" value="<?= $valve_grossprofit ?>" class="readonlyText input-res" readonly>
              <?php endif; ?>
              
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
                <label class="common_label" for="footnote">備考</label>
                <textarea id="footnote" style="margin-left: 1rem;" name="footnote" rows="3" cols="120" class="textarea-res"><?= $footnote ?></textarea>
              </div>
            </td>
          </tr>
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
<script src="assets/js/ec_article_check.js"></script>
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
        $("#ec_form2").attr("action", "ec_article_input1.php");
      }
      //ヘッダ更新処理の場合
      else if (process == "update") {
        //submitしたいボタン名をセットする
        $("#confirm_okBtn").attr("name", "submit");
        //sales_request_update.phpへ移動する
        $("#ec_form2").attr("action", "ec_article_update.php");
      }
    });

    /**-------------------------------------------------------------------------------------------------------------- */

    //ALERT BOXに"はい"ボタンを押下する場合
    $("#ok_okBtn").click(function(event) {
      var process = $("#btnProcess").val();

      if (process == "errExec") {
        //sq_class_input1へ移動
        $('#ec_form2').attr('action', 'ec_article_input1.php');
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
  // t_list_price(定価(計)を計算する
  function cal_t_listprice() {
    var property_code = '<?= $property_code ?>';      //物件種別    
    let con_listprice = parseFloat($('#con_listprice').val(), 10);  //定価(工事)

    //物件種別＝１ならt_listprice=m_listprice＋con_listprice
    if (property_code == '1') {
      let m_listprice = parseFloat($('#m_listprice').val(), 10);      //定価(材料)
      var t_listprice = (m_listprice + con_listprice).toFixed(2);
    } else {
      //物件種別＝2ならt_listprice=wt_listprice＋valve_listprice+con_listprice
      let wt_listprice = parseFloat($('#wt_listprice').val(), 10);      //定価(割T)
      let valve_listprice = parseFloat($('#valve_listprice').val(), 10);//定価(バルブ)
      var t_listprice =  (wt_listprice + valve_listprice + con_listprice).toFixed(2);
    }
    $('#t_listprice').val(t_listprice); //定価(計)
  }

  /**-------------------------------------------------------------------------------------------------------------- */

  // t_cost(原価(計))を計算する
  function cal_t_cost() {
    var property_code = '<?= $property_code ?>';          //物件種別    
    let con_cost = parseFloat($('#con_cost').val(), 10);  //原価(工事)

    //物件種別＝１の場合
    if (property_code == '1') {
      let m_cost = parseFloat($('#m_cost').val(), 10);      //原価(材料)
      var t_cost = (m_cost + con_cost).toFixed(2);
    } else {
      //物件種別＝２の場合
      let wt_cost = parseFloat($('#wt_cost').val(), 10);      //原価(割T)
      let valve_cost = parseFloat($('#valve_cost').val(), 10);//原画(バルブ)
      var t_cost =  (wt_cost + valve_cost + con_cost).toFixed(2);
    }
    $('#t_cost').val(t_cost); //原価(計)
    cal_t_partition();
    cal_t_grossprofit()
  }

  /**-------------------------------------------------------------------------------------------------------------- */

  // t_orders(受注(計))を計算する
  function cal_t_orders() {
    var property_code = '<?= $property_code ?>';              //物件種別    
    let con_orders = parseFloat($('#con_orders').val(), 10);  //受注(工事)

    //物件種別＝１の場合
    if (property_code == '1') {
      let m_orders = parseFloat($('#m_orders').val(), 10);      //受注(材料)
      var t_orders = (m_orders + con_orders).toFixed(2);
    } else {
      //物件種別＝２の場合
      let wt_orders = parseFloat($('#wt_orders').val(), 10);      //受注(割T)
      let valve_orders = parseFloat($('#valve_orders').val(), 10);//受注(バルブ)
      var t_orders =  (wt_orders + valve_orders + con_orders).toFixed(2);
    }
    $('#t_orders').val(t_orders); //受注(計)
    cal_t_partition();
    cal_t_grossprofit()
  }

  /**-------------------------------------------------------------------------------------------------------------- */

  // m_partition(仕切(材料))を計算する
  function cal_m_partition() {
    let m_orders = parseFloat($('#m_orders').val(), 10);  //受注(材料)
    let m_cost = parseFloat($('#m_cost').val(), 10);      //原価(材料)
    var m_partition = (m_orders / m_cost).toFixed(2);
    $('#m_partition').val(m_partition); //仕切(材料)
  }

  /**-------------------------------------------------------------------------------------------------------------- */

  // wt_partition(仕切(割T))を計算する
  function cal_wt_partition() {
    let wt_orders = parseFloat($('#wt_orders').val(), 10);  //受注（割T）
    let wt_cost = parseFloat($('#wt_cost').val(), 10);      //原価(割T)
    var wt_partition = (wt_orders / wt_cost).toFixed(2);
    $('#wt_partition').val(wt_partition); //仕切(割T)
  }

  /**-------------------------------------------------------------------------------------------------------------- */

  // valve_partition(仕切（バルブ）)を計算する
  function cal_valve_partition() {
    let valve_orders = parseFloat($('#valve_orders').val(), 10);  //受注(バルブ)
    let valve_cost = parseFloat($('#valve_cost').val(), 10);      //原画(バルブ)
    var valve_partition = (valve_orders / valve_cost).toFixed(2);
    $('#valve_partition').val(valve_partition); //仕切（バルブ）
  }

  /**-------------------------------------------------------------------------------------------------------------- */

  // con_partition(仕切（工事）)を計算する
  function cal_con_partition() {
    let con_orders = parseFloat($('#con_orders').val(), 10);  //受注(工事)
    let con_cost = parseFloat($('#con_cost').val(), 10);      //原価(工事)
    var con_partition = (con_orders / con_cost).toFixed(2);
    $('#con_partition').val(con_partition); //仕切（工事）
  }

  /**-------------------------------------------------------------------------------------------------------------- */

  // t_partition(仕切（計）)を計算する
  function cal_t_partition() {
    let t_orders = parseFloat($('#t_orders').val(), 10);  //受注(計)
    let t_cost = parseFloat($('#t_cost').val(), 10);      //原価（計）
    var t_partition = (t_orders / t_cost).toFixed(2);
    $('#t_partition').val(t_partition); //仕切（計）
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
                      'wt_orders', 'valve_orders', 'con_orders', ''
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