<?php
    // 初期処理
    require_once('function.php');
    include('sq_mail_send_select.php');
    $url = $_SERVER['HTTP_REFERER']; //メール送信する時、利用するため    

    try {
        // DB接続
        $from_name = $from_email = $to_name = '';
        $email_datas = [];        

        //Employeeデータを取得する
        $userdatas = get_employee_datas($user_code);
        if ($userdatas) {
            $from_name = $userdatas['employee_name'];
            $from_email = $userdatas['email'];
        }

        //メールの内容を取得する
        $mail_details = getSqMailSentence();
        if (!empty($mail_details)) {
            //データベースからもらったテキストにclientとsq_no、URLをセットする
            $search = array("client", "sq_no", "restoration_comments");
            $replace = array($from_name, $sq_no, $restoration_comments);
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
            'sq_no' => $sq_no,
            'url' => $url
        ];

        //sq_route_mail_trを読む
        $route_mail_datas = get_sq_route_mail_datas();

        if ($route_mail_datas) {
            foreach ($route_mail_datas as $item) {
                $url = $base_url . $item['url'];
                $email_datas['url'] = $url;
            }

            //メール送信処理を行う
            $success_mail = sendMail($email_datas, $route_mail_datas);
        } else {
            $success_mail = false;
        }

    } catch (PDOException $e) {
        $success_mail = false;
        error_log("Error:" . $e->getMessage(), 3, 'error_log.txt');
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
    function get_sq_route_mail_datas() {
        global $return_dept;    //差し戻し先
        global $dept_id;        //ログインユーザーの部署
        global $title;
        global $route_pattern;
        global $sq_no;
        global $sq_line_no;
        global $pdo;
        $sq_route_mail = [];
        $sq_header_tr = [];
        $send_mail_datas = [];
        $s_title = substr($title, 0, 2);
        $e_title = substr($title, 3);

        //sq_route_mail_tr に存在しない差し戻し先の場合
        //sq_header_tr の、依頼者（client）・確認者（confirmor）・承認者（approver）　へ送信
        /*-------------------------------------------------------開始---------------------------------------------------------------------- */
        $sql = "SELECT e1.employee_name AS client_name, e1.email AS client_email, h.confirmer,
                e2.employee_name AS confirmer_name, e2.email AS confirmer_email, 
                e3.employee_name AS approver_name, e3.email AS approver_email
                FROM sq_header_tr h
                LEFT JOIN employee e1 ON e1.employee_code = h.client
                LEFT JOIN employee e2 ON e2.employee_code = h.confirmer
                LEFT JOIN employee e3 ON e3.employee_code = h.approver
                WHERE h.sq_no='$sq_no'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $sq_header_tr = $stmt->fetch(PDO::FETCH_ASSOC);
        /*-------------------------------------------------------完了---------------------------------------------------------------------- */
 
        //sq_route_mail_tr に存在する差し戻し先の場合
        //sq_route_mail_tr を自部署で検索し、自部署以前の全ての処理者へ送信
        //差し戻し先までに存在する全ての担当者（受付者（reception）・入力者（entrant）・確認者（confirmor）・承認者（approver））へ送信
        /*-------------------------------------------------------開始---------------------------------------------------------------------- */
        $start = false;
        $end = false;
        $sql1 = "SELECT * FROM sq_route_mail_tr WHERE route_id='$route_pattern' AND sq_no='$sq_no' AND sq_line_no='$sq_line_no'";
        $stmt1 = $pdo->prepare($sql1);
        $stmt1->execute();
        $sq_route_mail_tr = $stmt1->fetch(PDO::FETCH_ASSOC);
        
        if (isset($sq_route_mail_tr) && !empty($sq_route_mail_tr)) {
            foreach ($sq_route_mail_tr as $item) {
                for ($i = 0; $i < 5; $i ++) {
                    $x = $i+1;
                    if ($sq_route_mail_tr['route'.$x.'_dept'] == $return_dept || $return_dept == '00') {
                        $filter_datas[$i]['route'] = $x;
                        $indexs = ['dept', 'receipt_ad', 'entrant_ad', 'confirmer_ad', 'approver_ad'];
                        foreach ($indexs as $index) {
                            $filter_datas[$i][$index] = $sq_route_mail_tr['route'.$x.'_'.$index];
                        }
                        $start = true;
                    } 
                    if ($sq_route_mail_tr['route'.$x.'_dept'] == $dept_id) {
                        $filter_datas[$i]['route'] = $x;
                        $indexs = ['dept', 'receipt_ad', 'entrant_ad', 'confirmer_ad', 'approver_ad'];
                        foreach ($indexs as $index) {
                            $filter_datas[$i][$index] = $sq_route_mail_tr['route'.$x.'_'.$index];
                        }
                        $end = true;
                        break;
                    }
                    if ($start && !($end)) {
                        $filter_datas[$i]['route'] = $x;
                        $indexs = ['dept', 'receipt_ad', 'entrant_ad', 'confirmer_ad', 'approver_ad'];
                        foreach ($indexs as $index) {
                            $filter_datas[$i][$index] = $sq_route_mail_tr['route'.$x.'_'.$index];
                        }
                    }
                    $x++;
                }          
            }
            //array keyをrearrangeする
            $sq_route_mail = array_values($filter_datas);
        }
        /*-------------------------------------------------------完了---------------------------------------------------------------------- */

        //営業部画面の場合
        if ($title == 'check' || $title == 'approve') {
            //sq_header_trからclient、確認者と承認者へ送信
            if (!empty($sq_header_tr) && isset($sq_header_tr)) {
                $i = 0;
                //メール送信する時、渡すURLを設定する
                //登録画面へ移動する
                $url = 'sales_request_input2.php?from=mail&title=input&sq_no=' . $sq_no;
                //確認画面の場合
                if ($title == 'check') {
                    //clientへメール送信する
                    $send_mail_datas[$i]['email'] = $sq_header_tr['client_email'];
                    $send_mail_datas[$i]['url'] = $url;
                } 
                //承認画面の場合
                else {
                    $indexs = ['client_email', 'confirmer_email'];
                    //確認者とclientへメール送信する
                    foreach ($indexs as $index) {
                        $send_mail_datas[$i]['email'] = $sq_header_tr[$index];
                        $send_mail_datas[$i]['url'] = $url;
                        $i++;
                    }
                }
            }
        }
        //その他の部署の画面の場合
        else {
            //営業部へ差し戻しする場合
            if ($return_dept == '00') {
                $i = 0;
                //メール送信する時、渡すURLを設定する
                //登録画面へ移動する
                $url = 'sales_request_input2.php?from=mail&title=input&sq_no=' . $sq_no;
                //自部署以前の全ての担当者へ送信する
                foreach ($sq_route_mail as $item) {
                    $indexs = ['receipt_ad', 'entrant_ad', 'confirmer_ad', 'approver_ad'];
                    foreach ($indexs as $index) {
                        if ($item[$index] !== '' && $item[$index] !== NULL) {
                            $send_mail_datas[$i]['email'] = $item[$index];
                            $send_mail_datas[$i]['url'] = $url;
                            $i++;
                        }
                    }
                }
                //client、確認者と承認者へも送信する
                if (!empty($sq_header_tr) && isset($sq_header_tr)) {
                    $indexs = ['client_email', 'confirmer_email', 'approver_email'];
                    //承認画面の場合、確認者とclientへメール送信する
                    foreach ($indexs as $index) {
                        $send_mail_datas[$i]['email'] = $sq_header_tr[$index];
                        $send_mail_datas[$i]['url'] = $url;
                        $i++;
                    }
                }
            } else {
                //メール送信する時、渡すURLを設定する
                //各部署の入力画面へ移動する
                $redirectList = [
                    '02' => 'sq_detail_tr_engineering_input2.php?from=mail&title=' .$s_title . '_entrant&sq_no=' . $sq_no,        //技術部
                    '05' => 'sq_detail_tr_sales_management_input2.php?from=mail&title=' . $s_title .'_entrant&sq_no=' . $sq_no,   //営業管理部
                    '06' => 'sq_detail_tr_const_management_input2.php?from=mail&title=' . $s_title .'_entrant&sq_no=' . $sq_no,   //工事管理部
                    '04' => 'sq_detail_tr_procurement_input2.php?from=mail&title=' . $s_title .'_entrant&sq_no=' . $sq_no         //資材部
                ];

                $url = $redirectList[$return_dept];
                //部署内での差し戻しの場合
                if ($return_dept == $dept_id) {
                    $i = 0;
                    
                    //sq_route_mail_tr の、同じ部署の差し戻し先へ送信
                    foreach ($sq_route_mail as $item) {
                        //承認者の場合、
                        if ($e_title == 'approve') {                            
                            //確認者（confirmor）　と　入力者（entrant）　へ送信
                            $indexs = ['entrant_ad', 'confirmer_ad'];
                            foreach ($indexs as $index) {
                                $send_mail_datas[$i]['email'] = $item[$index];
                                $send_mail_datas[$i]['url'] = $url;
                                $i++;
                            }
                        }
                        //確認者の場合、
                        else if ($e_title == 'confirm') {
                            //入力者（entrant）　へ送信
                            $send_mail_datas[$i]['email'] = $item['entrant_ad'];
                            $send_mail_datas[$i]['url'] = $url;
                        }
                    }
                }
                //（営業部以外）他部署への差し戻しの場合
                else {
                    $i = 0;
                    //自部署以前の全ての担当者へ送信する
                    foreach ($sq_route_mail as $item) {
                        $indexs = ['receipt_ad', 'entrant_ad', 'confirmer_ad', 'approver_ad'];
                        foreach ($indexs as $index) {
                            if ($item[$index] !== '' && $item[$index] !== NULL) {
                                $send_mail_datas[$i]['email'] = $item[$index];
                                $send_mail_datas[$i]['url'] = $url;
                                $i++;
                            }                            
                        }
                    }
                }
            }
        }
        return $send_mail_datas;
    }
    
    /***
     * メールの内容を取得する
     */
    function getSqMailSentence() {
        global $pdo;
        global $title;        

        $sq_mail_id = '04';
        $seq_no = '1';

        $sql = "SELECT sq_mail_title, sq_mail_sentence FROM sq_mail_sentence WHERE sq_mail_id = :sq_mail_id AND seq_no = :seq_no";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':sq_mail_id', $sq_mail_id);
        $stmt->bindParam(':seq_no', $seq_no);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);        

        return $row;
    }

    /**
     * 現在の部署までの部署リストをsq_route_mail_trから取得する
     */
    function getDeptList() {
        global $return_dept;    //差し戻し先
        global $dept_id;        //ログインユーザーの部署
        global $route_pattern;
        global $sq_no;
        global $sq_line_no;
        global $pdo;
        $filter_datas = [];
        $start = false;
        $end = false;

        $sql1 = "SELECT * FROM sq_route_mail_tr WHERE route_id='$route_pattern' AND sq_no='$sq_no' AND sq_line_no='$sq_line_no'";
        $stmt1 = $pdo->prepare($sql1);
        $stmt1->execute();
        $sq_route_mail_tr = $stmt1->fetch(PDO::FETCH_ASSOC);
            
        if (isset($sq_route_mail_tr) && !empty($sq_route_mail_tr)) {
            foreach ($sq_route_mail_tr as $item) {
                for ($i = 0; $i < 5; $i ++) {
                    $x = $i+1;
                    if ($sq_route_mail_tr['route'.$x.'_dept'] == $return_dept) {
                        $filter_datas[$i]['route'] = $x;
                        $filter_datas[$i]['dept'] = $sq_route_mail_tr['route'.$x.'_dept'];
                        $filter_datas[$i]['receipt_ad'] = $sq_route_mail_tr['route'.$x.'_receipt_ad'];
                        $filter_datas[$i]['entrant_ad'] = $sq_route_mail_tr['route'.$x.'_entrant_ad'];
                        $filter_datas[$i]['confirmer_ad'] = $sq_route_mail_tr['route'.$x.'_confirmer_ad'];
                        $filter_datas[$i]['approver_ad'] = $sq_route_mail_tr['route'.$x.'_approver_ad'];
                        $start = true;
                    } 
                    if ($sq_route_mail_tr['route'.$x.'_dept'] == $dept_id) {
                        $filter_datas[$i]['route'] = $x;
                        $filter_datas[$i]['dept'] = $sq_route_mail_tr['route'.$x.'_dept'];
                        $filter_datas[$i]['receipt_ad'] = $sq_route_mail_tr['route'.$x.'_receipt_ad'];
                        $filter_datas[$i]['entrant_ad'] = $sq_route_mail_tr['route'.$x.'_entrant_ad'];
                        $filter_datas[$i]['confirmer_ad'] = $sq_route_mail_tr['route'.$x.'_confirmer_ad'];
                        $filter_datas[$i]['approver_ad'] = $sq_route_mail_tr['route'.$x.'_approver_ad'];
                        $end = true;
                        break;
                    }
                    if ($start && !($end)) {
                        $filter_datas[$i]['route'] = $x;
                        $filter_datas[$i]['dept'] = $sq_route_mail_tr['route'.$x.'_dept'];
                        $filter_datas[$i]['receipt_ad'] = $sq_route_mail_tr['route'.$x.'_receipt_ad'];
                        $filter_datas[$i]['entrant_ad'] = $sq_route_mail_tr['route'.$x.'_entrant_ad'];
                        $filter_datas[$i]['confirmer_ad'] = $sq_route_mail_tr['route'.$x.'_confirmer_ad'];
                        $filter_datas[$i]['approver_ad'] = $sq_route_mail_tr['route'.$x.'_approver_ad'];
                    }
                    $x++;
                }          
            }
            //array keyをrearrangeする
            $filter_datas = array_values($filter_datas); 
        }

        return $filter_datas;
    }
    
?>