<?php
    // 初期処理
    require_once('function.php');
    include('sq_mail_send_select.php');

    // DB接続
    $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
    $group_id = $role = $confirmor_email = $approver_email = $name = '';
    $email_datas = [];
    $success = true;

    //①営業部内で、入力完了後、sq_default_role にある、確認者（confirmor）へ送信
    if (isset($_POST['submit'])) {
        //Employeeデータを取得する
        $userdatas = get_sq_route_in_dept($dept_id, $_POST['user_code']);
        if (!empty($userdatas) && isset($userdatas)) {
            $group_id = $userdatas['group_id'];
            $role = $userdatas['role'];
            $from_name = $userdatas['employee_name'];
            $from_email = $userdatas['email'];
        }
        
        //sq_default_roleからデータを取得する
        $datas = getDefaultRoleUser($dept_id, $group_id);

        if (!empty($datas) && isset($datas)) {
            //メールの内容を取得する
            $mail_details = getSqMailSentence();
            foreach ($datas as $item) {
                //データベースからもらったテキストにclientとsq_noをセットする
                $search = array("client", "sq_no");
                $replace = array($from_name, $sq_no);
                $subject = str_replace($search, $replace, $mail_details['sq_mail_title']); //subject
                $body = str_replace($search, $replace, $mail_details['sq_mail_sentence']); //body
                
                //入力画面の場合
                if ($title == 'input') {
                    $to_email = $item['confirmor_email'];
                    $to_name = $item['confirmor_name'];
                }
                //確認画面の場合
                else if ($title == 'check') {
                    $to_email = $item['confirmor_email'];
                    $to_name = $item['confirmor_name'];
                }
                $email_datas[] = [
                    'to_email' => $item['confirmor_email'],         //送信先email
                    'to_name' => $item['confirmor_name'],           //送信先name
                    'from_email' => $from_email,                    //送信者email
                    'from_name' => $from_name,                    //送信者name
                    'subject' => $subject,    
                    'body' => $body,
                    'sq_no' => $sq_no
                ];
            }

            //メール送信処理を行う
            $success = sendMail($email_datas);
            if ($success) {
                header('location:sales_request_input1.php?sq_no='.$sq_no.'&process=update&title='.$title);
            }
        } else {
            header('location:sales_request_input1.php?sq_no='.$sq_no.'&process=update&title='.$title);
        }
    }

    function get_sq_route_in_dept($dept_id, $user_code) {
        global $pdo;
        global $dept_id;
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

    function getDefaultRoleUser($dept_id, $group_id) {
        global $pdo;
        $datas = [];
        $sql = "SELECT d.confirmor, d.approver, e1.email AS confirmor_email, e1.employee_name AS confirmor_name, e2.email AS approver_email, e2.employee_name AS approver_name
                FROM sq_default_role d
                LEFT JOIN employee e1 ON e1.employee_code = d.confirmor
                LEFT JOIN employee e2 ON e2.employee_code = d.approver
                WHERE dept_id = '$dept_id' AND group_id = '$group_id' ";
                
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $datas[] = $row;
        }        

        return $datas;
    }

    function getSqMailSentence() {
        global $pdo;

        $sq_mail_id = '01';
        $seq_no = '1';
        $sql = "SELECT sq_mail_title, sq_mail_sentence FROM sq_mail_sentence WHERE sq_mail_id = '$sq_mail_id' AND seq_no = '$seq_no'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);        

        return $row;
    }
    
?>