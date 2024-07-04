function errMsgForLength(name, max) {
  return "「" + name + "」は文字数「" + max + "」以下で入力してください。";
}

function errMsgForEmpty(name) {
  return "「" + name + "」を選択してください。";
}

function checkValidation() {
  var isErr = false;
  var errMessage = "";
  var group = document.getElementById("group").value; //グループ
  var entrant = document.getElementById("entrant").value; //担当者
  var title = document.getElementById("title").value; //title

  if (!isErr && group == "" && title !== 'sm_receipt' && title !== 'pc_receipt') {
    errMessage = errMsgForEmpty("グループ");
    isErr = true;
  }

  if (!isErr && entrant == "") {
    errMessage = errMsgForEmpty("担当者");
    isErr = true;
  }

  return errMessage;
}
