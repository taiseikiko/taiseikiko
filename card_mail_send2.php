<?php
    // 初期処理
    require_once('function.php');
    include('card_mail_send_select.php');
    $url = $_SERVER['HTTP_REFERER']; //メール送信する時、利用するため

    $redirect = './card_input1.php';

    try {
        // DB接続
        $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
        $from_name = $from_email = $to_name = $to_email = '';
        $email_datas = [];
        $success = true;

        //Employeeデータを取得する
        $userdatas = get_employee_datas($user_code);
        if (!empty($userdatas)) {
            $from_name = $userdatas[0]['employee_name'];
            $from_email = $userdatas[0]['email'];
        }

        //card_details_trデータを取得する
        $details_datas = get_card_detail($sq_card_no, $sq_card_line_no);

        //メールの内容を取得する
        $mail_details = getSqMailSentence();
        if (!empty($mail_details)) {
            //データベースからもらったテキストにclientとsq_noをセットする
            $search = array("client", "card_no", "procurement_no", "p_office_no", "zkm_code", "pipe", "sizeA", "specification_no");
            $replace = array($from_name, $sq_card_no, $details_datas['procurement_no'], $details_datas['pf_name'], $details_datas['zkm_name'], 
                        $details_datas['pipe'], $details_datas['sizeA'], $details_datas['specification_no']);
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

        //URLを設定する
        //承認以外の場合
        if ($page !== '承認') {
            $url = $base_url . '/card_input3.php?sq_card_no=' . $sq_card_no . '&sq_card_line_no=' . $sq_card_line_no;
        } else {
            $url = $base_url . '/card_input2.php?card_no=' . $sq_card_no;
        } 
        

        //送信内容をセットする
        $email_datas = [
            'from_email' => $from_email,     //送信者email
            'from_name' => $from_name,       //送信者name
            'subject' => $subject,    
            'body' => $body,
            'sq_card_no' => $sq_card_no,
            'url' => $url
        ];

        //送信先のデータを取得する
        $to_datas = get_mail_recipient_datas();

        if (!empty($to_datas) && isset($to_datas)) {
            // メール送信処理を行う
            $success = sendMail($email_datas, $to_datas);
            if ($success) {
                echo "<script>window.location.href='$redirect'  </script>";
            } else {
                echo "<script>window.location.href='card_input2.php?err=exceErr'</script>";
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
    function get_employee_datas($user_code) {
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
     * 送信先のデータを取得する
     */
    function get_mail_recipient_datas() {
        global $pdo;
        global $entrant;
        global $page;

        //受付者が担当者を選択したときに、その担当者（card_detail_trのentrant）へ送信
        if ($page == '受付') {
            if ($entrant !== '') {
                $datas = get_employee_datas($entrant);
            }
        }

        //技術部、工事技術部の担当者が入力した後に、その担当者の承認ルートを見て確認者（confirmor）へメール送信
        else if ($page == '入力') {
            $column = 'confirmer';
            $datas = get_mail_receiver_from_sq_default_role($column);
        }

        //技術部、工事技術部の確認者が確認した後に、その担当者の承認ルートを見て承認者（approver）へメール送信
        else if ($page == '確認') {
            $column = 'approver';
            $datas = get_mail_receiver_from_sq_default_role($column);
        }

        //技術部、工事技術部の承認者が承認した後に、今まで経由した全員にメール送信
        else {
            $datas = get_all_user();
        }

        return $datas;
    }

    /***
     * メールの内容を取得する
     */
    function getSqMailSentence() {
        global $pdo;
        global $page;

        $sq_mail_id = '08';

        switch ($page) {
            case '受付':
                $seq_no = '1';
                break;

            case '入力':
                $seq_no = '2';
                break;

            case '確認':
                $seq_no = '3';
                break;

            case '承認':
                $seq_no = '4';
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

    /***
     * 送信先をsq_default_roleテーブルから取得する
     */
    function get_mail_receiver_from_sq_default_role($column) {
        global $pdo;
        global $entrant;
        $datas = [];
        $department_codes = ['02', '03'];

        foreach ($department_codes as $department_code) {
            $sql = "SELECT e.employee_name, e.email
                FROM sq_default_role d
                LEFT JOIN employee e
                ON d.$column = e.employee_code
                WHERE d.dept_id = :dept_id AND d.entrant = :entrant";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':dept_id', $department_code);
            $stmt->bindParam(':entrant', $entrant);
            $stmt->execute();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $datas[] = $row;
            }
        }        

        return $datas;
    }

    /***
     * 今まで経由した全員のメールをcard_detail_trテーブルから取得する
     */
    function get_all_user() {
        global $pdo;
        global $sq_card_no;
        global $sq_card_line_no;
        $emailList = [];

        $sql = "SELECT e1.email AS client_email, e2.email AS email_procurement_approver_email, e3.email AS entrant_email, e4.email AS confirmer_email, e5.email AS approver_email
            FROM card_header_tr h
            LEFT JOIN card_detail_tr d ON h.card_no = d.sq_card_no AND d.sq_card_line_no = '$sq_card_line_no'
            LEFT JOIN employee e1 ON e1.employee_code = h.client
            LEFT JOIN employee e2 ON e2.employee_code = h.procurement_approver
            LEFT JOIN employee e3 ON e3.employee_code = d.entrant
            LEFT JOIN employee e4 ON e4.employee_code = d.confirmer
            LEFT JOIN employee e5 ON e5.employee_code = d.approver
            WHERE d.sq_card_no = '$sq_card_no' AND d.sq_card_line_no = '$sq_card_line_no'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!empty($row)) {
            $emails = ['client_email', 'email_procurement_approver_email', 'entrant_email', 'confirmer_email', 'approver_email'];
            $i = 0;
            foreach ($emails as $email) {
                $emailList[$i]['email'] = $row[$email];
                $i++;
            }
        }

        return $emailList;
    }

      /**
     * card_detail_trテーブルから取得する
     */
    function get_card_detail($card_no, $card_line_no)
    {
        global $pdo;

        $sql = "SELECT pf.pf_name, d.procurement_no, z.zkm_name, d.pipe, d.sizeA, d.specification_no 
                FROM card_header_tr h
                LEFT JOIN card_detail_tr d ON h.card_no = d.sq_card_no AND d.sq_card_line_no=:sq_card_line_no 
                LEFT JOIN public_office pf ON pf.pf_code = h.p_office_no
                LEFT JOIN sq_zaikoumei z ON z.class_code = d.class_code AND z.zkm_code = d.zkm_code
                WHERE sq_card_no = :sq_card_no";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':sq_card_no', $card_no);
        $stmt->bindParam(':sq_card_line_no', $card_line_no);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }
    
?>