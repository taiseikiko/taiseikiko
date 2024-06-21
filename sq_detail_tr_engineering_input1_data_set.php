<?php
require_once('function.php');
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
//ログインユーザーの部署ID
$dept_id = getDeptId($dept_code);

function get_sq_datas($title) {
  global $pdo;
  global $dept_id;

  $sql = "SELECT h.sq_no, h.cust_no, c.cust_name, h.p_office_no, pf.pf_name, pf.person_in_charge, e.employee_name, h.item_name
          FROM sq_header_tr h
          LEFT JOIN customer c ON h.cust_no = c.cust_code
          LEFT JOIN public_office pf ON h.p_office_no = pf.pf_code
          LEFT JOIN employee e ON h.client = e.employee_code";
  if ($title !== '') {
    $sql .= " WHERE EXISTS (
                SELECT 1
                FROM sq_route_tr r
                WHERE h.sq_no = r.sq_no AND route1_dept = '$dept_id' AND ";
    switch ($title) {
      case 'td_receipt':
        $sql .= "route1_receipt_date IS NULL";
        break;
      case 'td_entrant':
        $sql .= "route1_entrant_date IS NULL AND route1_receipt_date IS NOT NULL";
        break;
      case 'td_confirm':
        $sql .= "route1_confirm_date IS NULL AND route1_entrant_date IS NOT NULL";
        break;
      case 'td_approve':
        $sql .= "route1_approval_date IS NULL AND route1_confirm_date IS NOT NULL";
        break;
    }
    $sql.=  ")";
  }
  $stmt = $pdo->prepare($sql);
  $stmt->execute();

  $datas = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $datas;
}
