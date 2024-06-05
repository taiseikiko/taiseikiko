<?php
  session_start();
  require_once('function.php');
  header('Content-type: text/html; charset=utf-8');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
  $msg = "";

  if(isset($_POST['em_nm'])){
    $em_nm = $_POST['em_nm'];
  } else{
    $em_nm = "";
  }

  // ヘッダーセット
  header_set1();
?>
<!DOCTYPE html>
<html>
<body>
  <div class="window active">
    <div class="title-bar">
      <div class="title-bar-text">社員検索</div>
      <div class="title-bar-controls">
        <!-- <button aria-label="Minimize"></button>
        <button aria-label="Maximize" onclick="window.resizeTo(300, 300)"></button>
        <button aria-label="Close" onclick="window.close()"></button> -->
      </div>
    </div>
    <div class="window-body has-space" style="min-height:550px; over-flow:hidden;">
      <form method="POST">
        <div class="field-row">
          <label for="text26">社員名で絞り込み</label>
          <input type="text" id="text26" name="em_nm"
            style="background-color: #ffffe0;" onchange="submit(this.form)" <?php echo 'value='.$em_nm; ?>>
        </div>
        <br>
  <?php
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
  
  //初期処理
  $employee_code = array();
  $employee_name = array();
  $employee_cd = "";
  $employee_nm = "";
  $kana = array();
  $department_code =array();
  $office_position_code = array();
  $dept_name = array();
  $op_name = array();

  if(isset($_POST['em_nm'])){

    // パラメーターセット
    if(isset($_POST['upd'])){
      $x = key($_POST['upd']);
    }

    //プレースホルダで SQL 作成
    $sql1 = "SELECT * FROM employee WHERE employee_name LIKE '$em_nm%'";
    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute();
    //error_log('sql1='.print_r($sql1,true)."\n",3,'error_log.txt');
    
    $i=0;
    WHILE($row = $stmt1->fetch(PDO::FETCH_ASSOC)){
      $employee_code[$i] = $row['employee_code'];
      $employee_name[$i] = $row['employee_name'];
      $kana[$i] = $row['kana'];
      $department_code[$i] = $row['department_code'];
      $office_position_code[$i] = $row['office_position_code'];

      // 部署
      $code_id = 'department';
      $sql2 = "SELECT * FROM code_master WHERE code_id = '$code_id' AND text1 = '$department_code[$i]';";
      $stmt2 = $pdo->prepare($sql2);
      $stmt2->execute();
      if($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)){
        $dept_name[$i]=$row2['text2'];
      } else{
        $dept_name[$i]="";
      }

      // 役職
      $code_id = 'office_position';
      $sql2 = "SELECT * FROM code_master WHERE code_id = '$code_id' AND code_no = '$office_position_code[$i]';";
      $stmt2 = $pdo->prepare($sql2);
      $stmt2->execute();
      if($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)){
        $op_name[$i]=$row2['text1'];
      }
      else{
        $op_name[$i]="";
      }
      $i++;
    }

    //Json Encode
    $employee_cd = json_encode($employee_code);  
    $employee_nm = json_encode($employee_name);  
    $dept_cd = json_encode($department_code);  
    $dept_nm = json_encode($dept_name);  
    $op_cd = json_encode($office_position_code);  
    $op_nm = json_encode($op_name);  

    //テーブルヘッダー  
    $thead = '<table class="tab1">
                <tr>
                  <th>社員コード</th>
                  <th>社　員　名</th>
                  <th>部　署</th>
                  <th>役　職</th>
                  <th>Process</th>
                </tr>';
    echo $thead;

    //テーブル明細  
    $i=0;
    WHILE(isset($employee_code[$i])){
      $html = '<tr>';
      $html .= '<td>'.$employee_code[$i].'</td>';
      $html .= '<td>'.$employee_name[$i].'</td>';
      $html .= '<td>'.$dept_name[$i].'</td>';
      $html .= '<td>'.$op_name[$i].'</td>';
      $html .= '<td><button type="button" name="upd['.$i.']" class="btn btn-outline-primary btn-sm"
                  onclick="inq_ent('.$i.');">Select</button>';
                  //onclick="child11('.$i.','.$employee_code[$i].','.$employee_name[$i].');">Select</button>';
      $html .= '<input type="hidden" name="employee_code[]" id="employee_code_array['.$i.']" value='.$employee_code[$i].'>';
      $html .= '<input type="hidden" name="employee_name[]" id="employee_name_array['.$i.']" value='.$employee_name[$i].'>';
      $html .= '<input type="hidden" name="department_code[]" id="department_code_array['.$i.']" value='.$department_code[$i].'>';
      $html .= '<input type="hidden" name="dept_name[]" id="dept_name_array['.$i.']" value='.$dept_name[$i].'>';
      $html .= '<input type="hidden" name="office_position_code[]" id="office_position_code_array['.$i.']" value='.$office_position_code[$i].'>';
      $html .= '<input type="hidden" name="op_name[]" id="op_name_array['.$i.']" value='.$op_name[$i].'>';
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
  let employee_cd = <?php echo $employee_cd ?>;
  let employee_nm = <?php echo $employee_nm ?>;
  let dept_cd = <?php echo $dept_cd ?>;
  let dept_nm = <?php echo $dept_nm ?>;
  let op_cd = <?php echo $op_cd ?>;
  let op_nm = <?php echo $op_nm ?>;

    //console.log("employee_cd=" + employee_cd);
    //console.log("employee_nm=" + employee_nm);
</script>
<script src="assets/js/inquiry_ent.js"></script>
