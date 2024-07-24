function errMsgForLength(name, max) {
  return "「" + name + "」は文字数「" + max + "」以下で入力してください。";
}


function errMsgForEmpty(name) {
  return "「" + name + "」を入力してください。";
}

function errMsgForEmptySelect(name) {
  return "「" + name + "」を選択してください。";
}

function checkValidationInput2() {
  var isErr = false;
  var errMessage = "";
  var item_name = document.getElementById("item_name").value; //件名
  var cust_dept = document.getElementById("cust_dept").value; //担当部署
  var cust_pic = document.getElementById("cust_pic").value; //担当者
  var pf_dept = document.getElementById("pf_dept").value; //担当部署
  var pf_pic = document.getElementById("pf_pic").value; //担当者
  var daily_report_url = document.getElementById("daily_report_url").value; //営業日報
  var note = document.getElementById("note").value; //備考

  if (!isErr && item_name.length > 200) {
    errMessage = errMsgForLength("件名", "200");
    isErr = true;
  }

  if (!isErr && cust_dept.length > 40) {
    errMessage = errMsgForLength("担当部署", "40");
    isErr = true;
  }

  if (!isErr && cust_pic.length > 40) {
    errMessage = errMsgForLength("担当者", "40");
    isErr = true;
  }

  if (!isErr && pf_dept.length > 40) {
    errMessage = errMsgForLength("担当部署", "40");
    isErr = true;
  }

  if (!isErr && pf_pic.length > 40) {
    errMessage = errMsgForLength("担当者", "40");
    isErr = true;
  }

  if (!isErr && daily_report_url.length > 200) {
    errMessage = errMsgForLength("営業日報", "200");
    isErr = true;
  }

  if (!isErr && note.length > 80) {
    errMessage = errMsgForLength("備考", "80");
    isErr = true;
  }

  return errMessage;
}

function checkValidationInput3() {
  var isErr = false;
  var errMessage = "";
  var design_water_pressure = document.getElementById(
    "design_water_pressure"
  ).value; //設計水圧
  var normal_water_puressure = document.getElementById(
    "normal_water_puressure"
  ).value; //常圧
  var inner_film = document.getElementById("inner_film").value; //膜厚
  var outer_film = document.getElementById("outer_film").value; //膜厚
  var quantity = document.getElementById("quantity").value; //数量
  var right_quantity = document.getElementById("right_quantity").value; //右用
  var left_quantity = document.getElementById("left_quantity").value; //左用
  var special_note = document.getElementById("special_note").value; //特記仕様

  if (!isErr && design_water_pressure.length > 15) {
    errMessage = errMsgForLength("設計水圧", "15");
    isErr = true;
  }

  if (!isErr && normal_water_puressure.length > 15) {
    errMessage = errMsgForLength("常圧", "15");
    isErr = true;
  }

  if (!isErr && inner_film.length > 10) {
    errMessage = errMsgForLength("膜厚", "10");
    isErr = true;
  }

  if (!isErr && outer_film.length > 10) {
    errMessage = errMsgForLength("膜厚", "10");
    isErr = true;
  }

  if (!isErr && quantity.length > 10) {
    errMessage = errMsgForLength("数量", "10");
    isErr = true;
  }

  if (!isErr && right_quantity.length > 10) {
    errMessage = errMsgForLength("右用", "10");
    isErr = true;
  }

  if (!isErr && left_quantity.length > 10) {
    errMessage = errMsgForLength("左用", "10");
    isErr = true;
  }

  if (!isErr && special_note.length > 80) {
    errMessage = errMsgForLength("特記仕様", "80");
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
