<?php
    // 初期処理
    require_once('function.php');
    include('sq_mail_send_select.php');
    $url = $_SERVER['HTTP_REFERER']; //メール送信する時、利用するため
    $s_title = substr($title, 0, 2);
    $e_title = substr($title, 3);

    $redirectList = [
        'td' => './sq_detail_tr_engineering_input1.php?title=' . $title,        //技術部
        'sm' => './sq_detail_tr_sales_management_input1.php?title=' . $title,   //営業管理部
        'cm' => './sq_detail_tr_const_management_input1.php?title=' . $title,   //工事管理部
        'pc' => './sq_detail_tr_procurement_input1.php?title=' . $title         //資材部
    ];

    $redirect = $redirectList[$s_title];

    try {
        // DB接続
        $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
        $confirmer_email = $approver_email = $name = $from_name = $from_email = $to_name = $to_email = '';
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
        if (!empty($mail_details)) {
            //データベースからもらったテキストにclientとsq_noをセットする
            $search = array("client", "sq_no");
            $replace = array($from_name, $sq_no);
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
            'sq_no' => $sq_no
        ];

        //スキップの場合
        //sq_route_mail_tr の、次の部署の受付者（reception）へ送信

        //sq_route_mail_trを読む
        $sq_route_mail_datas = get_sq_route_mail_datas($title, $base_url);

        if ($sq_route_mail_datas) {
            foreach ($sq_route_mail_datas as $item) {
                $url = $item['url'];
                $email_datas['url'] = $url;
            }

            //メール送信処理を行う
            $success = sendMail($email_datas, $sq_route_mail_datas);
            if ($success) {
                if ($e_title == 'receipt') {
                    echo "<script>
                    window.close();
                    window.opener.location.href='$redirect';
                    </script>";
                } else {
                    echo "<script>window.location.href='$redirect'  </script>";
                }
            }
        } else {
            echo "<script>window.location.href='$redirect'  </script>";
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
     * sq_route_mail_trを読む
     */
    function get_sq_route_mail_datas($title, $base_url) {
        global $pdo;
        global $route_pattern;
        global $sq_no;
        global $sq_line_no;
        global $dept_id;
        $s_title = substr($title, 0, 2);
        $e_title = substr($title, 3);

        //⑧各部署で、承認処理後、sq_route_mail_tr の、次の部署（route1_dept ～ route5_dept）の、
        //受付者（route1_receipt_person ～ route5_receipt_person）へ送信

        $sql = "SELECT COALESCE(
                    CASE
                        WHEN route1_dept = '$dept_id' THEN COALESCE(route2_dept, route3_dept, route4_dept, route5_dept)
                    END,
                    CASE
                        WHEN route2_dept = '$dept_id' THEN COALESCE(route3_dept, route4_dept, route5_dept)
                    END,
                    CASE
                        WHEN route3_dept = '$dept_id' THEN COALESCE(route4_dept, route5_dept)
                    END,
                    CASE
                        WHEN route4_dept = '$dept_id' THEN COALESCE(route5_dept)
                    END
                ) AS column_name
                FROM sq_route_mail_tr
                WHERE route_id = :route_id AND sq_no = :sq_no AND sq_line_no = :sq_line_no";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':route_id', $route_pattern);
        $stmt->bindParam(':sq_no', $sq_no);
        $stmt->bindParam(':sq_line_no', $sq_line_no);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        //存在する場合、次の部署（route1_dept ～ route5_dept）の、受付者（route1_receipt_person ～ route5_receipt_person）へ送信
        if ($row['column_name']) {
            $next_dept_id = $row['column_name'];
            $column = 'receipt_ad';

            //メール送信する時、渡すURL
            $url_list = [
                '02' => 'sq_detail_tr_engineering_input2.php?from=mail&title=td_receipt&sq_no=',        //技術部
                '05' => 'sq_detail_tr_sales_management_input2.php?from=mail&title=sm_receipt&sq_no=',   //営業管理部
                '06' => 'sq_detail_tr_const_management_input2.php?from=mail&title=cm_receipt&sq_no=',   //工事管理部
                '04' => 'sq_detail_tr_procurement_input2.php?from=mail&title=pc_receipt&sq_no='         //資材部
            ];
            $url_to = $base_url . $url_list[$next_dept_id] . $sq_no;

            //送信先の情報を取得する
            $datas = get_mail_receiver_from_sq_route_in_dept($next_dept_id, $column, $url_to);
        }

        return $datas;
    }

    /***
     * メールの内容を取得する
     */
    function getSqMailSentence() {
        global $pdo;
 
        $sq_mail_id = '05';
        $seq_no = '1';        

        $sql = "SELECT sq_mail_title, sq_mail_sentence FROM sq_mail_sentence WHERE sq_mail_id = :sq_mail_id AND seq_no = :seq_no";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':sq_mail_id', $sq_mail_id);
        $stmt->bindParam(':seq_no', $seq_no);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);        

        return $row;
    }

    function get_mail_receiver_from_sq_route_in_dept($dept_id, $column, $url) {
        global $pdo;
        $role = '0';    //受付者
        $datas = [];
        $i = 0;
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
            $datas[$i]['url'] = $url;
            $i++;
        }

        return $datas;
    }
    
?>