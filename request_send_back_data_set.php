<?php
require_once('function.php');
// DB接続
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

//初期処理
$employee_datas = [];
$restoration_comments = '';

//Parent Pageからデータを取得
$request_form_number = $_GET['request_form_number'] ?? '';
$err = $_GET['err'] ?? ''; //エラーを取得する

//差し戻し先担当者
$employee_datas = getEmployeeDatas($request_form_number);

function getEmployeeDatas($request_form_number)
{
    global $pdo;
    $employee_datas = [];

    // `request_form_tr` テーブルから `request_person` を取得
    $sql = "
            SELECT e.employee_code, e.employee_name, 'request_person' AS type
            FROM request_form_tr dw
            JOIN employee e ON dw.request_person = e.employee_code
            WHERE dw.request_form_number = :request_form_number
        ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':request_form_number', $request_form_number);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $employee_datas[] = $row;
    };

    return $employee_datas;
}
