
<div class="container">
<form class="row g-3" method="POST" id="request_input_form">
  <table style="width:auto;">
    <tr>
        <div class="field-row">
        <td>
          <label class="common_label" for="publish_department">発行部署</label>
          <input type="text" id="publish_department" name="publish_department" value="">

          <label class="common_label" for="requester">依頼者</label>
          <input type="text" id="requester" name="requester" value="">

          <label class="common_label" for="class_name">分類</label>
          <input type="text" id="class_name" name="class_name" value="">

          <button type="submit" style="margin-left:20px;background:#80dfff;" id="searchBtn" name="process" value="search" class="search_btn">検索</button>
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
    <tr>
      <td colspan="8">
        <button type="submit" name="process" id="regBtn" value="new">新規登録</button>
      </td>
    </tr>
    <tbody>
    <tr>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td style="text-align:center">
        <button type="submit" class="updateBtn"  name="process" value="update">更新</button>
      </td>
      <input type="hidden" class="request_no" name="request_no" value="">
    </tr>
    </tbody>
  </table>
</form>
</div>