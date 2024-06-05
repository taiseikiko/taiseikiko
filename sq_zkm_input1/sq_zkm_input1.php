<?php
  session_start();
  header('Program-id: estimate_entry.php');
  header('Content-type: text/html; charset=utf-8');
  require_once('function.php');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
  $msg = "";

  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

// 初期設定 & データセット
    //include('inquiry_ent21.php');
    //$_FILES["upfile"] = "";
    //$_FILES["upfile"]['temp_name'] = "";

// 戻るボタンの遷移先
    $url = 'inquiry_ent.php';

  /* header.html                                        */  
  /*   ・ヘッダー記述                                    */  
  /*   ・メニュー記述                                    */  
  /*   ・サイドバー記述                                  */  
  include("header1.php");

  // 分類コードを取得する
  $sql_class_code = "SELECT class_code FROM sq_zaikoumei";
  $stmt_class_code = $pdo->prepare($sql_class_code);
  $stmt_class_code->execute();
  $class_codes = $stmt_class_code->fetchAll();

  // 材工名マスターからデータ取得する
  $class_code_filter = isset($_POST['class_category']) ? $_POST['class_category'] : '';
  $sql_material = "SELECT * FROM sq_zaikoumei";
  if (!empty($class_code_filter)) {
      $sql_material .= " WHERE class_code = :class_code";
  }
  $stmt_material = $pdo->prepare($sql_material);
  if (!empty($class_code_filter)) {
      $stmt_material->bindValue(':class_code', $class_code_filter, PDO::PARAM_STR);
  }
  $stmt_material->execute();

// $material_datas = $stmt_material->fetchAll();
  $material_datas = [];
  while ($row = $stmt_material->fetch(PDO::FETCH_ASSOC)) {
    $material_datas[] = $row;
  }
/* 
error_log(print_r($coating_ex_nm)."\n",3,'error_log.txt');
error_log($sql2."\n",3,'error_log.txt');
error_log('row2='.print_r($row2,true)."\n",3,'error_log.txt');
*/
?>
<style>

  input[type=text], input[type=checkbox], select {
    /* width: 50%; */
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
  }

  #common_label {
    width: 100px;
    text-align: start;
  }

  input.readonlyText {
    background-color: #ffffe0;
  }

  .clearfix:after {
    clear: both;
    content: "";
    display: block;
    height: 0;
  }

  /* Responsive Arrow Progress Bar */

  .container {
    font-family: 'Lato', sans-serif;
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
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<main>
  <div class="pagetitle">
    <h3>材工名マスター保守</h3>
    <div class="container">
      <form class="row g-3"  method="POST" name="inq_ent" enctype="multipart/form-data">
        <table>
            <tr>
                <td>
                <div class="field-row">
                    <label id="common_label" for="class_code" >分類コード</label>
                    <select name="class_category" onchange="submit(this.form)" required>
                      <option value="">※選択して下さい。</option>
                        <?php 
                          $previous_code = null;
                          foreach ($class_codes as $class_code) {
                            // Check if the current code is different from the previous one
                            if ($class_code['class_code'] != $previous_code) {
                            // If different, display it as an option
                        ?>
                      <option value="<?= $class_code['class_code'] ?>" 
                        <?php if ($class_code_filter === $class_code['class_code']) echo 'selected'; ?>>
                        <?= $class_code['class_code'] ?>
                      </option>
                        <?php 
                          // Update the previous code to the current one
                          $previous_code = $class_code['class_code'];
                          }
                        } 
                        ?>
                    </select>
                </div>
                </td>                
            </tr>
        </table>

        <table class="tab1" style="width:100%; margin-top:20px;">
          <tr>
            <th style="width:10%">材工名コード</th>
            <th>材工名　名称</th>
            <th style="width:140px;">区分</th>
            <th style="width:140px;">処理</th>
          </tr>
          <tr>
            <td colspan="4" style="text-align:left"><button class="createBtn" onclick="location.href='sq_zkm_input2.php'">新規作成</button></td>
          </tr>
          <tr>
            <?php 
                foreach ($material_datas as $material_data) {
              ?>
              <td><?= $material_data['zkm_code'] ?></td>
              <td><?= $material_data['zkm_name']?></td>
              <td><?= $material_data['c_div']?></td>
            <td style="text-align:center"><button class="updateBtn" onclick="location.href='sq_zkm_input2.php'">更新</button></td>
          </tr>
          <?php } ?>
        </table>
      </form><!-- Vertical Form -->
    </div>
  </main><!-- End #main -->

</html>

<script type="text/javascript">
function checkForm($this)
{
    var str=$this.value;
    while(str.match(/[^A-Z^a-z\d\-\ \,]/))
    {
        str=str.replace(/[^A-Z^a-z\d\-\ \,]/,"");
    }
    $this.value=str;
}
</script>
<script src="assets/js/inquiry_ent.js"></script>
<script src="assets/js/inquiry_ent_check.js"></script>
<?php

// フッターセット
footer_set();
?>
