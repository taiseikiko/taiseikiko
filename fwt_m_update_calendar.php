<?php 
// 初期処理
require_once('function.php');
session_start();
$user_code = $_SESSION["login"];
// DB接続
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
$today = date('Y/m/d');

$request = "";
$class = $candidate1_date = $candidate1_start = $candidate1_end = $candidate2_date = $candidate2_start = $candidate2_end =
$candidate3_date = $candidate3_start = $candidate3_end = $pf_code = $cust_code = $post_name = $p_number = $companion = $purpose =
$qm_visit = $fb_visit = $er_visit = $p_demo = $p_demo_note = $dvd_gd = $dvd_gd_note = $d_document_note = 
$other_req = $note = $name = $size = $quantity = $card_no = $inspection_note = $training_plan = $lecture = $demonstration =
$experience = $hid_dvd = '';
$d_document = $ht_visit = $lunch = $inspection = [];

// Read $_GET value
if(isset($_POST['request'])){
	$request = $_POST['request'];
}

// Add to fwt_m_tr
if($request == 'add_to_fwt'){
	// POST data
	$start_date = $_POST['start_date'] ?? '';
	$end_date= $_POST['end_date'] ?? '';
	$formList = $_POST['form_values'];
	$status = '1';

	$variables = ['class', 'candidate1_date', 'candidate1_start', 'candidate1_end', 'candidate2_date', 'candidate2_start', 'candidate2_end', 
			'candidate3_date', 'candidate3_start', 'candidate3_end', 'pf_code', 'cust_code', 'post_name', 'p_number', 'companion', 'purpose',
			'qm_visit', 'fb_visit', 'er_visit', 'p_demo', 'p_demo_note', 'dvd_gd', 'dvd_gd_note', 'd_document', 'd_document_note', 'ht_visit',
			'lunch', 'other_req', 'note', 'name', 'size', 'quantity', 'card_no', 'inspection', 'inspection_note', 'training_plan', 'lecture', 'demonstration',
			'experience', 'hid_dvd'];

	//フォームデータをセットする
	foreach ($variables as $variable) {
		${$variable} = $formList[$variable];
	}

	$response = array();
	$err_status = 0;

	try {
		$pdo->beginTransaction();

		/* *************************************************************fwt_m_noを設定する**************************************************************************************** */
		$ym = substr(str_replace('/', '', $today), 0, 6);
		
		$sql1 = "SELECT MAX(fwt_m_no) AS fwt_m_no FROM fwt_m_tr WHERE fwt_m_no LIKE '$ym%'";
		$stmt1 = $pdo->prepare($sql1);
		$stmt1->execute();
		$from_tb_fwt_m_no = $stmt1->fetchColumn();
		
		if (!empty($from_tb_fwt_m_no)) {
			$no = substr($from_tb_fwt_m_no, 6, 2) + 1;      
		} else {
			$no = '1';
		}

		$fwt_m_no = $ym . sprintf('%02d', $no);

		/* ********************************************************************************************************************************************************************** */
		
		$sql = "INSERT INTO fwt_m_tr (fwt_m_no, select_date, class, client, status, candidate1_date, candidate1_start, candidate1_end, candidate2_date, candidate2_start, candidate2_end, 
	  			candidate3_date, candidate3_start, candidate3_end, p_office_no, cust_no, post_name, p_number, companion, purpose, 
				qm_visit, fb_visit, er_visit, p_demo, p_demo_note, dvd_gd, dvd_gd_note, d_document, d_document_note, ht_visit, lunch, 
				other_req, note, name, size, quantity, card_no, inspection, inspection_note, training_plan, lecture, demonstration, experience, 
				dvd, add_date)
				VALUES (:fwt_m_no, :select_date, :class, :client, :status, :candidate1_date, :candidate1_start, :candidate1_end, :candidate2_date, :candidate2_start, :candidate2_end, :candidate3_date, 
				:candidate3_start, :candidate3_end, :pf_code, :cust_code, :post_name, :p_number, :companion, :purpose, :qm_visit, :fb_visit, 
				:er_visit, :p_demo, :p_demo_note, :dvd_gd, :dvd_gd_note, :d_document, :d_document_note, :ht_visit, :lunch, :other_req, 
				:note, :name, :size, :quantity, :card_no, :inspection, :inspection_note, :training_plan, :lecture, :demonstration, :experience, :dvd, :add_date)";

		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':fwt_m_no', $fwt_m_no, PDO::PARAM_STR);
		$stmt->bindParam(':select_date', $start_date, PDO::PARAM_STR);
		$stmt->bindParam(':class', $class, PDO::PARAM_STR);
		$stmt->bindParam(':client', $user_code, PDO::PARAM_STR);
		$stmt->bindParam(':status', $status, PDO::PARAM_STR);
		$stmt->bindParam(':candidate1_date', $candidate1_date, PDO::PARAM_STR);
		$stmt->bindParam(':candidate1_start', $candidate1_start, PDO::PARAM_STR);
		$stmt->bindParam(':candidate1_end', $candidate1_end, PDO::PARAM_STR);
		$stmt->bindParam(':candidate2_date', $candidate2_date, PDO::PARAM_STR);
		$stmt->bindParam(':candidate2_start', $candidate2_start, PDO::PARAM_STR);
		$stmt->bindParam(':candidate2_end', $candidate2_end, PDO::PARAM_STR);
		$stmt->bindParam(':candidate3_date', $candidate3_date, PDO::PARAM_STR);
		$stmt->bindParam(':candidate3_start', $candidate3_start, PDO::PARAM_STR);

		$stmt->bindParam(':candidate3_end', $candidate3_end, PDO::PARAM_STR);
		$stmt->bindParam(':pf_code', $pf_code, PDO::PARAM_STR);
		$stmt->bindParam(':cust_code', $cust_code, PDO::PARAM_STR);
		$stmt->bindParam(':post_name', $post_name, PDO::PARAM_STR);
		$stmt->bindParam(':p_number', $p_number, PDO::PARAM_STR);
		$stmt->bindParam(':companion', $companion, PDO::PARAM_STR);
		$stmt->bindParam(':purpose', $purpose, PDO::PARAM_STR);
		$stmt->bindParam(':qm_visit', $qm_visit, PDO::PARAM_STR);
		$stmt->bindParam(':fb_visit', $fb_visit, PDO::PARAM_STR);
		$stmt->bindParam(':er_visit', $er_visit, PDO::PARAM_STR);

		$stmt->bindParam(':p_demo', $p_demo, PDO::PARAM_STR);
		$stmt->bindParam(':p_demo_note', $p_demo_note, PDO::PARAM_STR);
		$stmt->bindParam(':dvd_gd', $dvd_gd, PDO::PARAM_STR);
		$stmt->bindParam(':dvd_gd_note', $dvd_gd_note, PDO::PARAM_STR);
		$stmt->bindParam(':d_document', $d_document, PDO::PARAM_STR);
		$stmt->bindParam(':d_document_note', $d_document_note, PDO::PARAM_STR);
		$stmt->bindParam(':ht_visit', $ht_visit, PDO::PARAM_STR);
		$stmt->bindParam(':lunch', $lunch, PDO::PARAM_STR);
		$stmt->bindParam(':other_req', $other_req, PDO::PARAM_STR);
		$stmt->bindParam(':note', $note, PDO::PARAM_STR);

		$stmt->bindParam(':name', $name, PDO::PARAM_STR);
		$stmt->bindParam(':size', $size, PDO::PARAM_STR);
		$stmt->bindParam(':quantity', $quantity, PDO::PARAM_STR);
		$stmt->bindParam(':card_no', $card_no, PDO::PARAM_STR);
		$stmt->bindParam(':inspection', $inspection, PDO::PARAM_STR);
		$stmt->bindParam(':inspection_note', $inspection_note, PDO::PARAM_STR);
		$stmt->bindParam(':training_plan', $training_plan, PDO::PARAM_STR);
		$stmt->bindParam(':lecture', $lecture, PDO::PARAM_STR);
		$stmt->bindParam(':demonstration', $demonstration, PDO::PARAM_STR);
		$stmt->bindParam(':experience', $experience, PDO::PARAM_STR);

		$stmt->bindParam(':dvd', $hid_dvd, PDO::PARAM_STR);
		$stmt->bindParam(':add_date', $today, PDO::PARAM_STR);

		$stmt->execute();

      	$pdo->commit();
    } catch (PDOException $e) {
      	$err_status = 1;
		$pdo->rollback();
		error_log("PDO Exception: " . $e->getMessage(),3,'error_log.txt');
    }	

	if ($err_status == 1){
		$response['status'] = 0;
		$response['message'] = '失敗しました。';
		echo json_encode($response);
		exit;
	} else {
		$msg = '登録';
		include('fwt_mail_send1_calendar.php');
	}
	
} 

