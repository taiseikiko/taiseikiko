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
    $sql .= " INNER JOIN (
                SELECT DISTINCT(sq_no) 
                FROM sq_route_tr 
                WHERE ";
      switch ($title) {
        case 'pc_receipt':
          $sql .= "(
                      route1_dept = ? AND route1_receipt_date IS NULL
                    ) OR (
                      route2_dept = ? AND route2_receipt_date IS NULL AND route1_approval_date IS NOT NULL
                    ) OR (
                      route3_dept = ? AND route3_receipt_date IS NULL AND route2_approval_date IS NOT NULL
                    ) OR (
                      route4_dept = ? AND route4_receipt_date IS NULL AND route3_approval_date IS NOT NULL
                    ) OR (
                      route5_dept = ? AND route5_receipt_date IS NULL AND route4_approval_date IS NOT NULL
                    )"; 
          break;
        case 'pc_entrant':
          $sql .= "(
                      route1_dept = ? AND route1_receipt_date IS NOT NULL AND route1_entrant_date IS NULL
                    ) OR (
                      route2_dept = ? AND route2_receipt_date IS NOT NULL AND route2_entrant_date IS NULL
                    ) OR (
                      route3_dept = ? AND route3_receipt_date IS NOT NULL AND route3_entrant_date IS NULL
                    ) OR (
                      route4_dept = ? AND route4_receipt_date IS NOT NULL AND route4_entrant_date IS NULL
                    ) OR (
                      route5_dept = ? AND route5_receipt_date IS NOT NULL AND route5_entrant_date IS NULL
                    )"; 
          break;
        case 'pc_confirm':
          $sql .= "(
                      route1_dept = ? AND route1_entrant_date IS NOT NULL AND route1_confirm_date IS NULL
                    ) OR (
                      route2_dept = ? AND route2_entrant_date IS NOT NULL AND route2_confirm_date IS NULL
                    ) OR (
                      route3_dept = ? AND route3_entrant_date IS NOT NULL AND route3_confirm_date IS NULL
                    ) OR (
                      route4_dept = ? AND route4_entrant_date IS NOT NULL AND route4_confirm_date IS NULL
                    ) OR (
                      route5_dept = ? AND route5_entrant_date IS NOT NULL AND route5_confirm_date IS NULL
                    )"; 
          break;
        case 'pc_approve':
          $sql .= "(
                      route1_dept = ? AND route1_confirm_date IS NOT NULL AND route1_approval_date IS NULL
                    ) OR (
                      route2_dept = ? AND route2_confirm_date IS NOT NULL AND route2_approval_date IS NULL
                    ) OR (
                      route3_dept = ? AND route3_confirm_date IS NOT NULL AND route3_approval_date IS NULL
                    ) OR (
                      route4_dept = ? AND route4_confirm_date IS NOT NULL AND route4_approval_date IS NULL
                    ) OR (
                      route5_dept = ? AND route5_confirm_date IS NOT NULL AND route5_approval_date IS NULL
                    )"; 
          break;
      }
      $sql.=  ") AS dist_sq_route_tr ON h.sq_no = dist_sq_route_tr.sq_no";
  }
  $stmt = $pdo->prepare($sql);
  $params = array_fill(0, substr_count($sql, '?'), $dept_id);
  $stmt->execute($params);

  $datas = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $datas;
}
