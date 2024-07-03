<?php
    session_start();
    require_once('function.php');

    // DB接続
    $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
    $success = true;

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
        $process = $_POST['process'];
        $route_id = $_POST['route_id'];
        $route_depts = [];

        for ($i = 1; $i <= 5; $i++) {
            $route_depts[] = $_POST["route{$i}_dept"];
        }

        try {
            $pdo->beginTransaction();

            if ($process == 'create') {
                createRoute($route_id, $route_depts);
            } elseif ($process == 'update') {
                updateRoute($route_id, $route_depts);
            }

            $pdo->commit();
        } catch (PDOException $e) {
            $success = false;
            $pdo->rollback();
            error_log("PDO Exception: " . $e->getMessage(),3,'error_log.txt');
        }

       if ($success) {
            echo "<script>
            window.location.href='sr_route_input1.php';
            </script>";
        } else {
            echo "<script>
            window.location.href='sr_route_input2.php?err=exceErr';
            </script>";
        }
    }

    function createRoute($route_id, $route_depts)
    {
        global $pdo;
        $add_date = date('Y/m/d');
        $sql = "INSERT INTO sq_route (route_id, route1_dept, route2_dept, route3_dept, route4_dept, route5_dept, add_date) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array_merge([$route_id], $route_depts, [$add_date]));
    }

    function updateRoute($route_id, $route_depts)
    {
        global $pdo;
        $upd_date = date('Y/m/d');
        $sql = "UPDATE sq_route SET route1_dept = ?, route2_dept = ?, route3_dept = ?, route4_dept = ?, route5_dept = ?, upd_date = ?  WHERE route_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array_merge($route_depts, [$upd_date,$route_id]));
    }
?>
