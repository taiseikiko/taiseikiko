<?php
  session_start();
  header('Program-id: estimate_entry.php');
  header('Content-type: text/html; charset=utf-8');
  require_once('function.php');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
  $dept_code = $_SESSION['department_code'];
  $department_code = getDeptId($dept_code);
  // ヘッダーセット
  include("header1.php");
  include("card_input3_data_set.php");
?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<main>
  <div class="pagetitle">
    <h3>技術部＆工事技術部での<?= $page ?></h3>
    <div class="container">
      <form class="row g-3" method="POST" name="inq_ent" enctype="multipart/form-data" id="card_input3">
        <?php include("dialog.php") ?>
        <input type="hidden" name="sq_card_no" id="sq_card_no" value="<?= $sq_card_no ?>">
        <input type="hidden" name="sq_card_line_no" id="sq_card_line_no" value="<?= $sq_card_line_no ?>">
        <input type="hidden" name="page" id="page" value="<?= $page ?>">
        <input type="hidden" name="hid_sq_card_no" id="hid_sq_card_no">
        <input type="hidden" name="hid_sq_card_line_no" id="hid_sq_card_line_no">
        <input type="hidden" name="hid_file_name" id="hid_file_name">
        <table style="width:auto;">
          <tr style="height:10px; margin-top:20px"></tr>
          <tr style="height:10px;"></tr>
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="sq_card_no">依頼書№</label>
                <input type="text" style="margin-left: 1rem;" class="input-res" name="sq_card_no" value="<?= $sq_card_no ?>">
                <input type="hidden" name="" value="">
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="user_name">登録者</label>
                <input type="text" style="margin-left: 1rem;" class="input-res" name="user_name" value="<?= $client_name ?>">
                <input type="hidden" name="user_code" value="<?= $_SESSION["login"] ?>">

                <label class="common_label" for="office_name">部署</label>
                <input type="text" style="width:370px;margin-left: 1rem;" name="office_name" class="input-res" value="<?= $dept_name ?>">

                <label class="common_label" for="office_position_name">役職</label>
                <input type="text" style="width:100px;margin-left: 1rem;" class="input-res" name="office_position_name" value="<?= $role_name ?>">
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="p_office_no">事業体 </label>
                <input type="text" style="margin-left: 1rem;" id="p_office_name" name="p_office_name" value="<?= $p_office_name ?>" class="input-res">
                <input type="hidden" name="p_office_code" id="p_office_code" value="<?= $p_office_code ?>">
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
        </table>
        <table class="tab1">
          <tr>
            <th> 添付された資料 </th>
          </tr>
          <?php
          if (!empty($sq_no)) {
            $files = glob('document/sales_management/*.*');
            foreach ($files as $key => $value) {
              $cut = str_replace('document/sales_management/', '', $value);
              $chk = substr($cut, 0, strlen($sq_no));
              if ($sq_no == $chk) {
                echo "<tr><td><a href=" . $value . " target='_blank'>" . $value . "</a></td></tr>";
              }
            }
          }
          ?>
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
                <label class="common_label" for="procurement_no">資材部№</label>
                <input type="text" style="margin-left: 1rem;" class="input-res" name="procurement_no" value="<?= $procurement_no ?>">
                <label class="common_label" for="maker">製造メーカー</label>
                <input type="text" style="margin-left: 1rem;" class="business_daily_report" name="maker" id="" value="<?= $maker ?>">
              </div>
            </td>
          </tr>

          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="class4">分類 </label>
                <select style="margin-left: 1rem;" class="dropdown-menu" id="classList4" name="class_code4">
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

                <label class="common_label" for="zkm_code">材工名 </label>
                <?php 
                  if (!isset($zaikoumeiList) || empty($zaikoumeiList)) {
                    $zaikoumeiDisabled = 'disabled';
                  }
                ?>
                <select class="dropdown-menu" style="margin-left: 1rem;" id="zaikoumeiList" name="zaikoumei" <?= $zaikoumeiDisabled ?>>
                  <option value="">選択して下さい。</option>
                  <?php 
                    if (isset($zaikoumeiList) && !empty($zaikoumeiList)) {
                      foreach ($zaikoumeiList as $item) {
                        $code = $item['zkm_code'];
                        $text = $item['zkm_name'];
                        $selected = ($code == $zkm_code) ? 'selected' : '';
                        echo "<option value='$code' $selected>$text</option>";
                      }
                    }
                  ?>
                </select>
                <input type="hidden" name="zkm_code" id="zkm_code" value="<?= $zkm_code ?>">

                <label class="common_label" for="pipe">管種</label>
                <?php 
                  if (!isset($pipeList) || empty($pipeList)) {
                    $pipeDisabled = 'disabled';
                  }
                ?>
                <select class="dropdown-menu" style="margin-left: 1rem;" name="pipe" <?= $pipeDisabled ?>>
                  <option value="">選択して下さい。</option>
                  <?php 
                    if (isset($pipeList) && !empty($pipeList)) {
                      foreach ($pipeList as $item) {
                        $code = $item['zk_div_data'];
                        $text = $item['zk_div_data'];
                        $selected = ($code == $pipe) ? 'selected' : '';
                        echo "<option value='$code' $selected>$text</option>";
                      }
                    }
                  ?>
                </select>   
                
                <label class="common_label" for="size4">　　サイズ </label>
                <input type="text" style="margin-left: 1rem; width:80px;" name="sizeA4" value="<?= $sizeA ?>">mm　　✖
                <input type="text" style="margin-left: 1rem; width:80px;" name="sizeB4" value="<?= $sizeB ?>">mm　
              </div>
            </td>
          </tr>

          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="specification_no">仕様書№</label>
                <input type="text" style="margin-left: 1rem;" class="input-res" name="specification_no" value="<?= $specification_no ?>">
                <label class="common_label" for="special_note">特記事項</label>
                <input type="text" style="margin-left: 1rem;" class="business_daily_report" name="special_note" id="" value="<?= $special_note ?>">
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
                <label class="common_label" for="user_name">担当指定者</label>
                <input type="text" style="margin-left: 1rem;" class="input-res" name="user_name" value="<?= $_SESSION['user_name'] ?>">
                <input type="hidden" name="user_code" value="<?= $_SESSION["login"] ?>">

                <label class="common_label" for="office_name">部署</label>
                <input type="text" style="width:370px;margin-left: 1rem;" name="office_name" class="input-res" value="<?= $_SESSION['office_name'] ?>">

                <label class="common_label" for="office_position_name">役職</label>
                <input type="text" style="width:100px;margin-left: 1rem;" class="input-res" name="office_position_name" value="<?= $_SESSION['office_position_name'] ?>">
              </div>
            </td>
          </tr>

          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="entrant_set_date">担当指定日</label>
                <input type="date" style="margin-left: 1rem;" min="2023-01-01" max="2028-12-31" name="entrant_set_date" id="entrant_set_date" value="<?= $entrant_set_date ?>" class="input-res" />
                <label class="common_label" for="entrant_set_comments">コメント</label>
                <textarea id="entrant_set_comments" style="margin-left: 1rem;" name="entrant_set_comments" rows="3" cols="120" class="textarea-res" ><?= $entrant_set_comments ?></textarea>
              </div>
            </td>
          </tr>
          <tr style="height:10px;"></tr>

          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="entrant">担当者</label><i class="fa fa-asterisk" style="font-size:10px;color:red;margin-left:1px;"></i>
                <select name="entrant" class="input-res" id="entrant" >
                  <option value="">選択して下さい。</option>
                  <?php 
                    if (isset($entrantList) && !empty($entrantList)) {
                      foreach($entrantList as $item) {
                        $code = $item['employee_code'];
                        $text = $item['employee_name'];
                        $selected = ($code == $entrant) ? 'selected' : '';
                        echo "<option value='$code' $selected>$text</option>";
                      }
                    }
                  ?>
                </select>
                <input type="hidden" name="hid_entrant" value="<?= $entrant ?>">

                <label class="common_label" for="office_name">部署</label>
                <input type="text" style="width:370px;margin-left: 1rem;" name="entrant_dept_name" id="entrant_dept_name" class="input-res" value="<?= $entrant_dept_name ?>">

                <label class="common_label" for="office_position_name">　　役職</label>
                <input type="text" style="width:100px;margin-left: 1rem;" class="input-res" id="entrant_role_name" name="entrant_role_name" value="<?= $entrant_role_name ?>">
              </div>
            </td>
          </tr>

          <!-- 受付画面の場合、表示しない------------------------　開始 ------------------------------------------>
          <?php if ($page !== '受付') { ?>
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="entrant_date">登録日</label>
                <input type="date" style="margin-left: 1rem;" min="2023-01-01" max="2028-12-31" name="entrant_date" id="entrant_date" value="<?= $entrant_date ?>" class="input-res"/>
                <label class="common_label" for="entrant_comments">コメント</label>
                <textarea id="entrant_comments" style="margin-left: 1rem;" name="entrant_comments" rows="3" cols="120" class="textarea-res" ><?= $entrant_comments ?></textarea>
              </div>
            </td>
          </tr>
          <tr style="height:20px;"></tr>

          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="production-drawing">制作図面</label>
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
            <th style="width:450px;"> コメント </th>
            <th style="width:40px;"> 処理 </th>
          </tr>
          <?php
            $i = 0;
            $files = glob('document/card_engineering/card_detail_no' . $sq_card_line_no . '/*.*');
            foreach ($files as $key => $value) {            
              $cut = str_replace('document/card_engineering/card_detail_no' . $sq_card_line_no . '/', '', $value);
              $chk = substr($cut,0,strlen($sq_card_no));//get sq_card_no from file name
              $type = mb_substr($cut,strlen($sq_card_no)+1,4);

              //コメントをセットする
              if (!empty($file_comment_List)) {
                foreach ($file_comment_List as $item) {
                  //ファイル名を検索する
                  $search = strpos($value, $item['file_name']);
                  //見つかったら、そのKEYのファイルコメントをセットする
                  if ($search !== false) {
                    $comments = $item['file_comments'];
                    $tb_sq_card_no = $item['sq_card_no'];
                    $tb_sq_card_line_no = $item['sq_card_line_no'];
                    $tb_file_name = $item['file_name'];
                  }
                }
              }
              
              if($sq_card_no == $chk && $type == '制作図面'){
                $i++;
                echo "
                <tr>
                  <td>
                    <a href=".$value." target='_blank'>".$value."</a>
                  </td>
                  <td>
                    $comments
                  </td>
                  <td>
                    <button class='cancelProcessBtn' id='delBtn" . $i . "' name='delete'
                    data-file_name=" . $tb_file_name . "
                    data-sq_card_no=" . $tb_sq_card_no . "
                    data-sq_card_line_no=" . $tb_sq_card_line_no . "
                    >削除</button>
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
                <label class="common_label" for="document">資料</label>
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
            <th style="width:450px;"> コメント </th>
            <th style="width:40px;"> 処理 </th>
          </tr>
          <?php
            $z = 0;
            $files = glob('document/card_engineering/card_detail_no' . $sq_card_line_no . '/*.*');
            foreach ($files as $key => $value) {
              $cut = str_replace('document/card_engineering/card_detail_no' . $sq_card_line_no . '/', '', $value);
              $chk = substr($cut,0,strlen($sq_card_no));//get sq_card_no from file name
              $type = mb_substr($cut,strlen($sq_card_no)+1,2);

              //コメントをセットする
              if (!empty($file_comment_List)) {
                foreach ($file_comment_List as $item) {
                  //ファイル名を検索する
                  $search = strpos($value, $item['file_name']);
                  //見つかったら、そのKEYのファイルコメントをセットする
                  if ($search !== false) {
                    $comments = $item['file_comments'];
                    $tb_sq_card_no = $item['sq_card_no'];
                    $tb_sq_card_line_no = $item['sq_card_line_no'];
                    $tb_file_name = $item['file_name'];
                  }
                }
              }

              if($sq_card_no == $chk && $type == '資料'){
                $z++;
                echo "
                <tr>
                  <td>
                    <a href=".$value." target='_blank'>".$value."</a>
                  </td>
                  <td>
                    $comments
                  </td>
                  <td>
                    <button class='cancelProcessBtn' id='delBtnTwo" . $z . "' name='delete'
                    data-file_name=" . $tb_file_name . "
                    data-sq_card_no=" . $tb_sq_card_no . "
                    data-sq_card_line_no=" . $tb_sq_card_line_no . "
                    >削除</button>
                  </td>
                </tr>";                
              }              
            }
          ?>
          <tr style="height:10px;"></tr>
        </table>  

        <table>
          <!-- 確認と承認画面の場合だけ、表示する -->
          <?php if ($page == '確認' || $page == '承認' || $page == '詳細') { ?>
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="confirmer_comments">確認者コメント</label>
                <textarea id="confirmer_comments" name="confirmer_comments" rows="3" cols="120" class="textarea-res" ><?= $confirmer_comments ?></textarea>
              </div>
            </td>
          </tr>
          <?php } 
          //承認画面の場合だけ、表示する
          if ($page == '承認' || $page == '詳細') { ?>
          <table  style="margin-top:13px;">
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="approver_comments">承認者コメント</label>
                <textarea id="approver_comments" name="approver_comments" rows="3" cols="120" class="textarea-res" ><?= $approver_comments ?></textarea>
              </div>
            </td>
          </tr>
          <?php } ?>
        </table>
        <?php } ?>
        <!-- 受付画面の場合、表示しない------------------------　完了 ------------------------------------------>

        <table>
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="confirmer">確認者</label>
                <input type="text" style="margin-left: 7px;" id="confirmer" name="confirmer" value="<?= $confirmer_name ?>" class="readonlyText" readonly>

                <label class="common_label" for="confirm_date">確認日</label>
                <input type="date" min="2023-01-01" max="2028-12-31" name="confirm_date" id="confirm_date" value="<?= $confirm_date ?>" class="input-res" />
              </div>
            </td>
          </tr>

          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="approver">承認者</label>
                <input type="text" style="margin-left: 7px;" id="approver" name="approver" value="<?= $approver_name ?>" class="readonlyText" readonly>

                <label class="common_label" for="approve_date">承認日</label>
                <input type="date" min="2023-01-01" max="2028-12-31" name="approve_date" id="approve_date" value="<?= $approve_date ?>" class="input-res" />
              </div>
            </td>
          </tr>
        </table>
        
        <table  style="margin-top:13px;">
          <tr>
            <td>
              <div class="flex-container">
                <div>
                  <button id="returnBtn" class="returnBtn">前の画面に戻る </button>
                  <button class="updateBtn" id="updateBtn" name="update" value="update">更新</button>
                </div>
              </div>
            </td>
            <!-- 確認画面と承認画面の場合だけ、表示する------------------------　開始 ------------------------------------------>
            <td>
              <div class="flex-container" style="margin-left:50rem">
                <div>
                  <button id="returnProcessBtn" class="returnProcessBtn">差し戻し </button>
                  <?php if ($page !== '入力' && $page !== '受付') { ?>
                  <button class="cancelProcessBtn" id="cancelProcessBtn">中止</button>
                  <?php } ?>
                </div>
              </div>
            </td>
            <!-- 確認画面と承認画面の場合だけ、表示する------------------------　完了 ------------------------------------------>
          </tr>
        </table>
      </form><!-- Vertical Form -->
    </div>
  </div>
</main><!-- End #main -->
</body>
</html>
<script src="assets/js/card_check.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript">
  /*--------------------------------------------JQUERY END--------------------------------------------------------*/
  $(document).ready(function() {
    //表示さる画面（受付、入力、確認、承認）によって入力可能不可能の処理
    var page = '<?= $page ?>';
    //受付画面の場合だけ、　担当指定日とコメントを入力可能にする
    if (page !== '受付') {
      $('#entrant_set_date').prop('disabled', true);                  //担当指定者
      $('#entrant_set_comments').prop('disabled', true);              //担当指定者コメント
      $('#entrant_set_comments').css('background-color', '#e6e6e6');
      $('#entrant').prop('disabled', true);                           //担当者
    }
    //確認画面の場合、入力関連のINPUT BOXを入力不可能にする
    if (page == '確認' || page == '承認') {
      $('#entrant_date').prop('disabled', true);      //登録日
      $('#entrant_comments').prop('disabled', true);  //コメント
      $('#entrant_comments').css('background-color', '#e6e6e6');
    }
    //承認画面の場合、確認関連のINPUT BOXを入力不可能にする
    if (page == '承認') {
      $('#confirmer_comments').prop('disabled', true);  //コメント
      $('#confirmer_comments').css('background-color', '#e6e6e6');
    }
    //承認後の場合
    if (page == '詳細') {
      const ids = ['entrant_date', 'entrant', 'entrant_set_date'];
      ids.forEach(element => {
        $('#' + element).prop('disabled', true);
      });
      const comments = ['entrant_set_comments', 'entrant_comments','confirmer_comments', 'approver_comments'];
      comments.forEach(element => {
        $('#' + element).prop('disabled', true);              //担当指定者コメント
        $('#' + element).css('background-color', '#e6e6e6');
      });
    }

    /*----------------------------------------------------------------------------------------------- */
    //担当者が選択された場合、部署名と役割をセットする
    $('#entrant').change(function () {
      var entrant = $(this).val();
      $.ajax({
        type: "POST",
        url: "card_input3_data_set.php",
        data: {
          getUserInfo: true,
          employee_code: entrant 
        },
        success: function(response) {
          var employee = JSON.parse(response);
          $('#entrant_dept_name').val(employee.dept_name);  //部署
          $('#entrant_role_name').val(employee.role_name);  //部署
        },
        error: function(xhr, status, error) {
          console.error("AJAX request failed:", error); // Log any errors
        }
      })
    });

    /*----------------------------------------------------------------------------------------------- */

    //更新ボタンを押下する場合
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
        //確認メッセージを書く
        var msg = "更新します。よろしいですか？";
        //何の処理かを書く
        var process = "update";
        //確認Dialogを呼ぶ
        openConfirmModal(msg, process);      
      }
    });

    /*----------------------------------------------------------------------------------------------- */

    //戻るボタンを押下する場合
    $('#returnBtn').click(function(event) {
      //確認メッセージを書く
      var msg = "前の画面に戻します。よろしいですか？";
      //何の処理科を書く
      var process = "return";
      //確認Dialogを呼ぶ
      openConfirmModal(msg, process);
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
          event.preventDefault();
          var uploaded_file = document.getElementById("uploaded_file1"); //ファイル
          var errMessage = checkValidationFile(uploaded_file);
          
          //エラーがある場合
          if (errMessage !== '') {
            //何の処理かを書く
            var process = "validate";
            //OKDialogを呼ぶ
            openOkModal(errMessage, process);
          } else {
            //何の処理かを書く
            var process = "upload1";
            //エラーメッセージを書く
            var msg = "アプロードします。よろしいですか？";
            //確認Dialogを呼ぶ
            openConfirmModal(msg, process);
          }          
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
          event.preventDefault();
          var uploaded_file = document.getElementById("uploaded_file2"); //ファイル
          var errMessage = checkValidationFile(uploaded_file);
          
          //エラーがある場合
          if (errMessage !== '') {
            //何の処理かを書く
            var process = "validate";
            //OKDialogを呼ぶ
            openOkModal(errMessage, process);
          } else {
            //何の処理かを書く
            var process = "upload2";
            //エラーメッセージを書く
            var msg = "アプロードします。よろしいですか？";
            //確認Dialogを呼ぶ
            openConfirmModal(msg, process);
          }          
        }
      }).catch(function(error) {
        console.error("Error checking duplicate:", error);
      })      
    })

    /*----------------------------------------------------------------------------------------------- */

    //差し戻しボタンを押下する場合
    $('#returnProcessBtn').click(function () {
      event.preventDefault();
      var sq_card_no = document.getElementById('sq_card_no').value;
      var sq_card_line_no = document.getElementById('sq_card_line_no').value;
      var page = document.getElementById('page').value;

      var from = 'other_dept';

      var url = "card_send_back.php" + "?sq_card_no=" + sq_card_no + 
      "&sq_card_line_no=" + sq_card_line_no +
      "&page=" + page +
      "&from=" + from;
      window.open(url, "popupWindow", "width=900,height=260,left=100,top=50");
    })

    /*----------------------------------------------------------------------------------------------- */
    //中止ボタンを押下する場合
    $('#cancelProcessBtn').click(function () {
      event.preventDefault();
      var sq_card_no = document.getElementById('sq_card_no').value;
      var sq_card_line_no = document.getElementById('sq_card_line_no').value;
      var from = 'other_dept';

      var url = "card_abort.php" + "?sq_card_no=" + sq_card_no + 
      "&sq_card_line_no=" + sq_card_line_no +
      "&from=" + from;
      window.open(url, "popupWindow", "width=900,height=260,left=100,top=50");
    })

    /*----------------------------------------------------------------------------------------------- */
    //削除処理   
    var i = <?= $i ?>;
    for (var x = 1; x <= i; x++) {
      $('#delBtn' + x).click(function () {
        var sq_card_no = $(this).data('sq_card_no');
        var sq_card_line_no = $(this).data('sq_card_line_no');
        var file_name = $(this).data('file_name');
        $('#hid_sq_card_no').val(sq_card_no);
        $('#hid_sq_card_line_no').val(sq_card_line_no);
        $('#hid_file_name').val(file_name);

        event.preventDefault();
        //何の処理かを書く
        var process = "delete";
        //エラーメッセージを書く
        var msg = "削除します。よろしいですか？";
        //確認Dialogを呼ぶ
        openConfirmModal(msg, process);
      })      
    }

    var z = <?= $z ?>;
    for (var x = 1; x <= z; x++) {
      $('#delBtnTwo' + x).click(function () {
        var sq_card_no = $(this).data('sq_card_no');
        var sq_card_line_no = $(this).data('sq_card_line_no');
        var file_name = $(this).data('file_name');
        $('#hid_sq_card_no').val(sq_card_no);
        $('#hid_sq_card_line_no').val(sq_card_line_no);
        $('#hid_file_name').val(file_name);

        event.preventDefault();
        //何の処理かを書く
        var process = "delete";
        //エラーメッセージを書く
        var msg = "削除します。よろしいですか？";
        //確認Dialogを呼ぶ
        openConfirmModal(msg, process);
      })      
    }

    //localStorageからフォームデータをセットする
    const formData = JSON.parse(localStorage.getItem('card_input3'));
    if (formData) {
      var myForm = document.getElementById('card_input3');
      console.log(formData);
      Object.keys(formData).forEach(key => {
        const exceptId = ['upload_comments1', 'upload_comments2', 'uploaded_file1', 'uploaded_file2'];
        if (!exceptId.includes(key)) {
          myForm.elements[key].value = formData[key];
        }
      })

      //フォームにセット後、クリアする
      localStorage.removeItem('card_input3');
    }

    /*----------------------------------------------------------------------------------------------- */

    //エラーがあるかどうか確認する
    var err = '<?= $err ?>';
    //エラーがある場合
    if (err !== '') {
      //OKメッセージを書く
      var msg = "処理にエラーがありました。係員にお知らせください。";
      //OKDialogを呼ぶ
      openOkModal(msg, 'exceErr');
    }

    /*----------------------------------------------------------------------------------------------- */

    //確認BOXに"はい"ボタンを押下する場合
    $("#confirm_okBtn").click(function(event) {
      var process = $("#btnProcess").val();
      //戻る処理の場合
      if (process == "return") {
        $('#card_input3').attr('action', 'card_input2.php?card_no=<?= $sq_card_no ?>&process=<?= $process ?>');
      }
      //ヘッダ更新処理の場合
      else if (process == "update") {
        //submitしたいボタン名をセットする
        $("#confirm_okBtn").attr("name", "update");
        //sales_request_update.phpへ移動する
        $('#card_input3').attr('action', 'card_update.php');
      }
      //アプロード１処理の場合
      else if (process == "upload1") {
        //submitしたいボタン名をセットする
        $("#confirm_okBtn").attr("name", "upload");
        //sales_request_update.phpへ移動する
        uploadFile("card_attach_upload1.php?from=input3_1", "1", "_制作図面_");
      }
      //アプロード２処理の場合
      else if (process == "upload2") {
        //submitしたいボタン名をセットする
        $("#confirm_okBtn").attr("name", "upload");
        //sales_request_update.phpへ移動する
        uploadFile("card_attach_upload1.php?from=input3_2", "2", "_資料_");
      }
      //ファイル削除処理の場合
      else if (process == "delete") {
        //submitしたいボタン名をセットする
        $("#confirm_okBtn").attr("name", "delete");
        //sales_request_update.phpへ移動する
        deleteFile("card_attach_delete1.php");
      }
    });

    /*----------------------------------------------------------------------------------------------- */

    //ALERT BOXに"はい"ボタンを押下する場合
    $("#ok_okBtn").click(function(event) {
      var process = $("#btnProcess").val();
      //エラーがある場合
      if (process == "error1") {
        //選択したファイルをクリアする
        $('#uploaded_file1').val('');
        $('#upload_comments1').val('');
      }
      else if (process == "error2") {
        //選択したファイルをクリアする
        $('#uploaded_file2').val('');
        $('#upload_comments2').val('');
      }
      else if (process == "exceErr") {
        //card_input1へ移動
        $('#card_input3').attr('action', 'card_input1.php');
      } else {
        //画面上変更なし
        $('#ok_okBtn').attr('data-dismiss', 'modal');
      }
    });
  });

  /*--------------------------------------------JQUERY END--------------------------------------------------------*/

  function openConfirmModal(msg, process) {
    event.preventDefault();
    //何の処理かをセットする
    $("#btnProcess").val(process);
    //確認メッセージをセットする
    $("#confirm-message").text(msg);
    //確認Dialogを呼ぶ
    $("#confirm").modal({backdrop: false});
  }

  //重複エラーチェック
  function checkDuplicate(uploaded_file_name) {
    event.preventDefault();
    
    var isExist;
    var sq_card_no = $("#sq_card_no").val();
    var sq_card_line_no = $("#sq_card_line_no").val();
    var file_name = $("#"+uploaded_file_name).val().split('\\').pop();

    return new Promise(function (resolve, reject) {
      $.ajax({
        type: "POST",
        url: "card_file.php",
        data: {
          checkDuplicate: true,
          sq_card_no: sq_card_no,
          sq_card_line_no: sq_card_line_no,
          file_name: file_name
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

  function openOkModal(msg, process) {
    //何の処理かをセットする
    $("#btnProcess").val(process);
    //確認メッセージをセットする
    $("#ok-message").text(msg);
    //確認Dialogを呼ぶ
    $("#ok").modal({backdrop: false});
  }

  /*----------------------------------------------------------------------------------------------- */

  function uploadFile(url, index, filename) {
    event.preventDefault();
    var sq_card_no = document.getElementById('sq_card_no').value;
    var sq_card_line_no = document.getElementById('sq_card_line_no').value;
    var uploaded_file = document.getElementById('uploaded_file' + index).files[0];
    var upload_comments = document.getElementById('upload_comments' + index).value;
    var save_file_name = sq_card_no + filename;

    var formData = new FormData();
    formData.append('sq_card_no', sq_card_no);
    formData.append('sq_card_line_no', sq_card_line_no);
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

  function deleteFile(url) {
    event.preventDefault();
    var sq_card_no = document.getElementById('hid_sq_card_no').value;
    var sq_card_line_no = document.getElementById('hid_sq_card_line_no').value;
    var file_name = document.getElementById('hid_file_name').value;

    var formData = new FormData();
    formData.append('sq_card_no', sq_card_no);
    formData.append('sq_card_line_no', sq_card_line_no);
    formData.append('file_name', file_name);

    $.ajax({
      type: "POST",
      url: url,
      data: formData,
      processData: false, // Important: prevent jQuery from processing the data
      contentType: false, // Important: ensure jQuery does not add a content-type header
      success: function(response) {
        console.log(response);
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
    var myForm = document.getElementById('card_input3');
    const formData = new FormData(myForm);
    const jsonData = JSON.stringify(Object.fromEntries(formData));
    localStorage.setItem('card_input3', jsonData);
  }

  /*----------------------------------------------------------------------------------------------- */

  //Disable Input
  var inputs = document.getElementsByTagName('input');
  const excludeInputs = ['hidden'];
  const excludeInputsWithID = ['entrant_set_date', 'entrant_date', 'uploaded_file1', 'uploaded_file2', 'upload1', 'upload2'];
  for (var i = 0; i < inputs.length; i++) {
    if (!excludeInputs.includes(inputs[i].type.toLowerCase()) && !excludeInputsWithID.includes(inputs[i].id)) {
      inputs[i].disabled = true;
    }
    if (inputs[i].type.toLowerCase() == "text") {
      inputs[i].style.backgroundColor = '#e6e6e6';
    }
  }

  //Disabled select 
  var selects = document.getElementsByTagName('select');
  const excludeSelects = ['entrant'];
  for (var k = 0; k < selects.length; k++) {
    if (!excludeSelects.includes(selects[k].id)) {
      selects[k].disabled = true;
    }
  }

  //Disabled button 
  var buttons = document.getElementsByTagName('button');
  const excludeButtons = ['returnBtn', 'updateBtn', 'okBtn', 'cancelBtn', 'cancelProcessBtn', 'returnProcessBtn'];
  for (var k = 0; k < buttons.length; k++) {
    if (!excludeButtons.includes(buttons[k].className)) {
      buttons[k].disabled = true;
    }
  }
</script>
<style>
  .flex-container {
    display: flex;    
  }

  .flex-container > div {
    margin: 20px 5px;
  }
</style>
<?php
  // フッターセット
  footer_set();
?>