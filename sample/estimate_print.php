<?php
require_once('TCPDF/tcpdf.php');
require_once('TCPDF/FPDI/autoload.php');
require_once('function.php');

$pdf = new setasign\Fpdi\Tcpdf\Fpdi();

$pdf->setPrintHeader( false );

/** ページ基本設定 P:縦 L:横 */
//$pdf->setPageOrientation("P", true, 10);

// A4サイズのPDF文書を準備
//$pdf = new PDF_Japanese('P', 'mm', 'A4');
//$pdf->AddSJISFont();
$pdf->setSourceFile("PDF/estimate.pdf");
$pdf->AddPage("P","A4");
$tpl = $pdf->importPage(1);
$pdf->useTemplate($tpl, 0, 0);

//$pdf->SetFont('times', スタイル, サイズ);
//$pdf->Text(x座標, y座標, テキスト);
$estimate_no = "";
  try {
    // DB接続
    $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
  } catch (PDOException $e) {
    die($e->getMessage());
  }

 if(!empty($_POST['estimate_no'])){
    $x = key($_POST['estimate_no']);  
    $estimate_no = $_POST['estimate_no'][$x];
 }

// estimate
  // 見積ヘッダー
  $sql1 = "SELECT * FROM estimate WHERE estimate_no = '$estimate_no';";
    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute();
      if($row = $stmt1->fetch(PDO::FETCH_ASSOC)){
        $item_no = $row['item_no'];
        $customer_no = $row['customer_no'];
        $coating_ex = $row['coating_ex'];
        $coating_in = $row['coating_in'];
        $gasket = $row['gasket'];
        $spacer = $row['spacer'];
        $by_air_or_sea = $row['by_air_or_sea'];
        $gasket_fee = $row['gasket_fee'];
        $spacer_fee = $row['spacer_fee'];
        $add_amount_text = $row['add_amount_text'];
        $add_amount = $row['add_amount'];
        $total_amount = $row['total_amount'];
        $cost_amount = $row['cost_amount'];
        $gross_profit = $row['gross_profit'];
        $transit_time = $row['transit_time'];
        $packing_size = $row['packing_size'];
        $lead_time = $row['production_lead_time'];
        $currency = $row['currency'];
        $valid_date = $row['valid_date'];
        $payment_terms = $row['payment_terms'];
        $discount_rate = $row['discount_rate'];
        $add_date = $row['add_date'];
        $delivery_term1 = $row['delivery_term1'];
        $delivery_term2 = $row['delivery_term2'];
      }

  // Customer master
  $sql1 = "SELECT * FROM customer WHERE customer_no = '$customer_no';";
    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute();
      if($row = $stmt1->fetch(PDO::FETCH_ASSOC)){
        $customer_nm = $row['customer_nm'];
      }

  // Item Header master
  $sql1 = "SELECT * FROM item_header_master WHERE item_no = '$item_no';";
    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute();
      if($row = $stmt1->fetch(PDO::FETCH_ASSOC)){
        $item_nm = $row['item_nm'];
        $description = $row['description'];
      }

    // コーディングデータセット select box
    $key1 = "coating_external";
    $key2 = $coating_ex;
    //code_master用 SQL 作成
    $sql1 = "SELECT * FROM code_master WHERE code_id = '$key1' AND code_no = '$key2';";
    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute();
        if($row1 = $stmt1->fetch(PDO::FETCH_ASSOC)){
            $coating_ex_nm = $row1['text1'];
        }

    $key1 = "coating_internal";
    $key2 = $coating_in;
    //code_master用 SQL 作成
    $sql1 = "SELECT * FROM code_master WHERE code_id = '$key1' AND code_no = '$key2';";
    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute();
        if($row1 = $stmt1->fetch(PDO::FETCH_ASSOC)){
            $coating_in_nm = $row1['text1'];
        }

    $key1 = "gasket";
    $key2 = $gasket;
    //code_master用 SQL 作成
    $sql1 = "SELECT * FROM code_master WHERE code_id = '$key1' AND code_no = '$key2';";
    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute();
        if($row1 = $stmt1->fetch(PDO::FETCH_ASSOC)){
            $gasket_nm = $row1['text1'];
        }

    $key1 = "spacer";
    $key2 = $spacer;
    //code_master用 SQL 作成
    $sql1 = "SELECT * FROM code_master WHERE code_id = '$key1' AND code_no = '$key2';";
    $stmt1 = $pdo->prepare($sql1);
    $stmt1->execute();
        if($row1 = $stmt1->fetch(PDO::FETCH_ASSOC)){
            $spacer_nm = $row1['text1'];
        }


