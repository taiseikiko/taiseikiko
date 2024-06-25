<?php
require_once('function.php');
$pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

function get_card_header_datas() {
  global $pdo;

  $sql = "
    SELECT ch.card_no, ch.card_status, ch.client, ch.add_date, ch.procurement_approver, ch.procurement_approver_date,
      GROUP_CONCAT(cd.sq_card_line_no ORDER BY cd.sq_card_line_no ASC) AS sq_card_line_nos,
      GROUP_CONCAT(cd.procurement_no ORDER BY cd.sq_card_line_no ASC) AS procurement_nos,
      GROUP_CONCAT(cd.procurement_status ORDER BY cd.sq_card_line_no ASC) AS procurement_statuses
    FROM card_header_tr ch 
    LEFT JOIN card_detail_tr cd ON ch.card_no = cd.sq_card_no
    GROUP BY ch.card_no
    ";
    
  $stmt = $pdo->prepare($sql);
  $stmt->execute();
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$cardData = get_card_header_datas();
?>
