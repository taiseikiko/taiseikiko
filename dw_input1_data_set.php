<?php
require_once('function.php');
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
//ログインユーザーの部署ID
$dept_cd = $_POST['dept_code'] ?? $dept_code;
$dept_id = getDeptId($dept_cd);
$dw_datas = [];
$search_kw = [];

//検索ボタンを押下した場合
if (isset($_POST['process']) == 'search') {
  $class_code = $_POST['class_code']?? '';  //分類
  $zkm_code = $_POST['zkm_code'] ?? '';     //材工名
  $size = $_POST['size'] ?? '';             //サイズ
  $joint = $_POST['joint'] ?? '';           //接合形状

  $sql = "SELECT dw.dw_no, dw.client, 
          CASE dw.dw_status
            WHEN 1 THEN '承認待ち'
            WHEN 2 THEN '完了'
            WHEN 3 THEN '差し戻し'
          END AS status, 
          dw.dw_div1, dw.open_div, dw.class_code, dw.zkm_code, dw.size, dw.joint,
          dw.pipe, dw.specification, dw.dw_div2, dw.upd_date
          FROM dw_management_tr dw 
          WHERE 1 = 1";
  if (!empty($class_code)) {
    $search_kw['class_code'] = $class_code;
    $sql .= " AND dw.class_code LIKE :class_code";
  }
  if (!empty($zkm_code)) {
    $search_kw['zkm_code'] = $zkm_code;
    $sql .= " AND dw.zkm_code LIKE :zkm_code";
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

