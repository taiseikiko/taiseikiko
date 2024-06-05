<?php
  session_start();
  require_once('function.php');
  header('Content-type: text/html; charset=utf-8');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する

  if(isset($_POST['cus_nm'])){
    $cus_nm = $_POST['cus_nm'];
  } else{
    $cus_nm = "";
  }

  // ヘッダーセット
  header_set1();
?>
<!DOCTYPE html>
<html>
<body>
  <div class="window active">
    <div class="title-bar">
      <div class="title-bar-text">提出先（得意先）検索</div>
      <div class="title-bar-controls">
      </div>
    </div>
    <div class="window-body has-space" style="min-height:550px; overflow:hidden;">
      <form method="POST">
        <div class="field-row">
          <label for="cus_nm">提出先（得意先）名で絞り込み　</label>
          <input type="text" id="cus_nm" name="cus_nm" class="readonlyText" onchange="submit(this.form)" <?php echo 'value='.$cus_nm; ?>>
        </div>
        <br>

  <?php
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
  
  //初期処理
  $customer_codes = array();
  $customer_names = array();
  $office_codes = array();
  $office_names = array();
  $customer_code = "";
  $customer_name = "";
  $office_code = "";
  $office_name = "";

  if(isset($_POST['cus_nm'])){
    // パラメーターセット
    if(isset($_POST['select'])){
      $x = key($_POST['select']);
    }

    //プレースホルダで SQL 作成
    $sql1 = "SELECT c.cust_code, c.cust_name, o.office_code, o.office_name 
            FROM customer c
            LEFT JOIN office_m o
            ON c.office_code = o.office_code
            WHERE c.cust_name LIKE '$cus_nm%'";
    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute();
    
    $i=0;
    WHILE($row = $stmt1->fetch(PDO::FETCH_ASSOC)){
      $customer_codes[$i] = $row['cust_code'];
      $customer_names[$i] = $row['cust_name'];
      $office_codes[$i] = $row['office_code'];
      $office_names[$i] = $row['office_name'];
      $i++;
    }

    //Json Encode
    $customer_code = json_encode($customer_codes);  
    $customer_name = json_encode($customer_names);

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
    WHILE(isset($customer_codes[$i])){
      $html = '<tr>';
      $html .= '<td>'.$customer_names[$i].'</td>';
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
  let customer_code = <?= $customer_code ?>;
  let customer_name = <?= $customer_name ?>;
</script>
<script src="assets/js/customer_ent.js"></script>
