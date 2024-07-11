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
  $comfirmor_comment = '';  //確認者コメント
  $approval_comment = '';   //承認者コメント
  $btn_class = 'updRegBtn'; 
  $header = '承認';
  $err = $_GET['err'] ?? '';
  $btn_name = "依頼書承認";
  $class_datas = get_class_datas(); //分類プルダウンにセットするデータを取得する
  
  //一覧画面から来た場合
  if (isset($_POST['process1'])) {
    $process = $_POST['process1'];
    $request_form_number = $_POST['request_form_number'];

    //更新の場合  
    if ($process == 'update') {

      //request_form_trのデータを取得する
      $request_form_datas = get_request_form_datas($request_form_number);
      if (isset($request_form_datas)) {
        $variables = ['request_class', 'request_comment', 'request_dept', 'request_person', 'comfirmor_comment', 'approval_comment'];
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

    $sql = "SELECT e.employee_name, cmd.text2 AS dept_name, cmp.text1 AS role_name, pf.pf_code AS p_office_code, pf.pf_name AS p_office_name 
      FROM card_header_tr h
      LEFT JOIN employee e
      ON e.employee_code = h.client
      LEFT JOIN code_master cmd
      ON e.department_code = cmd.text1
      AND cmd.code_id = 'department'
      LEFT JOIN code_master cmp
      ON e.office_position_code = cmp.code_no
      AND cmp.code_id = 'office_position'
      LEFT JOIN public_office pf
      ON pf.pf_code = h.p_office_no
      WHERE h.client = :client";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':client', $client);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  function get_class_datas()
  {
    global $pdo;
    $sql = "SELECT * FROM sq_class";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $datas = $stmt->fetchAll();

    return $datas;
  }

?>