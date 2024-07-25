<?php
require_once('function.php');
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
$sq_no = '';      //依頼書№
$office_name = '';//事業所
$employee = '';   //担当者
$pf_name = '';    //事業体
$zkm_name = '';   //材工名
$size = '';       //サイズ
$record_div = ''; //依頼内容
$status = '';     //処理状況

//事業所
$officeList = getOfficeList();

function getOfficeList() {
  global $pdo;
  $datas = [];

  $sql = "SELECT * FROM office_m";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $datas[] = $row;
  }

  return $datas;
}

function get_sq_datas($sq_no = "", $office_name = "", $employee = "", $pf_name = "", $zkm_name = "", $size = "", $record_div = "", $status = "") {
  global $pdo;
  $search_kw = [];
  $datas = [];

  $sql = "SELECT d.sq_no, d.sq_line_no, z.zkm_name, d.size, d.joint, d.pipe, d.inner_coating, d.outer_coating, d.fluid, d.valve, dept.text2 AS dept_name,
          CASE d.record_div 
          WHEN '1' THEN '見積'
          WHEN '2' THEN '図面'
          ELSE ''
          END AS record_div_nm,
          CASE d.processing_status
          WHEN '1' THEN '受付'
          WHEN '2' THEN '入力'
          WHEN '3' THEN '確認'
          WHEN '4' THEN '承認'
          ELSE ''
          END AS processing_status,
          pf.pf_name,
          e.employee_name AS entrant_name,
          office.office_name
          FROM sq_header_tr h
          LEFT JOIN sq_detail_tr d ON h.sq_no = d.sq_no
          LEFT JOIN sq_zaikoumei z ON d.zkm_code = z.zkm_code AND d.class_code = z.class_code
          LEFT JOIN sq_code dept ON dept.code_id = 'sq_dept' AND dept.text1 = d.processing_dept
          LEFT JOIN public_office pf ON pf.pf_code = h.p_office_no
          LEFT JOIN office_m office ON office.office_code = pf.office_code
          LEFT JOIN sq_route_tr r ON r.route_id = d.route_pattern AND r.sq_no = d.sq_no AND r.sq_line_no = d.sq_line_no
          LEFT JOIN employee e ON e.employee_code = r.entrant
          WHERE 1=1";
  
  if (!empty($sq_no)) {
    $search_kw['sq_no'] = '%' . $sq_no . '%';
    $sql .= " AND d.sq_no LIKE :sq_no";
  }
  if (!empty($office_name)) {
    $search_kw['office_code'] = '%' . $office_name . '%';
    $sql .= " AND office.office_code LIKE :office_code";
  }
  if (!empty($employee)) {
    $search_kw['employee'] = '%' . $employee . '%';
    $sql .= " AND e.employee_name LIKE :employee";
  }
  if (!empty($pf_name)) {
    $search_kw['pf_name'] = '%' . $pf_name . '%';
    $sql .= " AND pf.pf_name LIKE :pf_name";
  }
  if (!empty($zkm_name)) {
    $search_kw['zkm_name'] = '%' . $zkm_name . '%';
    $sql .= " AND z.zkm_name LIKE :zkm_name";
  }
  if (!empty($record_div)) {
    $search_kw['record_div'] = '%' . $record_div . '%';
    $sql .= " AND d.record_div LIKE :record_div";
  }
  if (!empty($size)) {
    $search_kw['size'] = '%' . $size . '%';
    $sql .= " AND d.size LIKE :size";
  }
  if (!empty($status)) {
    $search_kw['status'] = '%' . $status . '%';
    $sql .= " AND d.processing_status LIKE :status";
  }
  $sql .= " ORDER BY h.sq_no ASC, d.sq_line_no ASC";
  $stmt = $pdo->prepare($sql);
  $stmt->execute($search_kw);

  $datas = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $datas;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['isReturn'])) {
    $sq_no = $_POST['sq_no'] ?? "";
    $office_name = $_POST['dept_name'] ?? "";
    $employee = $_POST['employee'] ?? "";
    $pf_name = $_POST['pf_name'] ?? "";
    $zkm_name = $_POST['zkm_name'] ?? "";
    $size = $_POST['size'] ?? "";
    $record_div = $_POST['record_div'] ?? "";
    $status = $_POST['status'] ?? "";

    $sq_datas = get_sq_datas($sq_no, $office_name, $employee, $pf_name, $zkm_name, $size, $record_div, $status);

    if (isset($sq_datas) && !empty($sq_datas)) {
      foreach ($sq_datas as $item) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($item['sq_no']) . 'ー' . htmlspecialchars($item['sq_line_no']) . '</td>';
        echo '<td>' . htmlspecialchars($item['office_name']) . '</td>';
        echo '<td>' . htmlspecialchars($item['entrant_name']) . '</td>';
        echo '<td>' . htmlspecialchars($item['pf_name']) . '</td>';
        echo '<td>' . htmlspecialchars($item['zkm_name']) . '</td>';
        echo '<td>' . htmlspecialchars($item['size']) . '</td>';
        echo '<td>' . htmlspecialchars($item['record_div_nm']) . '</td>';
        echo '<td>' . htmlspecialchars($item['dept_name']) . htmlspecialchars($item['processing_status']) . '</td>';
        echo '<td style="text-align:center"><button type="submit" class="selectBtn" data-sq_no="' . htmlspecialchars($item['sq_no']) . '" 
        data-sq_line_no="' . htmlspecialchars($item['sq_line_no']) . '" name="process2" value="detail">選択</button></td>';
        echo '<input type="hidden" class="sq_no" name="sq_no" value="' . htmlspecialchars($item['sq_no']) . '">';
        echo '<input type="hidden" class="sq_line_no" name="sq_line_no" value="' . htmlspecialchars($item['sq_line_no']) . '">';
        echo '</tr>';
      }
    } else {

    }
    exit;
  }
}
