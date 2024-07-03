<?php
require_once('function.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $mail_groups = [
    'mail1' => isset($_POST['mail_address1'][0]) ? explode('|', $_POST['mail_address1'][0]) : [],
    'mail2' => isset($_POST['mail_address2'][0]) ? explode('|', $_POST['mail_address2'][0]) : [],
    'mail3' => isset($_POST['mail_address3'][0]) ? explode('|', $_POST['mail_address3'][0]) : [],
    'mail4' => isset($_POST['mail_address4'][0]) ? explode('|', $_POST['mail_address4'][0]) : [],
    'mail5' => isset($_POST['mail_address5'][0]) ? explode('|', $_POST['mail_address5'][0]) : [],
  ];
  $current_date = date('Y/m/d');

  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

  // 既存のデータを消去
  $sql = "DELETE FROM sq_mailing_list";
  $pdo->exec($sql);

  // 各email addressを適切な列に入力
  $sql = "INSERT INTO sq_mailing_list (mail1, mail2, mail3, mail4, mail5, add_date, upd_date) VALUES (?, ?, ?, ?, ?, ?, ?)";
  $stmt = $pdo->prepare($sql);

  $max_rows = max(array_map('count', $mail_groups));
  
  for ($i = 0; $i < $max_rows; $i++) {
    $mails = [
      isset($mail_groups['mail1'][$i]) ? $mail_groups['mail1'][$i] : '',
      isset($mail_groups['mail2'][$i]) ? $mail_groups['mail2'][$i] : '',
      isset($mail_groups['mail3'][$i]) ? $mail_groups['mail3'][$i] : '',
      isset($mail_groups['mail4'][$i]) ? $mail_groups['mail4'][$i] : '',
      isset($mail_groups['mail5'][$i]) ? $mail_groups['mail5'][$i] : ''
    ];
    $stmt->execute(array_merge($mails, [$current_date, $current_date]));
  }

  header('Location: listmail_request_input1.php');
  exit();
}




