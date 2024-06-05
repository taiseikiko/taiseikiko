<?php
  session_start();
  require_once('function.php');
  $today = date('Y/m/d');
  $year = substr($today,0,4);
  $month = substr($today,5,2);
  $entry_person = $_SESSION["login"];
  $ym = $year.$month;

/*
echo "code_id=".$code_id."<br>";
echo "item_size".$item_size."<br>";
*/
//echo "SESSION_mode=".$_SESSION['mode'];

// 戻る処理
  if(isset($_POST['return'])){
    header("Location:receptionist_input.php");                
    exit();
  }

// DB接続
    $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

// パラメーターセット
  include('receptionist_update_set.php');

//error_log('inq_no='.$inq_no."\n",3,'error_log.txt');
    $sql1 = "SELECT * FROM receptionist WHERE dept_code = '$dept_code';";
      $stmt1 = $pdo->prepare($sql1);
      $stmt1->execute();
    IF($row = $stmt1->fetch(PDO::FETCH_ASSOC)){

// 更新処理
        $sql = "UPDATE receptionist SET
          dept_code=:dept_code,receptionist1=:receptionist1,receptionist2=:receptionist2,
          receptionist3=:receptionist3,receptionist4=:receptionist4,receptionist5=:receptionist5,
          r_com1=:r_com1,r_com2=:r_com2,r_com3=:r_com3,
          r_com4=:r_com4,r_com5=:r_com5,r_mail1=:r_mail1,
          r_mail2=:r_mail2,r_mail3=:r_mail3,r_mail4=:r_mail4,
          r_mail5=:r_mail5,upd_date=:upd_date
          WHERE dept_code=:dept_code;";

        $stmt = $pdo->prepare($sql);
          $stmt->bindParam(':dept_code',$dept_code,PDO::PARAM_STR);
          $stmt->bindParam(':receptionist1',$receptionist1,PDO::PARAM_STR);
          $stmt->bindParam(':receptionist2',$receptionist2,PDO::PARAM_STR);
          $stmt->bindParam(':receptionist3',$receptionist3,PDO::PARAM_STR);
          $stmt->bindParam(':receptionist4',$receptionist4,PDO::PARAM_STR);
          $stmt->bindParam(':receptionist5',$receptionist5,PDO::PARAM_STR);
          $stmt->bindParam(':r_com1',$r_com1,PDO::PARAM_STR);
          $stmt->bindParam(':r_com2',$r_com2,PDO::PARAM_STR);
          $stmt->bindParam(':r_com3',$r_com3,PDO::PARAM_STR);
          $stmt->bindParam(':r_com4',$r_com4,PDO::PARAM_STR);
          $stmt->bindParam(':r_com5',$r_com5,PDO::PARAM_STR);
          $stmt->bindParam(':r_mail1',$r_mail1,PDO::PARAM_STR);
          $stmt->bindParam(':r_mail2',$r_mail2,PDO::PARAM_STR);
          $stmt->bindParam(':r_mail3',$r_mail3,PDO::PARAM_STR);
          $stmt->bindParam(':r_mail4',$r_mail4,PDO::PARAM_STR);
          $stmt->bindParam(':r_mail5',$r_mail5,PDO::PARAM_STR);
          $stmt->bindParam(':upd_date',$upd_date,PDO::PARAM_STR);
          $stmt->execute();
      }

    else{
// 登録処理

        $stmt = $pdo->prepare("INSERT INTO receptionist (
          dept_code,receptionist1,receptionist2,receptionist3,receptionist4,
          receptionist5,r_com1,r_com2,r_com3,r_com4,
          r_com5,r_mail1,r_mail2,r_mail3,r_mail4,
          r_mail5,add_date)
          VALUES(
          :dept_code,:receptionist1,:receptionist2,:receptionist3,:receptionist4,
          :receptionist5,:r_com1,:r_com2,:r_com3,:r_com4,
          :r_com5,:r_mail1,:r_mail2,:r_mail3,:r_mail4,
          :r_mail5,:add_date)");

          $stmt->bindParam(':dept_code',$dept_code,PDO::PARAM_STR);
          $stmt->bindParam(':receptionist1',$receptionist1,PDO::PARAM_STR);
          $stmt->bindParam(':receptionist2',$receptionist2,PDO::PARAM_STR);
          $stmt->bindParam(':receptionist3',$receptionist3,PDO::PARAM_STR);
          $stmt->bindParam(':receptionist4',$receptionist4,PDO::PARAM_STR);
          $stmt->bindParam(':receptionist5',$receptionist5,PDO::PARAM_STR);
          $stmt->bindParam(':r_com1',$r_com1,PDO::PARAM_STR);
          $stmt->bindParam(':r_com2',$r_com2,PDO::PARAM_STR);
          $stmt->bindParam(':r_com3',$r_com3,PDO::PARAM_STR);
          $stmt->bindParam(':r_com4',$r_com4,PDO::PARAM_STR);
          $stmt->bindParam(':r_com5',$r_com5,PDO::PARAM_STR);
          $stmt->bindParam(':r_mail1',$r_mail1,PDO::PARAM_STR);
          $stmt->bindParam(':r_mail2',$r_mail2,PDO::PARAM_STR);
          $stmt->bindParam(':r_mail3',$r_mail3,PDO::PARAM_STR);
          $stmt->bindParam(':r_mail4',$r_mail4,PDO::PARAM_STR);
          $stmt->bindParam(':r_mail5',$r_mail5,PDO::PARAM_STR);
          $stmt->bindParam(':add_date',$add_date,PDO::PARAM_STR);
          $stmt->execute();
      }

    header("Location:receptionist_input.php");                
    exit();

?>