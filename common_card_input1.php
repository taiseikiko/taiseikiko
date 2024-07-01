<div class="container">
  <form class="row g-3" method="POST" action="card_input2.php" name="inq_ent" enctype="multipart/form-data" id="card_input1">
    <div class="scrollable-table-container">
      <table class="tab1">
        <thead>
          <tr>
            <th>依頼書№</th>
            <th>状況</th>
            <th>申請者</th>
            <th>申請日付</th>
            <th>承認者</th>
            <th>承認日付</th>
            <th>１行目</th>
            <th>状況</th>
            <th>２行目</th>
            <th>状況</th>
            <th>３行目</th>
            <th>状況</th>
            <th>４行目</th>
            <th>状況</th>
            <th>処理</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td colspan="15">
              <button type="submit" name="process" id="createBtn" class="createBtn" value="new">新規登録</button>
            </td>
          </tr>
          <?php if (isset($cardData) && !empty($cardData)) : ?>
            <?php foreach ($cardData as $row) : 
              $procurement_nos = $row['procurement_nos'] ? explode(',', $row['procurement_nos']) : '';
              $procurement_statuses = $row['procurement_statuses'] ? explode(',', $row['procurement_statuses']) : '';
            ?>
              <tr>
                <td><?= htmlspecialchars($row['card_no']??'') ?></td>
                <td><?= htmlspecialchars($row['card_status']??'') ?></td>
                <td><?= htmlspecialchars($row['client_name']??'') ?></td>
                <td><?= htmlspecialchars($row['add_date']??'') ?></td>
                <td><?= htmlspecialchars($row['procurement_approver_name']??'') ?></td>
                <td><?= htmlspecialchars($row['procurement_approver_date']??'') ?></td>
                <td><?= isset($procurement_nos[0]) ? htmlspecialchars($procurement_nos[0]) : '' ?></td>
                <td><?= isset($procurement_statuses[0]) ? htmlspecialchars($procurement_statuses[0]) : '' ?></td>
                <td><?= isset($procurement_nos[1]) ? htmlspecialchars($procurement_nos[1]) : '' ?></td>
                <td><?= isset($procurement_statuses[1]) ? htmlspecialchars($procurement_statuses[1]) : '' ?></td>
                <td><?= isset($procurement_nos[2]) ? htmlspecialchars($procurement_nos[2]) : '' ?></td>
                <td><?= isset($procurement_statuses[2]) ? htmlspecialchars($procurement_statuses[2]) : '' ?></td>
                <td><?= isset($procurement_nos[3]) ? htmlspecialchars($procurement_nos[3]) : '' ?></td>
                <td><?= isset($procurement_statuses[3]) ? htmlspecialchars($procurement_statuses[3]) : '' ?></td>
                <td style="text-align:center">
                  <button type="submit" class="updateBtn" id="updateBtn" name="process" value="update" data-card_no="<?= htmlspecialchars($row['card_no']) ?>">更新</button>
                </td>                
              </tr>
            <?php endforeach; ?>
          <?php else : ?>
            <tr>
              <td colspan="15"><b>表示するデータがございません。</b></td>
            </tr>
          <?php endif; ?>
          <input type="hidden" id="card_no" name="card_no">
        </tbody>
      </table>
    </div>
  </form><!-- Vertical Form -->
</div>

<?php
// フッターセット
footer_set();
?>
<style>
  .scrollable-table-container {
    width: fit-content;
    height: 700px;
    overflow: auto;
  }

  thead th {
    position: sticky;
    top: 0;
    z-index: 1;
  }

  .detailBtn {
    margin: 2px 1px;
  }

  .createBtn {
    width: 110px;
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