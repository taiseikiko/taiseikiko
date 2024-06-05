<?php
  // 初期処理
  require_once('function.php');
  // DB接続
  $pdo = new PDO(DNS, USER_NAME, PASSWORD, get_pdo_options());
  // 初期設定 & データセット
  $count = 0;
  $dept_datas = array();

  //部署ルートマスターからデータ取得する
  $dept_datas = getDeptDatas();
  function getDeptDatas() {
    global $pdo;
    // SQLクエリ: sq_route と sq_code テーブルを結合して必要なデータを取得
    $sql = "
      SELECT
        r.route_id,
        c1.text2 AS route1_dept,
        c2.text2 AS route2_dept,
        c3.text2 AS route3_dept,
        c4.text2 AS route4_dept,
        c5.text2 AS route5_dept
      FROM
        sq_route r
      LEFT JOIN
        sq_code c1 ON r.route1_dept = c1.text1 AND c1.code_id = 'sq_dept'
      LEFT JOIN
        sq_code c2 ON r.route2_dept = c2.text1 AND c2.code_id = 'sq_dept'
      LEFT JOIN
        sq_code c3 ON r.route3_dept = c3.text1 AND c3.code_id = 'sq_dept'
      LEFT JOIN
        sq_code c4 ON r.route4_dept = c4.text1 AND c4.code_id = 'sq_dept'
      LEFT JOIN
        sq_code c5 ON r.route5_dept = c5.text1 AND c5.code_id = 'sq_dept'
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $dept_datas = $stmt->fetchAll();
    return $dept_datas;
  }  
  

?>