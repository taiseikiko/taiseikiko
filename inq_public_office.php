<?php
  session_start();
  require_once('function.php');
  header('Content-type: text/html; charset=utf-8');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する

  if(isset($_POST['pf_nm'])){
    $pf_nm = $_POST['pf_nm'];
  } else{
    $pf_nm = "";
  }

  // ヘッダーセット
  header_set1();
?>
<!DOCTYPE html>
<html>
<body>
  <div class="window active">
    <div class="title-bar">
      <div class="title-bar-text">事業体検索</div>
      <div class="title-bar-controls">
      </div>
    </div>
    <div class="window-body has-space" style="min-height:550px; overflow:hidden;">
      <form method="POST">
        <div class="field-row">
          <label for="pf_nm">事業体名で絞り込み　</label>
          <input type="text" id="pf_nm" name="pf_nm" class="readonlyText" onchange="submit(this.form)" <?php echo 'value='.$pf_nm; ?>>
        </div>
        <br>

  <?php
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
  
  //初期処理
  $pf_codes = array();
  $pf_names = array();
  $office_codes = array();
  $office_names = array();
  $pf_code = "";
  $pf_name = "";

  if(isset($_POST['pf_nm'])){
    // パラメーターセット
    if(isset($_POST['select'])){
      $x = key($_POST['select']);
    }

    //プレースホルダで SQL 作成
    $sql1 = "SELECT pf.pf_code, pf.pf_name, o.office_code, o.office_name 
            FROM public_office pf
            LEFT JOIN office_m o
            ON pf.office_code = o.office_code
            WHERE pf.pf_name LIKE '$pf_nm%'";
    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute();
    
    $i=0;
    WHILE($row = $stmt1->fetch(PDO::FETCH_ASSOC)){
      $pf_codes[$i] = $row['pf_code'];
      $pf_names[$i] = $row['pf_name'];
      $office_codes[$i] = $row['office_code'];
      $office_names[$i] = $row['office_name'];
      $i++;
    }

    
    //Json Encode
    $pf_code = json_encode($pf_codes);  
    $pf_name = json_encode($pf_names);

    //テーブルヘッダー  
    $thead = '<table class="tab1">
                <tr>
                  <th>提出先（得意先）</th>
                  <th>事業所</th>
                  <th>処理</th>
                </tr>';
    echo $thead;

    //テーブル明細  
    $i=0;
    WHILE(isset($pf_codes[$i])){
      $html = '<tr>';
      $html .= '<td>'.$pf_names[$i].'</td>';
      $html .= '<td>'.$office_names[$i].'</td>';
      $html .= '<td><button type="button" name="select['.$i.']" class="btn btn-outline-primary btn-sm" onclick="inq_ent('.$i.');">Select</button>';
      $html .= '</tr>';
      $i++;
      echo $html;
      $html = '';
    }
    echo "</table></form><br>";
  }

  // フッターセット
  echo "Copyright <strong><span>情報システムグループ</span></strong>. All Rights Reserved<hr>";
  //  footer_set();
?>
  </div>
</div>
</body>
</html>
<script>
  let public_office_code = <?= $pf_code ?>;
  let public_office_name = <?= $pf_name ?>;
</script>
<script src="assets/js/public_office_ent.js"></script>
