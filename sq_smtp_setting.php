<?php
  session_start();
  header('Program-id: estimate_entry.php');
  header('Content-type: text/html; charset=utf-8');
  require_once('function.php');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
  include("header1.php");
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
 
  // 初期設定 & データセット
  $count = 0;
  $material_datas = [];

  // 分類コードを取得する
  $class_datas = getClassDatas();

  //選択された分類コードを取得する
  $class_code_filter = isset($_POST['class_category']) ? $_POST['class_category'] : '';

  // 検索データ取得する
  if ($class_code_filter !== '') {
    $material_datas = getZaikomeiDatas($class_code_filter);
    if(!empty($material_datas)) {
      $count = count($material_datas);
    }
  }
  function getClassDatas() {
    global $pdo;
    $sql = "SELECT code_no,text1 FROM sq_code WHERE code_id = 'smtp_server'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $class_codes = $stmt->fetchAll();

    return $class_codes;
  }

  function getZaikomeiDatas($class_code_filter) {
    global $pdo;
    $material_datas = [];
    $sql = "SELECT server_id,host_name,aothentication,user_name,password,smtp_secure,port,add_date,upd_date FROM  smtp_setting WHERE server_id = '".$class_code_filter."'";

    $stmt = $pdo->prepare($sql);
    
    $stmt->execute();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $material_datas[] = $row;
    }

    return $material_datas;
  }
?>
<main>
  <div class="pagetitle">
    <h3>メールマスター保守</h3>
  </div>
  <div class="container">
    <form class="row g-3" method="POST" name="inq_ent" enctype="multipart/form-data" id="sq_zkm_form">
    
    <table>
        <tr>
          <td>
            <div class="field-row">
              <label class="common_label" for="code_no" >メールコード</label>
              <select name="class_category" id="flag1" onchange="submit()" required>
                <option value="">※選択して下さい。</option>
                <?php 
                  $previous_code = null;
                  if (isset($class_datas)) {
                    foreach ($class_datas as $class_item) {
                      // Check if the current code is different from the previous one
                      if ($class_item['code_no'] != $previous_code) {
                      // If different, display it as an option
                ?>
                <option id="" value="<?= $class_item['code_no'] ?>" 
                  <?php if ($class_code_filter == $class_item['code_no']) echo 'selected'; ?>>
                  <?= $class_item['text1'] ?>
                </option>
                <?php 
                  // Update the previous code to the current one
                  $previous_code = $class_item['code_no'];
                      }
                    }
                  } 
                ?>
              </select>
            </div>
          </td>                
        </tr>
      </table>
      <tbody>
          <?php
              $i = 1;
              if(isset($material_datas)) {
                foreach ($material_datas as $material_data) {
            ?>
              <table style="width:auto;">
        <tr>
          <td>
            <div class="field-row">
              <label style="padding-left: 20px; padding: right 0;" class="common_label" for="host_name" >ホスト</label>
              <input style="width: 60%;" type="text" id="host_name" name="host_name" value="<?= $material_data['host_name'] ?>" maxlength="40">
            </div>
          </td>                
        </tr>
        <tr>
          <td>
            <div class="field-row">
              <label class="common_label" for="secure">Authentication</label>
              <select name="aniba" id="aniba">
                
                <option value="" <?php if ($material_data['aothentication'] == '') echo 'selected'; ?>>※選択して下さい。</option>
                <option value=1 <?php if ($material_data['aothentication'] == 1) { echo 'selected'; $hidden = false; }?>>TRUE</option>
                <option value=0 <?php if ($material_data['aothentication'] == 0) { echo 'selected'; $hidden = true; }?>>FALSE</option>
              </select>
            </div>
          </td>
        </tr>
        <?php if (!$hidden) { ?>
        <tr id="username"> 
          <td>
            <div class="field-row">
            <label style="padding-left: 20px; padding: right 0;" class="common_label" for="user_name" >Username</label>
            <input style="width: 60%;" type="text" id="user_name" name="user_name" value="<?= $material_data['user_name'] ?>" maxlength="40">
            </div>
          </td>                
        </tr>
        <tr id="password">
          <td>
            <div class="field-row">
            <label style="padding-left: 20px; padding: right 0;" class="common_label" for="password" >Password</label>
            <input style="width: 60%;" type="text" id="password" name="password" value="<?= $material_data['password'] ?>" maxlength="40">
            </div>
          </td>                
        </tr>
        <tr>
        <?php } ?>

          <td>
            <div class="flex-container">
              <div>            
                <button id="returnBtn" name="return">戻る </button>
              </div>
              <div>
               <!-- <button class="updateBtn" id="upd_regBtn" name="btnsubmit" value="update"><?= $btn_name ?></button> -->
              </div>
            </div>
          </td>
        </tr>
      </table>
            <?php $i++; } }?>
            <input type="hidden" class="count" value="<?= $count ?>">
          </tbody>
    </form><!-- Vertical Form -->
  </div>
</main><!-- End #main -->

<script src="assets/js/inquiry_ent.js"></script>
<script src="assets/js/inquiry_ent_check.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript">

  $(document).ready(function(){
    $("#aniba").change(function(){
      var aniba = $(this).val();
      if (aniba == 1) {
        $('#username').show();
        $('#password').show();
      } else {
        $('#username').hide();
        $('#password').hide();
      }
    })

    //Handle return button click
    $("#create").click(function(){
      $("#sq_zkm_form").attr("action", "sq_smtp_setting.php");
    });    
    
    let count = $(".count").val();

    //一覧データがない場合、新規作成ボタンを非表示にする
    if ($("#hid_class_code").val() == '') {
      $("#createBtnRow").hide();
    }

    //更新ボタンを押下場合
    for (let index = 1; index <= count; index++) {
      $("#update"+index).click(function(){
        var selectedId = $(this).data('id');
        $('.zkm_code').val(selectedId);
        $("#sq_zkm_form").attr("action", "sq_zkm_input2.php");
      });
    }    
  });
</script>
<?php
// フッターセット
footer_set();
?>
<style>
  .scrollable-table-container {
    width: fit-content;
    height:700px;
    overflow: auto;
  }
  thead th {
    position: sticky;
    top: 0; 
    z-index: 1;
  }

  .updateBtn {
    margin: 2px 1px;
  }

  .createBtn {
    width: 110px;
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

  @media only screen and (max-width:800px) {
    .pagetitle, .container, .field-row {
      width: 80%;
      padding: 0;
    }
    .createBtn {
      width: 40px;
    }
  }
  @media only screen and (max-width:500px) {
    .pagetitle, .container, .field-row {
      width: 100%;
    }
    .createBtn {
      width: 40px;
    }
  }
</style>