// Estimate明細 
  // Estimate detail
  $sql2 = "SELECT * FROM estimate_detailes WHERE estimate_no = '$estimate_no' ;";
  $stmt2 = $pdo->prepare($sql2);
  $stmt2->execute();

    $i=0;
    WHILE($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)){
        $i = $row2['line_no'] -1;
        $item_size[$i] = $row2['item_size'];
        $quantity[$i] = $row2['quantity'];
        $unit_price[$i] = $row2['unit_price'];
        $gasket_s_price[$i] = $row2['gasket_price'];
        $spacer_s_price[$i] = $row2['spacer_price'];
        $line_amount[$i] = $row2['line_amount'];
        $line_total_amount[$i] = $row2['line_total_amount'];
        $gasket_amount[$i] = $row2['gasket_amount'];
        $spacer_amount[$i] = $row2['spacer_amount'];
        $cost_price[$i] = $row2['cost_price'];
        $gasket_cost_amount[$i] = $row2['gasket_cost_amount'];
        $spacer_cost_amount[$i] = $row2['spacer_cost_amount'];
        //$cost_amount[$i] = $row2['cost_amount'];
        $total_cost_amount[$i] = $row2['total_cost_amount'];
        $delivery_schedule[$i] = $row2['delivery_schedule'];
        //$gross_profit[$i] = $row2['gross_profit'];

  // Item Detail master
  $sql3 = "SELECT * FROM item_detail_master WHERE item_no = '$item_no' AND item_size = '$item_size[$i]';";
    $stmt3 = $pdo->prepare($sql3);
    $stmt3->execute();
      if($row3 = $stmt3->fetch(PDO::FETCH_ASSOC)){
        $subsidence[$i] = $row3['subsidence'];
      }
        $i++;
    }




//日付
$pdf->SetFont('times', '', 11);
$pdf->Text(173, 21, $add_date);

//No.
$pdf->SetFont('times', '', 15);
$pdf->Text(59, 29, htmlspecialchars( $estimate_no ) );

//得意先名
$pdf->SetFont('times', '', 16);
$pdf->Text(105, 29, htmlspecialchars( $customer_nm ) );

//品名
$pdf->SetFont('times', '', 16);
$pdf->Text(44, 39, htmlspecialchars( $item_nm ) );

//Subsidence
$pdf->SetFont('times', '', 11);
$pdf->Text(44, 50, htmlspecialchars( $description ) );

//Coating External
$pdf->SetFont('times', '', 14);
$pdf->Text(44, 59, htmlspecialchars( $coating_ex_nm ),0 );

//Subsidence
$pdf->SetFont('times', '', 14);
$pdf->Text(44, 66, htmlspecialchars( $coating_in_nm ) );

//Gasket
$pdf->SetFont('times', '', 14);
$pdf->Text(44, 76, htmlspecialchars( $gasket_nm ) );

//Spacer
$pdf->SetFont('times', '', 14);
$pdf->Text(132, 76, htmlspecialchars( $spacer_nm ) );

