<?php
    // 初期処理
    require_once('function.php');
    include('fwt_mail_send_select.php');
    $url = $_SERVER['HTTP_REFERER']; //メール送信する時、利用するため

    $redirect = "./fwt_m_input1.php?&title=" . $title;
    $to_datas = array();

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
            $replace = array($from_name, $_POST['fwt_m_no']);
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
            'url' => $base_url . "fwt_m_input2.php?title=" . $title ."&fwt_m_no=".$_POST['fwt_m_no']
        ];

        //送信先のデータを取得する
        $to_datas = get_mail_recipient_datas($_POST['fwt_m_no'], $user_code);

        if (!empty($to_datas) && isset($to_datas)) {
            // メール送信処理を行う
            $success = sendMail($email_datas, $to_datas);
            if ($success) {
                echo "<script>window.location.href='$redirect'</script>";
            } else {
                echo "<script>window.location.href='fwt_m_input2.php?err=exceErr'</script>";
            }
        } else {
            echo "<script>window.location.href='$redirect'</script>";
        }

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
        global $title;  
        global $status;  

        switch ($title) {
            //日程登録後
            case 'adjust':
                $authorization = '2';
                break;
            //本予約後
            case 'booking':
                $authorization = '3';
                break;
            case 'confirm':
                //日程確認時
                if ($status == '3') {
                    $authorization = '3';
                } 
                //本予約確認時
                else if ($status == '4') {
                    $authorization = '4';
                } 
                //本予約承認時
                else if ($status == '5') {
                    $authorization = '4';
                } 
                break;
        }

        $employee_datas = [];

        //headerテーブルからclientとdetailからentrantのデータを取得する
        $sql = "
                SELECT 'client' AS type, e.employee_code, e.employee_name, e.email
                FROM fwt_m_tr f
                LEFT JOIN employee e ON f.client = e.employee_code
                WHERE f.fwt_m_no = :fwt_m_no AND f.client = :client
                UNION ALL
                SELECT 'user' AS type, e.employee_code, e.employee_name, e.email
                FROM fwt_m_mailing_list m
                LEFT JOIN employee e ON m.employee_code = e.employee_code 
                WHERE m.employee_code = :user AND m.authorization = :authorization;
            ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':fwt_m_no', $fwt_m_no);
        $stmt->bindParam(':client', $user_code);
        $stmt->bindParam(':user', $user_code);
        $stmt->bindParam(':authorization', $authorization);
        $stmt->execute();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (($title == 'booking' || !($title == 'confirm' && $status == '5')) && $row['type'] == 'client') {} 
            else {
                $employee_datas[] = $row;
            }
        }

        return $employee_datas;
    }
    
    /***
     * メールの内容を取得する
     */
    function getSqMailSentence() {
        global $pdo;
        global $title;  
        global $status;      

        $sq_mail_id = '15';

        switch ($title) {
            //日程登録時
            case 'adjust':
                $seq_no = '3';
                break;
            //本予約後
            case 'booking':
                $seq_no = '2';
                break;
            
            case 'confirm':
                //日程確認時
                if ($status == '3') {
                    $seq_no = '4';
                } 
                //本予約確認時
                else if ($status == '4') {
                    $seq_no = '5';
                } 
                //本予約承認時
                else if ($status == '5') {
                    $seq_no = '6';
                } 
                break;
                
            default:
                $seq_no = '';
                break;
        }

        $sql = "SELECT sq_mail_title, sq_mail_sentence FROM sq_mail_sentence WHERE sq_mail_id = :sq_mail_id AND seq_no = :seq_no";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':sq_mail_id', $sq_mail_id);
        $stmt->bindParam(':seq_no', $seq_no);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);        

        return $row;
    }
    
?>