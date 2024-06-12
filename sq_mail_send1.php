<?php
    // 初期処理
    require_once('function.php');
    include('sq_mail_send_select.php');
    $url = $_SERVER['HTTP_REFERER']; //メール送信する時、利用するため

    $redirectList = [
        'input' => './sales_request_input1.php?sq_no='.$sq_no.'&process=update&title='.$title,    //入力
        'check' => './sales_request_check1.php?sq_no='.$sq_no.'&process=update&title='.$title,    //確認
        'approve' => './sales_request_approve1.php?sq_no='.$sq_no.'&process=update&title='.$title,    //承認
    ];

    $redirect = $redirectList[$title];

    try {
        // DB接続
        $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
        $group_id = $role = $confirmer_email = $approver_email = $name = $from_name = $from_email = '';
        $email_datas = [];
        $success = true;

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
        
        //入力画面と確認画面の場合
        if ($title !== 'approve') {
            //sq_default_roleからデータを取得する
            $datas = getDefaultRoleUser($dept_id, $group_id, $_POST['user_code']);
        } 
        //承認画面の場合
        else {
            //sq_route_in_deptからデータを取得する
            $datas = get_sq_route_in_dept2();
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

        switch ($title) {
            //入力画面の場合確認画面へ移動出来るように設定する
            case 'input':
                $url = $base_url . "sales_request_check2.php?from=mail&title=check&sq_no=".$sq_no;
                break;
            //確認画面の場合承認画面へ移動出来るように設定する
            case 'check':
                $url = $base_url . "sales_request_approve2.php?from=mail&title=approve&sq_no=".$sq_no;
                break;
            //承認画面の場合ルート設定画面へ移動するように設定する
            case 'approve':
                $url = $base_url . "sales_route_input2.php?from=mail&title=set_route&sq_no=".$sq_no;
                break;
        }

        if ($datas) {
            foreach ($datas as $item) {
                //データベースからもらったテキストにclientとsq_no、URLをセットする
                $search = array("client", "sq_no", "sq_line_no");
                $replace = array($from_name, $sq_no, $sq_line_no);
                $subject = str_replace($search, $replace, $mail_details['sq_mail_title']); //subject
                $body = str_replace($search, $replace, $mail_details['sq_mail_sentence']); //body

                switch ($title) {
                    //①営業部内で、入力完了後、sq_default_role にある、確認者（confirmor）へ送信
                    case 'input':
                        $to_email = $item['confirmer_email'];
                        $to_name = $item['confirmer_name'];
                        break;

                    //②営業部内で、確認処理後、sq_default_role にある、承認者（approver）へ送信
                    case 'check':
                        $to_email = $item['approver_email'];
                        $to_name = $item['approver_name'];
                        break;

                    //③営業部内で、承認処理後、sq_route_in_dept の、dept_id = "01"：ルート設定　かつ　role = "0"：受付者 の全員へ送信
                    case 'approve':
                        $to_email = $item['email'];
                        $to_name = $item['email'];
                        break;

                    default:
                        $to_email = '';
                        $to_name = '';
                        break;
                }

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
                echo "<script>window.location.href='$redirect'  </script>";
            }
        } else {
            echo "<script>window.location.href='$redirect'  </script>";
        }
    } catch (PDOException $e) {
        error_log("Error:" . $e->getMessage(), 3, 'error_log.txt');
    }

    /***
     * Employeeデータを取得する
     */
    function get_sq_route_in_dept($dept_id, $user_code) {
        global $pdo;

        $sql = "SELECT r.group_id, r.role, e.employee_name, e.email
                FROM sq_route_in_dept r
                LEFT JOIN employee e 
                ON r.employee_code = e.employee_code
                WHERE r.dept_id = :dept_id AND r.employee_code = :user_code";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':dept_id', $dept_id);
        $stmt->bindParam(':user_code', $user_code);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row;
    }

    /***
     * sq_route_in_deptからデータを取得する
     */
    function get_sq_route_in_dept2() {
        global $pdo;
        $dept_id = '01';//ルート設定
        $role = '0';    //受付者
        $datas = [];
        $sql = "SELECT r.group_id, r.role, e.employee_name, e.email
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

        return $datas;
    }

    /***
     * sq_default_roleからデータを取得する
     */
    function getDefaultRoleUser($dept_id, $group_id, $entrant) {
        global $pdo;
        $datas = [];
        $sql = "SELECT d.confirmer, d.approver, e1.email AS confirmer_email, e1.employee_name AS confirmer_name, e2.email AS approver_email, e2.employee_name AS approver_name
                FROM sq_default_role d
                LEFT JOIN employee e1 ON e1.employee_code = d.confirmer
                LEFT JOIN employee e2 ON e2.employee_code = d.approver
                WHERE dept_id = :dept_id AND group_id = :group_id AND entrant = :entrant";
                
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':dept_id', $dept_id);
        $stmt->bindParam(':group_id', $group_id);
        $stmt->bindParam(':entrant', $entrant);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $datas[] = $row;
        }        

        return $datas;
    }
    
    /***
     * メールの内容を取得する
     */
    function getSqMailSentence() {
        global $pdo;
        global $title;        

        switch ($title) {
            //営業依頼書入力画面の場合
            case 'input':
                $sq_mail_id = '01';
                $seq_no = '1';
                break;
            //営業依頼書確認画面の場合
            case 'check':
                $sq_mail_id = '01';
                $seq_no = '2';
                break;
            //営業依頼書承認画面の場合
            case 'approve':
                $sq_mail_id = '01';
                $seq_no = '3';
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