function errMsgForLength(name, max) {
  return "「" + name + "」は文字数「" + max + "」以下で入力してください。";
}

function errMsgForEmpty(name) {
  return "「" + name + "」は必須項目です。";
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
  var dw_div = document.getElementsByName("dw_div1"); //区分
  var open_div = document.getElementsByName("open_div"); //公開区分
  var classList = document.getElementById("classList"); //分類
  var zaikoumeiList = document.getElementById("zaikoumeiList"); //材工名
  var sizeList = document.getElementById("sizeList"); //サイズ
  var jointList = document.getElementById("jointList"); //接合形状
  var pipeList = document.getElementById("pipeList"); //管種
  var specification = document.getElementById("specification").value; //営業日報
  var dw_div2 = document.getElementsByName("dw_div2"); //種類

  if (!isErr) {
    var dw_div_checked = checkEmptyForRadio(dw_div);
    if (!dw_div_checked) {
      errMessage = errMsgForEmpty("区分");
      isErr = true;
    }
  }

  if (!isErr) {
    var open_div_checked = checkEmptyForRadio(open_div);
    if (!open_div_checked) {
      errMessage = errMsgForEmpty("公開区分");
      isErr = true;
    }
  }

  if (!isErr && classList.selectedIndex === 0) {
    errMessage = errMsgForEmpty("分類");
    isErr = true;
  }

  if (!isErr && zaikoumeiList.selectedIndex === 0) {
    errMessage = errMsgForEmpty("材工名");
    isErr = true;
  }

  if (!isErr && sizeList.selectedIndex === 0) {
    errMessage = errMsgForEmpty("サイズ");
    isErr = true;
  }

  if (!isErr && jointList.selectedIndex === 0) {
    errMessage = errMsgForEmpty("接合形状");
    isErr = true;
  }

  if (!isErr && pipeList.selectedIndex === 0) {
    errMessage = errMsgForEmpty("管種");
    isErr = true;
  }

  if (!isErr && specification == "") {
    errMessage = errMsgForEmpty("営業日報");
    isErr = true;
  }

  if (!isErr && specification.length > 50) {
    errMessage = errMsgForLength("営業日報", "50");
    isErr = true;
  }

  if (!isErr) {
    var dw_div2_checked = checkEmptyForRadio(dw_div2);
    if (!dw_div2_checked) {
      errMessage = errMsgForEmpty("種類");
      isErr = true;
    }
  }

  return errMessage;
}
