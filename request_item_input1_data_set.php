<?php
require_once('function.php');
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
$dept_id = getDeptId($dept_code);
$request_datas = [];
$request_dept = '';

$request_datas = request_form_list($request_dept);

//検索ボタンを押下した場合
if (isset($_POST['process1']) == 'search') {
  $request_dept = $_POST['request_dept'] ?? ''; //部署

  //request_form_trからデータを取得する
  $request_datas = request_form_list($request_dept);  
}

/***
 * request_form_trからデータを取得する
 */
function request_form_list($request_dept) {
    global $pdo;
    $search_kw = [];

    $sql = "SELECT m.request_item_id, m.request_item_name, m.request_dept
            FROM request_m m
            LEFT JOIN sq_dept d ON d.sq_dept_code = m.request_dept
            WHERE 1 = 1";
    if (!empty($request_dept)) {
        $search_kw['request_dept'] =  '%' . $request_dept . '%';
        $sql .= " AND d.sq_dept_name LIKE :request_dept";
    }
    $stmt = $pdo->prepare($sql);
    $stmt->execute($search_kw);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}




