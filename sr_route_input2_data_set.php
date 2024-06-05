<?php
// 初期処理
require_once('function.php');
// DB接続
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

// 初期設定 & データセット
$route_id = '';
$btn_name = '登録';
$dept_list = getDropdownData();
$route_depts = array_fill(0, 5, '');

// 一覧画面からPOSTを取得
if (isset($_POST['process'])) {
  $process = $_POST['process'];

  if ($process == 'create') {
    $route_id = getRouteId();
  } else {
    $btn_name = '更新';
    $route_id = $_POST['route_id'];
    $route_depts = getRouteData($route_id);
  }
}

function generateOptions($dept_list, $selected_value = '')
{
  $options = '<option value="">※選択して下さい。</option>';
  foreach ($dept_list as $dept) {
    $selected = $dept['id'] == $selected_value ? 'selected' : '';
    $options .= '<option value="' . htmlspecialchars($dept['id']) . '" ' . $selected . '>' . htmlspecialchars($dept['name']) . '</option>';
  }
  return $options;
}

function getDropdownData()
{
  global $pdo;
  $sql = "SELECT text1 as id, text2 as name FROM sq_code WHERE code_id = 'sq_dept'";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function getRouteId()
{
  global $pdo;
  $sql = "SELECT MAX(route_id) as max FROM sq_route";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $max_route_id = $stmt->fetch(PDO::FETCH_ASSOC);
  return $max_route_id ? $max_route_id['max'] + 1 : 1;
}

function getRouteData($route_id)
{
  global $pdo;
  $sql = "SELECT route1_dept, route2_dept, route3_dept, route4_dept, route5_dept FROM sq_route WHERE route_id = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([$route_id]);
  $route_data = $stmt->fetch(PDO::FETCH_ASSOC);
  return $route_data ? array_values($route_data) : array_fill(0, 5, '');
}

function createRoute($route_id, $route_depts)
{
  global $pdo;
  $add_date = date('Y/m/d');
  $sql = "INSERT INTO sq_route (route_id, route1_dept, route2_dept, route3_dept, route4_dept, route5_dept, add_date) VALUES (?, ?, ?, ?, ?, ?, ?)";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array_merge([$route_id], $route_depts, [$add_date]));
}

function updateRoute($route_id, $route_depts)
{
  global $pdo;
  $upd_date = date('Y/m/d');
  $sql = "UPDATE sq_route SET route1_dept = ?, route2_dept = ?, route3_dept = ?, route4_dept = ?, route5_dept = ?, upd_date = ?  WHERE route_id = ?";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(array_merge($route_depts, [$upd_date,$route_id]));
}
