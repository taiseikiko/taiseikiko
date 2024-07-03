<?php
require_once('function.php');

function fetch_mailing_list() {
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());

  $sql = "SELECT mail1, mail2, mail3, mail4, mail5 FROM sq_mailing_list";
  $stmt = $pdo->prepare($sql);
  $stmt->execute();

  $mailing_list = [
    "mail1" => [],
    "mail2" => [],
    "mail3" => [],
    "mail4" => [],
    "mail5" => []
  ];

  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    foreach ($row as $key => $value) {
      if ($value) {
        $mailing_list[$key][] = $value;
      }
    }
  }

  return $mailing_list;
}
$mailing_list = fetch_mailing_list();
echo json_encode($mailing_list);




