function errMsgForLength(name, max) {
  return "「" + name + "」は文字数「" + max + "」以下で入力してください。";
}

function errMsgForEmpty(name) {
  return "「" + name + "」を入力してください。";
}

function checkValidation(event) {
  var isErr = false;
  var errMsg = '';
  var employee_code = document.getElementById("contact_person").value;
  var role = document.getElementById("role").value;

  if (!isErr && employee_code.length > 10) {
    errMsg = errMsgForLength("担当者", "40");
    isErr = true;
  }
  //isEmpty error for employee_code
  if (!isErr && employee_code == "") {
    errMsg = errMsgForEmpty("担当者");
    isErr = true;
  }
  //isEmpty error for role
  if (!isErr && role == "") {
    errMsg = errMsgForEmpty("役割");
    isErr = true;
  }

  if (isErr) {
    event.preventDefault();
    return errMsg
  }
}
