<?php
require_once('function.php');
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
$dept_id = getDeptId($dept_code);
$request_datas = [];
$class_name = $requester = $publish_department = '';

$request_datas = request_form_list($class_name, $requester, $publish_department);

//検索ボタンを押下した場合
if (isset($_POST['process']) == 'search') {
  $requester = $_POST['requester'] ?? '';                   //依頼者
  $publish_department = $_POST['publish_department'] ?? ''; //発行部署

  //request_form_trからデータを取得する
  $request_datas = request_form_list($requester, $publish_department);  
}

/***
 * request_form_trからデータを取得する
 */
function request_form_list($requester, $publish_department) {
    global $pdo;
    $search_kw = [];

    $sql = "SELECT r.request_case_dept, d.sq_dept_name AS request_dept_name, r.request_case_item_id, r.request_case_person, e.employee_name AS request_person_name,
            r.request_item_name
            FROM request_m r
            LEFT JOIN sq_dept d ON d.sq_dept_code = r.request_case_dept
            LEFT JOIN employee e ON e.employee_code = r.request_case_person
            WHERE 1 = 1";
    if (!empty($requester)) {
        $search_kw['request_case_person'] =  '%' . $requester . '%';
        $sql .= " AND r.request_case_person LIKE :request_case_person";
    }
    if (!empty($publish_department)) {
        $search_kw['request_case_dept'] =  '%' . $publish_department . '%';
        $sql .= " AND r.request_case_dept LIKE :request_case_dept";
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute($search_kw);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}




