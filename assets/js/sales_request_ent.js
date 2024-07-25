/*                          */
/* 営業依頼書検索子画面呼び出し   */
/*                          */
function sales_request_open(event) {
  event.preventDefault();
  window.open(
    "inq_sales_request.php",
    "_blank",
    "width=1500,height=760,left=100,top=50"
  );
}

/*                   */
/* 選択された後処理    */
/*                   */
function inq_ent(sq_no, sq_line_no) {
  // 親画面に値を挿入
  window.opener.document.getElementById("sq_no").value = sq_no;
  window.opener.document.getElementById("sq_line_no").value = sq_line_no;
  
  window.onunload = function () {
    if (window.opener && !window.opener.closed) {
      window.opener.handleViewBtn();
    }
  };
  // ウィンドウを閉じる
  window.close();
}
