<?php 
// 初期処理
require_once('function.php');
session_start();
$user_code = $_SESSION["login"];
// DB接続
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
$today = date('Y/m/d');
$success = true;
$request = "";
$class = $candidate1_date = $candidate1_start = $candidate1_end = $candidate2_date = $candidate2_start = $candidate2_end =
$candidate3_date = $candidate3_start = $candidate3_end = $pf_code = $cust_code = $post_name = $p_number = $companion = $purpose =
$qm_visit = $fb_visit = $er_visit = $p_demo = $p_demo_note = $dvd_gd = $dvd_gd_note = $d_document_note = 
$other_req = $note = $name = $size = $quantity = $card_no = $inspection_note = $training_plan = $lecture = $demonstration =
$experience = $hid_dvd = '';
$d_document = $ht_visit = $lunch = $inspection = [];
$columns = '';
$title = $_POST['title'];

// Read $_POST value
if(isset($_POST['submit'])){
	$status = $_POST['status'] ?? '';

	//日程調整の場合
	if ($status == '1') {
		$status_new = '2';
		$datas = [
			'fixed_date' => $_POST['fixed_date']?? '',
			'fixed_start' => $_POST['fixed_start']?? '',
			'fixed_end' => $_POST['fixed_end']?? '',
			'note' => $_POST['note']?? '',
		];
		$columns = ", fixed_date=:fixed_date, fixed_start=:fixed_start, fixed_end=:fixed_end, note=:note";
	}

	//本予約登録の場合
	else if ($status == '2') {
		$status_new = '3';
		$datas = [
			'note' => $_POST['note']?? '',
		];
		$columns = ", note=:note";
	}

	//日程確認の場合
	else if ($status == '3') {
		$status_new = '4';
	}

	//本予約確認の場合
	else if ($status == '4') {
		$status_new = '5';
	}

	//本予約承認の場合
	else if ($status == '5') {
		$status_new = '6';
	}

	$datas['status'] = $status_new;
	$datas['upd_date'] = $today;
	$datas['fwt_m_no'] = $_POST['fwt_m_no'];

	try {
		$pdo->beginTransaction();
		
		$sql = "UPDATE fwt_m_tr SET status=:status, upd_date=:upd_date $columns WHERE fwt_m_no=:fwt_m_no";
		$stmt = $pdo->prepare($sql);
		$stmt->execute($datas);

      	$pdo->commit();
    } catch (PDOException $e) {
      	$success = false;
		$pdo->rollback();
		error_log("PDO Exception: " . $e->getMessage(),3,'error_log.txt');
    }

	if ($success) {
		include('fwt_mail_send1.php');
	}
}