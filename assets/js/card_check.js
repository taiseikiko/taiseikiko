function errMsgForLength(name, max) {
  return "「" + name + "」は文字数「" + max + "」以下で入力してください。";
}

function errMsgForEmpty(name) {
  return "「" + name + "」は必須項目です。";
}

function errMsgForEmptySelect(name) {
  return "「" + name + "」を選択してください。";
}

function checkEmptyForRadio(item) {
  var checked = false;
  for (var i = 0; i < item.length; i++) {
    if (item[i].checked) {
      checked = true;
      break;
    }
  }

  return checked;
}

function checkValidation() {
  var isErr = false;
  var errMessage = "";
  var entrant = document.getElementById("entrant"); //担当者

  if (!isErr && entrant.selectedIndex === 0) {
    errMessage = errMsgForEmpty("担当者");
    isErr = true;
  }
  return errMessage;
}

function checkValidationFile(file) {
  var isErr = false;
  var errMessage = "";

  if (file) {
    if (!isErr && file.value == "") {
      errMessage = errMsgForEmptySelect("ファイル");
      isErr = true;
    }
  }

  return errMessage;
}
