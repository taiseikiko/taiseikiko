<?php
  session_start();
  header('Program-id: estimate_entry.php');
  header('Content-type: text/html; charset=utf-8');
  require_once('function.php');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
  $title = $_GET['title'] ?? '';
  $user_code = $_SESSION['login'];
  $user_name = $_SESSION['user_name'];      //登録者
  $office_name = $_SESSION['office_name'];  //部署
  $office_position_name = $_SESSION['office_position_name'];  //役職
  include("dw_input2_data_set.php");
  // ヘッダーセット
  include("header1.php");
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<main>
  <div class="pagetitle">
    <h3>図面管理　<?= $header ?></h3>
    <div class="container">
      <form class="row g-3" method="POST" name="inq_ent" enctype="multipart/form-data" id="dw_input2">
        <input type="hidden" id="process" name="process" value="<?= $process ?>">
        <input type="hidden" name="dw_no" id="dw_no" value="<?php echo htmlspecialchars($dw_no); ?>">
        <input type="hidden" name="client" id="client" value="<?= $user_code ?>">
        <?php include('dialog.php'); ?>
        <table style="width:auto;">
          <input type="hidden" name="sq_no" id="sq_no" value="<?= $sq_no ?>">
          <tr style="height:10px; margin-top:20px"></tr>
          <tr style="height:10px;"></tr>
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="user_name" >登録者</label>
                <input type="text" style="margin-left: 1rem;" class="readonlyText input-res" name="user_name" value="<?= $user_name ?>" readonly>
                <input type="hidden" name="user_code" value="<?= $_SESSION["login"] ?>">
                
                <label class="common_label" for="office_name">　　部署</label>
                <input type="text" style="width:370px;" name="office_name" class="readonlyText input-res" value="<?= $office_name ?>" readonly>

                <label class="common_label" for="office_position_name" >　　役職</label>
                <input type="text" style="width:100px;" class="readonlyText input-res" name="office_position_name" value="<?= $office_position_name ?>" readonly>
              </div>
            </td>      
          </tr>

          <tr style="margin-top:10px"></tr>
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="dw_div">区分</label><i class="fa fa-asterisk" style="font-size:10px;color:red"></i>

                <input type="radio" id="dw_div1_1" name="dw_div1" value="1" <?php if ($dw_div1 == '1') { echo "checked"; } ?>>
                <label class="common_label" for="dw_div1_1" style="margin-left:35px; ">標準</label>

                <input type="radio" id="dw_div1_2" name="dw_div1" value="2" <?php if ($dw_div1 == '2') { echo "checked"; } ?>>
                <label class="common_label" for="dw_div1_2">特殊</label>

                <input type="radio" id="dw_div1_3" name="dw_div1" value="3" <?php if ($dw_div1 == '3') { echo "checked"; } ?>>
                <label class="common_label" for="dw_div1_3">鋼板製</label>

                <label class="common_label" for="open_div">公開区分</label><i class="fa fa-asterisk" style="font-size:10px;color:red"></i>

                <input type="radio" id="open_div1" name="open_div" value="1" <?php if ($open_div == '1') { echo "checked"; } ?>>
                <label class="common_label" for="open_div1" style="margin-left:35px; ">公開</label>

                <input type="radio" id="open_div2" name="open_div" value="2" <?php if ($open_div == '2') { echo "checked"; } ?>>
                <label class="common_label" for="open_div2">非公開</label>
              </div>
            </td>
          </tr>

          <tr style="margin-top:10px"></tr>
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="class">分類 </label><i class="fa fa-asterisk" style="font-size:10px;color:red"></i>
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

                <label class="common_label" for="zaikoumei">　　材工名 </label><i class="fa fa-asterisk" style="font-size:10px;color:red"></i>
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
                <label class="common_label" for="size">サイズ</label><i class="fa fa-asterisk" style="font-size:10px;color:red"></i>
                <select class="dropdown-menu" style="margin-left: 1rem;" name="size" id="sizeList">
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

                <label class="common_label" for="joint">接合形状</label><i class="fa fa-asterisk" style="font-size:10px;color:red"></i>
                <select class="dropdown-menu" name="joint" id="jointList">
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

                <label class="common_label" for="pipe">管種 </label><i class="fa fa-asterisk" style="font-size:10px;color:red"></i>
                <select class="dropdown-menu" name="pipe" id="pipeList">
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
                <label class="common_label" for="specification">営業日報</label><i class="fa fa-asterisk" style="font-size:10px;color:red"></i>
                <input type="text" style="margin-left: 1rem;" class="business_daily_report" name="specification" id="specification" value="<?= $specification ?>">
              </div>
            </td>
          </tr>

          <tr style="margin-top:10px"></tr>
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="dw_div">種類</label><i class="fa fa-asterisk" style="font-size:10px;color:red"></i>

                <input type="radio" id="dw_div2_1" name="dw_div2" value="1" <?php if ($dw_div2 == '1') { echo "checked"; } ?>>
                <label class="common_label" for="dw_div2_1" style="margin-left:35px; ">営業図面</label>

                <input type="radio" id="dw_div2_2" name="dw_div2" value="2" <?php if ($dw_div2 == '2') { echo "checked"; } ?>>
                <label class="common_label" for="dw_div2_2" >工事図面</label>
              </div>
            </td>
          </tr>
        </table>

        <table>
          <tr style="height:20px;"></tr>

          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="document">図面</label>
                  　アップロードするファイル ⇒
                  <input type="file" name="uploaded_file1" id="uploaded_file1">
              </div>
            </td>
          </tr>

          <tr>
            <td>
              <div class="field-row">
                <textarea id="upload_comments1" style="margin-left: 285px; margin-top : 10px;" name="upload_comments1" rows="3" cols="120" class="textarea-res" ></textarea>
                <input type="submit" name="upload" id="upload1" value="アップロード">
              </div>
            </td>
          </tr>
        </table>
        <table class="tab1" style="margin-left:120px; margin-top:10px;width: 1020px;">
          <tr>
            <th> 添付された資料 </th>
            <th> コメント </th>
            <th> 登録日 </th>
            <th> 登録者 </th>
          </tr>
          <?php
            $files = glob('document/drawing_management/*.*');
            foreach ($files as $key => $value) {
              $cut = str_replace('document/drawing_management/', '', $value);
              $chk = substr($cut,0,strlen($dw_no));//get dw_no from file name
              $type = mb_substr($cut,strlen($dw_no)+1,2);

              //コメントをセットする
              if (!empty($file_comment_List)) {
                foreach ($file_comment_List as $item) {
                  //ファイル名を検索する
                  $search = strpos($value, $item['dw_path']);
                  //見つかったら、そのKEYのファイルコメントをセットする
                  if ($search !== false) {
                    $comments = $item['comment'];
                    $comments_date = $item['add_date'];
                    $comments_creater = $item['employee_name'];                    
                  }
                }
              }

              if($dw_no == $chk && $type == '図面'){
                echo "
                <tr>
                  <td>
                    <a href=".$value." target='_blank'>".$value."</a>
                  </td>
                  <td>$comments</td>
                  <td>$comments_date</td>
                  <td>$comments_creater</td>
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
                <label class="common_label" for="document">仕様書</label>
                  　アップロードするファイル ⇒
                  <input type="file" name="uploaded_file2" id="uploaded_file2">
              </div>
            </td>
          </tr>

          <tr>
            <td>
              <div class="field-row">
                <textarea id="upload_comments2" style="margin-left: 285px; margin-top : 10px;" name="upload_comments2" rows="3" cols="120" class="textarea-res" ></textarea>
                <input type="submit" name="upload" id="upload2" value="アップロード">
              </div>
            </td>
          </tr>
        </table>
        <table class="tab1" style="margin-left:120px; margin-top:10px;width: 1020px;">
          <tr>
            <th> 添付された資料 </th>
            <th> コメント </th>
            <th> 登録日 </th>
            <th> 登録者 </th>
          </tr>
          <?php
            $files = glob('document/drawing_management/*.*');
            foreach ($files as $key => $value) {
              $cut = str_replace('document/drawing_management/', '', $value);
              $chk = substr($cut,0,strlen($dw_no));//get dw_no from file name
              $type = mb_substr($cut,strlen($dw_no)+1,3);

              //コメントをセットする
              if (!empty($file_comment_List)) {
                foreach ($file_comment_List as $item) {
                  //ファイル名を検索する
                  $search = strpos($value, $item['dw_path']);
                  //見つかったら、そのKEYのファイルコメントをセットする
                  if ($search !== false) {
                    $comments = $item['comment'];
                    $comments_date = $item['add_date'];
                    $comments_creater = $item['employee_name'];                    
                  }
                }
              }

              if($dw_no == $chk && $type == '仕様書'){
                echo "
                <tr>
                  <td>
                    <a href=".$value." target='_blank'>".$value."</a>
                  </td>
                  <td>$comments</td>
                  <td>$comments_date</td>
                  <td>$comments_creater</td>
                </tr>";
              }
            }
          ?>
          <tr style="height:10px;"></tr>
        </table>

        <table>
          <tr>
            <td>
              <div class="flex-container">
                <div>
                  <button id="returnBtn" class="returnBtn">前の画面に戻る </button>
                </div>
                <div>
                  <button id="updBtn" class="<?=$btn_class?>" name="submit"><?= $btn_name ?> </button>
                </div>
              </div>
            </td>
            <td>
            <div class="flex-container" style="margin-left: 50rem;">
              <div>            
                <button id="returnProcessBtn" class="returnProcessBtn" <?= $btn_status?> >差し戻し </button>
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
<script src="assets/js/dw_check.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript">
  $(document).ready(function(){
    var private = '<?= $private ?>';
    if (private) {
      disableInput();
    }

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
        var btnName = $('#updBtn').text();
        //確認メッセージを書く
        var msg = btnName + "します？よろしいですか？";
        //何の処理科を書く
        var process = "update";

        if ($.trim(btnName) == '承認'){
          $('#process').val('approve');
        }
        
        //確認Dialogを呼ぶ
        openConfirmModal(msg, process);
      }
    });

    /*----------------------------------------------------------------------------------------------- */

    //アプロードボタンを押下する場合
    $('#upload1').click(function(event) {
      //重複エラーチェック
      checkDuplicate('uploaded_file1').then(function(isExist) {
        //重複エラーがある場合
        if (isExist) {
          //何の処理かを書く
          var process = "error1";
          //エラーメッセージを書く
          var errMsg = "同じ依頼書の中で同じファイル名はアップロードできません。";
          //OKDialogを呼ぶ
          openOkModal(errMsg, process);
        } else {
          //何の処理かを書く
          var process = "upload1";
          //エラーメッセージを書く
          var msg = "アプロードします。よろしいですか？";
          //確認Dialogを呼ぶ
          openConfirmModal(msg, process);
        }
      }).catch(function(error) {
        console.error("Error checking duplicate:", error);
      })
    });

    /*----------------------------------------------------------------------------------------------- */

    //アプロードボタンを押下する場合
    $('#upload2').click(function () {
      //重複エラーチェック
      checkDuplicate('uploaded_file2').then(function(isExist) {
        //重複エラーがある場合
        if (isExist) {
          //何の処理かを書く
          var process = "error2";
          //エラーメッセージを書く
          var errMsg = "同じ依頼書の中で同じファイル名はアップロードできません。";
          //OKDialogを呼ぶ
          openOkModal(errMsg, process);
        } else {
          //何の処理かを書く
          var process = "upload2";
          //エラーメッセージを書く
          var msg = "アプロードします。よろしいですか？";
          //確認Dialogを呼ぶ
          openConfirmModal(msg, process);
        }
      }).catch(function(error) {
        console.error("Error checking duplicate:", error);
      })      
    })

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
        //dw_update.phpへ移動する
        $("#dw_input2").attr("action", "dw_update.php");
      }
      //アプロード１処理の場合
      else if (process == "upload1") {
        //submitしたいボタン名をセットする
        $("#confirm_okBtn").attr("name", "upload");
        //sales_request_update.phpへ移動する
        uploadFile("dw_attach_upload1.php?from=input2_1", "1", "_図面_");
      }
      //アプロード２処理の場合
      else if (process == "upload2") {
        //submitしたいボタン名をセットする
        $("#confirm_okBtn").attr("name", "upload");
        //sales_request_update.phpへ移動する
        uploadFile("dw_attach_upload1.php?from=input2_2", "2", "_仕様書_");
      }
    });

    /**-------------------------------------------------------------------------------------------------------------- */

    //ALERT BOXに"はい"ボタンを押下する場合
    $("#ok_okBtn").click(function(event) {
      var process = $("#btnProcess").val();

      if (process == "errExec") {
        //dw_input1へ移動
        $('#dw_input2').attr('action', 'dw_input1.php');
      } else {
        //画面上変更なし
        $('#ok_okBtn').attr('data-dismiss', 'modal');
      }
    });

    /*----------------------------------------------------------------------------------------------- */

    //エラーがあるかどうか確認する
    var err = '<?= $err ?>';
    //エラーがある場合
    if (err !== '') {
      //OKメッセージを書く
      var msg = "処理にエラーがありました。係員にお知らせください。";
      //OKDialogを呼ぶ
      openOkModal(msg, 'errExec');
    }

    /**-------------------------------------------------------------------------------------------------------------- */
    
    //差し戻しボタンを押下する場合
    $('#returnProcessBtn').click(function () {
      event.preventDefault();
      var dw_no = document.getElementById('dw_no').value;

      var url = "dw_send_back.php" + "?dw_no=" + dw_no;
      window.open(url, "popupWindow", "width=900,height=260,left=100,top=50");
    })

    /*----------------------------------------------------------------------------------------------- */

    //localStorageからフォームデータをセットする
    const formData = JSON.parse(localStorage.getItem('dw_input2'));
    if (formData) {
      var myForm = document.getElementById('dw_input2');
      console.log(formData);
      Object.keys(formData).forEach(key => {
        const exceptId = ['upload_comments1', 'upload_comments2', 'uploaded_file1', 'uploaded_file2'];
        if (!exceptId.includes(key)) {
          myForm.elements[key].value = formData[key];
        }
      })

      //フォームにセット後、クリアする
      localStorage.removeItem('dw_input2');
    }

    /*----------------------------------------------------------------------------------------------- */
    

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

  //重複エラーチェック
  function checkDuplicate(uploaded_file_name) {
    event.preventDefault();
    
    var isExist;
    var dw_no = $("#dw_no").val();
    var file_path = $("#"+uploaded_file_name).val().split('\\').pop();

    return new Promise(function (resolve, reject) {
      $.ajax({
        type: "POST",
        url: "dw_file.php",
        data: {
          checkDuplicate: true,
          dw_no: dw_no,
          file_path: file_path
        },
        success: function(response) {
          var parse_response = JSON.parse(response);
          resolve(parse_response.isExist);
        },
        error: function(xhr, status, error) {
          console.error("AJAX request failed:", error); // Log any errors
          reject(error);
        }
      });
    });
  }

  /*----------------------------------------------------------------------------------------------- */

  function uploadFile(url, index, filename) {
    event.preventDefault();
    var dw_no = document.getElementById('dw_no').value;
    var client = document.getElementById('client').value;
    var uploaded_file = document.getElementById('uploaded_file' + index).files[0];
    var upload_comments = document.getElementById('upload_comments' + index).value;
    var save_file_name = dw_no + filename;

    var formData = new FormData();
    formData.append('dw_no', dw_no);
    formData.append('client', client);
    formData.append('uploaded_file', uploaded_file);
    formData.append('upload_comments', upload_comments);
    formData.append('save_file_name', save_file_name);

    $.ajax({
      type: "POST",
      url: url,
      data: formData,
      processData: false, // Important: prevent jQuery from processing the data
      contentType: false, // Important: ensure jQuery does not add a content-type header
      success: function(response) {
        //フォームデータを保存する
        saveFormData();
        //reload page
        location.reload();
      },
      error: function(xhr, status, error) {
      }
    })

  }

  /*----------------------------------------------------------------------------------------------- */

  function saveFormData() {
    var myForm = document.getElementById('dw_input2');
    const formData = new FormData(myForm);
    const jsonData = JSON.stringify(Object.fromEntries(formData));
    localStorage.setItem('dw_input2', jsonData);
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
    for (var k = 0; k < selects.length; k++) {
      selects[k].disabled = true;
    }

    //Disabled button 
    var buttons = document.getElementsByTagName('button');
    const excludeButtons = ['returnBtn', 'okBtn', 'cancelBtn'];
    for (var k = 0; k < buttons.length; k++) {
      if (!excludeButtons.includes(buttons[k].className)) {
        buttons[k].disabled = true;
      }
    }
  }
  /*----------------------------------------------------------------------------------------------- */
  
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

  .updRegBtn {
    background:#80dfff;
  }
</style>
<?php

// フッターセット
footer_set();
?>
