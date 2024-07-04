function errMsgForLength(name, max) {
  return "「" + name + "」は文字数「" + max + "」以下で入力してください。";
}

function errMsgForEmpty(name) {
  return "「" + name + "」を選択してください。";
}

function checkValidation() {
  var isErr = false;
  var errMessage = "";
  var route_pattern = document.getElementById("route_no").value; //ルート設定

  if (!isErr && route_pattern == "") {
    errMessage = errMsgForEmpty("ルート設定");
    isErr = true;
  }

  return errMessage;
}
