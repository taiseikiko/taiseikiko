<?php
    // 初期処理
    require_once('function.php');
    include('card_mail_send_select.php');
    $url = $_SERVER['HTTP_REFERER']; //メール送信する時、利用するため
    $redirect = './card_input1.php';

    try {
        // DB接続
        $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
        $from_name = $from_email = $to_name = $to_email = '';
        $email_datas = [];
        $success = true;

        //Employeeデータを取得する
        $userdatas = get_employee_datas($user_code);
        if (!empty($userdatas)) {
            $from_name = $userdatas[0]['employee_name'];
            $from_email = $userdatas[0]['email'];
        }

        //メールの内容を取得する
        $mail_details = getSqMailSentence();
        if (!empty($mail_details)) {
            //データベースからもらったテキストにclientとsq_noをセットする
            $search = array("client", "card_no");
            $replace = array($from_name, $card_no);
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

        //URL設定
        $url = $base_url . 'card_input2.php?send_back=true&card_no=' . $card_no;

        //送信内容をセットする
        $email_datas = [
            'from_email' => $from_email,     //送信者email
            'from_name' => $from_name,       //送信者name
            'subject' => $subject,    
            'body' => $body,
            'sq_card_no' => $card_no,
            'url' => $url
        ];

        //送信先のデータを取得する
        $to_datas = get_mail_recipient_datas();

        if (!empty($to_datas) && isset($to_datas)) {
            // メール送信処理を行う
            $success = sendMail($email_datas, $to_datas);
        } 

        // リダイレクト処理
        $redirect_url = ($success) ? "$redirect" : "card_input2.php?err=" . ($datas ? "exceErr" : "noMail");
        echo "<script>window.close(); window.opener.location.href='$redirect_url';</script>";

    } catch(PDOException $e) {
        error_log("Error: " . $e->getMessage(), 3, "error_log.txt");
        echo "<script>window.close();window.opener.location.href='card_input2.php?err=exceErr'</script>";
    }

    /***
     * Employeeデータを取得する
     */
    function get_employee_datas($user_code) {
        global $pdo;
        $datas = [];

        $sql = "SELECT * FROM employee
                WHERE employee_code = :employee_code";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':employee_code', $user_code);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $datas[] = $row;
        }

        return $datas;
    }
    
    /***
     * 送信先のデータを取得する
     */
    function get_mail_recipient_datas() {
        global $pdo;
        global $entrant;
        global $from;
        global $card_no;
        global $sq_card_line_no;
        global $send_back_to_person;
        global $type;
        $header = [];
        $detail = [];
        $receipt = [];
        $datas = [];

        //headerからclientデータを取得する
        $sql_h = "SELECT e.employee_code, e.employee_name, e.email
                FROM card_header_tr h
                LEFT JOIN employee e ON h.client = e.employee_code
                WHERE h.card_no = :card_no";
        $stmt_h = $pdo->prepare($sql_h);
        $stmt_h->bindParam(':card_no', $card_no);
        $stmt_h->execute();
        while($row = $stmt_h->fetch(PDO::FETCH_ASSOC)) {
            $header[] = $row;
        }

        //detailからentrantを取得する
        $sql_d = "SELECT e.employee_code, e.employee_name, e.email
                FROM card_detail_tr d
                LEFT JOIN employee e ON d.entrant = e.employee_code                
                WHERE d.sq_card_no = :sq_card_no AND d.sq_card_line_no = :sq_card_line_no";
        $stmt_d = $pdo->prepare($sql_d);
        $stmt_d->bindParam(':sq_card_no', $card_no);
        $stmt_d->bindParam(':sq_card_line_no', $sq_card_line_no);
        $stmt_d->execute();
        while($row = $stmt_d->fetch(PDO::FETCH_ASSOC)) {
            $detail[] = $row;
        }

        //資材部での差し戻しの場合
        if ($from == 'procurement') {
            //→headerのclientにメール送信
            if (!empty($header)) {
                $datas = $header;
            }
        } 
        //技術部工事技術部での差し戻しの場合
        else {
            //→資材部への差し戻しの場合
            if ($type == 'client') {
                //headerのclientにメール送信
                if (!empty($header)) {
                    $datas = $header;
                }
            }
            //同部署内で受付者ロールの人に差し戻しの場合
            else if ($type == 'receipt') {
                $sql = "SELECT e.employee_code, e.employee_name, e.email
                            FROM employee e
                            WHERE e.employee_code = :employee_code";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':employee_code', $send_back_to_person);
                $stmt->execute();
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $receipt[] = $row;
                }
                $datas = $receipt;
            }
            //同部署内で入力者に差し戻しの場合
            else {
                //detailのentrantにメール送信
                if (!empty($detail)) {
                    $datas = $detail;
                }
            }

        }

        return $datas;
    }

    /***
     * メールの内容を取得する
     */
    function getSqMailSentence() {
        global $pdo;
        global $page;

        $sq_mail_id = '09';
        $seq_no = '1';

        $sql = "SELECT sq_mail_title, sq_mail_sentence FROM sq_mail_sentence WHERE sq_mail_id = :sq_mail_id AND seq_no = :seq_no";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':sq_mail_id', $sq_mail_id);
        $stmt->bindParam(':seq_no', $seq_no);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);        

        return $row;
    }
    
?>