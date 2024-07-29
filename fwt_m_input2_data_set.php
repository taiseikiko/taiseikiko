<?php
  // 初期処理
  require_once('function.php');
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
  $today = date('Y/m/d');
  //ログインユーザーの部署ID
  $dept_id1 = getDeptId($dept_code);
  // 初期設定 & データセット
  
  $class = $candidate1_date = $candidate1_start = $candidate1_end = $candidate2_date = $candidate2_start = $candidate2_end =
  $candidate3_date = $candidate3_start = $candidate3_end = $p_office_no = $cust_no = $post_name = $p_number = $companion = $purpose =
  $qm_visit = $fb_visit = $er_visit = $p_demo = $p_demo_note = $dvd_gd = $dvd_gd_note = $d_document_note = 
  $other_req = $note = $name = $size = $quantity = $card_no = $inspection_note = $training_plan = $lecture = $demonstration =
  $experience = $dvd = '';
  $d_document = $ht_visit = $lunch = $inspection = [];

  $training_plan_list = getTrainingPlanList();

  //更新の場合
  if (isset($_POST['fwt_m_no'])) {
    $fwt_m_no = $_POST['fwt_m_no'];

    $fwtList = getFwtList($fwt_m_no);
    if (isset($fwtList) && !empty($fwtList)) {
      $variables = ['class', 'candidate1_date', 'candidate1_start', 'candidate1_end', 'candidate2_date', 'candidate2_start', 'candidate2_end', 
                'candidate3_date', 'candidate3_start', 'candidate3_end', 'p_office_no', 'cust_no', 'post_name', 'p_number', 'companion', 'purpose',
                'qm_visit', 'fb_visit', 'er_visit', 'p_demo', 'p_demo_note', 'dvd_gd', 'dvd_gd_note', 'd_document', 'd_document_note', 'ht_visit',
                'lunch', 'other_req', 'note', 'name', 'size', 'quantity', 'card_no', 'inspection', 'inspection_note', 'training_plan', 'lecture', 'demonstration',
                'experience', 'dvd'];
      foreach ($variables as $variable) {
        ${$variable} = $fwtList[$variable];
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