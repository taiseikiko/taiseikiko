<?php
    // 初期処理
    require_once('function.php');
    include('card_mail_send_select.php');
    $url = $_SERVER['HTTP_REFERER']; //メール送信する時、利用するため

    $redirect = './dw_input1.php';

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
            $search = array("client", "dw_no");
            $replace = array($from_name, $dw_no);
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
                $url = $base_url . 'dw_input2.php?dw_no=' . $dw_no;
                break;
            
            default:
                $url = $base_url . 'dw_input1.php';
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
                echo "<script>window.location.href='dw_input2.php?err=exceErr'</script>";
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
        global $dw_no;
        global $process;
        $datas = [];

        //入力後dw_route_in_deptのrole=2のメンバー（承認者）へ送信
        if ($process == 'new') {
            
            $sql = "SELECT e.employee_name, e.email
                    FROM dw_route_in_dept d
                    LEFT JOIN employee e ON d.employee_code = e.employee_code
                    WHERE (d.department_code = '02' OR d.department_code = '03') AND d.role = '3'";
            $stmt = $pdo->prepare($sql);
        } 
        //承認後clientにメール送信
        else {

            $sql = "SELECT e.employee_name, e.email
                    FROM dw_management_tr dw
                    LEFT JOIN employee e ON dw.client = e.employee_code
                    WHERE dw.dw_no = :dw_no";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':dw_no', $dw_no);

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

        $sq_mail_id = '11';

        switch ($process) {
            case 'new':
            // case 'update':
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