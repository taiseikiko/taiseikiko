<?php
    // 初期処理
    require_once('function.php');
    include('sq_mail_send_select.php');

    // DB接続
    $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
    $group_id = $role = $confirmer_email = $approver_email = $name = $from_name = $from_email = '';
    $email_datas = [];
    $success = true;

    if (isset($_POST['submit'])) {
        //Employeeデータを取得する
        $userdatas = get_sq_route_in_dept($dept_id, $_POST['user_code']);
        if (!empty($userdatas) && isset($userdatas)) {
            $group_id = $userdatas['group_id'];
            $role = $userdatas['role'];
            $from_name = $userdatas['employee_name'];
            $from_email = $userdatas['email'];
        }

        //メールの内容を取得する
        $mail_details = getSqMailSentence();

        //④ルート設定後、sq_route_tr の、route1_dept をKEYにして、sq_route_in_dept を読み、
        //role = "0"：受付者　全員に送信（※group_id は無視）
        $route1_dept = get_route1_dept();
        //sq_route_in_deptを読む
        $datas = get_sq_route_in_dept2($route1_dept);

        if (!empty($datas) && isset($datas)) {
            foreach ($datas as $item) {
                //データベースからもらったテキストにclientとsq_noをセットする
                $search = array("client", "sq_no");
                $replace = array($from_name, $sq_no);
                $subject = str_replace($search, $replace, $mail_details['sq_mail_title']); //subject
                $body = str_replace($search, $replace, $mail_details['sq_mail_sentence']); //body
                $to_email = $item['email'];
                $to_name = $item['email'];

                $email_datas[] = [
                    'to_email' => $to_email,         //送信先email
                    'to_name' => $to_name,           //送信先name
                    'from_email' => $from_email,     //送信者email
                    'from_name' => $from_name,       //送信者name
                    'subject' => $subject,    
                    'body' => $body,
                    'sq_no' => $sq_no
                ];
            }

            //メール送信処理を行う
            $success = sendMail($email_datas);
            if ($success) {
                header('location:sales_route_input1.php');
            }
        } else {
            header('location:sales_route_input1.php');
        }
    }

    function get_sq_route_in_dept($dept_id, $user_code) {
        global $pdo;

        $sql = "SELECT r.group_id, r.role, e.employee_name, e.email
                FROM sq_route_in_dept r
                LEFT JOIN employee e 
                ON r.employee_code = e.employee_code
                WHERE r.dept_id = '$dept_id' AND r.employee_code = '$user_code'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row;
    }

    function get_sq_route_in_dept2($route1_dept) {
        global $pdo;
        $role = '0';    //受付者
        $datas = [];
        $sql = "SELECT r.group_id, r.role, e.employee_name, e.email
                FROM sq_route_in_dept r
                LEFT JOIN employee e 
                ON r.employee_code = e.employee_code
                WHERE r.dept_id = '$route1_dept' AND r.role = '$role'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $datas[] = $row;
        }

        return $datas;
    }

    function get_route1_dept() {
        global $pdo;
        global $route_pattern;
        $route1_dept = '';

        $sql = "SELECT * FROM sq_route WHERE route_id = '$route_pattern'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!empty($row) && isset($row)) {
            $route1_dept = $row['route1_dept'];
        }      

        return $route1_dept;
    }

    function getSqMailSentence() {
        global $pdo;
        global $title;

        $sq_mail_id = '02';
        $seq_no = '1';

        $sql = "SELECT sq_mail_title, sq_mail_sentence FROM sq_mail_sentence WHERE sq_mail_id = '$sq_mail_id' AND seq_no = '$seq_no'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);        

        return $row;
    }
    
?>