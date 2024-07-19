<?php
    // 初期処理
    require_once('function.php');
    include('request_mail_send_select.php');
    $url = $_SERVER['HTTP_REFERER']; //メール送信する時、利用するため

    $redirect = './receipt_input1.php?title=receipt';

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

        switch ($status) {
            //受付の場合
            case '3':            
                $url = $base_url . 'receipt_input3.php?title=receipt&request_form_number=' . $request_form_number;
                break;
            // 確認の場合
            case '4':
                $url = $base_url . 'receipt_input4.php?title=receipt&request_form_number=' . $request_form_number;
                break;
            //承認の場合
            case '5':
                $url = $base_url . 'receipt_input2.php?title=receipt&request_form_number=' . $request_form_number;
                break;
        }

        //送信内容をセットする
        $email_datas = [
            'from_email' => $from_email,     //送信者email
            'from_name' => $from_name,       //送信者name
            'subject' => $subject,    
            'body' => $body,
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
                echo "<script>window.location.href='receipt_input2.php?err=exceErr&title=receipt'</script>";
            }
        } else {
            echo "<script>window.location.href='$redirect'  </script>";
        }

    } catch(PDOException $e) {
        error_log("Error: " . $e->getMessage(), 3, "error_log.txt");
        echo "<script>window.location.href='receipt_input2.php?err=exceErr&title=receipt'</script>";
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
        global $status;
        global $dept_id;
        global $request_form_number;
        $datas = [];
        
        switch ($status) {
            //受付の場合、確認者へ送信
            case '3':
                $role = '2';
                break;
            //確認の場合、承認者へ送信
            case '4':
                $role = '3';
                break;

            case '5':
                $role = '0';
        }

        //入力後と確認後の場合
        if ($status == '3' || $status == '4') {
            
            $sql = "SELECT e.employee_name, e.email
                FROM sq_route_in_dept r
                LEFT JOIN employee e 
                ON r.employee_code = e.employee_code
                WHERE r.dept_id = :dept_id AND r.role = :role";
                
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':dept_id', $dept_id);
            $stmt->bindParam(':role', $role);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $datas[] = $row;
            }
        } 
        //承認後の場合
        else {
            $sql = "SELECT e.employee_name, e.email
                FROM request_form_tr r
                LEFT JOIN employee e 
                ON r.request_person = e.employee_code
                WHERE r.request_form_number=:request_form_number";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':request_form_number', $request_form_number);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $datas[] = $row;
            }
        }

        return $datas;
    }

    /***
     * メールの内容を取得する
     */
    function getSqMailSentence() {
        global $pdo;
        global $status;

        $sq_mail_id = '14';

        switch ($status) {
            case '3':
                $seq_no = '2';
                break;

            case '4':
                $seq_no = '3';
                break;

            case '5':
                $seq_no = '4';
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