/*                          */
/* 得意先検索子画面呼び出し   */
/*                          */
function customer_open(event) {
  event.preventDefault();
  window.open(
    "inq_customer.php",
    "_blank",
    "width=500,height=600,left=100,top=50"
  );
}

/*                   */
/* 選択された後処理    */
/*                   */
function inq_ent(btn) {
  var x = btn; // 押されたボタンの配列の指標をセット
  var customer_codes = customer_code; // 呼び出し元PHPで JSON_encode した配列
  var customer_names = customer_name;

  // 配列の指標に従ってデータセット
  customer_code = customer_codes.splice(x, 1);
  customer_name = customer_names.splice(x, 1);

  // 親画面に値を挿入
  window.opener.document.getElementById("cust_code").value = customer_code;
  window.opener.document.getElementById("cust_name").value = customer_name;
  window.onunload = function() {
  if(window.opener && !window.opener.closed) {
    window.opener.handleWindowClose();
  }
  }

  // ウィンドウを閉じる
  window.close();
}
