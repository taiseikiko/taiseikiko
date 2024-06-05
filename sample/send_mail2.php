<?php 

// メールデータセット
/*
  $from_address           送信者アドレス 
  $from_person_name       送信者名
  $to_address             宛先アドレス
  $to_person_name         宛先指名
  $replayto_address       返信先アドレス
　$replayto_person_name   返信先指名
  $cc_address             ccアドレス
  $cc_person_name         CC宛先指名
  $sender_address         送信元アドレス
  $mail_title             メールタイトル
  $mail_body              メール本文
*/

//----------------------------------------------------------------//
// PHPmailer 準備
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;
  use PHPMailer\PHPMailer\SMTP;

// 設置した場所のパスを指定する
  require('PHPMailer/src/PHPMailer.php');
  require('PHPMailer/src/Exception.php');
  require('PHPMailer/src/SMTP.php');

// 文字エンコードを指定
  mb_language('uni');
  mb_internal_encoding('UTF-8');

// インスタンスを生成（true指定で例外を有効化）
  $mail = new PHPMailer(true);

// 文字エンコードを指定
  $mail->CharSet = 'utf-8';
//----------------------------------------------------------------//

try {
  // デバッグ設定
  // $mail->SMTPDebug = 2; // デバッグ出力を有効化（レベルを指定）
  // $mail->Debugoutput = function($str, $level) {echo "debug level $level; message: $str<br>";};

  // SMTPサーバの設定
  $mail->isSMTP();                          // SMTPの使用宣言
  $mail->Host       = '192.168.1.241';   // SMTPサーバーを指定
  //$mail->SMTPAuth   = true;                 // SMTP authenticationを有効化
  $mail->SMTPAuth   = false;                 // SMTP authenticationを無効化
  //$mail->Username   = 'Admin';   // SMTPサーバーのユーザ名
  //$mail->Password   = 'taiseiedp';           // SMTPサーバーのパスワード
  //$mail->SMTPSecure = 'tls';  // 暗号化を有効（tls or ssl）無効の場合はfalse
  //$mail->Port       = 465; // TCPポートを指定（tlsの場合は465や587）
  $mail->SMTPSecure = 'false';  // 暗号化を有効（tls or ssl）無効の場合はfalse
  $mail->Port       = 25; // TCPポートを指定（tlsの場合は465や587）

			// ここからがポイント
			$mail->SMTPOptions = array(
				'ssl' => array(
					'verify_peer' => false,
					'verify_peer_name' => false,
					// 'allow_self_signed' => true
				)
      );


  // 送受信先設定（第二引数は省略可）
  $mail->setFrom($from_address, $from_person_name); // 送信者
  $mail->addAddress($to_address[$i], $to_person_name[$i]);   // 宛先
  $mail->addReplyTo($replayto_address, $replayto_person_name); // 返信先
  //$mail->addCC($cc_address, $cc_person_name); // CC宛先
  $mail->Sender = $sender_address; // Return-path

  // 送信内容設定
  $mail->Subject = $mail_title[$i]; 
  $mail->Body    = $mail_body[$i];  

  // 送信
  $mail->send();
} catch (Exception $e) {
  // エラーの場合
  echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}


?>
