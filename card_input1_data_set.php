<?php
require_once('function.php');
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

function map_procurement_status($status) {
  switch ($status) {
    // case 1:
    //   return '技術部承認待ち';
    case 2:
      return '技術部承認済み';
    case 3:
      return '完了';
    case 4:
      return '差し戻し';
    case 5:
      return '中止';
    default:
      return '技術部入力待ち';
  }
}

function map_card_status($status) {
  switch ($status) {
    // case 1:
    //   return '資材部承認待ち';
    case 2:
      return '資材部承認済み';
    case 3:
      return '完了';
    case 4:
      return '差し戻し';
    case 5:
      return '中止';
    default:
      return '資材部承認待ち';
  }
}

function get_card_header_datas() {
  global $pdo;

  $sql = "
  SELECT 
    ch.card_no, 
    ch.card_status, 
    e_client.employee_name AS client_name, 
    ch.add_date, 
    e_approver.employee_name AS procurement_approver_name, 
    ch.procurement_approver_date,
    GROUP_CONCAT(cd.sq_card_line_no ORDER BY cd.sq_card_line_no ASC) AS sq_card_line_nos,
    GROUP_CONCAT(cd.procurement_no ORDER BY cd.sq_card_line_no ASC) AS procurement_nos,
    GROUP_CONCAT(cd.procurement_status ORDER BY cd.sq_card_line_no ASC) AS procurement_statuses
  FROM 
    card_header_tr ch 
  LEFT JOIN 
    card_detail_tr cd ON ch.card_no = cd.sq_card_no
  LEFT JOIN 
    employee e_client ON ch.client = e_client.employee_code
  LEFT JOIN 
    employee e_approver ON ch.procurement_approver = e_approver.employee_code
  GROUP BY 
    ch.card_no, 
    ch.card_status, 
    e_client.employee_name, 
    ch.add_date, 
    e_approver.employee_name, 
    ch.procurement_approver_date
  ";
    
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

  foreach ($results as &$row) {
    $statuses = explode(',', $row['procurement_statuses']);
    foreach ($statuses as &$status) {
      $status = map_procurement_status((int)$status);
    }
    $row['procurement_statuses'] = implode(',', $statuses);

    $row['card_status'] = map_card_status((int)$row['card_status']);
  }

  return $results;
}

$cardData = get_card_header_datas();
?>