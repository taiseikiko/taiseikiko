<?php

require_once('function.php');

$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
$property_datas = getPropertyDatas();

function getPropertyDatas() {
  global $pdo;
  $sql = "
    SELECT 
      d.sq_dept_name AS bridge,
      p.renewal_date,
      c.code_name AS company,
      p.name,
      p.attendance_year,
      p.footnote,
      p.key_number
    FROM ec_stp_detail_tr_procurment p
    LEFT JOIN sq_dept d ON p.bridge = d.sq_dept_code
    LEFT JOIN ec_code_master c ON p.company = c.code_no
    WHERE c.code_key = 'company'
    GROUP BY 
      p.key_number,
      d.sq_dept_name,
      p.renewal_date,
      c.code_name,
      p.name,
      p.attendance_year,
      p.footnote
  ";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
