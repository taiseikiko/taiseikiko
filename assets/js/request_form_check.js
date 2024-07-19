function errMsgForLength(name, max) {
  return "「" + name + "」は文字数「" + max + "」以下で入力してください。";
}

function errMsgForEmpty(name) {
  return "「" + name + "」を選択してください。";
}

function checkValidationFile() {
  var isErr = false;
  var errMessage = "";
  var uploaded_file = document.getElementById("uploaded_file").value; //ルート設定

  if (!isErr && uploaded_file == "") {
    errMessage = errMsgForEmpty("ファイル");
    isErr = true;
  }

  return errMessage;
}

function checkValidation() {
  var isErr = false;
  var errMessage = "";
  var classList = document.getElementById("classList").value; //分類
  var hasFile = document.getElementById("hasFile"); //ファイル

  if (!isErr && classList == "") {
    errMessage = errMsgForEmpty("分類");
    isErr = true;
  }

  if (!isErr && (!hasFile)) {
    errMessage = errMsgForEmpty("ファイル");
    isErr = true;
  }

  return errMessage;
}
