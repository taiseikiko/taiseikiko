<?php
// 初期処理
require_once('function.php');
// DB接続
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

// Fetch the values from the sq_detail_tr table
$stmt = $pdo->prepare("SELECT processing_dept, processing_status, route_pattern FROM sq_detail_tr WHERE sq_no = ? AND sq_line_no = ?");
$stmt->execute([$sq_no, $sq_line_no]);
$sq_detail = $stmt->fetch(PDO::FETCH_ASSOC);
$processing_dept = $sq_detail['processing_dept'] ?? '';
$processing_status = $sq_detail['processing_status'] ?? '';
$route_pattern = $sq_detail['route_pattern'] ?? '';

// Fetch the route details from the sq_route table
$route_stmt = $pdo->prepare("SELECT * FROM sq_route WHERE route_id = ?");
$route_stmt->execute([$route_pattern]);
$route_details = $route_stmt->fetch(PDO::FETCH_ASSOC);

// Department statuses array
$dept_statuses = [];
for ($i = 1; $i <= 5; $i++) {
  $route_dept_key = "route" . $i . "_dept";
  if (!empty($route_details[$route_dept_key])) {
    $code_stmt = $pdo->prepare("SELECT text1, text2 FROM sq_code WHERE code_id = ? AND text1 = ?");
    $code_stmt->execute(['sq_dept', $route_details[$route_dept_key]]);
    $dept_row = $code_stmt->fetch(PDO::FETCH_ASSOC);
    if ($dept_row) {
      $dept_statuses[] = $dept_row;
    }
  }
}

// Generate the 部署ステータス arrow steps
$dept_steps_html = '';
foreach ($dept_statuses as $dept) {
  $current_class = ($dept['text1'] == $processing_dept) ? 'current' : '';
  $dept_steps_html .= "<div class='step $current_class'> <span>{$dept['text2']}</span> </div>";
}

// Generate the 処理ステータス arrow steps
if(!empty($processing_status)) {
  $status_steps = ['受付', '登録', '確認', '承認'];
  $status_steps_html = '';
  foreach ($status_steps as $index => $status) {
    $current_class = ($index + 1 == $processing_status) ? 'current' : '';
    $status_steps_html .= "<div class='step $current_class'> <span>$status</span> </div>";
  }
} else {
  $status_steps_html = '';
}

?>  