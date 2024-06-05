<?php
  // 初期処理
  require_once('function.php');
  include('public_office_update.php');
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
  // 初期設定 & データセット
  $btn_name = '登録';
  $pf_code = $pf_name = '';
  $office_name = '';
  $office_code = '';
  $employee_code = '';
  $employee_name = '';
  $department_name = '';
  $office_position_name = '';

  //一覧画面からPOSTを取得
  if (isset($_POST['process'])) {
    $process = $_POST['process'];

    //一覧画面に選択された事業所コードを取得する
    $office_code = $_POST['office_code'];
    $office_name = getOfficeNameByOfficeCd($office_code);

    //新規作成の場合
    if ($process == 'create') {
      //官庁コード取得
      $pf_code = getPfCode();
    } else {
      $btn_name = '更新';
      $pf_code = $_POST['pf_code'];
      

      //材工名マスタからデータを取得する
      $pf_datas = getPfDatasByPfCode($pf_code);

      if (!empty($pf_datas)) {
        $pf_name = $pf_datas[0]['pf_name'];
        $employee_code = $pf_datas[0]['person_in_charge'];

        if ($employee_code !== '') {
          $cpDatas = getEmpDatasByEmpCd($employee_code);
          if (isset($cpDatas)) {
            $employee_name = $cpDatas['employee_name'];
            $department_name = $cpDatas['text2'];
            $office_position_name = $cpDatas['text1'];
          }
        }
      }
    }
  }
  $_SESSION['pf_name'] = $pf_name;

  //When form is submitted
  if (isset($_POST['submit'])) {
    $success = reg_or_upd_public_office();
    if ($success) {
      echo "<script>
        window.location.href='public_office_input1.php';
      </script>";
    } else {
      echo "<script>
        window.onload = function() { alert('失敗しました。'); }
      </script>";
    }
  }
  function getPfCode() {
    global $pdo;
    //官庁マスターからMAXを取得する
    $sql = "SELECT MAX(pf_code) as max FROM public_office";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $max_pf_code = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($max_pf_code) {
      $pf_code = $max_pf_code['max'] + 1;
    } else {
      $pf_code = 1;
    }
    return $pf_code;
  }

  function getOfficeNameByOfficeCd($office_code) {
    global $pdo;
    $office_name = '';
    //sq_code テーブルからデータ取得する
    $sql = "SELECT office_name FROM office_m WHERE office_code='$office_code'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $datas = $stmt->fetch(PDO::FETCH_ASSOC);

    if(isset($datas)) {
      $office_name = $datas['office_name'];
    }
    return $office_name;
  }

  function getEmpDatasByEmpCd($employee_code) {
    global $pdo;

    $sql = "SELECT e.employee_name, cmd.text2, cmp.text1 
    FROM employee e
    LEFT JOIN code_master cmd
    ON e.department_code = cmd.text1
    AND cmd.code_id = 'department'
    LEFT JOIN code_master cmp
    ON e.office_position_code = cmp.code_no
    AND cmp.code_id = 'office_position'
    WHERE employee_code = '$employee_code'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }

  function getPfDatasByPfCode($pf_code) {
    global $pdo;
    //官庁マスターからデータを取得する
    $sql = "SELECT * FROM public_office WHERE pf_code = '$pf_code'";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $datas = $stmt->fetchAll();
    return $datas;
  }
?>