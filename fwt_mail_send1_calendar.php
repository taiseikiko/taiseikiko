<?php
    // 初期処理
    require_once('function.php');
    include('fwt_mail_send_select.php');
    $url = $_SERVER['HTTP_REFERER']; //メール送信する時、利用するため

    $to_datas = array();
    $response = array();
    try {
        // DB接続
        $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
        $from_name = $from_email = '';
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
            //データベースからもらったテキストにclientとfwt_m_no、URLをセットする
            $search = array("client", "fwt_m_no");
            $replace = array($from_name, $fwt_m_no);
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

        $email_datas = [
            'from_email' => $from_email,     //送信者email
            'from_name' => $from_name,       //送信者name
            'subject' => $subject,    
            'body' => $body,
            'url' => $base_url . "fwt_m_input2.php?title=adjust&fwt_m_no=".$fwt_m_no
        ];

        //送信先のデータを取得する
        $to_datas = get_mail_recipient_datas($fwt_m_no, $user_code);

        if (!empty($to_datas) && isset($to_datas)) {
            // メール送信処理を行う
            $success = sendMail($email_datas, $to_datas);
            if ($success) {
                $response['status'] = 1;
                $response['message'] = $msg . 'しました。';
            } else {
                $response['status'] = 0;
                $response['message'] = '失敗しました。';
            }
        } else {
            $response['status'] = 0;
            $response['message'] = '失敗しました。';
        }

        echo json_encode($response);

    } catch (PDOException $e) {
        error_log("Error:" . $e->getMessage(), 3, 'error_log.txt');
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
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $datas[] = $row;
        }

        return $datas;
    }

    /***
     * sq_route_in_deptからデータを取得する
     */
    function get_mail_recipient_datas($fwt_m_no, $user_code) {
        global $pdo; 
        $authorization = '1';               

        $employee_datas = [];

        $sql = "
                SELECT e.employee_code, e.employee_name, e.email
                FROM fwt_m_mailing_list m
                LEFT JOIN employee e ON m.employee_code = e.employee_code 
                WHERE m.employee_code = :user AND m.authorization = :authorization;
            ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user', $user_code);
        $stmt->bindParam(':authorization', $authorization);
        $stmt->execute();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $employee_datas[] = $row;
        }

        return $employee_datas;
    }
    
    /***
     * メールの内容を取得する
     */
    function getSqMailSentence() {
        global $pdo;

        $sq_mail_id = '15';
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