// Update fwt_m_tr
if($request == 'edit_to_fwt'){

	// POST data
	$start_date = $_POST['start_date'] ?? '';
	$end_date= $_POST['end_date'] ?? '';
	$formList = $_POST['form_values'];
	$status = '1';

	$variables = ['fwt_m_no', 'class', 'candidate1_date', 'candidate1_start', 'candidate1_end', 'candidate2_date', 'candidate2_start', 'candidate2_end', 
			'candidate3_date', 'candidate3_start', 'candidate3_end', 'pf_code', 'cust_code', 'post_name', 'p_number', 'companion', 'purpose',
			'qm_visit', 'fb_visit', 'er_visit', 'p_demo', 'p_demo_note', 'dvd_gd', 'dvd_gd_note', 'd_document', 'd_document_note', 'ht_visit',
			'lunch', 'other_req', 'note', 'name', 'size', 'quantity', 'card_no', 'inspection', 'inspection_note', 'training_plan', 'lecture', 'demonstration',
			'experience', 'hid_dvd'];

	//フォームデータをセットする
	foreach ($variables as $variable) {
		${$variable} = $formList[$variable];
	}

	$response = array();
	$err_status = 0;

	try {
		$pdo->beginTransaction();		
		
		$sql = "UPDATE fwt_m_tr SET class=:class, status=:status, candidate1_date=:candidate1_date, candidate1_start=:candidate1_start, candidate1_end=:candidate1_end, candidate2_date=:candidate2_date, 
				candidate2_start=:candidate2_start, candidate2_end=:candidate2_end, 
	  			candidate3_date=:candidate3_date, candidate3_start=:candidate3_start, candidate3_end=:candidate3_end, p_office_no=:pf_code, cust_no=:cust_code, post_name=:post_name, p_number=:p_number, 
				companion=:companion, purpose=:purpose, 
				qm_visit=:qm_visit, fb_visit=:fb_visit, er_visit=:er_visit, p_demo=:p_demo, p_demo_note=:p_demo_note, dvd_gd=:dvd_gd, dvd_gd_note=:dvd_gd_note, d_document=:d_document, 
				d_document_note=:d_document_note, ht_visit=:ht_visit, lunch=:lunch, 
				other_req=:other_req, note=:note, name=:name, size=:size, quantity=:quantity, card_no=:card_no, inspection=:inspection, inspection_note=:inspection_note, training_plan=:training_plan, 
				lecture=:lecture, demonstration=:demonstration, experience=:experience, 
				dvd=:dvd, upd_date=:upd_date WHERE fwt_m_no=:fwt_m_no";
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':fwt_m_no', $fwt_m_no, PDO::PARAM_STR);
		$stmt->bindParam(':class', $class, PDO::PARAM_STR);
		// $stmt->bindParam(':client', $user_code, PDO::PARAM_STR);
		$stmt->bindParam(':status', $status, PDO::PARAM_STR);
		$stmt->bindParam(':candidate1_date', $candidate1_date, PDO::PARAM_STR);
		$stmt->bindParam(':candidate1_start', $candidate1_start, PDO::PARAM_STR);
		$stmt->bindParam(':candidate1_end', $candidate1_end, PDO::PARAM_STR);
		$stmt->bindParam(':candidate2_date', $candidate2_date, PDO::PARAM_STR);
		$stmt->bindParam(':candidate2_start', $candidate2_start, PDO::PARAM_STR);
		$stmt->bindParam(':candidate2_end', $candidate2_end, PDO::PARAM_STR);
		$stmt->bindParam(':candidate3_date', $candidate3_date, PDO::PARAM_STR);
		$stmt->bindParam(':candidate3_start', $candidate3_start, PDO::PARAM_STR);

		$stmt->bindParam(':candidate3_end', $candidate3_end, PDO::PARAM_STR);
		$stmt->bindParam(':pf_code', $pf_code, PDO::PARAM_STR);
		$stmt->bindParam(':cust_code', $cust_code, PDO::PARAM_STR);
		$stmt->bindParam(':post_name', $post_name, PDO::PARAM_STR);
		$stmt->bindParam(':p_number', $p_number, PDO::PARAM_STR);
		$stmt->bindParam(':companion', $companion, PDO::PARAM_STR);
		$stmt->bindParam(':purpose', $purpose, PDO::PARAM_STR);
		$stmt->bindParam(':qm_visit', $qm_visit, PDO::PARAM_STR);
		$stmt->bindParam(':fb_visit', $fb_visit, PDO::PARAM_STR);
		$stmt->bindParam(':er_visit', $er_visit, PDO::PARAM_STR);

		$stmt->bindParam(':p_demo', $p_demo, PDO::PARAM_STR);
		$stmt->bindParam(':p_demo_note', $p_demo_note, PDO::PARAM_STR);
		$stmt->bindParam(':dvd_gd', $dvd_gd, PDO::PARAM_STR);
		$stmt->bindParam(':dvd_gd_note', $dvd_gd_note, PDO::PARAM_STR);
		$stmt->bindParam(':d_document', $d_document, PDO::PARAM_STR);
		$stmt->bindParam(':d_document_note', $d_document_note, PDO::PARAM_STR);
		$stmt->bindParam(':ht_visit', $ht_visit, PDO::PARAM_STR);
		$stmt->bindParam(':lunch', $lunch, PDO::PARAM_STR);
		$stmt->bindParam(':other_req', $other_req, PDO::PARAM_STR);
		$stmt->bindParam(':note', $note, PDO::PARAM_STR);

		$stmt->bindParam(':name', $name, PDO::PARAM_STR);
		$stmt->bindParam(':size', $size, PDO::PARAM_STR);
		$stmt->bindParam(':quantity', $quantity, PDO::PARAM_STR);
		$stmt->bindParam(':card_no', $card_no, PDO::PARAM_STR);
		$stmt->bindParam(':inspection', $inspection, PDO::PARAM_STR);
		$stmt->bindParam(':inspection_note', $inspection_note, PDO::PARAM_STR);
		$stmt->bindParam(':training_plan', $training_plan, PDO::PARAM_STR);
		$stmt->bindParam(':lecture', $lecture, PDO::PARAM_STR);
		$stmt->bindParam(':demonstration', $demonstration, PDO::PARAM_STR);
		$stmt->bindParam(':experience', $experience, PDO::PARAM_STR);

		$stmt->bindParam(':dvd', $hid_dvd, PDO::PARAM_STR);
		$stmt->bindParam(':upd_date', $today, PDO::PARAM_STR);

		$stmt->execute();


      $pdo->commit();
    } catch (PDOException $e) {
      	$err_status = 1;
		$pdo->rollback();
		error_log("PDO Exception: " . $e->getMessage(),3,'error_log.txt');
    }	

	if ($err_status == 1){
		$response['status'] = 0;
		$response['message'] = '失敗しました。';
		echo json_encode($response);
		exit;
	} else {
		$msg = '更新';
		include('fwt_mail_send1_calendar.php');
	}	
	
}

