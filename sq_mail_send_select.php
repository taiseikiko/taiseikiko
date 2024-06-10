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
function sendMail($email_datas) {
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
        foreach ($email_datas as $item) {
            $body = '';
            $body = $item['body'] . "<br>"; // Add a line break before the link;
            $body .= "<a href='" . $item['url'] . "'>" . $item['url'] . "</a>";
            $mail->setFrom($item['from_email'], $item['from_name']); // 送信者
            $mail->addAddress($item['to_email'], $item['to_name']);
            $mail->addReplyTo('peacefullife4497@gmail.com', 'HTET HTET'); // 返信先
            //$mail->addCC($cc_address, $cc_person_name); // CC宛先
            $mail->Sender = $item['from_email']; // Return-path

            // 送信内容設定
            
            $mail->Subject = $item['subject'];
            $mail->isHTML(true); // Set email format to plain text
            $mail->Body    = "<pre>$body</pre>";

            if(!$mail->send()){
                $success = false;
            }
            $sq_no = $item['sq_no'];
        }

    } catch (Exception $e) {
        $success = false;
    // エラーの場合
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    return $success;
}
?>