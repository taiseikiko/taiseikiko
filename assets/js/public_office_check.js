function errMsgForLength(name, max) {
  return "「" + name + "」は文字数「" + max + "」以下で入力してください。";
}

function errMsgForEmpty(name) {
  return "「" + name + "」を入力してください。";
}

function checkValidation(event) {
  var isErr = false;
  var errMessage = '';
  var pf_name = document.getElementById("pf_name").value;
  var pf_name_nm = "官庁名称";
  var employee_code = document.getElementById("contact_person").value;
  var employee_code_nm = "担当者";

  if (!isErr && pf_name.length > 40) {
    errMessage = errMsgForLength(pf_name_nm, "40");
    isErr = true;
  }

  if (!isErr && pf_name == '') {
    errMessage = errMsgForEmpty(pf_name_nm);
    isErr = true;
  }

  if (!isErr && employee_code.length > 10) {
    errMessage = errMsgForLength(employee_code_nm, "40");
    isErr = true;
  }

  if (!isErr && employee_code == "") {
    errMessage = errMsgForEmpty(employee_code_nm);
    isErr = true;
  }

  return errMessage;
}
