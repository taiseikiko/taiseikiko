<?php
// 初期処理
require_once('function.php');
include('request_mail_send_select.php');
$url = $_SERVER['HTTP_REFERER']; //メール送信する時、利用するため

$redirect = './request_input1.php?title=request';

try {
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
  $from_name = $from_email = $to_name = $to_email = $subject = $body = '';
  $email_datas = [];
  $success = true;

  //Employeeデータを取得する
  $userdatas = get_employee_data($user_code);
  if (!empty($userdatas)) {
    $from_name = $userdatas[0]['employee_name'];
    $from_email = $userdatas[0]['email'];
  }

  //メールの内容を取得する
  $mail_details = getSqMailSentence();
  if (!empty($mail_details)) {
    //データベースからもらったテキストにclientとsq_noをセットする
    $search = array("client", "request_form_number");
    $replace = array($from_name, $request_form_number);
    $subject = str_replace($search, $replace, $mail_details['sq_mail_title']); //subject
    $body = str_replace($search, $replace, $mail_details['sq_mail_sentence']); //body
  }

  //baseurl を設定する
  $parsed_url = parse_url($url);

  if ($parsed_url !== false) {
    if (isset($parsed_url['port'])) {
      $base_url = $parsed_url['scheme'] . '://' . $parsed_url['host'] . ':' . $parsed_url['port'] . '/';
    } else {
      $base_url = $parsed_url['scheme'] . '://' . $parsed_url['host'] . '/taisei/taiseikiko/';
    }
  }
  $url = $base_url . 'request_input1.php?title=request';
  // $url = $base_url . 'request_input4.php?request_form_number=' . $request_form_number;

  //送信内容をセットする
  $email_datas = [
    'from_email' => $from_email,     //送信者email
    'from_name' => $from_name,       //送信者name
    'subject' => $subject,
    'body' => $body,
    'url' => $url
  ];

  //送信先のデータを取得する
  $to_datas = get_mail_recipient_data($request_form_number);
  
  if (!empty($to_datas) && isset($to_datas)) {
      // メール送信処理を行う
      $success = sendMail($email_datas, $to_datas);
      if ($success) {
          echo "<script>window.close(); window.opener.location.href='$redirect'  </script>";
      } 
      else {
          echo "<script>window.close(); window.opener.location.href='request_input4.php?err=exceErr&title=request'</script>";
      }
  } else {
      echo "<script>window.close(); window.opener.location.href='$redirect'  </script>";
  }

} catch (PDOException $e) {
  error_log("Error: " . $e->getMessage(), 3, "error_log.txt");
  echo "<script>window.close(); window.opener.location.href='card_input2.php?err=exceErr&title=request'</script>";
}

/***
 * Employeeデータを取得する
 */
function get_employee_data($user_code)
{
  global $pdo;
  $datas = [];

  $sql = "SELECT * FROM employee
          WHERE employee_code = :employee_code";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':employee_code', $user_code);
  $stmt->execute();
  while ($emp_row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $datas[] = $emp_row;
  }

  return $datas;
}

/***
 * 送信先のデータを取得する
 */
function get_mail_recipient_data($request_form_number)
{
  global $pdo;
  $datas = [];

  // リクエストフォームの担当者を取得する
  $sql = "SELECT request_person FROM request_form_tr WHERE request_form_number = :request_form_number";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':request_form_number', $request_form_number);
  $stmt->execute();
  $request_person_row = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($request_person_row) {
    $request_person = $request_person_row['request_person'];

    // 担当者のメールアドレスと名前を取得する
    $sql = "SELECT employee_name, email FROM employee WHERE employee_code = :employee_code";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':employee_code', $request_person);
    $stmt->execute();
    while ($recipient_row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $datas[] = $recipient_row;
    }
  }

  return $datas;
}

/***
 * メールの内容を取得する
 */
function getSqMailSentence()
{
  global $pdo;

  $sq_mail_id = '13';
  $seq_no = '3';


  $sql = "SELECT sq_mail_title, sq_mail_sentence FROM sq_mail_sentence WHERE sq_mail_id = :sq_mail_id AND seq_no = :seq_no";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':sq_mail_id', $sq_mail_id);
  $stmt->bindParam(':seq_no', $seq_no);
  $stmt->execute();
  $mail_row = $stmt->fetch(PDO::FETCH_ASSOC);

  return $mail_row;
}
