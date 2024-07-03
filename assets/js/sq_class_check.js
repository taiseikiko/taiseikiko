function errMsgForLength(name, max) {
  return "「" + name + "」は文字数「" + max + "」以下で入力してください。";
}

function errMsgForEmpty(name) {
  return "「" + name + "」を入力してください。";
}

function checkValidation(event) {
  var errMessage = '';
  var isErr = false;
  var class_name = document.getElementById("class_name").value;

  if (!isErr && class_name.length > 20) {
    errMessage = errMsgForLength("分類名称", "40");
    isErr = true;
  }

  if (!isErr && class_name == "") {
    errMessage = errMsgForEmpty("分類名称");
    isErr = true;
  }

  return errMessage;
}
