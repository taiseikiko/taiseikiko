function errMsgForLength(name, max) {
  return "「" + name + "」は文字数「" + max + "」以下で入力してください。";
}

function errMsgForEmpty(name) {
  return "「" + name + "」を入力してください。";
}

function checkValidation(event) {
  var errMessage = '';
  var isErr = false;
  var zkm_name = document.getElementById("zkm_name").value;
  var zkm_nm = "材工名　名称";

  if (!isErr && zkm_name.length > 40) {
    errMessage = errMsgForLength(zkm_nm, "40");
    isErr = true;
  }

  if (!isErr && zkm_name == "") {
    errMessage = errMsgForEmpty(zkm_nm);
    isErr = true;
  }

  return errMessage;
}
