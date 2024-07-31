<?php
  session_start();
  header('Program-id: estimate_entry.php');
  header('Content-type: text/html; charset=utf-8');
  require_once('function.php');
  $_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
  // $title = $_GET['title'] ?? '';
  $dept_code = $_SESSION['department_code'];
  $user_code = $_SESSION["login"]?? '';
  $user_name = $_SESSION['user_name']?? '';      //登録者
  $office_name = $_SESSION['office_name']?? '';  //部署
  $office_position_name = $_SESSION['office_position_name']?? '';  //役職
  include("fwt_m_input3_data_set.php");
  // ヘッダーセット
  header_set1();
?>

<main>
  <div class="pagetitle">
    <table style="width: 100%;">
      <input type="hidden" name="status" id="status" value="<?= $status ?>">
      <tr>
        <td style="width: 100%;">
          <div class="field-row" style="display: flex; justify-content: space-between; align-items: center;">
            <label for="title"><h3>予約不可、解除</h3></label>
            <label class="common_label" for="add_date" style="margin-left: auto;">　　申請日</label>
            <input type="date" style="margin-left: 1rem;" min="2023-01-01" max="2028-12-31" name="add_date" value="<?= $add_date?>" class="input-res"/>
          </div>
        </td>
      </tr>
    </table>
  </div>
  <div class="container">
    <form class="row g-3" method="POST" name="inq_ent" enctype="multipart/form-data" id="input2">
      <?php include('dialog.php'); ?>
      <table style="width:auto;">
        <tr style="height:10px; margin-top:20px"></tr>
        <tr style="height:10px;"></tr>
        <tr>
          <td>
            <div class="field-row">
              <label class="common_cal_label" for="user_name" >登録者</label>
              <input type="text" style="margin-left: 1rem;" class="readonlyText input-res" name="user_name" value="<?= $user_name ?>" readonly>
              <input type="hidden" name="fwt_m_no" id="fwt_m_no" value="<?= $fwt_m_no ?>">
              
              <label class="common_cal_label" for="office_name">　　部署</label>
              <input type="text" style="width:370px;" name="office_name" class="readonlyText input-res" value="<?= $office_name ?>" readonly>

              <label class="common_cal_label" for="office_position_name" >　　役職</label>
              <input type="text" style="width:100px;" class="readonlyText input-res" name="office_position_name" value="<?= $office_position_name ?>" readonly>
            </div>            
          </td>      
        </tr>
        <tr style="height:10px;"></tr>
      </table>
           
      <table style="width:auto;">
        <tr>
          <hr>
        </tr>
        <tr style="height:10px;"></tr>
        <tr>
          <td>
            <div class="field-row">
              <label class="common_cal_label" for="">予約不可内容</label>

              <input type="checkbox" id="stop_note1" name="stop_note" value="1" <?php if (in_array('1', $stop_note_arr)) echo 'checked' ?>>
              <label class="common_cal_label" for="stop_note1">工場見学 </label>
              
              <input type="checkbox" id="stop_note2" name="stop_note" value="2" <?php if (in_array('2', $stop_note_arr)) echo 'checked' ?>>
              <label class="common_cal_label" for="stop_note2">立会検査 </label>
              
              <input type="checkbox" id="stop_note3" name="stop_note" value="3" <?php if (in_array('3', $stop_note_arr)) echo 'checked' ?>>
              <label class="common_cal_label" for="stop_note3" style="width: auto;">技術研修 </label>

              <input type="checkbox" id="stop_note4" name="stop_note" value="4" <?php if (in_array('4', $stop_note_arr)) echo 'checked' ?>>
              <label class="common_cal_label" for="stop_note4" style="width: auto;">開発案件 </label>
            </div>
          </td>  
        </tr>  

        <tr style="height:10px;"></tr>
        <tr>
          <td>
            <div class="field-row">
              <label class="common_cal_label" for="">予約不可日</label>
              <input type="date" style="margin-left: 1rem;" min="2023-01-01" max="2028-12-31" id="stop_date" name="stop_date" value="<?= $stop_date?>" class="input-res"/>
            </div>
          </td>  
        </tr> 
        
        <tr style="height:10px;"></tr>
        <tr>
          <td>
            <div class="field-row">
              <label class="common_cal_label" for="">予約不可時間</label>

              <input type="radio" id="stop_time1" name="stop_time" value="1" <?php if ($stop_time == '1') echo 'checked' ?>>
              <label class="common_label" for="stop_time1" style="margin-left:35px;">午前</label>

              <input type="radio" id="stop_time2" name="stop_time" value="2" <?php if ($stop_time == '2') echo 'checked' ?>>
              <label class="common_label" for="stop_time2">午後</label>

              <input type="radio" id="stop_time3" name="stop_time" value="3" <?php if ($stop_time == '3') echo 'checked' ?>>
              <label class="common_label" for="stop_time3">終日</label>
            </div>
          </td>  
        </tr>    

        <tr style="height:10px;"></tr>
        <tr>
          <td>
            <div class="field-row">
              <label class="common_cal_label" for="stop_name">予約不可名</label>
              <input type="text" style="width:370px;margin-left:1rem;" name="stop_name" class="input-res" value="<?= $stop_name ?>">
            </div>
          </td>
        </tr>

      </table>
    </form><!-- Vertical Form -->
  </div>
</main><!-- End #main -->
</body>
</html>
<style>
    pre {
    font-size: medium;
    text-align: left;
    border: none; /* Removes the border */
    padding: 0;   /* Optional: Remove padding */
    margin: 0;    /* Optional: Remove margin */
    background: none; /* Optional: Remove background color */
    white-space: pre-line;
  }
  .hide {
    display : none;
  }
  .dropdown-menu {
    width: 180px;
  }
  p, a {
    font-size: small;
    text-align: left;
  }
</style>
<script src="assets/js/customer_ent.js"></script>
<script src="assets/js/public_office_ent.js"></script>
<script src="assets/js/sales_request_check.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
  });

  /**-------------------------------------------------------------------------------------------------------------- */

</script>
<?php
// フッターセット
// footer_set();
?>
