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

        //baseurl を設定する
        $parsed_url = parse_url($url);

        if ($parsed_url !== false) {
            if (isset($parsed_url['port'])) {
                $base_url = $parsed_url['scheme'] . '://' . $parsed_url['host'] . ':' . $parsed_url['port'] . '/';
            } else {
                $base_url = $parsed_url['scheme'] . '://' . $parsed_url['host'] . '/taisei/taiseikiko/';
            }
        }

        //if (array_intersect_key($_POST, array_flip(['submit_receipt', 'submit_entrant', 'submit_entrant1', 'submit_entrant2']))) {

        //sq_route_mail_trを読む
        $sq_route_mail_datas = get_sq_route_mail_datas($title, $base_url);

        if ($sq_route_mail_datas) {
            //データベースからもらったテキストにclientとsq_noをセットする
            $search = array("client", "sq_no");
            $replace = array($from_name, $sq_no);
            $subject = str_replace($search, $replace, $mail_details['sq_mail_title']); //subject
            $body = str_replace($search, $replace, $mail_details['sq_mail_sentence']); //body
            $to_email = $sq_route_mail_datas['email'];
            $url = $sq_route_mail_datas['url'];

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

            //メール送信処理を行う
            $success = sendMail($email_datas);
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

        //⑤各部署で、受付処理後、入力者へ送信
        //⑥各部署で、入力完了後、確認者へ送信
        //⑦各部署で、確認処理後、承認者へ送信
        
        $column = ($e_title === 'receipt') ? 'entrant_ad' : (($e_title === 'entrant') ? 'confirmer_ad' : (($e_title === 'confirm') ? 'approver_ad' : ''));

        $datas = [];
        if (!empty($column)) {
            //送信先の情報を取得する
            $datas = get_mail_receiver($dept_id, $column);

            //メール送信する時、渡すURL
            $url_list = [
                'td' => 'sq_detail_tr_engineering_input2.php?from=mail&title=',        //技術部
                'sm' => 'sq_detail_tr_sales_management_input2.php?from=mail&title=',   //営業管理部
                'cm' => 'sq_detail_tr_const_management_input2.php?from=mail&title=',   //工事管理部
                'pc' => 'sq_detail_tr_procurement_input2.php?from=mail&title='         //資材部
            ];

            switch ($e_title) {
                //各部署の受付画面の場合、
                case 'receipt':
                    $url_to = $base_url . $url_list[$s_title] . $s_title . "_entrant&sq_no=" . $sq_no;
                    break;
                //各部署の入力画面の場合、
                case 'entrant':
                    $url_to = $base_url . $url_list[$s_title] . $s_title . "_confirm&sq_no=" . $sq_no;
                    break;
                //各部署の確認画面の場合、
                case 'confirm':
                    $url_to = $base_url . $url_list[$s_title] . $s_title . "_approve&sq_no=" . $sq_no;
                    break;
            }
            $datas['url'] = $url_to;
        } 
        //⑧各部署で、承認処理後、sq_route_mail_tr の、次の部署（route1_dept ～ route5_dept）の、
        //受付者（route1_receipt_person ～ route5_receipt_person）へ送信
        else {
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
                    '02' => 'sq_detail_tr_engineering_input2.php?title=td_receipt&sq_no=',        //技術部
                    '05' => 'sq_detail_tr_sales_management_input2.php?title=sm_receipt&sq_no=',   //営業管理部
                    '06' => 'sq_detail_tr_const_management_input2.php?title=cm_receipt&sq_no=',   //工事管理部
                    '04' => 'sq_detail_tr_procurement_input2.php?title=pc_receipt&sq_no='         //資材部
                ];
                $url_to = $base_url . $url_list[$next_dept_id] . $sq_no;

                //送信先の情報を取得する
                $datas = get_mail_receiver($next_dept_id, $column);

                $datas['url'] = $url_to;
            } 
            //存在しない場合、sq_header_tr の、client（依頼者）へ送信
            else {
                $sql = "SELECT e.employee_name, e.email
                        FROM sq_header_tr h
                        LEFT JOIN employee e
                        ON h.client = e.employee_code 
                        WHERE sq_no = :sq_no";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':sq_no', $sq_no);
                $stmt->execute();
                $datas = $stmt->fetch(PDO::FETCH_ASSOC);
            }
        }

        return $datas;
    }
    
    /**
     * 送信先の情報を取得する
     */
    function get_mail_receiver($dept_id, $column) {
        global $pdo;
        global $route_pattern;
        global $sq_no;
        global $sq_line_no;
        $sql = "SELECT COALESCE(
                CASE
                    WHEN route1_dept = '$dept_id' THEN route1_$column
                END,
                CASE
                    WHEN route2_dept = '$dept_id' THEN route2_$column
                END,
                CASE
                    WHEN route3_dept = '$dept_id' THEN route3_$column
                END,
                CASE
                    WHEN route4_dept = '$dept_id' THEN route4_$column
                END,
                CASE
                    WHEN route5_dept = '$dept_id' THEN route5_$column
                END
            ) AS email
            FROM sq_route_mail_tr
            WHERE route_id = :route_id AND sq_no = :sq_no AND sq_line_no = :sq_line_no";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':route_id', $route_pattern);
            $stmt->bindParam(':sq_no', $sq_no);
            $stmt->bindParam(':sq_line_no', $sq_line_no);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row;
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

        $e_title = substr($title, 3);

        switch ($e_title) {
            //各部署で、受付処理の場合
            case 'receipt':
                $sq_mail_id = '03';
                $seq_no = '1';
                break;
            //各部署で、入力完了後の場合
            case 'entrant':
                $sq_mail_id = '03';
                $seq_no = '2';
                break;
            //各部署で、確認完了後の場合
            case 'confirm':
                $sq_mail_id = '03';
                $seq_no = '3';
                break;
            //各部署で、承認完了後の場合
            case 'approve':
                $sq_mail_id = '03';
                $seq_no = '4';
                break;
            default:
                $sq_mail_id = '';
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