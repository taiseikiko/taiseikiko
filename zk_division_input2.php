  <?php
  session_start();
  header('Program-id: estimate_entry.php');
  header('Content-type: text/html; charset=utf-8');
  require_once('function.php');
  include('zk_division_input2_data_set.php');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する                               
  include("header1.php");

  //一覧画面からPOSTを取得  
  $process = $_POST['process'];
  $zk_division = $_POST['zk_division'] ?? '';
  $zk_div_name = $_POST['zk_div_name'] ?? '';
  $zk_tp = $_POST['zk_tp'] ?? '';
  $zk_no = $_POST['zk_no'] ?? '';
  $zk_div_data = $_POST['zk_div_data'] ?? '';
  $btn_name = ($process === 'create') ? '登録' : '更新';
?>
<main>
<div class="pagetitle">
  <h3>材工名仕様マスター保守</h3>
</div>
  <div class="container">
    <form class="row g-3" action="zk_division_update.php" method="POST" id="zk_division_form" enctype="multipart/form-data">
      <input type="hidden" name="process" value="<?= htmlspecialchars($process) ?>">
      <input type="hidden" name="original_zk_division" value="<?= htmlspecialchars($zk_division) ?>">
      <input type="hidden" name="original_zk_div_name" value="<?= htmlspecialchars($zk_div_name) ?>">
      <input type="hidden" name="original_zk_tp" value="<?= htmlspecialchars($zk_tp) ?>">
      <input type="hidden" name="original_zk_no" value="<?= htmlspecialchars($zk_no) ?>">
      <input type="hidden" name="original_zk_div_data" value="<?= htmlspecialchars($zk_div_data) ?>">
      <input type="hidden" name="csrf_token" value="<?= $_SESSION['token'] ?>">
      <table style="width:auto;">
        <tr>
          <td>
            <div class="field-row">
              <label class="common_label" for="zk_div_name" >材工仕様</label>
              <select type="text" id="zk_div_name" name="zk_div_name" value="" style="width: 140px;" required>
                <option value="">※選択して下さい。</option>
                <?php foreach ($zk_div_names as $name) { ?>
                  <option value="<?= $name['zk_div_name'] ?>" <?= ($zk_div_name == $name['zk_div_name']) ? 'selected' : '' ?>><?= $name['zk_div_name'] ?></option>
                <?php } ?>
              </select>
              <label class="common_label" for="zk_tp" >区分１</label>
              <select type="text" id="zk_tp" name="zk_tp" value="" style="width: 140px;" required>
                <option value="">※選択して下さい。</option>
                <?php foreach ($zk_tp_values as $tp_value) { ?>
                  <option value="<?= $tp_value['zk_tp'] ?>" <?= ($zk_tp == $tp_value['zk_tp']) ? 'selected' : '' ?>><?= $tp_value['zk_tp'] ?></option>
                <?php } ?>
              </select>
              
              <label class="common_label" for="zk_no" >区分２</label>
              <select type="text" id="zk_no" name="zk_no" value="" style="width: 140px;" required>
                <option value="">※選択して下さい。</option>
                <?php foreach ($zk_no_values as $no_value) { ?>
                  <option value="<?= $no_value['zk_no'] ?>" <?= ($zk_no == $no_value['zk_no']) ? 'selected' : '' ?>><?= $no_value['zk_no'] ?></option>
                <?php } ?>
              </select>
            </div>
          </td>                               
        </tr>
        <tr>
          <td>
            <div class="field-row">
              <label class="common_label" for="zk_div_data" >材工名仕様詳細</label>
              <input type="text" id="zk_div_data" name="zk_div_data" style="width: 700px;" value="<?= $zk_div_data?>" maxlength="50" required>
            </div>
          </td>
        </tr>
        <tr>
          <td>
            <div class="flex-container">
              <div>            
                <button id="returnBtn" name="return">戻る </button>
              </div>
              <div>
                <button class="updateBtn" id="upd_regBtn" name="submit" value="update"><?= $btn_name?></button>
              </div>
            </div>
          </td>
        </tr>
      </table>
    </form><!-- Vertical Form -->
  </div>
</main><!-- End #main -->
<script src="assets/js/inquiry_ent.js"></script>
<script src="assets/js/inquiry_ent_check.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript">  
  $(document).ready(function(){
    
    $('#returnBtn').click(function(event) {
      event.preventDefault();  
      if (confirm('一覧画面に戻ります．よろしいですか？')) {
        window.location.href = 'zk_division_input1.php';
      }
    });

  });

  //更新ボタンをクリックする時、チェックする
  document.getElementById('upd_regBtn').onclick = function(event) {
    var zk_div_data = document.getElementById('zk_div_data').value;
    var isErr = false;
    if (zk_div_data == '') {
      alert('「入力項目に不備があります。」');
      isErr = true;
    }

    if(zk_div_data.length > 50) {
      alert('「材工仕様詳細」は50文字以内で入力して下さい。');
      isErr = true;
    }
    if(isErr) {
      event.preventDefault();
    } else {
      // Show confirmation dialog
      var btn_name = '<?= htmlspecialchars($btn_name) ?>';
      if (!confirm(btn_name + 'します、よろしいでしょうか？')) {
        event.preventDefault();
      }
    }
  }
  //
</script>
<style>
  .container {
    font-family: 'Lato', sans-serif;
  }

  .updateBtn {
    margin: 2px 1px;
    background-color:red;
  }

  .copyBtn {
    margin: 2px 1px;
    background-color:blue;
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

  #class_name {
    width: 630px;
  }
</style>
<?php
// フッターセット
footer_set();
?>
