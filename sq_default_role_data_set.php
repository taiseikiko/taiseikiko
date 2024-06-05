<?php
require_once('function.php');

// DB接続
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

$response = ['success' => false, 'groups' => [], 'entrants' => [], 'confirmors' => [], 'approvers' => [], 'existingData' => []];

if (isset($_POST['department_code'])) {
  $department_code = $_POST['department_code'];

  // 部署IDを取得
  $sql = "SELECT dept_id, dept2_id FROM sq_dept WHERE sq_dept_code = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$department_code]);
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($row) {
    $dept_id = $row['dept_id'];
    $dept2_id = $row['dept2_id'];

    // グループデータを取得
    $sql = "SELECT DISTINCT text2, text3 FROM sq_code WHERE text1 = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$dept_id]);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $response['groups'][] = ['id' => $row['text2'], 'name' => $row['text3']];
    }

    // employee dataも要求されているかどうかを確認する
    if (isset($_POST['fetch_employees']) && isset($_POST['group_id'])) {
      $group_id = $_POST['group_id'];

      // 社員データを取得
      $roles = [
        'entrants' => 1,
        'confirmors' => 2,
        'approvers' => 3
      ];

      foreach ($roles as $key => $role) {
        $sql = "SELECT DISTINCT rd.employee_code, e.employee_name
                    FROM sq_route_in_dept rd 
                    JOIN employee e ON rd.employee_code = e.employee_code
                    WHERE rd.dept_id = ? AND rd.group_id = ? AND rd.role = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$dept_id, $group_id, $role]);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $response[$key][] = ['code' => $row['employee_code'], 'name' => $row['employee_name']];
        }
      }

      // 既存のデータを取得する
      $sql = "SELECT entrant, confirmor, approver FROM sq_default_role WHERE dept_id = ? AND group_id = ?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$dept_id, $group_id]);
      $existingData = $stmt->fetch(PDO::FETCH_ASSOC);
      if ($existingData) {
        $response['existingData'] = [
          'entrant' => $existingData['entrant'],
          'confirmor' => $existingData['confirmor'],
          'approver' => $existingData['approver']
        ];
      }
    }
    $response['success'] = true;
  }
  echo json_encode($response);
}
