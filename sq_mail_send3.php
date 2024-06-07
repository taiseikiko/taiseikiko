<?php
    // 初期処理
    require_once('function.php');
    include('sq_mail_send_select.php');

    $redirectList = [
        'td' => './sq_detail_tr_engineering_input1.php?title=' . $title,    //技術部
        'sm' => './sq_detail_tr_sales_management_input1.php?title=' . $title,    //営業管理部
        'cm' => './sq_detail_tr_const_management_input1.php?title=' . $title,    //工事管理部
        'pc' => './sq_detail_tr_procurement_input1.php?title=' . $title,    //資材部
    ];

    $s_title = substr($title, 0, 2);
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

        //⑤各部署で、受付処理後、入力者へ送信
        if (isset($_POST['submit_receipt'])) {
            //sq_route_mail_tr の、該当の部署（route1_dept ～ route5_dept）の、
            //入力者（route1_entrant ～ route5_entrant）へ送信
            $sq_route_mail_datas = sq_route_mail_datas();

            if ($sq_route_mail_datas) {
                foreach ($sq_route_mail_datas as $item) {
                    //データベースからもらったテキストにclientとsq_noをセットする
                    $search = array("client", "sq_no");
                    $replace = array($from_name, $sq_no);
                    $subject = str_replace($search, $replace, $mail_details['sq_mail_title']); //subject
                    $body = str_replace($search, $replace, $mail_details['sq_mail_sentence']); //body
                    $to_email = $item['entrant_email'];

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
                echo "<script>
                        window.close();
                        window.opener.location.href='$redirect';
                    </script>";
                }
            } else {
                header($redirect);
            }            
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
    function sq_route_mail_datas() {
        global $pdo;
        global $route_pattern;
        global $sq_no;
        global $sq_line_no;
        global $dept_id;

        $datas = [];
        $sql = "SELECT COALESCE(
                CASE
                    WHEN route1_dept = '$dept_id' THEN route1_entrant_ad
                END,
                CASE
                    WHEN route2_dept = '$dept_id' THEN route2_entrant_ad
                END,
                CASE
                    WHEN route3_dept = '$dept_id' THEN route3_entrant_ad
                END,
                CASE
                    WHEN route4_dept = '$dept_id' THEN route4_entrant_ad
                END,
                CASE
                    WHEN route5_dept = '$dept_id' THEN route5_entrant_ad
                END
            ) AS entrant_email
        FROM sq_route_mail_tr
        WHERE route_id = :route_id AND sq_no = :sq_no AND sq_line_no = :sq_line_no";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':route_id', $route_pattern);
        $stmt->bindParam(':sq_no', $sq_no);
        $stmt->bindParam(':sq_line_no', $sq_line_no);
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