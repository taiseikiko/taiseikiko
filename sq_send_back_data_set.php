<?php
  require_once('function.php');
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

  //初期処理
  $dept_datas = [];
  $employee_datas = [];
  $restoration_comments = '';
  $selected_dept = '';

  //Parent Pageからデータを取得
  $sq_no = $_GET['sq_no'] ?? '';
  $sq_line_no = $_GET['sq_line_no'] ?? '';
  $record_div = $_GET['record_div'] ?? '';
  $route_pattern = $_GET['route_pattern'] ?? '';
  $dept_id = $_GET['dept_id'] ?? '';
  $title = $_GET['title'] ?? '';

  $s_title = substr($title, 0, 2);
  $e_title = substr($title, 3);
  //グループプルダウンのデータを取得する
  $dept_datas = getDeptDatas();

  if (isset($_POST['functionName'])) {
    $dept = $_POST['dept'];
    $route_pattern = $_POST['route_pattern'];
    $sq_no = $_POST['sq_no'];
    $sq_line_no = $_POST['sq_line_no'];
    $title = $_POST['title'];
    $log_in_dept_id = $_POST['log_in_dept_id'];

    if (!empty($dept)) {
      $employee_datas = getEmployeeDatas($dept, $route_pattern, $sq_no, $sq_line_no, $title, $log_in_dept_id);
      echo json_encode($employee_datas);
    }    
  }

  function getEmployeeDatas($selected_dept, $route_pattern, $sq_no, $sq_line_no, $title, $log_in_dept_id) {
    global $pdo;
    $s_title = substr($title, 0, 2);
    $e_title = substr($title, 3);
    $employee_datas = [];
    $dept_datas1 = [];
    $sql = '';

    $select = "SELECT e.employee_code, e.employee_name ";

    //営業部の場合
    if ($selected_dept == '00') {
      $sql = $select . "FROM sq_header_tr h LEFT JOIN employee e ON e.employee_code = h.client WHERE h.sq_no='$sq_no'";
      $stmt = $pdo->prepare($sql);
      $stmt->execute();
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $employee_datas[] = $row;
      }
    } 
    //その他の部署の場合
    else {
      switch ($selected_dept) {
        //差し戻し部署が技術部の場合
        case '02':
          $from_table = "FROM sq_detail_tr_engineering main ";
          break;
        //差し戻し部署が営業管理部の場合
        case '05':
          $from_table = "FROM sq_detail_tr_sales_management main ";
          break;
        //差し戻し部署が工事管理部の場合
        case '06':
          $from_table = "FROM sq_detail_tr_const_engineering main ";
          break;
        //差し戻し部署が資材部の場合
        case '04':
          $from_table = "FROM sq_detail_tr_procurement main ";
          break;
      
        default:
          $from_table = "";
          break;
      }

      $left_join_table = "LEFT JOIN employee e ON e.employee_code = main.entrant ";
      //差し戻し部署が営業管理部以外の場合
      // if ($selected_dept !== '05') {
      //   //各部署の入力者に差し戻し
        
      // } else {
      //   //業管理部の場合、headerのclientに差し戻し
      //   $on_condition = "ON e.employee_code = main.client ";
      // }
      
      
      //同部署内
      if ($log_in_dept_id == $selected_dept) {        
        //確認者（confirmer）or 承認者（approver）が入力者（entrant）に差し戻し
        if ($e_title == 'confirm' || $e_title == 'approve') {
          //query start
          $sql = $select . $from_table . $left_join_table;
        }
      }
      //前の部署の場合
      if ($log_in_dept_id !== $selected_dept) {
        //受付者（reception）or 確認者（confirmer）or 承認者（approver）が前の部署（営業部以外）の入力者（entrant）に差し戻し
        if ($e_title == 'receipt' || $e_title == 'confirm' || $e_title == 'approve') {
          //query start
          $sql .= $select . $from_table . $left_join_table;
        }
      }

      if ($sql !== '') {
        $sql .= "WHERE main.sq_no='$sq_no' AND main.sq_line_no='$sq_line_no'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $employee_datas[] = $row;
        }
      }      
    }

    return $employee_datas;
  }

  function getDeptDatas() {
    global $pdo;
    global $route_pattern;
    global $sq_no;
    global $sq_line_no;
    global $dept_id;

    $dept_datas = [];
    $filter_dept_datas = [];

    $sql = "SELECT c1.text1 AS dept1_id, c2.text1 AS dept2_id, c3.text1 AS dept3_id, c4.text1 AS dept4_id, c5.text1 AS dept5_id,
            c1.text2 AS dept1_name, c2.text2 AS dept2_name, c3.text2 AS dept3_name, c4.text2 AS dept4_name, c5.text2 AS dept5_name
            FROM sq_route_tr r
            LEFT JOIN sq_code c1 ON r.route1_dept = c1.text1 
            AND c1.code_id = 'sq_dept'
            LEFT JOIN sq_code c2 ON r.route2_dept = c2.text1 
            AND c2.code_id = 'sq_dept'
            LEFT JOIN sq_code c3 ON r.route3_dept = c3.text1 
            AND c3.code_id = 'sq_dept'
            LEFT JOIN sq_code c4 ON r.route4_dept = c4.text1 
            AND c4.code_id = 'sq_dept'
            LEFT JOIN sq_code c5 ON r.route5_dept = c5.text1 
            AND c5.code_id = 'sq_dept'
            WHERE r.route_id='$route_pattern' AND r.sq_no='$sq_no' AND r.sq_line_no='$sq_line_no'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $dept_datas[] = $row;
    }

    //現在の部署まで取得する
    if (!empty($dept_datas)) {
      for ($i = 0; $i < 5; $i ++) {
        $x = $i+1;
        if ($dept_datas[0]['dept'.$x.'_id'] !== $dept_id) {
          $filter_dept_datas[$i]['dept_id'] = $dept_datas[0]['dept'.$x.'_id'];
          $filter_dept_datas[$i]['dept_name'] = $dept_datas[0]['dept'.$x.'_name'];
        } else {
            $filter_dept_datas[$i]['dept_id'] = $dept_datas[0]['dept'.$x.'_id'];
          $filter_dept_datas[$i]['dept_name'] = $dept_datas[0]['dept'.$x.'_name'];
            break;
        }
        $x++;
      }
    }   
    return $filter_dept_datas;
  }
?>