<?php
    // 初期処理
    require_once('function.php');
    include('card_mail_send_select.php');
    $url = $_SERVER['HTTP_REFERER']; //メール送信する時、利用するため

    $redirect = './card_input1.php';

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
            $search = array("client", "card_no");
            $replace = array($from_name, $sq_card_no);
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

        //送信内容をセットする
        $email_datas = [
            'from_email' => $from_email,     //送信者email
            'from_name' => $from_name,       //送信者name
            'subject' => $subject,    
            'body' => $body,
            'sq_card_no' => $sq_card_no,
            'url' => $url
        ];
        
        //送信先のデータを取得する
        $to_datas = get_mail_recipient_data();

        if (!empty($to_datas) && isset($to_datas)) {
            // メール送信処理を行う
            $success = sendMail($email_datas, $to_datas);
            if ($success) {
                echo "<script>window.location.href='$redirect'  </script>";
            } else {
                echo "<script>window.location.href='card_input2.php?err=exceErr'</script>";
            }
        } else {
            echo "<script>window.location.href='$redirect'  </script>";
        }

    } catch(PDOException $e) {
        error_log("Error: " . $e->getMessage(), 3, "error_log.txt");
        echo "<script>window.location.href='card_input2.php?err=exceErr'</script>";
    }

    /***
     * Employeeデータを取得する
     */
    function get_employee_data($user_code) {
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
    function get_mail_recipient_data() {
        global $pdo;
        global $sq_card_no;
        global $process;
        $datas = [];

        //資材部入力後card_header_trのprocurement_approver（承認者）へ送信
        if ($process !== 'approve') {
            $sql = "SELECT e.employee_name, e.email
            FROM card_header_tr h
            LEFT JOIN employee e ON e.employee_code = h.procurement_approver
            WHERE h.card_no = :card_no";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':card_no', $sq_card_no);
        } else {
            //資材部承認後card_route_in_deptの、department_id = "02"or"03"　かつ　role = "0"：受付者 の全員へ送信
            $sql = "SELECT e.employee_name, e.email
                    FROM card_route_in_dept d
                    LEFT JOIN employee e ON d.employee_code = e.employee_code
                    WHERE (d.department_code = '02' OR d.department_code = '03') AND d.role = '0'";
            $stmt = $pdo->prepare($sql);
        }        
        
        $stmt->execute();
        while ($recipient_row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $datas[] = $recipient_row;
        }

        return $datas;
    }

    /***
     * メールの内容を取得する
     */
    function getSqMailSentence() {
        global $pdo;
        global $process;

        $sq_mail_id = '07';

        switch ($process) {
            case 'new':
            case 'update':
                $seq_no = '1';
                break;
            
            default:
                $seq_no = '2';
                break;
        }

        $sql = "SELECT sq_mail_title, sq_mail_sentence FROM sq_mail_sentence WHERE sq_mail_id = :sq_mail_id AND seq_no = :seq_no";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':sq_mail_id', $sq_mail_id);
        $stmt->bindParam(':seq_no', $seq_no);
        $stmt->execute();
        $mail_row = $stmt->fetch(PDO::FETCH_ASSOC);        

        return $mail_row;
    }
?>