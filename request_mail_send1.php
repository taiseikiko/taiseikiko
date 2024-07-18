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

        switch ($process) {
            case 'new':   
            case 'update':         
                $url = $base_url . 'request_input3.php?title=request&request_form_number=' . $request_form_number;
                break;
            
            case 'confirm':
                $url = $base_url . 'request_input4.php?title=request&request_form_number=' . $request_form_number;
                break;

            case 'approve':
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
                echo "<script>window.location.href='request_input2.php?err=exceErr&title=request'</script>";
            }
        } else {
            echo "<script>window.location.href='$redirect'  </script>";
        }

    } catch(PDOException $e) {
        error_log("Error: " . $e->getMessage(), 3, "error_log.txt");
        echo "<script>window.location.href='request_input2.php?err=exceErr&title=request'</script>";
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
        global $process;
        global $dept_id;
        global $user_code;
        global $recipent_dept;
        $datas = [];

        switch ($process) {
            //登録後sq_route_in_deptの現所属部署のrole=1のメンバー（確認者）へ送信
            case 'new':
            case 'update':
                $col = 'd.confirmer';
                break;

            //確認後role=２のメンバー（承認者）へ送信
            case 'confirm':
                $col = 'd.approver';
                break;

            case 'approve':
                $role = '0';
        }

        //入力後と確認後の場合
        if ($process !== 'approve') {
            
            $sql = "SELECT e.email
                FROM sq_default_role d
                LEFT JOIN employee e ON e.employee_code = $col
                WHERE dept_id = :dept_id AND entrant = :entrant";
                
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':dept_id', $dept_id);
            // $stmt->bindParam(':group_id', $group_id);
            $stmt->bindParam(':entrant', $user_code);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $datas[] = $row;
            }
        } 
        //承認後の場合
        else {
            //受付部署の受付者全員を取得する
            $receipt_dept_id = getDeptId($recipent_dept);
            $sql = "SELECT e.employee_name, e.email
                FROM sq_route_in_dept r
                LEFT JOIN employee e 
                ON r.employee_code = e.employee_code
                WHERE r.dept_id = :dept_id AND r.role = :role";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':dept_id', $receipt_dept_id);
            $stmt->bindParam(':role', $role);
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
        global $process;

        $sq_mail_id = '13';

        switch ($process) {
            case 'new':
                $seq_no = '1';
                break;

            case 'confirm':
                $seq_no = '2';
                break;

            case 'approve':
                $sq_mail_id = '14';
                $seq_no = '1';
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