<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once('function.php');

// DB接続
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

// 初期処理
$group_datas = [];
$employee_datas = [];

$department_code = isset($_SESSION['department_code']) ? $_SESSION['department_code'] : '';

if (!empty($department_code)) {
    // dept_id を取得
    $sql = "SELECT dept_id FROM sq_dept WHERE sq_dept_code = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$department_code]);
    $dept_id = $stmt->fetchColumn();

    // グループプルダウンのデータを取得する
    $group_datas = getGroupDatas($dept_id);
}

if (isset($_POST['functionName']) && $_POST['functionName'] === "getDropdownData") {
    $group_id = isset($_POST['group_id']) ? $_POST['group_id'] : '';
    $dept_id = isset($_POST['dept_id']) ? $_POST['dept_id'] : '';

    $entrantData = getEmployeeDatas($dept_id, $group_id, 1);
    $confirmerData = getEmployeeDatas($dept_id, $group_id, 2);
    $approverData = getEmployeeDatas($dept_id, $group_id, 3);

    // ロールデータを取得する
    $sql = "SELECT entrant, confirmer, approver FROM sq_default_role WHERE dept_id = ? AND group_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$dept_id, $group_id]);
    $existingRoleData = $stmt->fetch(PDO::FETCH_ASSOC);

    $response = [
        'entrant' => $entrantData,
        'confirmer' => $confirmerData,
        'approver' => $approverData,
        'existingRoleData' => $existingRoleData
    ];

    echo json_encode($response);
    exit;
}

function getGroupDatas($dept_id) {
    global $pdo;

    $sql = "SELECT text1, text2, text3 
            FROM sq_code
            WHERE code_id='dept_group' AND text1 = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$dept_id]);
    return $stmt->fetchAll();
}

function getEmployeeDatas($dept_id, $group_id, $role) {
    global $pdo;

    $sql = "SELECT r.employee_code, e.employee_name
            FROM sq_route_in_dept r
            LEFT JOIN employee e ON r.employee_code = e.employee_code
            WHERE dept_id = ? AND role = ?";
    $params = [$dept_id, $role];

    if (!empty($group_id)) {
        $sql .= " AND group_id = ?";
        $params[] = $group_id;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
