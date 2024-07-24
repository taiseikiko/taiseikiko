<?php
// 初期処理
require_once('function.php');
include('sq_mail_send_select.php');
$url = $_SERVER['HTTP_REFERER']; //メール送信する時、利用するため

try {
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
  $confirmer_email = $approver_email = $name = $from_name = $from_email = $to_name = $to_email = '';
  $email_datas = [];
  $success = true;

  // Employeeデータを取得する
  $userdatas = get_employee_datas($user_code);
  if ($userdatas) {
    $from_name = $userdatas['employee_name'];
    $from_email = $userdatas['email'];
  }

  // メールの内容を取得する
  $mail_details = getSqMailSentence();
  if (!empty($mail_details)) {
    //データベースからもらったテキストにclientとsq_no、URLをセットする
    $search = array("client", "sq_no", "comments");
    $replace = array($from_name, $sq_no, $comments);
    $subject = str_replace($search, $replace, $mail_details['sq_mail_title']); //subject
    $body = str_replace($search, $replace, $mail_details['sq_mail_sentence']); //body
  }

  // baseurl を設定する
  $parsed_url = parse_url($url);

  if ($parsed_url !== false) {
    if (isset($parsed_url['port'])) {
      $base_url = $parsed_url['scheme'] . '://' . $parsed_url['host'] . ':' . $parsed_url['port'] . '/';
    } else {
      $base_url = $parsed_url['scheme'] . '://' . $parsed_url['host'] . '/taisei/taiseikiko/';
    }
  }

  switch ($title) {
    //入力画面の場合確認画面へ移動出来るように設定する
    case 'input':
        $url = $base_url . "sales_request_check2.php?from=mail&title=check&sq_no=".$sq_no;
        break;
    //確認画面の場合承認画面へ移動出来るように設定する
    case 'check':
        $url = $base_url . "sales_request_approve2.php?from=mail&title=approve&sq_no=".$sq_no;
        break;
    //承認画面の場合ルート設定画面へ移動するように設定する
    case 'approve':
        $url = $base_url . "sales_route_input2.php?from=mail&title=set_route&sq_no=".$sq_no;
        break;
  }

  $email_datas = [
    'from_email' => $from_email,     //送信者email
    'from_name' => $from_name,       //送信者name
    'subject' => $subject,
    'body' => $body,
    'sq_no' => $sq_no,
    'url' => $url
  ];

  $route_mail_datas = get_sq_route_mail_datas($sq_no, $sq_line_no, $dept_id);

  if ($route_mail_datas) {
    //メール送信処理を行う
    $success_mail = sendMail($email_datas, $route_mail_datas);
    //<script>window.close();window.opener.location.href='$redirect';</script>
  } else {
    $success_mail = false;
  }
} catch (PDOException $e) {
  $success_mail = false;
  error_log("Error:" . $e->getMessage(), 3, 'error_log.txt');
}


/***
 * Employeeデータを取得する
 */
function get_employee_datas($user_code)
{
  global $pdo;

  $sql = "SELECT * FROM employee WHERE employee_code = :employee_code";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':employee_code', $user_code);
  $stmt->execute();
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

/***
 * sq_route_mail_trを読む
 */
