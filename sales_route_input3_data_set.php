<?php
  function get_route_pattern_list() {
    global $pdo;
    $sql = "SELECT * FROM sq_route";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $datas = $stmt->fetchAll();
    return $datas;
  }

?>