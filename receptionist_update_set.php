<?php
if(isset($_POST['dept_code'])){$dept_code = $_POST['dept_code'];}else{$dept_code='';}
if(isset($_POST['recept1'])){$receptionist1 = $_POST['recept1'];}else{$receptionist1='';}
if(isset($_POST['recept2'])){$receptionist2 = $_POST['recept2'];}else{$receptionist2='';}
if(isset($_POST['recept3'])){$receptionist3 = $_POST['recept3'];}else{$receptionist3='';}
if(isset($_POST['recept4'])){$receptionist4 = $_POST['recept4'];}else{$receptionist4='';}
if(isset($_POST['recept5'])){$receptionist5 = $_POST['recept5'];}else{$receptionist5='';}
if(isset($_POST['r_com1'])){$r_com1 = $_POST['r_com1'];}else{$r_com1='';}
if(isset($_POST['r_com2'])){$r_com2 = $_POST['r_com2'];}else{$r_com2='';}
if(isset($_POST['r_com3'])){$r_com3 = $_POST['r_com3'];}else{$r_com3='';}
if(isset($_POST['r_com4'])){$r_com4 = $_POST['r_com4'];}else{$r_com4='';}
if(isset($_POST['r_com5'])){$r_com5 = $_POST['r_com5'];}else{$r_com5='';}

$recept = '';
for($i=1; $i<6; $i++){
  //$emp_c = '$'.'receptionist'.$i;
  //if(!empty($emp_c)){
  if(!empty($recept.$i)){
    // 社員マスター
    $sql2 = "SELECT * FROM employee WHERE employee_code = '$recept.$i';";
      $stmt2 = $pdo->prepare($sql2);
      $stmt2->execute();
      if($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)){
        $r_mail.$i = $row2['email'];
        }
  }
}
?>