<?php
    session_start();
    require_once('function.php');

    // DB接続
    $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

    if (isset($_POST['submit'])) {
        $success = reg_or_upd_sq_class();
        if ($success) {
            echo "<script>
            window.location.href='sq_class_input1.php';
            </script>";
        } else {
            echo "<script>
            window.location.href='sq_class_input2.php?err=exceErr';
            </script>";
        }
    }

    function reg_or_upd_sq_class() {
        global $pdo;
        $today = date('Y/m/d');
        $success = true;

        if (isset($_POST['process'])) {      
            //新規作成or更新の場合
            $process = $_POST['process'];
            try {
                $pdo->beginTransaction();
                if ($process == 'create') {
                //新規作成の場合      
                $data = [
                    'class_code' => $_POST['class_code'],
                    'class_name' => $_POST['class_name'],
                    'add_date' => $today
                ];
                $sql = "INSERT INTO sq_class (class_code, class_name, add_date) VALUES (:class_code, :class_name, :add_date)";
                $stmt = $pdo->prepare($sql);
                } else {
                //更新の場合
                $data = [
                    'class_code' => $_POST['class_code'],
                    'class_name' => $_POST['class_name'],
                    'upd_date' => $today
                ];
                $sql = "UPDATE sq_class SET class_name=:class_name, upd_date=:upd_date WHERE class_code=:class_code";
                $stmt = $pdo->prepare($sql);       
                }

                $stmt->execute($data);
                $pdo->commit();
            } catch (PDOException $e) {
                $success = false;
                $pdo->rollback();
                error_log("PDO Exception: " . $e->getMessage(),3,'error_log.txt');
            }
            return $success;
        }
    }
?>
