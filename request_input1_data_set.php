<?php
require_once('function.php');
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
$dept_id = getDeptId($dept_code);
$request_datas = [];
$class_name = $requester = $publish_department = '';

$request_datas = request_form_list($class_name, $requester, $publish_department);

//検索ボタンを押下した場合
if (isset($_POST['process1']) == 'search') {
  $class_name = $_POST['class_name']?? '';                  //分類
  $requester = $_POST['requester'] ?? '';                   //依頼者
  $publish_department = $_POST['publish_department'] ?? ''; //発行部署

  //request_form_trからデータを取得する
  $request_datas = request_form_list($class_name, $requester, $publish_department);  
}

/***
 * request_form_trからデータを取得する
 */
function request_form_list($class_name, $requester, $publish_department) {
    global $pdo;
    $search_kw = [];

    $sql = "SELECT r.request_form_number, r.request_dept, r.request_person, r.recipent_dept, r.request_class, r.recipent, 
            request_dept.sq_dept_name AS request_dept_name,recipent_dept.sq_dept_name AS recipent_dept_name,
            request_e.employee_name AS request_person_name, recipent_e.employee_name AS recipent_person_name,
            c.request_item_name,
            CASE r.status
                WHEN 1 THEN '依頼書確認待ち'
                WHEN 2 THEN '依頼書承認待ち'
                WHEN 3 THEN '依頼書受付申請待ち'
                WHEN 4 THEN '依頼書受付確認待ち'
                WHEN 5 THEN '依頼書受付承認待ち'
                WHEN 6 THEN '完了'
                WHEN 7 THEN '差し戻し'
            END AS status,
            r.status AS status_no
            FROM request_form_tr r
            LEFT JOIN sq_dept request_dept ON request_dept.sq_dept_code = r.request_dept
            LEFT JOIN sq_dept recipent_dept ON recipent_dept.sq_dept_code = r.recipent_dept
            LEFT JOIN employee request_e ON request_e.employee_code = r.request_person
            LEFT JOIN employee recipent_e ON recipent_e.employee_code = r.recipent
            LEFT JOIN request_m c ON c.request_dept = r.recipent_dept AND c.request_item_id = r.request_class
            WHERE 1 = 1";
    if (!empty($class_name)) {
        $search_kw['request_class'] =  '%' . $class_name . '%';
        $sql .= " AND c.request_item_name LIKE :request_class";
    }
    if (!empty($requester)) {
        $search_kw['request_person'] =  '%' . $requester . '%';
        $sql .= " AND request_e.employee_name LIKE :request_person";
    }
    if (!empty($publish_department)) {
        $search_kw['request_dept'] =  '%' . $publish_department . '%';
        $sql .= " AND request_dept.sq_dept_name LIKE :request_dept";
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute($search_kw);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}




