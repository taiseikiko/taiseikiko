/*                          */
/* 事業体検索子画面呼び出し   */
/*                          */
function public_office_open(event) {
  event.preventDefault();
  window.open(
    "inq_public_office.php",
    "_blank",
    "width=500,height=600,left=100,top=50"
  );
}

/*                   */
/* 選択された後処理    */
/*                   */
function inq_ent(btn) {
  var x = btn; // 押されたボタンの配列の指標をセット
  var pf_codes = public_office_code; // 呼び出し元PHPで JSON_encode した配列
  var pf_names = public_office_name;

  // 配列の指標に従ってデータセット
  public_office_code = pf_codes.splice(x, 1);
  public_office_name = pf_names.splice(x, 1);

  // 親画面に値を挿入
  window.opener.document.getElementById("pf_code").value = public_office_code;
  window.opener.document.getElementById("pf_name").value = public_office_name;
  window.onunload = function() {
    if(window.opener && !window.opener.closed) {
      window.opener.handleWindowClose();
    }
    }
  
  // ウィンドウを閉じる
  window.close();
}
