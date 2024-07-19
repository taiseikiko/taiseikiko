<div class="container">
  <form class="row g-3" method="POST" id="req_rec_form1">
    <table style="width:auto;">
      <tr>
          <div class="field-row">
          <td>
            <label class="common_label" for="publish_department">発行部署</label>
            <input type="text" id="publish_department" name="publish_department" value="<?= $publish_department ?>">

            <label class="common_label" for="requester">依頼者</label>
            <input type="text" id="requester" name="requester" value="<?= $requester ?>">

            <label class="common_label" for="class_name">分類</label>
            <input type="text" id="class_name" name="class_name" value="<?= $class_name ?>">

            <button type="submit" style="margin-left:20px;background:#80dfff;" id="searchBtn" name="process1" value="search" class="search_btn">検索</button>
          </td>
      </div>
      </tr>
    </table>
    
    <table class="tab1" style="margin-top:20px;">
      <tr>
        <th>依頼部署</th>
        <th>依頼書№</th>
        <th>依頼者</th>
        <th>分類</th>
        <th>進捗状況</th>
        <th>担当部署</th>
        <th>担当者</th>
        <th>処理</th>
      </tr>
      <!-- 依頼書入力の場合だけに表示する -->
      <?php if ($title == 'request') : ?>
       <tr>
        <td colspan="8">
          <button type="submit" name="process1" id="regBtn" value="new">新規登録</button>
        </td>
      </tr>
      <?php endif; ?>
      <tbody>
        <?php if (!empty($request_datas) && isset($request_datas)) : 
          foreach ($request_datas as $item) :
        ?>
        <tr>
          <td><?= $item['request_dept_name'] ?></td>
          <td><?= $item['request_form_number'] ?></td>
          <td><?= $item['request_person_name'] ?></td>
          <td><?= $item['request_item_name'] ?></td>
          <td><?= $item['status'] ?></td>
          <td><?= $item['recipent_dept_name'] ?></td>
          <td><?= $item['recipent_person_name'] ?></td>
          <td style="text-align:center">
            <button type="submit" class="updateBtn" 
            data-request_form_number="<?= $item['request_form_number'] ?>" 
            data-status_no="<?= $item['status_no'] ?>" 
            name="process1" value="update">更新</button>
          </td>
        </tr>
        <?php 
          endforeach;
          endif; 
        ?>
        <input type="hidden" class="request_form_number" name="request_form_number" value="">
      </tbody>
    </table>
  </form>
</div>