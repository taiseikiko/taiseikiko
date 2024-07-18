<?php
session_start();
header('Program-id: estimate_entry.php');
header('Content-type: text/html; charset=utf-8');
require_once('function.php');
$_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
$title = $_GET['title'] ?? '';
$dept_code = $_SESSION['department_code'];
$user_code = $_SESSION["login"];
$user_name = $_SESSION['user_name'];      //登録者
$office_name = $_SESSION['office_name'];  //部署
$office_position_name = $_SESSION['office_position_name'];  //役職
$property_code = isset($_GET['property_code']) ? $_GET['property_code'] : '';
// include("ec_ec_form2_data_set.php");
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
        <input type="hidden" name="dept_id" id="dept_id" value="<?= $dept_id ?>">
        <input type="hidden" name="client" value="<?= $client ?>">
        <input type="hidden" name="title" id="title" value="<?= $title ?>">
        <table style="width:auto;">
          <tr style="height:10px; margin-top:20px"></tr>
          <tr style="height:10px;"></tr>
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="user_name">登録者</label>
                <input type="text" style="margin-left: 1rem;" class="readonlyText input-res" id="user_name" name="user_name" value="<?= $user_name ?>" readonly>
                <input type="hidden" name="user_code" value="<?= $_SESSION["login"] ?>">

                <label class="common_label" for="office_name">　　部署</label>
                <input type="text" style="width:370px;" name="office_name" id="office_name" class="readonlyText input-res" value="<?= $office_name ?>" readonly>

                <label class="common_label" for="office_position_name">　　役職</label>
                <input type="text" style="width:100px;" class="readonlyText input-res" name="office_position_name" id="office_position_name" value="<?= $office_position_name ?>" readonly>
              </div>
            </td>
          </tr>          
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="bridge">出先 </label>
                <select name="bridge" id="bridge" class="input-res">
                  <option value="">選択して下さい。</option>
                </select>

                <label class="common_label" for="sq_no" style="margin-left: 4rem;">営業依頼書№ </label>
                <input type="text"  id="sq_no" name="sq_no" value="" class="readonlyText input-res" readonly>
                <button class="search_btn" onclick="">営業依頼書検索</button>

                <label class="common_label" for="add_date" style="margin-left: 5rem;">登録日 </label>
                <input type="date" min="2023-01-01" max="2028-12-31" name="add_date" id="add_date" value="" class="input-res" />
              </div>
            </td>
          </tr>
          <tr style="height:10px;"></tr>
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="ec_name">工事件名 </label>
                <select name="ec_name" id="ec_name" class="input-res">
                  <option value="">選択して下さい。</option>
                </select>

                <label class="common_label" for="pipe">管種 </label>
                <select name="pipe" id="pipe" class="input-res">
                  <option value="">選択して下さい。</option>
                </select>

                <label class="common_label" for="size">サイズ </label>
                <select name="size" id="size" class="input-res">
                  <option value="">選択して下さい。</option>
                </select>

                <label class="common_label" for="valve">バルブ種類 </label>
                <select name="valve" id="valve" class="input-res">
                  <option value="">選択して下さい。</option>
                </select>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="maker">メーカー </label>
                <select name="maker" id="maker" class="input-res">
                  <option value="">選択して下さい。</option>
                </select>

                <label class="common_label" for="bifurcation">分岐形状 </label>
                <select name="bifurcation" id="bifurcation" class="input-res">
                  <option value="">選択して下さい。</option>
                </select>

                <label class="common_label" for="tank">使用タンク </label>
                <select name="tank" id="tank" class="input-res">
                  <option value="">選択して下さい。</option>
                </select>

                <label class="common_label" for="mpa">仕様(Mpa) </label>
                <select name="mpa" id="mpa" class="input-res">
                  <option value="">選択して下さい。</option>
                </select>
              </div>
            </td>
          </tr>

          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="supplier">発注者 </label>
                <select name="supplier" id="supplier" class="input-res">
                  <option value="">選択して下さい。</option>
                </select>
              </div>
            </td>
          </tr>
          <tr style="height:10px;"></tr>
          <tr>
            <td>
              <div class="field-row">
              <label class="common_label" for="m_listprice">定価（材料）</label>
              <input type="text" style="margin-left: 1rem;" id="m_listprice" name="m_listprice" value="" class="input-res">
              
              <label class="common_label" for="con_listprice" style="margin-left: 4rem;"> 　定価（工事）</label>
              <input type="text" id="con_listprice" name="con_listprice" value="" class="input-res">

              <label class="common_label" for="t_listprice" style="margin-left: 4rem;">　　定価（計）</label>
              <input type="text" id="t_listprice" name="t_listprice" value="" class="readonlyText input-res">
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <div class="field-row">
              <label class="common_label" for="m_cost">原価（材料）</label>
              <input type="text" style="margin-left: 1rem;" id="m_cost" name="m_cost" value="" class="input-res">
              
              <label class="common_label" for="con_cost" style="margin-left: 4rem;"> 　原価（工事）</label>
              <input type="text" id="con_cost" name="con_cost" value="" class="input-res">

              <label class="common_label" for="t_cost" style="margin-left: 4rem;">　　原価（計）</label>
              <input type="text" id="t_cost" name="t_cost" value="" class="readonlyText input-res">
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <div class="field-row">
              <label class="common_label" for="card_no">カード№</label>
              <input type="text" style="margin-left: 1rem;" id="card_no" name="card_no" value="" class="input-res">
              
              <label class="common_label" for="ec_no" style="margin-left: 4rem;"> 　工事番号</label>
              <input type="text" id="ec_no" name="ec_no" value="" class="input-res">

              <label class="common_label" for="contact" style="margin-left: 4rem;">　　契約先</label>
              <input type="text" id="contact" name="contact" value="" class="input-res">
              </div>
            </td>
          </tr>

          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="estimate_date">見積返答日 </label>
                <input type="date" min="2023-01-01" max="2028-12-31" name="estimate_date" id="estimate_date" value="" class="input-res" style="margin-left: 1rem;"/>
                
                <label class="common_label" for="cost_date" style="margin-left: 3rem;">原価返答日 </label>
                <input type="date" min="2023-01-01" max="2028-12-31" name="cost_date" id="cost_date" value="" class="input-res" />
                
                <label class="common_label" for="card_date" style="margin-left: 3rem;">カード計上日 </label>
                <input type="date" min="2023-01-01" max="2028-12-31" name="card_date" id="card_date" value="" class="input-res" />
                
                <label class="common_label" for="construction_date" style="margin-left: 3rem;">施工予定日 </label>
                <input type="date" min="2023-01-01" max="2028-12-31" name="construction_date" id="construction_date" value="" class="input-res" />
              </div>
            </td>
          </tr>
          <tr style="height:10px;"></tr>
          <tr>
            <td>
              <div class="field-row">
              <label class="common_label" for="m_orders">受注（材料）</label>
              <input type="text" style="margin-left: 1rem;" id="m_orders" name="m_orders" value="" class="input-res">
              
              <label class="common_label" for="con_orders" style="margin-left: 4rem;"> 　受注（工事）</label>
              <input type="text" id="con_orders" name="con_orders" value="" class="input-res">

              <label class="common_label" for="t_orders" style="margin-left: 4rem;">　　受注（計）</label>
              <input type="text" id="t_orders" name="t_orders" value="" class="readonlyText input-res">
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <div class="field-row">
              <label class="common_label" for="m_partition">仕切（材料）</label>
              <input type="text" style="margin-left: 1rem;" id="m_partition" name="m_partition" value="" class="readonlyText input-res">
              
              <label class="common_label" for="con_partition" style="margin-left: 4rem;"> 　仕切（工事）</label>
              <input type="text" id="con_partition" name="con_partition" value="" class="readonlyText input-res">

              <label class="common_label" for="t_partition" style="margin-left: 4rem;">　　仕切（計）</label>
              <input type="text" id="t_partition" name="t_partition" value="" class="readonlyText input-res">
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <div class="field-row">
              <label class="common_label" for="m_grossprofit">粗利（材料）</label>
              <input type="text" style="margin-left: 1rem;" id="m_grossprofit" name="m_grossprofit" value="" class="readonlyText input-res">
              
              <label class="common_label" for="con_grossprofit" style="margin-left: 4rem;"> 　粗利（工事）</label>
              <input type="text" id="con_grossprofit" name="con_grossprofit" value="" class="readonlyText input-res">

              <label class="common_label" for="t_grossprofit" style="margin-left: 4rem;">　　粗利（計）</label>
              <input type="text" id="t_grossprofit" name="t_grossprofit" value="" class="readonlyText input-res">
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="footnote">備考</label>
                <textarea id="footnote" style="margin-left: 1rem;" name="footnote" rows="3" cols="120" class="textarea-res"></textarea>
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
                  <button id="updateBtn" class="updateBtn" name="submit">更新</button>
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
<script src="assets/js/customer_ent.js"></script>
<script src="assets/js/public_office_ent.js"></script>
<script src="assets/js/sales_request_check.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript">
  //その他処理が変わる場合
  function other_process(event) {
    event.preventDefault();
     
  }
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

    //確認BOXにはいボタンを押下する場合
    $("#confirm_okBtn").click(function(event) {
      var process = $("#btnProcess").val();
      //戻る処理の場合
      if (process == "return") {
        $("#ec_form2").attr("action", "ec_input1.php");
      }
      //ヘッダ更新処理の場合
      else if (process == "update") {
        //submitしたいボタン名をセットする
        $("#confirm_okBtn").attr("name", "submit");
        //sales_request_update.phpへ移動する
        $("#ec_form2").attr("action", "sales_request_update.php?title=<?= $title ?>");
      }
    });

    /**-------------------------------------------------------------------------------------------------------------- */

    //ALERT BOXに"はい"ボタンを押下する場合
    $("#ok_okBtn").click(function(event) {
      var process = $("#btnProcess").val();

      if (process == "errExec") {
        //sq_class_input1へ移動
        $('#ec_form2').attr('action', 'sales_request_input1.php?title=<?= $title ?>');
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
  function disableInput() {
    //Disabled Input 
    var inputs = document.getElementsByTagName('input');
    for (var i = 0; i < inputs.length; i++) {
      if (inputs[i].type.toLowerCase() !== 'hidden') {
        inputs[i].disabled = true;
      }
      if (inputs[i].type.toLowerCase() == 'text') {
        inputs[i].style.backgroundColor = '#e6e6e6';
      }
    }

    //Disabled textarea 
    var textareas = document.getElementsByTagName('textarea');
    for (var j = 0; j < textareas.length; j++) {
      textareas[j].disabled = true;
      textareas[j].style.backgroundColor = '#e6e6e6';
    }

    //Disabled select
    var selects = document.getElementsByTagName('select');
    const excludeSelect = ['otherProcess'];
    for (var k = 0; k < selects.length; k++) {
      if (!excludeSelect.includes(selects[k].id)) {
        selects[k].disabled = true;
      }
    }

    //Disabled button 
    var buttons = document.getElementsByTagName('button');
    const excludeButtons = ['returnBtn', 'updateBtn', 'okBtn', 'cancelBtn', 'copyBtn', 'createBtn'];
    for (var k = 0; k < buttons.length; k++) {
      if (!excludeButtons.includes(buttons[k].className)) {
        buttons[k].disabled = true;
      }
    }
  }
</script>
<?php
// フッターセット
footer_set();
?>
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