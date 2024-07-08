<?php
  session_start();
  header('Program-id: estimate_entry.php');
  header('Content-type: text/html; charset=utf-8');
  require_once('function.php');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
  $title = $_GET['title'] ?? '';
  $dept_code = $_SESSION['department_code'];
  include("dw_input2_data_set.php");
  // ヘッダーセット
  include("header1.php");
?>

<main>
  <div class="pagetitle">
    <h3>図面管理入力</h3>
    <div class="container">
      <form class="row g-3" method="POST" name="inq_ent" enctype="multipart/form-data" id="dw_input2">
        <?php include('dialog.php'); ?>
        <table style="width:auto;">
          <input type="hidden" name="sq_no" id="sq_no" value="<?= $sq_no ?>">
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

          <tr style="margin-top:10px"></tr>
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="dw_div">区分</label>

                <input type="radio" id="dw_div1_1" name="dw_div1" value="1" <?php if ($dw_div1 == '1') { echo "checked"; } ?>>
                <label class="common_label" for="dw_div1_1" style="margin-left:35px; ">標準</label>

                <input type="radio" id="dw_div1_2" name="dw_div1" value="2" <?php if ($dw_div1 == '2') { echo "checked"; } ?>>
                <label class="common_label" for="dw_div1_2" style="">特殊</label>

                <input type="radio" id="dw_div1_3" name="dw_div1" value="3" <?php if ($dw_div1 == '2') { echo "checked"; } ?>>
                <label class="common_label" for="dw_div1_3" style="">鋼板製</label>

                <label class="common_label" for="open_div">公開区分</label>

                <input type="radio" id="open_div1" name="case_div" value="1" <?php if ($open_div == '1') { echo "checked"; } ?>>
                <label class="common_label" for="open_div1" style="margin-left:35px; ">公開</label>

                <input type="radio" id="open_div2" name="case_div" value="2" <?php if ($open_div == '2') { echo "checked"; } ?>>
                <label class="common_label" for="open_div2" style="">非公開</label>
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

                <label class="common_label" for="zaikoumei">　　材工名 </label>
                <select class="dropdown-menu" id="zaikoumeiList" name="zaikoumei">
                  <option value="" class="">選択して下さい。</option>
                </select>
                <input type="hidden" name="zkm_code" id="zkm_code" value="<?= $zkm_code ?>">                 
              </div>
            </td>
          </tr>

          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="size">サイズ</label>
                <select class="dropdown-menu" style="margin-left: 1rem;" name="size">
                  <option value="">※選択して下さい。</option>
                  <?php
                    if (!empty($sizeList)) {
                      foreach ($sizeList as $item) {
                        $size_text = $item['text1'];
                        $selectedSize = ($size_text == $size) ? 'selected' : '';
                        echo "<option value='$size_text' $selectedSize>$size_text</option>";
                      } 
                    }
                  ?>
                </select>

                <label class="common_label" for="joint">接合形状</label>
                <select class="dropdown-menu" name="joint">
                  <option value="">※選択して下さい。</option>
                  <?php
                    if (!empty($jointList)) {
                      foreach ($jointList as $item) {
                        $joint_text = $item['text1'];
                        $selectedJoint = ($joint_text == $joint) ? 'selected' : '';
                        echo "<option value='$joint_text' $selectedJoint>$joint_text</option>";
                      } 
                    }
                  ?>
                </select>

                <label class="common_label" for="pipe">管種 </label>
                <select class="dropdown-menu" name="pipe">
                  <option value="">※選択して下さい。</option>
                  <?php
                    if (!empty($pipeList)) {
                      foreach ($pipeList as $item) {
                        $pipe_text = $item['text1'];
                        $selectedPipe = ($pipe_text == $pipe) ? 'selected' : '';
                        echo "<option value='$pipe_text' $selectedPipe>$pipe_text</option>";
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
                <label class="common_label" for="specification">営業日報</label>
                <input type="text" style="margin-left: 1rem;" class="business_daily_report" name="specification" id="specification" value="<?= $specification ?>">
              </div>
            </td>
          </tr>

          <tr style="margin-top:10px"></tr>
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="dw_div">種類</label>

                <input type="radio" id="dw_div2_1" name="dw_div2" value="1" <?php if ($dw_div2 == '1') { echo "checked"; } ?>>
                <label class="common_label" for="dw_div2_1" style="margin-left:35px; ">営業図面</label>

                <input type="radio" id="dw_div2_2" name="dw_div2" value="2" <?php if ($dw_div2 == '2') { echo "checked"; } ?>>
                <label class="common_label" for="dw_div2_2" >工事図面</label>
              </div>
            </td>
          </tr>
        </table>
        
        <table style="width:auto;">
          <br>
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="mitsumori">図面 </label>
                <label for="upload">アップロードするファイル ⇒  </label>
                <input type="file" name="uploaded_file1">
                <input type="submit" name="submit_entrant1" id="submit_upload1" value="アップロード">
              </div>
            </td>
          </tr>
          <table class="tab1" style="margin-left:120px; margin-top:10px;">
            <tr>
              <th> 添付された資料 </th>
            </tr>
            <?php
              $files = glob('document/engineering/quotation/*.*');
              // foreach ($files as $key => $value) {
              //   $cut = str_replace('document/engineering/quotation/', '', $value);
              //   $chk = substr($cut,0,strlen($sq_no)); //get sq_no from file name
              //   $type = mb_substr($cut,(strlen($sq_no)+1),4);
              //   if($sq_no == $chk && $type == '図面'){
              //     echo "<tr><td><a href=".$value." target='_blank'>".$value."</a></td></tr>";
              //   }
              // }
            ?>
            <tr style="height:10px"></tr>
          </table>

          <tr>
            <td>
              <div class="field-row" style="margin-top: 10px;">
                <label class="common_label" for="comments">コメント</label>
                <textarea id="comments" name="comments" rows="3" cols="120" class="textarea-res"><?= $comments ?></textarea>
              </div>
            </td>
          </tr>        
        </table>
        <table>
          <br>
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="mitsumori">仕様書 </label>
                <label for="upload">アップロードするファイル ⇒  </label>
                <input type="file" name="uploaded_file1">
                <input type="submit" name="submit_entrant1" id="submit_upload1" value="アップロード">
              </div>
            </td>
          </tr>
          <table class="tab1" style="margin-left:120px; margin-top:10px;">
            <tr>
              <th> 添付された資料 </th>
            </tr>
            <?php
              $files = glob('document/engineering/quotation/*.*');
              // foreach ($files as $key => $value) {
              //   $cut = str_replace('document/engineering/quotation/', '', $value);
              //   $chk = substr($cut,0,strlen($sq_no)); //get sq_no from file name
              //   $type = mb_substr($cut,(strlen($sq_no)+1),4);
              //   if($sq_no == $chk && $type == '図面'){
              //     echo "<tr><td><a href=".$value." target='_blank'>".$value."</a></td></tr>";
              //   }
              // }
            ?>
            <tr style="height:10px"></tr>
          </table>

          <tr>
            <td>
              <div class="field-row" style="margin-top: 10px;">
                <label class="common_label" for="comments">コメント</label>
                <textarea id="comments" name="comments" rows="3" cols="120" class="textarea-res"><?= $comments ?></textarea>
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
                  <button id="updBtn" class="setRoute" style="background:#80dfff;" name="submit"><?= $btn_name ?> </button>
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
  $(document).ready(function(){

    let class_code = $('#classList').val();
    if (class_code != '') {
      fetchData(class_code, function(response) {
      }, function(error) {
        console.log(error);
      })
    }

    /**-------------------------------------------------------------------------------------------------------------- */

    $("#classList").change(function() {
      let class_code = $(this).val();
      $('#zkm_code').val('');
      fetchData(class_code, function(response) {
        console.log(response);
      }, function(error) {
        console.log(error);
      })
    });

     /**-------------------------------------------------------------------------------------------------------------- */
    
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
    $('#updBtn').click(function() {
      event.preventDefault();
      var errMessage = checkValidationInput2();

      //エラーがある場合
      if (errMessage !== '') {
        //何の処理かを書く
        var process = "validate";
        //OKDialogを呼ぶ
        openOkModal(errMessage, process);
      } else {
        //確認メッセージを書く
        var msg = "営業依頼書を入力します？よろしいですか？";
        //何の処理科を書く
        var process = "update";
        //確認Dialogを呼ぶ
        openConfirmModal(msg, process);
      }
      $('#dw_input2').attr('action', 'dw_update.php');
    });

    /**-------------------------------------------------------------------------------------------------------------- */

    //確認BOXにはいボタンを押下する場合
    $("#confirm_okBtn").click(function(event) {
      var process = $("#btnProcess").val();
      //戻る処理の場合
      if (process == "return") {
        $("#dw_input2").attr("action", "dw_input1.php");
      }
      //ヘッダ更新処理の場合
      else if (process == "update") {
        //submitしたいボタン名をセットする
        $("#confirm_okBtn").attr("name", "submit");
        //sales_request_update.phpへ移動する
        $("#dw_input2").attr("action", "sales_request_update.php?title=<?= $title ?>");
      }
    });

    /**-------------------------------------------------------------------------------------------------------------- */

    //ALERT BOXに"はい"ボタンを押下する場合
    $("#ok_okBtn").click(function(event) {
      var process = $("#btnProcess").val();

      if (process == "errExec") {
        //sq_class_input1へ移動
        $('#input2').attr('action', 'sales_request_input1.php?title=<?= $title ?>');
      } else {
        //画面上変更なし
        $('#ok_okBtn').attr('data-dismiss', 'modal');
      }
    });

    /**-------------------------------------------------------------------------------------------------------------- */


  });

  /**-------------------------------------------------------------------------------------------------------------- */

  function fetchData(class_code) {
    $('#zaikoumeiList option:not(:first-child)').remove();
    $.ajax({
      url: "sales_request_input3_data_set.php",
      type: "POST",
      data: {
        function_name: "get_zaikoumei_datas",
        class_code: class_code
      },
      success: function(response){
        var zaikoumeiList = JSON.parse(response);
        var selected = $('#zkm_code').val();
        let i = 1;
        $.each(zaikoumeiList, function(index, item) {
          $('#zaikoumeiList').append($('<option>', {
            value: item.zkm_code,
            text: item.zkm_name,
            id: 'val'+i,
            class: item.code_no+','+item.text1
          }));
          if (item.zkm_code == selected) {
            $('#val'+i).prop('selected', true);
          }
          i++;
        });
        set_c_div();
      },
      error: function(xhr, status, error) {
        console.log('error')
      }
    });
  }

  /**-------------------------------------------------------------------------------------------------------------- */

  function openConfirmModal(msg, process) {
    event.preventDefault();
    //何の処理かをセットする
    $("#btnProcess").val(process);
    //確認メッセージをセットする
    $("#confirm-message").text(msg);
    //確認Dialogを呼ぶ
    $("#confirm").modal({backdrop: false});
  }

  /**-------------------------------------------------------------------------------------------------------------- */

  function openOkModal(msg, process) {
    //何の処理かをセットする
    $("#btnProcess").val(process);
    //確認メッセージをセットする
    $("#ok-message").text(msg);
    //確認Dialogを呼ぶ
    $("#ok").modal({backdrop: false});
  }

  /**-------------------------------------------------------------------------------------------------------------- */
</script>
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

  .flex-container > div {
    margin: 20px 5px;
  }

  .business_daily_report {
    width: 630px;
  }
</style>
<?php

// フッターセット
footer_set();
?>
