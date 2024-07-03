<?php
session_start();
header('Program-id: estimate_entry.php');
header('Content-type: text/html; charset=utf-8');
require_once('function.php');
$_SESSION['token'] = get_csrf_token(); // CSRFのトークンを取得する
include("header1.php");
// include("listmail_request_input1_data_set.php");
?>
<main>
  <div class="pagetitle">
    <h3>通知メール保守</h3>
  </div>
  <div class="container">
    <form class="row g-3" method="POST" name="inq_ent" enctype="multipart/form-data" id="notification_mail_form" action="listmail_update_input1.php">
      <table style="width:auto;">
        <tr>
          <div class="field-row">
            <td>
              ※複数のアドレスを登録する場合、カンマで区切って入力して下さい。
            </td>
          </div>
        </tr>

        <tr>
          <td>
            <div class="field-row">
              <label class="common_label" for="mail_address1">品質管理課</label>
              <textarea id="mail_address1" name="mail_address1" rows="3" cols="100" maxlength="200"></textarea>
            </div>
          </td>
        </tr>

        <tr>
          <td>
            <div class="field-row">
              <label class="common_label" for="mail_address2">関西工事センター</label>
              <textarea id="mail_address2" name="mail_address2" rows="3" cols="100" maxlength="200"></textarea>
            </div>
          </td>
        </tr>

        <tr>
          <td>
            <div class="field-row">
              <label class="common_label" for="mail_address3">東京工事センター</label>
              <textarea id="mail_address3" name="mail_address3" rows="3" cols="100" maxlength="200"></textarea>
            </div>
          </td>
        </tr>

        <tr>
          <td>
            <div class="field-row">
              <label class="common_label" for="mail_address4">工事管理課</label>
              <textarea id="mail_address4" name="mail_address4" rows="3" cols="100" maxlength="200"></textarea>
            </div>
          </td>
        </tr>

        <tr>
          <td>
            <div class="field-row">
              <label class="common_label" for="mail_address5">営業管理部</label>
              <textarea id="mail_address5" name="mail_address5" rows="3" cols="100" maxlength="200"></textarea>
            </div>
          </td>
        </tr>

        <tr>
          <td>
            <div class="flex-container">
              <div>
                <button id="returnBtn" type="button" style="background:#80dfff;">前の画面に戻る</button>
              </div>
              <div>
                <button id="updateBtn" type="submit" class="updateBtn" name="submit">更新</button>
              </div>
            </div>
          </td>
        </tr>
      </table>
    </form><!-- Vertical Form -->
  </div>
</main><!-- End #main -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    // データを取得してフィールドに入力する
    $.getJSON('listmail_request_input1_data_set.php', function(data) {
      $('#mail_address1').val(data.mail1.join(', '));
      $('#mail_address2').val(data.mail2.join(', '));
      $('#mail_address3').val(data.mail3.join(', '));
      $('#mail_address4').val(data.mail4.join(', '));
      $('#mail_address5').val(data.mail5.join(', '));
    });

    // フォーム validation
    $('#notification_mail_form').on('submit', function(event) {
      var isValid = false;
      $('textarea').each(function() {
        if ($(this).val().trim() !== '') {
          isValid = true;
          return false;
        }
      });

      if (!isValid) {
        alert('入力項目に不備があります。');
        event.preventDefault();
      } else {
        let buttonText = $('#updateBtn').text();
        if (!confirm(buttonText + 'します、よろしいでしょうか？')) {
          event.preventDefault();
        } else {
          // Prepare hidden inputs for arrays of emails
          $('textarea').each(function() {
            let emails = $(this).val().split(',').map(email => email.trim()).filter(email => email !== '');
            $(this).after('<input type="hidden" name="' + $(this).attr('name') + '[]" value="' + emails.join('|') + '">');
          });
          $('textarea').remove();
        }
      }
    });

    // 戻る button
    $('#returnBtn').click(function() {
      window.history.back();
    });
  });
</script>

<style>
  .dropdown-menu {
    width: 180px;
  }

  .field-row {
    margin-top: 20px;
  }

  input.readonlyText {
    background-color: #ffffe0;
  }

  .flex-container {
    display: flex;
  }

  .flex-container>div {
    margin: 20px 5px;
  }
</style>

<?php
// フッターセット
footer_set();
?>