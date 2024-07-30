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
  var class_ = document.getElementById("class"); //種類
  var candidate1_date = document.getElementById("candidate1_date"); //第１候補日
  var candidate1_start = document.getElementById("candidate1_start"); //時間
  var candidate1_end = document.getElementById("candidate1_end"); //時間
  var pf_name = document.getElementById("pf_name"); //受注官庁
  var cust_name = document.getElementById("cust_name"); //来客社名
  var purpose = document.getElementById("purpose"); //目　的

  if (!isErr && class_.selectedIndex === 0) {
    errMessage = errMsgForEmpty("種類");
    isErr = true;
  }

  if (!isErr && candidate1_date.value == "") {
    errMessage = errMsgForEmpty("第１候補日");
    isErr = true;
  }

  if (!isErr && candidate1_start.value == "") {
    errMessage = errMsgForEmpty("第１候補時間");
    isErr = true;
  }

  if (!isErr && candidate1_end.value == "") {
    errMessage = errMsgForEmpty("第１候補時間");
    isErr = true;
  }

  if (!isErr && pf_name.value == "") {
    errMessage = errMsgForEmpty("受注官庁");
    isErr = true;
  }

  if (!isErr && cust_name.value == "") {
    errMessage = errMsgForEmpty("来客社名");
    isErr = true;
  }

  if (!isErr && purpose.value == "") {
    errMessage = errMsgForEmpty("目的");
    isErr = true;
  }

  return errMessage;
}
