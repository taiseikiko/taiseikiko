<?php
  // 初期処理
  require_once('function.php');
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
  // 初期設定 & データセット
  $process = '';
  $request_class = '';      //分類コード
  $request_comment = '';    //コメント
  $request_dept = '';       //依頼部署
  $recipent_dept = '';      //受付部署
  $comfirmor_comment = '';  //確認者コメント
  $approval_comment = '';   //承認者コメント
  $request_form_url = '';
  $recipt_form_url = '';
  $recipi_comment = $recipt_comfirmor_comment = $recipt_approval_comment = '';
  $btn_class = 'updRegBtn'; 
  $header = '受付';
  $status = '';
  $err = $_GET['err'] ?? '';
  $btn_name = "依頼書受付";
  $class_datas = get_class_datas(); //分類プルダウンにセットするデータを取得する
  $status = $_GET['status'] ?? '';
  
  //一覧画面から来た場合 or メールから来た場合
  if (isset($_POST['process1']) || isset($_GET['request_form_number'])) {
    $request_form_number = $_POST['request_form_number']?? $_GET['request_form_number'];    

    //request_form_trのデータを取得する
    $request_form_datas = get_request_form_datas($request_form_number);
    if (isset($request_form_datas)) {
      $variables = ['request_class', 'request_comment', 'request_dept', 'request_person', 'comfirmor_comment', 'approval_comment', 'request_form_url', 'recipent_dept',
      'status', 'recipi_comment', 'recipt_comfirmor_comment', 'recipt_approval_comment', 'recipt_form_url'];
      foreach ($variables as $variable) {
        ${$variable} = $request_form_datas[$variable];
      }
    }

    //登録者のデータを取得する
    $rp_datas = get_rp_infos($request_person);
    if (isset($rp_datas) && !empty($rp_datas)) {
      $user_name = $rp_datas['employee_name'];          //登録者名  
      $office_name = $rp_datas['dept_name'];            //部署名
      $office_position_name = $rp_datas['role_name'];   //役職
    }

    //確認の場合  
    if ($status == '4') {
      $header = '確認';
      $btn_name = "依頼書確認";
    }
    //確認の場合  
    else if ($status == '5') {
      $header = '承認';
      $btn_name = "依頼書承認";
    }

  }


  /*----------------------------------------------------------------FUNCTION---------------------------------------------------------------------*/
  function get_request_form_datas($request_form_number) {
    global $pdo;

    $sql = "SELECT * FROM request_form_tr WHERE request_form_number = :request_form_number";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':request_form_number',$request_form_number);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row;
  }

  function get_rp_infos($client)
  {
    global $pdo;

    $sql = "SELECT e.employee_name, cmd.text2 AS dept_name, cmp.text1 AS role_name
      FROM request_form_tr h
      LEFT JOIN employee e
      ON e.employee_code = h.request_person
      LEFT JOIN code_master cmd
      ON e.department_code = cmd.text1
      AND cmd.code_id = 'department'
      LEFT JOIN code_master cmp
      ON e.office_position_code = cmp.code_no
      AND cmp.code_id = 'office_position'      
      WHERE h.request_person = :client";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':client', $client);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  function get_class_datas()
  {
    global $pdo;
    $sql = "SELECT rc.request_dept, rc.request_item_id, rc.request_item_name, d.text2
            FROM request_m rc
            LEFT JOIN code_master d
            ON d.code_id = 'department' AND d.text1 = rc.request_dept";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $datas = $stmt->fetchAll();

    return $datas;
  }

?>