<?php
  // 初期処理
  require_once('function.php');
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
  // 初期設定 & データセット
  $today = date('Y/m/d');
  $dw_no = '';              //図面No
  $client = '';             //申請者
  $dw_status = '';          //状況
  $dw_div1 = '';            //区分
  $open_div = '';           //公開区分
  $class_code = '';         //分類コード
  $zkm_code = '';           //材工名コード
  $size = '';               //サイズ
  $joint = '';              //接合形状
  $pipe = '';               //管種
  $specification = '';      //仕様
  $dw_div2 = '';            //種類
  $comments = '';
  $comments_date = '';
  $comments_creater = '';
  $btn_name = '登録';
  $btn_status = '';          //登録ボタンの表示状態
  $btn_class = 'updRegBtn'; 
  $header = '入力処理';
  $err = $_GET['err'] ?? '';

  $class_datas = get_class_datas();                     //分類プルダウンにセットするデータを取得する
  $sizeList = getDropdownData('size');                  //サイズ
  $jointList = getDropdownData('joint');                //接合形状
  $pipeList = getDropdownData('pipe');                  //管種

  if (isset($_POST['process']) || isset($_GET['dw_no'])) {
    //メールのURLからきた場合
    if (isset($_GET['dw_no'])) {
      $process = 'update';
      $private = false;
    } else {
      $process = $_POST['process'];
      $private = $_POST['private'];
    }
    //新規の場合
    if ($process == 'new') {
      $btn_status = 'hidden';

      //図面№（sq_no）自動採番
      /**--------------------------------------------------------------------------------------------------**/
      //システム日付の年月を採取
      $ym = substr(str_replace('/', '', $today), 0, 6);
      $code_id = 'dw_request_no';

      $sql = "SELECT code_no FROM sq_code WHERE code_id = '$code_id' AND text1 = '$ym'";
      $stmt = $pdo->prepare($sql);
      $stmt->execute();
      $data = $stmt->fetchAll();

      if (isset ($data) && !empty($data)) {
        $code_no = $data[0]['code_no'];
        $no = $code_no+1;
        $dw_no = $ym.$no;
      } else {
        $no = '1';
        $dw_no = $ym.$no;
      }
    }
    //更新の場合  
    else {
      $btn_name = '承認';
      $header = '承認処理';
      $dw_no = $_POST['dw_no'] ?? $_GET['dw_no'];

      //dw_management_trのデータを取得する
      $dw_datas = get_dw_management_tr($dw_no);

      if (!empty($dw_datas) && isset($dw_datas)) {
        $variables = ['client', 'dw_div1', 'open_div', 'class_code', 'zkm_code', 'size', 'joint', 'pipe', 'specification', 'dw_div2'];
        foreach ($variables as $variable) {
          ${$variable} = $dw_datas[$variable];
        }
        
        if ($dw_datas['dw_status'] == '3') {
          $btn_name = '更新';
          $btn_class = 'updateBtn';
          $btn_status = 'hidden';
        }  
        
        //申請者のデータを取得する
        $client_datas = get_client_infos($client);
        if (isset($client_datas) && !empty($client_datas)) {
          $user_name = $client_datas['employee_name'];  //登録者名  
          $office_name = $client_datas['dept_name'];        //部署名
          $office_position_name = $client_datas['role_name'];        //役職
        }
      }
    }

    //ファイルコメントをdw_fileupload_trテーブルから取得する
    $file_comment_List = get_file_comment_datas($dw_no);

  }

  /*----------------------------------------------------------------FUNCTION---------------------------------------------------------------------*/
  
  function get_class_datas()
  {
    global $pdo;
    $sql = "SELECT * FROM sq_class";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $datas = $stmt->fetchAll();

    return $datas;
  }

  /*-------------------------------------------------------------------------------------------------------------------------------------*/

  function getDropdownData($code_id) {
    global $pdo;
    //sq_code テーブルからデータ取得する
    $sql = "SELECT text1, code_no FROM sq_code WHERE code_id='$code_id'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $datas = $stmt->fetchAll();
    return $datas;
  }

  /*-------------------------------------------------------------------------------------------------------------------------------------*/

  /**
   * dw_management_trからデータを取得する
   */
  function get_dw_management_tr($dw_no) {
    global $pdo;
    $datas = [];

    $sql = "SELECT * FROM dw_management_tr WHERE dw_no = :dw_no";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':dw_no', $dw_no);
    $stmt->execute();
    $datas = $stmt->fetch(PDO::FETCH_ASSOC);

    return $datas;
    
  }

  /**
   * ファイルコメントをdw_fileupload_trテーブルから取得する
   */
  function get_file_comment_datas($dw_no) {
    global $pdo;
    $datas = [];

    $sql = "SELECT dw.dw_no, dw.dw_path, dw.comment, dw.add_date, e.employee_name
           FROM dw_fileupload_tr dw
           LEFT JOIN employee e ON e.employee_code = dw.client
           WHERE dw_no=:dw_no";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':dw_no', $dw_no);
    $stmt->execute();
    while( $row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $datas[] = $row;
    }

    return $datas;
  }

  function get_client_infos($client)
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

?>