// 明細レコード処理
// 1行目
if($item_size[0]){
//item_size
$pdf->SetFont('times', '', 12);
$pdf->Text(35, 96, htmlspecialchars( $item_size[0] ) );
//item_size
$pdf->SetFont('times', '', 12);
$pdf->Text(50, 96, htmlspecialchars( $subsidence[0] ) );
//quantity
$pdf->SetFont('times', '', 12);
$qty[0] = number_format($quantity[0]);
$pdf->MultiCell(19, 5, htmlspecialchars( $qty[0] ),0,'R',0,0,73,96 );
//unit_price
$pdf->SetFont('times', '', 12);
$price[0] = number_format($unit_price[0]);
$pdf->MultiCell(28, 5, htmlspecialchars( $price[0] ),0,'R',0,0,93,96 );
//line_amount
$pdf->SetFont('times', '', 12);
$l_amount[0] = number_format($line_amount[0]);
$pdf->MultiCell(37, 5, htmlspecialchars( $l_amount[0] ),0,'R',0,0,124,96 );
//delivery_schedule
$pdf->SetFont('times', '', 12);
$pdf->Text(164, 96, htmlspecialchars( $delivery_schedule[0] ) );
}
// 2行目
if($item_size[1]){
//item_size
$pdf->SetFont('times', '', 12);
$pdf->Text(35, 104, htmlspecialchars( $item_size[1] ) );
//item_size
$pdf->SetFont('times', '', 12);
$pdf->Text(50, 104, htmlspecialchars( $subsidence[1] ) );
//quantity
$pdf->SetFont('times', '', 12);
$qty[1] = number_format($quantity[1]);
$pdf->MultiCell(19, 5, htmlspecialchars( $qty[1] ),0,'R',0,0,73,104 );
//unit_price
$pdf->SetFont('times', '', 12);
$price[1] = number_format($unit_price[1]);
$pdf->MultiCell(28, 5, htmlspecialchars( $price[1] ),0,'R',0,0,93,104 );
//line_amount
$pdf->SetFont('times', '', 12);
$l_amount[1] = number_format($line_amount[1]);
$pdf->MultiCell(37, 5, htmlspecialchars( $l_amount[1] ),0,'R',0,0,124,104 );
//delivery_schedule
$pdf->SetFont('times', '', 12);
$pdf->Text(164, 104, htmlspecialchars( $delivery_schedule[1] ) );
}
// 3行目
if($item_size[2]){
//item_size
$pdf->SetFont('times', '', 12);
$pdf->Text(35, 111, htmlspecialchars( $item_size[2] ) );
//item_size
$pdf->SetFont('times', '', 12);
$pdf->Text(50, 111, htmlspecialchars( $subsidence[2] ) );
//quantity
$pdf->SetFont('times', '', 12);
$qty[2] = number_format($quantity[2]);
$pdf->MultiCell(19, 5, htmlspecialchars( $qty[2] ),0,'R',0,0,73,111 );
//unit_price
$pdf->SetFont('times', '', 12);
$price[2] = number_format($unit_price[2]);
$pdf->MultiCell(28, 5, htmlspecialchars( $price[2] ),0,'R',0,0,93,111 );
//line_amount
$pdf->SetFont('times', '', 12);
$l_amount[2] = number_format($line_amount[2]);
$pdf->MultiCell(37, 5, htmlspecialchars( $l_amount[2] ),0,'R',0,0,124,111 );
//delivery_schedule
$pdf->SetFont('times', '', 12);
$pdf->Text(164, 111, htmlspecialchars( $delivery_schedule[2] ) );
}
// 4行目
if($item_size[3]){
//item_size
$pdf->SetFont('times', '', 12);
$pdf->Text(35, 119, htmlspecialchars( $item_size[3] ) );
//item_size
$pdf->SetFont('times', '', 12);
$pdf->Text(50, 119, htmlspecialchars( $subsidence[3] ) );
//quantity
$pdf->SetFont('times', '', 12);
$qty[3] = number_format($quantity[3]);
$pdf->MultiCell(19, 5, htmlspecialchars( $qty[3] ),0,'R',0,0,73,119 );
//unit_price
$pdf->SetFont('times', '', 12);
$price[3] = number_format($unit_price[3]);
$pdf->MultiCell(28, 5, htmlspecialchars( $price[3] ),0,'R',0,0,93,119 );
//line_amount
$pdf->SetFont('times', '', 12);
$l_amount[3] = number_format($line_amount[3]);
$pdf->MultiCell(37, 5, htmlspecialchars( $l_amount[3] ),0,'R',0,0,124,119 );
//delivery_schedule
$pdf->SetFont('times', '', 12);
$pdf->Text(164, 119, htmlspecialchars( $delivery_schedule[3] ) );
}
// 5行目
if($item_size[4]){
//item_size
$pdf->SetFont('times', '', 12);
$pdf->Text(35, 127, htmlspecialchars( $item_size[4] ) );
//item_size
$pdf->SetFont('times', '', 12);
$pdf->Text(50, 127, htmlspecialchars( $subsidence[4] ) );
//quantity
$pdf->SetFont('times', '', 12);
$qty[4] = number_format($quantity[4]);
$pdf->MultiCell(19, 5, htmlspecialchars( $qty[4] ),0,'R',0,0,73,127 );
//unit_price
$pdf->SetFont('times', '', 12);
$price[4] = number_format($unit_price[4]);
$pdf->MultiCell(28, 5, htmlspecialchars( $price[4] ),0,'R',0,0,93,127 );
//line_amount
$pdf->SetFont('times', '', 12);
$l_amount[4] = number_format($line_amount[4]);
$pdf->MultiCell(37, 5, htmlspecialchars( $l_amount[4] ),0,'R',0,0,124,127 );
//delivery_schedule
$pdf->SetFont('times', '', 12);
$pdf->Text(164, 127, htmlspecialchars( $delivery_schedule[4] ) );
}
// 6行目
if($item_size[5]){
//item_size
$pdf->SetFont('times', '', 12);
$pdf->Text(35, 135, htmlspecialchars( $item_size[5] ) );
//item_size
$pdf->SetFont('times', '', 12);
$pdf->Text(50, 135, htmlspecialchars( $subsidence[5] ) );
//quantity
$pdf->SetFont('times', '', 12);
$qty[5] = number_format($quantity[5]);
$pdf->MultiCell(19, 5, htmlspecialchars( $qty[5] ),0,'R',0,0,73,135 );
//unit_price
$pdf->SetFont('times', '', 12);
$price[5] = number_format($unit_price[5]);
$pdf->MultiCell(28, 5, htmlspecialchars( $price[5] ),0,'R',0,0,93,135 );
//line_amount
$pdf->SetFont('times', '', 12);
$l_amount[5] = number_format($line_amount[5]);
$pdf->MultiCell(37, 5, htmlspecialchars( $l_amount[5] ),0,'R',0,0,124,135 );
//delivery_schedule
$pdf->SetFont('times', '', 12);
$pdf->Text(164, 135, htmlspecialchars( $delivery_schedule[5] ) );
}
// 7行目
if($item_size[6]){
//item_size
$pdf->SetFont('times', '', 12);
$pdf->Text(35, 142, htmlspecialchars( $item_size[6] ) );
//item_size
$pdf->SetFont('times', '', 12);
$pdf->Text(50, 142, htmlspecialchars( $subsidence[6] ) );
//quantity
$pdf->SetFont('times', '', 12);
$qty[6] = number_format($quantity[6]);
$pdf->MultiCell(19, 5, htmlspecialchars( $qty[6] ),0,'R',0,0,73,142 );
//unit_price
$pdf->SetFont('times', '', 12);
$price[6] = number_format($unit_price[6]);
$pdf->MultiCell(28, 5, htmlspecialchars( $price[6] ),0,'R',0,0,93,142 );
//line_amount
$pdf->SetFont('times', '', 12);
$l_amount[6] = number_format($line_amount[6]);
$pdf->MultiCell(37, 5, htmlspecialchars( $l_amount[6] ),0,'R',0,0,124,142 );
//delivery_schedule
$pdf->SetFont('times', '', 12);
$pdf->Text(164, 142, htmlspecialchars( $delivery_schedule[6] ) );
}
// 8行目
if($item_size[7]){
//item_size
$pdf->SetFont('times', '', 12);
$pdf->Text(35, 150, htmlspecialchars( $item_size[7] ) );
//item_size
$pdf->SetFont('times', '', 12);
$pdf->Text(50, 150, htmlspecialchars( $subsidence[7] ) );
//quantity
$pdf->SetFont('times', '', 12);
$qty[7] = number_format($quantity[7]);
$pdf->MultiCell(19, 5, htmlspecialchars( $qty[7] ),0,'R',0,0,73,150 );
//unit_price
$pdf->SetFont('times', '', 12);
$price[7] = number_format($unit_price[7]);
$pdf->MultiCell(28, 5, htmlspecialchars( $price[7] ),0,'R',0,0,93,150 );
//line_amount
$pdf->SetFont('times', '', 12);
$l_amount[7] = number_format($line_amount[7]);
$pdf->MultiCell(37, 5, htmlspecialchars( $l_amount[7] ),0,'R',0,0,124,150 );
//delivery_schedule
$pdf->SetFont('times', '', 12);
$pdf->Text(164, 150, htmlspecialchars( $delivery_schedule[7] ) );
}
// 9行目
if($item_size[8]){
//item_size
$pdf->SetFont('times', '', 12);
$pdf->Text(35, 158, htmlspecialchars( $item_size[8] ) );
//item_size
$pdf->SetFont('times', '', 12);
$pdf->Text(50, 158, htmlspecialchars( $subsidence[8] ) );
//quantity
$pdf->SetFont('times', '', 12);
$qty[8] = number_format($quantity[8]);
$pdf->MultiCell(19, 5, htmlspecialchars( $qty[8] ),0,'R',0,0,73,158 );
//unit_price
$pdf->SetFont('times', '', 12);
$price[8] = number_format($unit_price[8]);
$pdf->MultiCell(28, 5, htmlspecialchars( $price[8] ),0,'R',0,0,93,158 );
//line_amount
$pdf->SetFont('times', '', 12);
$l_amount[8] = number_format($line_amount[8]);
$pdf->MultiCell(37, 5, htmlspecialchars( $l_amount[8] ),0,'R',0,0,124,158 );
//delivery_schedule
$pdf->SetFont('times', '', 12);
$pdf->Text(164, 158, htmlspecialchars( $delivery_schedule[8] ) );
}
// 10行目
if($item_size[9]){
//item_size
$pdf->SetFont('times', '', 12);
$pdf->Text(35, 165, htmlspecialchars( $item_size[9] ) );
//item_size
$pdf->SetFont('times', '', 12);
$pdf->Text(50, 165, htmlspecialchars( $subsidence[9] ) );
//quantity
$pdf->SetFont('times', '', 12);
$qty[9] = number_format($quantity[9]);
$pdf->MultiCell(19, 5, htmlspecialchars( $qty[9] ),0,'R',0,0,73,165 );
//unit_price
$pdf->SetFont('times', '', 12);
$price[9] = number_format($unit_price[9]);
$pdf->MultiCell(28, 5, htmlspecialchars( $price[9] ),0,'R',0,0,93,165 );
//line_amount
$pdf->SetFont('times', '', 12);
$l_amount[9] = number_format($line_amount[9]);
$pdf->MultiCell(37, 5, htmlspecialchars( $l_amount[9] ),0,'R',0,0,124,165 );
//delivery_schedule
$pdf->SetFont('times', '', 12);
$pdf->Text(164, 165, htmlspecialchars( $delivery_schedule[9] ) );
}


