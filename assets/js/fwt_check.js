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
  var post_name = document.getElementById("post_name"); // 役職・氏名　等
  var p_number = document.getElementById("p_number"); //人　数
  var companion = document.getElementById("companion"); // 当社同行者
  var p_demo_note = document.getElementById("p_demo_note"); // 製品デモメモ
  var dvd_gd_note = document.getElementById("dvd_gd_note"); // DVD案内メモ
  var d_document_note = document.getElementById("d_document_note"); // 内容
  var other_req = document.getElementById("other_req"); // その他客先要望
  var note = document.getElementById("note"); // 備　考
  var name = document.getElementById("name"); // 品名
  var size = document.getElementById("size"); // サイズ
  var quantity = document.getElementById("quantity"); // 数量
  var card_no = document.getElementById("card_no"); // カード番号
  var inspection_note = document.getElementById("inspection_note"); // その他

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

  if (!isErr && purpose.value.trim() == "") {
    errMessage = errMsgForEmpty("目的");
    isErr = true;
  }

  if (!isErr && purpose.value.length > 80) {
    errMessage = errMsgForLength("目的", "80");
    isErr = true;
  }

  if (!isErr && post_name.value.length > 50) {
    errMessage = errMsgForLength("役職・氏名　等", "50");
    isErr = true;
  }

  if (!isErr && p_number.value.length > 10) {
    errMessage = errMsgForLength("人数", "10");
    isErr = true;
  }

  if (!isErr && companion.value.length > 10) {
    errMessage = errMsgForLength("当社同行者", "10");
    isErr = true;
  }

  if (!isErr && p_demo_note.value.length > 80) {
    errMessage = errMsgForLength("製品デモメモ", "80");
    isErr = true;
  }

  if (!isErr && dvd_gd_note.value.length > 80) {
    errMessage = errMsgForLength("DVD案内メモ", "80");
    isErr = true;
  }

  if (!isErr && d_document_note.value.length > 80) {
    errMessage = errMsgForLength("内容", "80");
    isErr = true;
  }

  if (!isErr && other_req.value.length > 80) {
    errMessage = errMsgForLength("その他客先要望", "80");
    isErr = true;
  }

  if (!isErr && note.value.length > 80) {
    errMessage = errMsgForLength("備考", "80");
    isErr = true;
  }

  if (!isErr && name.value.length > 80) {
    errMessage = errMsgForLength("品名", "80");
    isErr = true;
  }

  if (!isErr && size.value.length > 10) {
    errMessage = errMsgForLength("サイズ", "10");
    isErr = true;
  }

  if (!isErr && quantity.value.length > 10) {
    errMessage = errMsgForLength("数量", "10");
    isErr = true;
  }

  if (!isErr && card_no.value.length > 10) {
    errMessage = errMsgForLength("カード番号", "10");
    isErr = true;
  }

  if (!isErr && inspection_note.value.length > 80) {
    errMessage = errMsgForLength("その他", "80");
    isErr = true;
  }

  return errMessage;
}

function checkValidation2() {
  var isErr = false;
  var errMessage = "";
  var fixed_date = document.getElementById("fixed_date"); //確定日程
  var fixed_start = document.getElementById("fixed_start"); //時間
  var fixed_end = document.getElementById("fixed_end"); //時間
  var note = document.getElementById("note"); //備　考

  
  if (!isErr && note.value.length > 80) {
    errMessage = errMsgForLength("備考", "80");
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
