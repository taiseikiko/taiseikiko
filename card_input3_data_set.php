<?php
require_once('function.php');
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
//ログインユーザーの部署ID
// $dept_cd = $_POST['dept_code'] ?? $dept_code;
// $title1 = $_POST['title'] ?? $_GET['title'];
$sq_card_no = $client = $size = ''; //header
$sq_card_line_no = $procurement_no = $maker = $zkm_code = $pipe = $sizeA = $sizeB = $class_code = $specification_no = $special_note =  
$entrant = $entrant_set_date = $entrant_set_comments = 
$entrant_date = $entrant_comments = $confirmer_comments = $approver_comments = '';//detail
$client_name = $dept_name = $role_name = $p_office_code = $p_office_name = '';  //登録者の情報
$entrant_dept_name = $entrant_role_name = '';  //担当者の情報
$sizeDisabled = $zaikoumeiDisabled = $pipeDisabled = $comments = $process = '';
$err = $_GET['err'] ?? '';//エラーを取得する

$pipeList = getDropdownData('pipe');                  //管種
$sizeList = getDropdownData('size');                  //サイズ

$class_datas = get_class_datas();                     //分類プルダウンにセットするデータを取得する

//担当者リストを取得する
$entrantList = get_entrant_datas();

//card_input2画面から詳細ボタンを押下場合
if (isset($_POST['detail']) || isset($_GET['sq_card_no'])) {
  $process = $_POST['process']?? ''; //処理
  $sq_card_no = $_POST['card_no']?? $_GET['sq_card_no'];              //依頼書No
  $sq_card_line_no = $_POST['detail']?? $_GET['sq_card_line_no'];          //依頼書行No
  $client = $_POST['user_code']?? '';                //登録者

  //どの画面を表示するかを確認する（受付、入力、確認、承認）
  /************************************************開始***************************************************************/

  //card_detail_trテーブルから当依頼者Noと依頼者行Noのデータを取得する
  $sql = "SELECT * FROM card_detail_tr WHERE sq_card_no=:sq_card_no AND sq_card_line_no=:sq_card_line_no";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':sq_card_no',$sq_card_no);
  $stmt->bindParam(':sq_card_line_no',$sq_card_line_no);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!empty($row)) {
    //担当指定日がNULLの場合、受付画面
    if ($row['entrant_set_date'] == NULL) {
      $page = '受付';
    }  

    //入力日がNULLの場合、入力画面
    else if ($row['entrant_date'] == NULL) {
      $page = '入力';
    }
    
    //確認日がNULLの場合、確認画面
    else if ($row['confirm_date'] == NULL) {
      $page = '確認';
    }
    
    //承認日がNULLの場合、承認画面
    else if ($row['approve_date'] == NULL) {
      $page = '承認';
    }
  }
  /************************************************完了***************************************************************/  

  //登録者の情報を取得する
  $client_datas = get_client_infos($client);
  if (isset($client_datas) && !empty($client_datas)) {
    $client_name = $client_datas['employee_name'];  //登録者名  
    $dept_name = $client_datas['dept_name'];        //部署名
    $role_name = $client_datas['role_name'];        //役割
    $p_office_code = $client_datas['p_office_code'];//事業体コード
    $p_office_name = $client_datas['p_office_name'];//事業体名
  }

  //card_detail_trを取得する
  $card_detail_list = get_card_detail_datas($sq_card_no, $sq_card_line_no);
  if (isset($card_detail_list) && !empty($card_detail_list)) {
    //資材部No、製造メーカー、材工名、管種、仕様書No、特記事項、担当者、担当指定日、担当指定コメント
    $variable_names = ['procurement_no', 'maker', 'zkm_code', 'pipe', 'sizeA', 'sizeB', 'class_code', 'specification_no', 'special_note', 'entrant', 'entrant_set_date', 'entrant_set_comments',
                        'entrant_comments', 'entrant_date', 'confirmer_comments', 'approver_comments'];
    foreach ($variable_names as $variable_name) {
      ${$variable_name} = $card_detail_list[$variable_name];
    }

    //材工名データを取得する
    if ($class_code !== '') {
      $zaikoumeiList = get_zaikoumei_datas($class_code);
    }    
    
    if ($entrant !== '') {    
      //担当者の情報を取得する  
      $entrant_datas = get_user_infos($entrant);
      if (isset($entrant_datas) && !empty($entrant_datas)) {
        $entrant_dept_name = $entrant_datas['dept_name'];        //部署名
        $entrant_role_name = $entrant_datas['role_name'];        //役割
      }
    }
  }

  //ファイルコメントをcard_file_trテーブルから取得する
  $file_comment_List = get_file_comment_datas($sq_card_no, $sq_card_line_no);
}