//　合計金額欄
//gasket_fee
$pdf->SetFont('times', '', 12);
$gasket_f = number_format($gasket_fee);
$pdf->MultiCell(33, 5, htmlspecialchars( $gasket_f ),0,'R',0,0,124,171 );
//spacer_fee
$pdf->SetFont('times', '', 12);
$spacer_f = number_format($spacer_fee);
$pdf->MultiCell(33, 5, htmlspecialchars( $spacer_f ),0,'R',0,0,124,177 );
//add_amount
$pdf->SetFont('times', '', 12);
$add_a = number_format($add_amount);
$pdf->MultiCell(33, 5, htmlspecialchars( $add_a ),0,'R',0,0,124,183 );
//total_amount
$pdf->SetFont('times', '', 12);
$t_amount = number_format($total_amount);
$pdf->MultiCell(33, 5, htmlspecialchars( $t_amount ),0,'R',0,0,124,190 );

//Add amount text
$pdf->SetFont('times', '', 11);
$pdf->Text(68, 183, htmlspecialchars( $add_amount_text ) );

// Remark
// by air or sea
switch ($by_air_or_sea){
  case "1":
    $text3 = "By Air";
    break;
  case "2":
    $text3 = "By Sea";
    break;
}
//$pdf->SetTextColor(220, 20, 60);
$pdf->SetFont('times', '', 15);
$pdf->Text(78, 200, htmlspecialchars( $text3 ) ,0.3);

