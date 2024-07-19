function errMsgForLength(name, max) {
  return "「" + name + "」は文字数「" + max + "」以下で入力してください。";
}

function errMsgForEmpty(name) {
  return "「" + name + "」を入力してください。";
}

function checkValidation() {
  var isErr = false;
  var errMessage = "";
  var request_item_name = document.getElementById("request_item_name").value; //案件名

  if (!isErr && request_item_name == "") {
    errMessage = errMsgForEmpty("案件名");
    isErr = true;
  }

  if (!isErr && request_item_name.length > 50) {
    errMessage = errMsgForLength("案件名", "50");
    isErr = true;
  }

  return errMessage;
}
