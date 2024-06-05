function errMsgForLength(name, max) {
  return "「" + name + "」は文字数「" + max + "」以下で入力してください。";
}

function errMsgForEmpty(name) {
  return "「" + name + "」を入力してください。";
}

function checkValidation(event) {
  var isErr = false;
  var employee_code = document.getElementById("contact_person").value;
  var cust_name = document.getElementById("cust_name").value;

  if (!isErr && cust_name == "") {
    alert(errMsgForEmpty("得意先　名称"));
    isErr = true;
  }

  if (!isErr && cust_name.length > 40) {
    alert(errMsgForLength("得意先　名称", "40"));
    isErr = true;
  }

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
