<?php
    // 初期処理
    require_once('function.php');
    include('sq_mail_send_select.php');
    $url = $_SERVER['HTTP_REFERER']; //メール送信する時、利用するため

    try {
        // DB接続
        $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
        $from_name = $from_email = '';
        $email_datas = [];
        $success = true;

        //Employeeデータを取得する
        $userdatas = get_employee_datas($user_code);
        if ($userdatas) {
            $from_name = $userdatas['employee_name'];
            $from_email = $userdatas['email'];
        }

        //メールの内容を取得する
        $mail_details = getSqMailSentence();        
        
        //route1_deptをsq_routeから取得する
        $route1_dept = get_route1_dept();
        
        //baseurl を設定する
        $parsed_url = parse_url($url);

        if ($parsed_url !== false) {
            if (isset($parsed_url['port'])) {
                $base_url = $parsed_url['scheme'] . '://' . $parsed_url['host'] . ':' . $parsed_url['port'] . '/';
            } else {
                $base_url = $parsed_url['scheme'] . '://' . $parsed_url['host'] . '/taisei/taiseikiko/';
            }
        }

        //メール内容に渡すURL
        //部署によって移動する画面が違います
        switch ($route1_dept) {                    
            //部署IDが「０２」の場合、技術部の入力画面へ移動する
            case '02':
                $url = $base_url . "sq_detail_tr_engineering_input2.php?from=mail&title=td_receipt&sq_no=" . $sq_no;
                break;
            //部署IDが「０５」の場合、営業管理部の入力画面へ移動する
            case '05':
                $url = $base_url . "sq_detail_tr_sales_management_input2.php?from=mail&title=sm_receipt&sq_no=" . $sq_no;
                break;
            //部署IDが「０４」の場合、資材部の入力画面へ移動する
            case '04':
                $url = $base_url . "sq_detail_tr_procurement_input2.php?from=mail&title=pc_receipt&sq_no=" . $sq_no;
                break;
            //部署IDが「０６」の場合、工事管理部の入力画面へ移動する
            case '06':
                $url = $base_url . "sq_detail_tr_const_management_input2.php?from=mail&title=cm_receipt&sq_no=" . $sq_no;
                break;
        }

        //④ルート設定後、sq_route_tr の、route1_dept をKEYにして、sq_route_in_dept を読み、
        //role = "0"：受付者　全員に送信（※group_id は無視）
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
                    'sq_no' => $sq_no,
                    'url' => $url
                ];
            }

            //メール送信処理を行う
            $success = sendMail($email_datas);
            if ($success) {
                header('location:sales_route_input1.php?title=set_route');
            }
        } else {
            header('location:sales_route_input1.php?title=set_route');
        }
    } catch(PDOException $e) {
        error_log("Error: " . $e->getMessage(), 3, "error_log.txt");
    }

    /***
     * Employeeデータを取得する
     */
    function get_employee_datas($user_code) {
        global $pdo;

        $sql = "SELECT * FROM employee
                WHERE employee_code = :employee_code";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':employee_code', $user_code);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row;
    }

    /***
     * sq_route_in_deptを読む
     */
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

    /***
     * sq_route_in_dept を読み
     */
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

    /***
     * メールの内容を取得する
     */
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