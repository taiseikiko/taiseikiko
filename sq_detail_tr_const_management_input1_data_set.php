<?php
require_once('function.php');
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
//ログインユーザーの部署ID
$dept_id = getDeptId();

function get_sq_datas($title) {
  global $pdo;
  global $dept_id;
  $sql = "SELECT h.sq_no, h.cust_no, c.cust_name, h.p_office_no, pf.pf_name, pf.person_in_charge, e.employee_name, h.item_name
          FROM sq_header_tr h
          LEFT JOIN customer c ON h.cust_no = c.cust_code
          LEFT JOIN public_office pf ON h.p_office_no = pf.pf_code
          LEFT JOIN employee e ON pf.person_in_charge = e.employee_code";
  if ($title !== '') {
    $sql.= " INNER JOIN (SELECT DISTINCT(sq_no) FROM sq_route_tr WHERE ";
      if ($title == 'cm_receipt') { 
        $sql.="(route1_dept = '$dept_id' AND route1_receipt_date IS NULL)
        OR (route2_dept = '$dept_id' AND route2_receipt_date IS NULL)
        OR (route3_dept = '$dept_id' AND route3_receipt_date IS NULL)
        OR (route4_dept = '$dept_id' AND route4_receipt_date IS NULL)
        OR (route5_dept = '$dept_id' AND route5_receipt_date IS NULL)"; 
      }
      if ($title == 'cm_entrant') { 
        $sql.="(route1_dept = '$dept_id' AND route1_receipt_date IS NOT NULL AND route1_entrant_date IS NULL)
        OR (route2_dept = '$dept_id' AND route2_receipt_date IS NOT NULL AND route2_entrant_date IS NULL)
        OR (route3_dept = '$dept_id' AND route3_receipt_date IS NOT NULL AND route3_entrant_date IS NULL)
        OR (route4_dept = '$dept_id' AND route4_receipt_date IS NOT NULL AND route4_entrant_date IS NULL)
        OR (route5_dept = '$dept_id' AND route5_receipt_date IS NOT NULL AND route5_entrant_date IS NULL)"; 
      }
      if ($title == 'cm_confirm') { 
        $sql.="(route1_dept = '$dept_id' AND route1_entrant_date IS NOT NULL AND route1_confirm_date IS NULL)
        OR (route2_dept = '$dept_id' AND route2_entrant_date IS NOT NULL AND route2_confirm_date IS NULL)
        OR (route3_dept = '$dept_id' AND route3_entrant_date IS NOT NULL AND route3_confirm_date IS NULL)
        OR (route4_dept = '$dept_id' AND route4_entrant_date IS NOT NULL AND route4_confirm_date IS NULL)
        OR (route5_dept = '$dept_id' AND route5_entrant_date IS NOT NULL AND route5_confirm_date IS NULL)"; 
      }
      if ($title == 'cm_approve') { 
        $sql.="(route1_dept = '$dept_id' AND route1_confirm_date IS NOT NULL AND route1_approval_date IS NULL)
        OR (route2_dept = '$dept_id' AND route2_confirm_date IS NOT NULL AND route2_approval_date IS NULL)
        OR (route3_dept = '$dept_id' AND route3_confirm_date IS NOT NULL AND route3_approval_date IS NULL)
        OR (route4_dept = '$dept_id' AND route4_confirm_date IS NOT NULL AND route4_approval_date IS NULL)
        OR (route5_dept = '$dept_id' AND route5_confirm_date IS NOT NULL AND route5_approval_date IS NULL)"; 
      }

    $sql.=  ") AS dist_sq_route_tr ON h.sq_no = dist_sq_route_tr.sq_no";
  }
  $stmt = $pdo->prepare($sql);
  $stmt->execute();

  $datas = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $datas;
}

function getDeptId() {
  global $pdo;
  global $dept_code;

  $dept_id = '';
  $sql = "SELECT dept_id FROM sq_dept WHERE sq_dept_code='$dept_code'";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if ($row) {
    $dept_id = $row['dept_id'];
  }
  return $dept_id;
}