// Add to fwt_stop_tr
if($request == 'add_to_fwt_stop'){
	// POST data
	$start_date = $_POST['start_date'] ?? '';
	$end_date= $_POST['end_date'] ?? '';
	$formList = $_POST['form_values'];

	$variables = ['stop_note', 'stop_date', 'stop_time', 'stop_name'];

	//フォームデータをセットする
	foreach ($variables as $variable) {
		${$variable} = $formList[$variable];
	}

	$response = array();
	$err_status = 0;

	try {
		$pdo->beginTransaction();
		
		$sql = "INSERT INTO fwt_stop_tr (stop_note, stop_date, stop_time, stop_name, add_date)
				VALUES (:stop_note, :stop_date, :stop_time, :stop_name, :add_date)";

		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':stop_note', $stop_note, PDO::PARAM_STR);
		$stmt->bindParam(':stop_date', $stop_date, PDO::PARAM_STR);
		$stmt->bindParam(':stop_time', $stop_time, PDO::PARAM_STR);
		$stmt->bindParam(':stop_name', $stop_name, PDO::PARAM_STR);
		$stmt->bindParam(':add_date', $today, PDO::PARAM_STR);

		$stmt->execute();

      	$pdo->commit();
    } catch (PDOException $e) {
      	$err_status = 1;
		$pdo->rollback();
		error_log("PDO Exception: " . $e->getMessage(),3,'error_log.txt');
    }	

	if ($err_status == 1){
		$response['status'] = 0;
		$response['message'] = '失敗しました。';
		echo json_encode($response);
		exit;
	} else {
		$response['status'] = 1;
		$response['message'] = '予定不可を登録しました。';
		echo json_encode($response);
		exit;
	}
	
} 