//delivery_term1
switch ($delivery_term1){
  case "1":
    $text2 = "EXW";
    break;
  case "2":
    $text2 = "FOB";
    break;
  case "3":
    $text2 = "CIF";
    break;
  case "4":
    $text2 = "CIP";
    break;
  case "5":
    $text2 = "DAP";
    break;
  case "6":
    $text2 = "DDP";
    break;
}

//$pdf->SetTextColor(64);

$pdf->SetFont('times', '', 11);
$pdf->Text(78, 210, htmlspecialchars( $text2 ) );

//Delivery term2
$pdf->SetFont('times', '', 11);
$pdf->Text(114, 210, htmlspecialchars( $delivery_term2 ) );

//Estimated transit time
$pdf->SetFont('times', '', 11);
$pdf->Text(78, 219, htmlspecialchars( $transit_time ) );

//packing_size
$pdf->SetFont('times', '', 11);
$pdf->MultiCell(125, 5, htmlspecialchars( $packing_size ),0,'L',0,0,78,228 );

//production_lead_time
$pdf->SetFont('times', '', 11);
$pdf->Text(78, 242, htmlspecialchars( $lead_time ) );

//currency
switch ($currency){
  case "1":
    $text1 = "¥ : Japanese Yen";
    break;
  case "2":
    $text1 = "$ : US dollar";
    break;
  case "3":
    $text1 = "€ : Euro";
    break;
}

$pdf->SetFont('times', '', 11);
$pdf->Text(78, 251, htmlspecialchars( $text1 ) );

//valid_date
$pdf->SetFont('times', '', 11);
$pdf->Text(78, 260, htmlspecialchars( $valid_date ) );

//payment_terms
$pdf->SetFont('times', '', 11);
$pdf->Text(78, 269, htmlspecialchars( $payment_terms ) );

//$pdf->Output(出力時のファイル名, 出力モード);
$pdf->Output("estimate_".$estimate_no.".pdf", "I");

/* ----------------------------------------------------------------------------------------------*/
?>