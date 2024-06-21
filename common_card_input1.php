<div class="container">
  <form class="row g-3" method="POST" name="inq_ent" enctype="multipart/form-data" id="input1">
    <input type="hidden" name="dept_id" value="<?= $dept_id ?>">
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
                <button type="submit" name="new" value="new">新規登録</button>
            </td>
            </tr>
          <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="text-align:center">
                <button type="submit" class="updateBtn" name="update" value="update">更新</button>
            </td>             
          </tr>
         
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
    height:700px;
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