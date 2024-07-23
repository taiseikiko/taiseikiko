<?php
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

/***
 * メール送信する
 */
function sendMail($email_datas, $to_datas) {
    // インスタンスを生成（true指定で例外を有効化）
    $mail = new PHPMailer(true);

    // 文字エンコードを指定
    $mail->CharSet = 'utf-8';

    try {
        $success = true;
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
        foreach ($to_datas as $item) {
            $mail->addAddress($item['email']);
        }

        //add bcc recipients
        $mail->addBCC('r-higashimura@taiseikiko.com', '東村 凌太');

        $body = '';
        $body = $email_datas['body'] . "<br>"; // Add a line break before the link;
        $body .= "<a href='" . $email_datas['url'] . "'>" . $email_datas['url'] . "</a>";
        $mail->setFrom($email_datas['from_email'], $email_datas['from_name']); // 送信者
        $mail->addReplyTo($email_datas['from_email'], $email_datas['from_name']); // 返信先
        $mail->Sender = $email_datas['from_email']; // Return-path

        // 送信内容設定        
        $mail->Subject = $email_datas['subject'];
        $mail->isHTML(true); // Set email format to plain text
        $mail->Body    = "<pre>$body</pre>";
        if(!$mail->send()){
            $success = false;
        }
    } catch (Exception) {
        $success = false;
        // エラーの場合
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}", 3, 'error_log.txt');
    }
    return $success;
}
?>