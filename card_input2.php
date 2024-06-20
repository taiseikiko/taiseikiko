<?php
  session_start();
  header('Program-id: estimate_entry.php');
  header('Content-type: text/html; charset=utf-8');
  require_once('function.php');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
  $title = $_GET['title'] ?? '';
  $dept_code = $_SESSION['department_code'];
  // ヘッダーセット
  include("header1.php");
?>

<main>
  <div class="pagetitle">
    <h3>資材部：入力</h3>
    <?php include("common_card_input2.php"); ?>
  </div>
</main><!-- End #main -->
</body>
</html>