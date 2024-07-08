<?php
require_once('function.php');
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

$dw_datas = [];
$search_kw = [];
$class_name = $zkm_name = $size = $joint = '';

//検索ボタンを押下した場合
if (isset($_POST['process']) == 'search') {
  $class_name = $_POST['class_name']?? '';  //分類
  $zkm_name = $_POST['zkm_name'] ?? '';     //材工名
  $size = $_POST['size'] ?? '';             //サイズ
  $joint = $_POST['joint'] ?? '';           //接合形状

  $sql = "SELECT dw.dw_no, dw.client, 
          CASE dw.dw_status
            WHEN 1 THEN '承認待ち'
            WHEN 2 THEN '完了'
            WHEN 3 THEN '差し戻し'
          END AS status, 
          dw.dw_div1, dw.open_div, c.class_name, z.zkm_name, dw.size, dw.joint,
          dw.pipe, dw.specification, dw.dw_div2, dw.upd_date
          FROM dw_management_tr dw 
          LEFT JOIN sq_class c ON c.class_code = dw.class_code
          LEFT JOIN sq_zaikoumei z ON z.class_code = dw.class_code AND z.zkm_code = dw.zkm_code
          WHERE 1 = 1";
  if (!empty($class_name)) {
    $search_kw['class_name'] = '%'. $class_name . '%';
    $sql .= " AND c.class_name LIKE :class_name";
  }
  if (!empty($zkm_name)) {
    $search_kw['zkm_name'] = '%' . $zkm_name . '%';
    $sql .= " AND z.zkm_name LIKE :zkm_name";
  }
  if (!empty($size)) {
    $search_kw['size'] = $size;
    $sql .= " AND dw.size LIKE :size";
  }
  if (!empty($joint)) {
    $search_kw['joint'] = $joint;
    $sql .= " AND dw.joint LIKE :joint";
  }
  $stmt = $pdo->prepare($sql);
  $stmt->execute($search_kw);

  $dw_datas = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

