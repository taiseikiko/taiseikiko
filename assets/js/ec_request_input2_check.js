function errMsgForLength(name, max) {
  return "「" + name + "」は文字数「" + max + "」以下で入力してください。";
}

function errMsgForEmpty(name) {
  return "「" + name + "」を入力してください。";
}

function checkValidation(event) {
  var isErr = false;
  var errMessage = "";    

  var bridge = document.getElementById("bridge").value; // 出先
  var company = document.getElementById("company").value; // 会社名
  var name = document.getElementById("name").value; // 氏名
  var birthday = document.getElementById("birthday").value; // 生年月日
  var attendance_year = document.getElementById("attendance_year").value; // 受講年
  var elementary_number = document.getElementById("elementary_number").value; // 初級No.
  var advance_number = document.getElementById("advance_number").value; // 上級No.
  var con_qualification = document.getElementById("con_qualification").value; // 施工資格
  var renewal_date = document.getElementById("renewal_date").value; // 更新年月日
  var expiration_date = document.getElementById("expiration_date").value; // 有効期限
  var footnote = document.getElementById("footnote").value; // 備考

  if (!isErr && bridge === "") {
    errMessage = errMsgForEmpty("出先");
    isErr = true;
  }

  if (!isErr && company === "") {
    errMessage = errMsgForEmpty("会社名");
    isErr = true;
  }

  if (!isErr && name.length > 50) {
    errMessage = errMsgForLength("氏名", "50");
    isErr = true;
  }

  if (!isErr && name === "") {
    errMessage = errMsgForEmpty("氏名");
    isErr = true;
  }

  if (!isErr && birthday === "") {
    errMessage = errMsgForEmpty("生年月日");
    isErr = true;
  }

  if (!isErr && isNaN(attendance_year)) {
    errMessage = "「受講年」は数字で入力してください。";
    isErr = true;
  }

  if (!isErr && attendance_year.length > 10) {
    errMessage = errMsgForLength("受講年", "10");
    isErr = true;
  }

  if (!isErr && elementary_number.length > 10) {
    errMessage = errMsgForLength("初級No.", "10");
    isErr = true;
  }

  if (!isErr && advance_number.length > 10) {
    errMessage = errMsgForLength("上級No.", "10");
    isErr = true;
  }

  if (!isErr && con_qualification.length > 10) {
    errMessage = errMsgForLength("施工資格", "10");
    isErr = true;
  }

  if (!isErr && renewal_date === "") {
    errMessage = errMsgForEmpty("更新年月日");
    isErr = true;
  }

  if (!isErr && expiration_date === "") {
    errMessage = errMsgForEmpty("有効期限");
    isErr = true;
  }

  if (!isErr && footnote.length > 100) {
    errMessage = errMsgForLength("備考", "100");
    isErr = true;
  }

  return errMessage;
}
