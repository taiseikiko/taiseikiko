<?php
  // 初期処理
  require_once('function.php');
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
  $today = date('Y/m/d');
  //ログインユーザーの部署ID
  $dept_id1 = getDeptId($dept_code);
  // 初期設定 & データセット
  $btn_name = '';
  $err = $_GET['err']?? '';
  $class = $candidate1_date = $candidate1_start = $candidate1_end = $candidate2_date = $candidate2_start = $candidate2_end =
  $candidate3_date = $candidate3_start = $candidate3_end = $p_office_no = $cust_no = $post_name = $p_number = $companion = $purpose =
  $qm_visit = $fb_visit = $er_visit = $p_demo = $p_demo_note = $dvd_gd = $dvd_gd_note = $d_document_note = 
  $other_req = $note = $name = $size = $quantity = $card_no = $inspection_note = $training_plan = $lecture = $demonstration =
  $experience = $dvd = $status = $fixed_date = $fixed_start = $fixed_end = $pf_name = $cust_name = $d_document = $ht_visit = $lunch = $inspection = '';
  $inspection_arr = [];
  $add_date = date('Y-m-d');

  $training_plan_list = getTrainingPlanList();

  //更新の場合
  if (isset($_POST['fwt_m_no']) || isset($_GET['fwt_m_no'])) {
    $fwt_m_no = $_POST['fwt_m_no']?? $_GET['fwt_m_no'];

    $fwtList = getFwtList($fwt_m_no);
    if (isset($fwtList) && !empty($fwtList)) {
      $variables = ['class', 'candidate1_date', 'candidate1_start', 'candidate1_end', 'candidate2_date', 'candidate2_start', 'candidate2_end', 
                'candidate3_date', 'candidate3_start', 'candidate3_end', 'p_office_no', 'cust_no', 'post_name', 'p_number', 'companion', 'purpose',
                'qm_visit', 'fb_visit', 'er_visit', 'p_demo', 'p_demo_note', 'dvd_gd', 'dvd_gd_note', 'd_document', 'd_document_note', 'ht_visit',
                'lunch', 'other_req', 'note', 'name', 'size', 'quantity', 'card_no', 'inspection', 'inspection_note', 'training_plan', 'lecture', 'demonstration',
                'experience', 'dvd', 'status', 'fixed_date', 'fixed_start', 'fixed_end', 'add_date'];
      foreach ($variables as $variable) {
        ${$variable} = $fwtList[$variable];
      }

      if ($add_date !== '') {
        $add_date = str_replace('/', '-', $add_date);
      }

      //官庁名を取得する
      if ($p_office_no !== '') {
        $pf_name = get_pf_name($p_office_no);
      }

      //社名を取得する
      if ($cust_no !== '') {
        $cust_name = get_cust_name($cust_no);
      }

      //検査内容
      if ($inspection !== '') {
        $inspection_arr = explode(',', $inspection);
      }

      if ($status == '1') {
        $btn_name = '日程調整';
      } else if ($status == '2') {
        $btn_name = '本予約登録';
      } else if ($status == '3') {
        $btn_name = '日程確認';
      } else if ($status == '4') {
        $btn_name = '本予約確認';
      } else if ($status == '5') {
        $btn_name = '本予約承認';
      }

    }
  } else {
    $fwt_m_no = getfwt_m_no();
  }

  function getFwtList($fwt_m_no) {
    global $pdo;

    $sql = "SELECT * FROM fwt_m_tr WHERE fwt_m_no = '$fwt_m_no'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row;
  }

  function getTrainingPlanList() {
    global $pdo;
    
    $sql = "SELECT * FROM training_plan_tr";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $datas = $stmt->fetchAll();

    return $datas;
  }

  function get_cust_name($cust_code) {
    global $pdo;
    $cust_name = '';
    $sql = "SELECT cust_name FROM customer WHERE cust_code = '$cust_code'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $datas = $stmt->fetch(PDO::FETCH_ASSOC);

    if (isset($datas) && !empty($datas)) {
      $cust_name = $datas['cust_name'];
    }
    return $cust_name;
  }

  function get_pf_name($pf_code) {
    global $pdo;
    $pf_name = '';
    $sql = "SELECT pf_name FROM public_office WHERE pf_code = '$pf_code'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $datas = $stmt->fetch(PDO::FETCH_ASSOC);

    if (isset($datas) && !empty($datas)) {
      $pf_name = $datas['pf_name'];
    }
    return $pf_name;
  }

  function getfwt_m_no() {
    global $pdo;
    global $today;

    $ym = substr(str_replace('/', '', $today), 0, 6);
    
    $sql = "SELECT MAX(fwt_m_no) AS fwt_m_no FROM fwt_m_tr WHERE fwt_m_no LIKE '$ym%'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $from_tb_fwt_m_no = $stmt->fetchColumn();
      
    if (!empty($from_tb_fwt_m_no)) {
      $no = substr($from_tb_fwt_m_no, 6, 2) + 1;      
    } else {
      $no = '1';
    }

    $fwt_m_no = $ym . sprintf('%02d', $no);

    return $fwt_m_no;
  }

  
?>