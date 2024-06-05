function errMsgForLength(name, max) {
  return "「" + name + "」は文字数「" + max + "」以下で入力してください。";
}

function errMsgForEmpty(name) {
  return "「" + name + "」を入力してください。";
}

function checkValidation(event) {
  var isErr = false;
  var employee_code = document.getElementById("contact_person").value;

  if (!isErr && employee_code.length > 10) {
    alert(errMsgForLength("担当者", "40"));
    isErr = true;
  }

  if (!isErr && employee_code == "") {
    alert(errMsgForEmpty("担当者"));
    isErr = true;
  }

  if (isErr) {
    event.preventDefault();
  }
}
