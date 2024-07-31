function errMsgForLength(name, max) {
  return "「" + name + "」は文字数「" + max + "」以下で入力してください。";
}

function errMsgForEmpty(name) {
  return "「" + name + "」は必須項目です。";
}

function checkValidation(event) {
  var errMessage = '';
  var isErr = false;
  var class_name = document.getElementById("class_name").value;
  var class_nm = "分類名称";

  if (!isErr && class_name.length > 20) {
    errMessage = errMsgForLength(class_nm, "20");
    isErr = true;
  }

  if (!isErr && class_name == "") {
    errMessage = errMsgForEmpty(class_nm);
    isErr = true;
  }

  return errMessage;
}
