<?php
  // 初期処理
  require_once('function.php');
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

  if (isset($_POST['submit'])) {
    $success = reg_or_upd_sq_zkm();
    if ($success) {
      echo "<script>
        window.location.href='sq_zkm_input1.php';
      </script>";
    } else {
      echo "<script>
        window.location.href='sq_zkm_input2.php?err=execErr';
      </script>";
    }
  }
  function reg_or_upd_sq_zkm() {
    $success = true;
    global $pdo;

    if (isset($_POST['process'])) {
      try {
        $pdo->beginTransaction();
        //新規作成or更新の場合
        $process = $_POST['process'];

        $data = [
          'class_code' => $_POST['class_code'],
          'zkm_code' => $_POST['zkm_code'],
          'zkm_name' => $_POST['zkm_name'],
          'size' => $_POST['size'],
          'joint' => $_POST['joint'],
          'pipe' => $_POST['pipe'],
          'inner_coating' => $_POST['inner_coating'],
          'outer_coating' => $_POST['outer_coating'],
          'fluid' => $_POST['fluid'],
          'valve' => $_POST['valve'],
          'o_c_direction' => $_POST['o_c_direction'],
          'c_div' => $_POST['c_div']
        ];
        
        if ($process == 'create') {
          //新規作成の場合
          $sql = "INSERT INTO sq_zaikoumei (class_code, zkm_code, zkm_name, size, joint, pipe, inner_coating, outer_coating, fluid, valve, o_c_direction, c_div) 
          VALUES (:class_code, :zkm_code, :zkm_name, :size, :joint, :pipe, :inner_coating, :outer_coating, :fluid, :valve, :o_c_direction, :c_div)";
          $stmt = $pdo->prepare($sql);
        } else {
          //更新の場合
          $sql = "UPDATE sq_zaikoumei SET zkm_name=:zkm_name, size=:size, joint=:joint, pipe=:pipe, 
          inner_coating=:inner_coating, outer_coating=:outer_coating, fluid=:fluid, valve=:valve, o_c_direction=:o_c_direction, c_div=:c_div 
          WHERE class_code=:class_code AND zkm_code=:zkm_code";
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