<?php
// 初期処理
require_once('function.php');
include('sq_mail_send_select.php');
$url = $_SERVER['HTTP_REFERER']; //メール送信する時、利用するため
$s_title = substr($title, 0, 2);
$e_title = substr($title, 3);

$redirectList = [
  'td' => './sq_detail_tr_engineering_input1.php?title=' . $title,        //技術部
  'sm' => './sq_detail_tr_sales_management_input1.php?title=' . $title,   //営業管理部
  'cm' => './sq_detail_tr_const_management_input1.php?title=' . $title,   //工事管理部
  'pc' => './sq_detail_tr_procurement_input1.php?title=' . $title         //資材部
];

$redirect = $redirectList[$s_title];

// try {
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
  $confirmer_email = $approver_email = $name = $from_name = $from_email = $to_name = $to_email = '';
  $email_datas = [];
  $success = true;

  //Employeeデータを取得する
  $userdatas = get_employee_datas($user_code);
  if ($userdatas) {
    $from_name = $userdatas['employee_name'];
    $from_email = $userdatas['email'];
  }

  //メールの内容を取得する
  $mail_details = getSqMailSentence();

  //baseurl を設定する
  $parsed_url = parse_url($url);

  if ($parsed_url !== false) {
    if (isset($parsed_url['port'])) {
      $base_url = $parsed_url['scheme'] . '://' . $parsed_url['host'] . ':' . $parsed_url['port'] . '/';
    } else {
      $base_url = $parsed_url['scheme'] . '://' . $parsed_url['host'] . '/taisei/taiseikiko/';
    }
  }

  //スキップの場合
  //sq_route_mail_tr の、次の部署の受付者（reception）へ送信

  //sq_route_mail_trを読む
  $sq_route_mail_datas = get_sq_route_mail_datas($title, $base_url);

print_r($sq_route_mail_datas);
  // if ($sq_route_mail_datas) {
  //   foreach ($sq_route_mail_datas as $item) {
  //     //データベースからもらったテキストにclientとsq_noをセットする
  //     $search = array("client", "sq_no");
  //     $replace = array($from_name, $sq_no);
  //     $subject = str_replace($search, $replace, $mail_details['sq_mail_title']); //subject
  //     $body = str_replace($search, $replace, $mail_details['sq_mail_sentence']); //body
  //     $to_email = $item['email'];
  //     $url = $item['url'];

  //     $email_datas[] = [
  //       'to_email' => $to_email,         //送信先email
  //       'to_name' => $to_name,           //送信先name
  //       'from_email' => $from_email,     //送信者email
  //       'from_name' => $from_name,       //送信者name
  //       'subject' => $subject,
  //       'body' => $body,
  //       'sq_no' => $sq_no,
  //       'url' => $url
  //     ];
  //   }

  //   //メール送信処理を行う
  //   $success = sendMail($email_datas);
  //   if ($success) {
  //     if ($e_title == 'receipt') {
  //       echo "<script>
  //           window.close();
  //           window.opener.location.href='$redirect';
  //           </script>";
  //     } else {
  //       echo "<script>window.location.href='$redirect'  </script>";
  //     }
  //   }
  // } else {
  //   echo "<script>window.location.href='$redirect'  </script>";
  // }
// } catch (PDOException $e) {
//   error_log("Error: " . $e->getMessage(), 3, "error_log.txt");
// }

/***
 * Employeeデータを取得する
 */
function get_employee_datas($user_code)
{
  global $pdo;

  $sql = "SELECT * FROM employee
            WHERE employee_code = :employee_code";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':employee_code', $user_code);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  return $row;
}

/***
 * sq_route_mail_trを読む
 */
function get_sq_route_mail_datas($sq_no, $sq_line_no)
{
  global $pdo;
    $sql = "SELECT route1_dept, route2_dept, route3_dept, route4_dept, route5_dept 
            FROM sq_route_tr WHERE sq_no = :sq_no AND sq_line_no = :sq_line_no";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':sq_no', $sq_no);
    $stmt->bindParam(':sq_line_no', $sq_line_no);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        return array_filter([$row['route1_dept'], $row['route2_dept'], $row['route3_dept'], $row['route4_dept'], $row['route5_dept']]);
    }
    return false;
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
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  return $row;
}

function get_mail_receiver_from_sq_route_in_dept($dept_id, $url)
{
  global $pdo;
  $datas = [];
  $i = 0;
  $sql = "SELECT DISTINCT e.employee_name, e.email
            FROM sq_route_in_dept r
            LEFT JOIN employee e 
            ON r.employee_code = e.employee_code
            WHERE r.dept_id != :dept_id";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':dept_id', $dept_id);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $datas[] = $row;
    $datas[$i]['url'] = $url;
    $i++;
  }

  return $datas;
}
