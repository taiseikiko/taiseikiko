function errMsgForLength(name, max) {
  return "「" + name + "」は文字数「" + max + "」以下で入力してください。";
}

function errMsgForEmpty(name) {
  return "「" + name + "」を選択してください。";
}

function checkValidation(event) {
  var errMessage = '';

  var filled = false;
  for (var i = 1; i <= 5; i++) {
    if (document.getElementById("route" + i + "_dept").value !== '') {
      filled = true;
      break;
    }
  }

  if (!filled) {
    errMessage = '少なくとも一つのルート部署を選択して下さい。';
  }

  return errMessage;
}