function get_sq_route_mail_datas($sq_no, $sq_line_no, $dept_id)
{
  global $pdo;
  global $route_pattern;
  global $dept_id;
  $sq_route_mail = [];
  $sq_header_tr = [];
  $send_mail_datas = [];
  //sq_header_tr の、依頼者（client）・確認者（confirmor）・承認者（approver）　へ送信
  /*-------------------------------------------------------開始---------------------------------------------------------------------- */
  $sql = "SELECT e1.employee_name AS client_name, e1.email AS client_email, h.confirmer,
            e2.employee_name AS confirmer_name, e2.email AS confirmer_email, 
            e3.employee_name AS approver_name, e3.email AS approver_email
            FROM sq_header_tr h
            LEFT JOIN employee e1 ON e1.employee_code = h.client
            LEFT JOIN sq_default_role r ON r.dept_id = '$dept_id' AND r.entrant = h.client
            LEFT JOIN employee e2 ON e2.employee_code = r.confirmer
            LEFT JOIN employee e3 ON e3.employee_code = r.approver
            WHERE h.sq_no='$sq_no'";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  $sq_header_tr = $stmt->fetch(PDO::FETCH_ASSOC);
  /*-------------------------------------------------------完了---------------------------------------------------------------------- */


  /*-------------------------------------------------------開始---------------------------------------------------------------------- */
  $start = false;
  $end = false;
  $sql1 = "SELECT * FROM sq_route_mail_tr WHERE route_id='$route_pattern' AND sq_no='$sq_no' AND sq_line_no='$sq_line_no'";
  $stmt1 = $pdo->prepare($sql1);
  $stmt1->execute();
  $sq_route_mail_tr = $stmt1->fetch(PDO::FETCH_ASSOC);

  if (isset($sq_route_mail_tr) && !empty($sq_route_mail_tr)) {
    foreach ($sq_route_mail_tr as $item) {
      for ($i = 1; $i <= 5; $i++) {
        if ($sq_route_mail_tr['route' . $i . '_dept'] == $dept_id) {
          $filter_datas[$i]['route'] = $i;
          $indexs = ['dept', 'receipt_ad', 'entrant_ad', 'confirmer_ad', 'approver_ad'];
          foreach ($indexs as $index) {
            $filter_datas[$i][$index] = $sq_route_mail_tr['route' . $i . '_' . $index];
          }
          $end = true;
          break;
        }
        if (!($end)) {
          $filter_datas[$i]['route'] = $i;
          $indexs = ['dept', 'receipt_ad', 'entrant_ad', 'confirmer_ad', 'approver_ad'];
          foreach ($indexs as $index) {
            $filter_datas[$i][$index] = $sq_route_mail_tr['route' . $i . '_' . $index];
          }
        }
      }
    }
    //array keyをrearrangeする
    $sq_route_mail = array_values($filter_datas);
  }
  /*-------------------------------------------------------完了---------------------------------------------------------------------- */
  $i = 0;
  //メール送信する時、渡すURLを設定する
  //自部署以前の全ての担当者へ送信する
  foreach ($sq_route_mail as $item) {
    $indexs = ['receipt_ad', 'entrant_ad', 'confirmer_ad', 'approver_ad'];
    foreach ($indexs as $index) {
      if ($item[$index] !== '' && $item[$index] !== NULL) {
        $send_mail_datas[$i]['email'] = $item[$index];
        $i++;
      }
    }
  }
  //client、確認者と承認者へも送信する
  if (!empty($sq_header_tr) && isset($sq_header_tr)) {
    $indexs = ['client_email', 'confirmer_email', 'approver_email'];
    //承認画面の場合、確認者とclientへメール送信する
    foreach ($indexs as $index) {
      $send_mail_datas[$i]['email'] = $sq_header_tr[$index];
      $i++;
    }
  }
  return $send_mail_datas;
}

/***
 * メールの内容を取得する
 */
function getSqMailSentence()
{
  global $pdo;

  $sq_mail_id = '06';
  $seq_no = '1';

  $sql = "SELECT sq_mail_title, sq_mail_sentence FROM sq_mail_sentence WHERE sq_mail_id = :sq_mail_id AND seq_no = :seq_no";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':sq_mail_id', $sq_mail_id);
  $stmt->bindParam(':seq_no', $seq_no);
  $stmt->execute();
  return $stmt->fetch(PDO::FETCH_ASSOC);
}

function get_mail_receiver_from_sq_route_in_dept($dept_id, $url)
{
  global $pdo;
  $datas = [];
  $i = 0;

  $sql = "SELECT DISTINCT e.employee_name, e.email
    FROM sq_route_in_dept r
    LEFT JOIN employee e ON r.employee_code = e.employee_code
    WHERE r.dept_id = :dept_id";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':dept_id', $dept_id);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $row['url'] = $url;
    $datas[] = $row;
  }

  return $datas;
}
