<?php
  session_start();
  require_once('function.php');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
  include("sr_route_input2_data_set.php");

  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $process = $_POST['process'];
    $route_id = $_POST['route_id'];
    $route_depts = [];

    for ($i = 1; $i <= 5; $i++) {
      $route_depts[] = $_POST["route{$i}_dept"];
    }

    if ($process == 'create') {
      createRoute($route_id, $route_depts);
    } elseif ($process == 'update') {
      updateRoute($route_id, $route_depts);
    }

    header('Location: sr_route_input1.php');
    exit;
  }
  header('Program-id: estimate_entry.php');
  header('Content-type: text/html; charset=utf-8');
  include("header1.php");

?>

<main>
  <div class="pagetitle">
    <h3>部署ルートマスター保守</h3>
    <div class="container">
      <form class="row g-3" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="sq_dept_form" enctype="multipart/form-data">
        <input type="hidden" name="process" id="process" value="<?= htmlspecialchars($process) ?>">
        <table style="width:auto;">
          <tr>
            <td>
              <div class="field-row">
                <label class="common_label" for="route_id">ルートID</label>
                <input style="width: 100px;" type="text" value="<?= htmlspecialchars($route_id) ?>" id="route_id" name="route_id" class="readonlyText" readonly>
              </div>
            </td>
          </tr>
          <tr>
            <td>
              <div class="field-row" style="margin: 1rem;">
                <font size=3>
                  <b>ルート部署を1～5までプルダウンから選択して下さい。</b>
                </font>
              </div>
            </td>
          </tr>
          <?php for ($i = 1; $i <= 5; $i++) : ?>
            <tr>
              <td>
                <div class="field-row">
                  <label class="common_label" for="route<?= $i ?>_dept">ルート<?= $i ?>部署</label>
                  <select name="route<?= $i ?>_dept">
                    <?= generateOptions($dept_list, $route_depts[$i - 1]) ?>
                  </select>
                </div>
              </td>
            </tr>
          <?php endfor; ?>
          <tr>
            <td>
              <div class="flex-container" style="margin-left:3rem">
                <div>
                  <button id="returnBtn" name="return">戻る</button>
                </div>
                <div>
                  <button class="updateBtn" id="upd_regBtn" name="submit" value="update"><?= htmlspecialchars($btn_name) ?></button>
                </div>
              </div>
            </td>
          </tr>
        </table>
      </form><!-- Vertical Form -->
    </div>
  </div>
</main>

<script src="assets/js/inquiry_ent.js"></script>
<script src="assets/js/inquiry_ent_check.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    // Handle return button click
    $('#returnBtn').click(function(event) {
      event.preventDefault(); // Prevent the default form submission
      if (confirm('一覧画面に戻ります．よろしいですか？')) {
        window.location.href = 'sr_route_input1.php';
      }
    });

    // Handle form submission
    $('#sq_dept_form').submit(function(event) {
      var filled = false;
      for (var i = 1; i <= 5; i++) {
        if ($('select[name="route' + i + '_dept"]').val() !== '') {
          filled = true;
          break;
        }
      }
      if (!filled) {
        alert('少なくとも一つのルート部署を選択して下さい。');
        event.preventDefault();
      }
    });
  });
</script>

<?php
// フッターセット
footer_set();
?>

<style>
  .updateBtn {
    margin: 2px 1px;
  }

  .createBtn {
    width: 120px;
    margin-top: 2px;
    margin-bottom: 2px;
    margin-right: 8px;
  }

  .flex-container {
    display: flex;
  }

  .flex-container>div {
    margin: 20px 5px;
  }

  @media only screen and (max-width:800px) {

    .pagetitle,
    .container,
    .field-row {
      width: 80%;
      padding: 0;
    }

    .createBtn {
      width: 40px;
    }
  }

  @media only screen and (max-width:500px) {

    .pagetitle,
    .container,
    .field-row {
      width: 100%;
    }

    .createBtn {
      width: 40px;
    }
  }
</style>