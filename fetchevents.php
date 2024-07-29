<?php 
// 初期処理
require_once('function.php');
session_start();
// DB接続
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

$sql = "SELECT 
		f.fwt_m_no, 
		CASE f.class
			WHEN '1' THEN '工場見学'
			WHEN '2' THEN '立会検査'
			WHEN '3' THEN '技術研修'
		 	ELSE ''
		END AS class,
		CASE f.status
			WHEN '1' THEN '仮予約済'
			WHEN '2' THEN '日程入力済'
			WHEN '3' THEN '本予約済'
			WHEN '4' THEN '関係部署確認済'
		END AS status
		FROM fwt_m_tr f";
$stmt = $pdo->prepare($sql);
$stmt->execute();
	
$response = array();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
	$response[] = array(
		"eventid" => $row['fwt_m_no'],
		"title" => $row['class'],
		"description" => $row['status'],
		"start" => '2024-07-26',
		"end" => '2024-07-26',
	);
}

echo json_encode($response);
exit;