/**
 * 担当者が選択された場合、部署名と役割をセットする
 */
if (isset($_POST['getUserInfo'])) {
  $employee_code = $_POST['employee_code'];
  $infos = get_user_infos($employee_code);

  echo json_encode($infos);
}
/**
 * プルダウンにセットするデータを取得する
 */
function getDropdownData($code_id) {
  global $pdo;
  //sq_code テーブルからデータ取得する
  $sql = "SELECT c.text1, zk.zk_div_data 
  FROM sq_code c
  LEFT JOIN sq_zk2 zk
  ON c.code_id = zk.zk_division
  AND c.text1 = zk.zk_tp
  WHERE code_id='$code_id'";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $datas = $stmt->fetchAll();
  return $datas;
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

/**
 * 材工名データを取得する
 */
function get_zaikoumei_datas($class_code) {
  global $pdo;
  $datas = [];

  $sql = "SELECT z.zkm_code, z.zkm_name, c.text1 ,c.code_no
    FROM sq_zaikoumei z
    LEFT JOIN sq_code c
    ON z.c_div = c.code_no
    AND c.code_id = 'c_div'
    WHERE z.class_code = '$class_code'";

  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $datas[] = $row;
  }

  return $datas;
}

/**
 * card_detail_trテーブルからデータを取得する
 */
function get_card_detail_datas($sq_card_no, $sq_card_line_no) {
  global $pdo;
  $datas = [];

  $sql = "SELECT * FROM card_detail_tr WHERE sq_card_no = :sq_card_no AND sq_card_line_no = :sq_card_line_no";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':sq_card_no',$sq_card_no);
  $stmt->bindParam(':sq_card_line_no',$sq_card_line_no);
  $stmt->execute();
  $datas = $stmt->fetch(PDO::FETCH_ASSOC);

  return $datas;
}

/**
 * 担当者リストを取得する
 */
function get_entrant_datas() {
  global $pdo;
  global $department_code;
  $datas = [];
  $role = '1'; //入力者

  $sql = "SELECT e.employee_code, e.employee_name
          FROM card_route_in_dept c
          LEFT JOIN employee e ON e.employee_code = c.employee_code
          WHERE c.role =:role AND c.department_code =:department_code";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':role', $role);
  $stmt->bindParam(':department_code', $department_code);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $datas[] = $row;
  }

  return $datas;
}

/**
 * client産の情報を取得する
 */
function get_client_infos($client) {
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

/**
 * 担当者の情報を取得する
 */
function get_user_infos($employee_code) {
  global $pdo;

  $sql = "SELECT e.employee_name, cmd.text2 AS dept_name, cmp.text1 AS role_name
  FROM employee e
  LEFT JOIN code_master cmd
  ON e.department_code = cmd.text1
  AND cmd.code_id = 'department'
  LEFT JOIN code_master cmp
  ON e.office_position_code = cmp.code_no
  AND cmp.code_id = 'office_position'
  WHERE e.employee_code = :employee_code";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':employee_code', $employee_code);
  $stmt->execute();
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

/**
 * ファイルコメントをcard_file_trテーブルから取得する
 */
function get_file_comment_datas($sq_card_no, $sq_card_line_no) {
  global $pdo;
  $datas = [];

  $sql = "SELECT * FROM card_file_tr WHERE sq_card_no=:sq_card_no AND sq_card_line_no=:sq_card_line_no";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':sq_card_no', $sq_card_no);
  $stmt->bindParam(':sq_card_line_no', $sq_card_line_no);
  $stmt->execute();
  while( $row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $datas[] = $row;
  }

  return $datas;
}


