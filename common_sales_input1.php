<div class="container">
  <form class="row g-3" method="POST" name="inq_ent" enctype="multipart/form-data" id="input1">
    <input type="hidden" name="dept_id" value="<?= $dept_id ?>">
    <div class="scrollable-table-container">
      <table class="tab1">
        <thead>
          <tr>
            <th>営業依頼書No</th>
            <th>提出先</th>
            <th>事業体</th>
            <th>件名</th>
            <th>担当者</th>
            <th>処理</th>
          </tr>
        </thead>
        <tbody>
          <?php
            if(isset($sq_datas) && !empty($sq_datas)) {
              foreach ($sq_datas as $item) {
          ?>
          <tr>
            <td><?= $item['sq_no'] ?></td>
            <td><?= $item['cust_name'] ?></td>
            <td><?= $item['pf_name']?></td>
            <td><?= $item['item_name']?></td>
            <td><?= $item['employee_name']?></td>
            <td style="text-align:center"><button class="updateBtn" data-sq_no="<?= $item['sq_no'] ?>" name="process" value="detail">詳細</button></td>
            <input type="hidden" class="sq_no" name="sq_no" value="">              
          </tr>
          <?php } }?>
        </tbody>
      </table>
      <?php 
        if (count($sq_datas) <= 0) {
          echo "<div><h4 style='font-size: 12px;'>表示するデータがございません。</h4></div>";
        }
      ?>
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
    height:600px;
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

  .flex-container > div {
    margin: 20px 5px;
  }

  @media only screen and (max-width:800px) {
    .pagetitle, .container, .field-row {
      width: 80%;
      padding: 0;
    }
    .createBtn {
      width: 40px;
    }
  }
  @media only screen and (max-width:500px) {
    .pagetitle, .container, .field-row {
      width: 100%;
    }
    .createBtn {
      width: 40px;
    }
  }
</style>