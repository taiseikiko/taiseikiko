function errMsgForLength(name, max) {
  return "「" + name + "」は文字数「" + max + "」以下で入力してください。";
}

function errMsgForEmpty(name) {
  return "「" + name + "」は必須項目です。";
}

function checkValidation(event) {
  var isErr = false;
  var errMessage = '';
  var employee_code = document.getElementById("contact_person").value;
  var role = document.getElementById("role").value;
  var employee_code_nm = "担当者";

  if (!isErr && employee_code.length > 40) {
    errMessage = errMsgForLength(employee_code_nm, "40");
    isErr = true;
  }

  if (!isErr && employee_code == "") {
    errMessage = errMsgForEmpty(employee_code_nm);
    isErr = true;
  }

  if (!isErr && role == "") {
    errMessage = errMsgForEmpty("役割");
    isErr = true;
  }

  return errMessage;
}
