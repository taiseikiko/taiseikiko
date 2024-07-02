<?php
require_once('function.php');
// DB接続
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

//初期処理
$employee_datas = [];
$abort_comments = '';

//Parent Pageからデータを取得
$sq_card_no = $_GET['sq_card_no'] ?? '';
$sq_card_line_no = $_GET['sq_card_line_no'] ?? '';
$from = $_GET['from'] ?? '';
$err = $_GET['err'] ?? ''; //エラーを取得する

//中止先担当者
$employee_datas = getEmployeeDatas($sq_card_no, $sq_card_line_no);
$manager_name = getManagerName($from, $employee_datas);

function getEmployeeDatas($sq_card_no, $sq_card_line_no)
{
  global $pdo;
  $employee_datas = [];

  //headerテーブルからclientとdetailからentrantのデータを取得する
  $sql = "
      SELECT 'client' AS type, e_header.employee_code, e_header.employee_name
      FROM card_header_tr h
      LEFT JOIN employee e_header ON h.client = e_header.employee_code
      WHERE h.card_no = :card_no
      UNION ALL
      SELECT 'entrant' AS type, e.employee_code, e.employee_name
      FROM card_detail_tr d
      LEFT JOIN employee e ON d.entrant = e.employee_code 
      WHERE d.sq_card_no = :sq_card_no AND d.sq_card_line_no = :sq_card_line_no;
      ";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':card_no', $sq_card_no);
  $stmt->bindParam(':sq_card_no', $sq_card_no);
  $stmt->bindParam(':sq_card_line_no', $sq_card_line_no);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $employee_datas[] = $row;
  };

  return $employee_datas;
}

function getManagerName($from, $employee_datas)
{
  if ($from === 'procurement') {
    foreach ($employee_datas as $employee) {
      if ($employee['type'] === 'client') {
        return $employee['employee_name'];
      }
    }
  } else {
    foreach ($employee_datas as $employee) {
      if ($employee['type'] === 'entrant') {
        return $employee['employee_name'];
      }
    }
  }
  return '';
}
