<?php
  // 初期処理
  require_once('function.php');
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
  // 初期設定 & データセット
  $today = date('Y/m/d');
  $process = '';
  $process2 = 'new';
  $request_class = '';      //分類コード
  $request_comment = '';    //コメント
  $request_dept = '';       //依頼部署
  $recipent_dept = '';      //受付部署
  $request_form_url = '';
  $btn_class = 'updRegBtn'; 
  $header = '入力';
  $err = $_GET['err'] ?? '';
  $btn_name = "依頼書登録";
  $class_datas = get_class_datas(); //分類プルダウンにセットするデータを取得する

  //一覧画面から来た場合
  if (isset($_POST['process1'])) {
    $process = $_POST['process1'];
    
    //新規の場合
    if ($process == 'new') {
      $process2 = 'new';
      //依頼書No.自動採番
      /**--------------------------------------------------------------------------------------------------**/
      //システム日付の年月を採取
      $ym = substr(str_replace('/', '', $today), 0, 6);
      $code_id = 'request_form_no';

      $sql = "SELECT code_no FROM sq_code WHERE code_id = '$code_id' AND text1 = '$ym'";
      $stmt = $pdo->prepare($sql);
      $stmt->execute();
      $data = $stmt->fetchAll();

      if (isset ($data) && !empty($data)) {
        $code_no = $data[0]['code_no'];
        $no = $code_no+1;
        $request_form_number = $ym.$no;
      } else {
        $no = '1';
        $request_form_number = $ym.$no;
      }

      //request_form_trのデータを取得する
      $request_form_datas = get_request_form_datas($request_form_number);
      if (isset($request_form_datas) && !empty($request_form_datas)) {
        $request_form_url = $request_form_datas['request_form_url'];
      }
    }
    //更新の場合
    else {
      $header = '更新';
      $btn_name = "依頼書更新";
      $process2 = 'update';

      $request_form_number = $_POST['request_form_number'];
      //request_form_trのデータを取得する
      $request_form_datas = get_request_form_datas($request_form_number);
      if (isset($request_form_datas)) {
        $variables = ['request_class', 'request_comment', 'request_dept', 'request_person', 'comfirmor_comment', 'request_form_url', 'recipent_dept'];
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
    }
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

?>