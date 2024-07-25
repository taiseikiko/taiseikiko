/*                          */
/* 営業依頼書明細検索子画面呼び出し   */
/*                          */
function sales_request_detail_open(event, sq_no, sq_line_no) {
  event.preventDefault();
  window.open(
    "ec_article_input3.php?sq_no=" + sq_no + "&sq_line_no=" + sq_line_no,
    "_blank",
    "width=1000,height=810,left=100,top=50"
  );
}
