function errMsgForLength(name, max) {
  return "「" + name + "」は文字数「" + max + "」以下で入力してください。";
}

function errMsgForEmpty(name) {
  return "「" + name + "」を入力してください。";
}

function checkValidation(event) {
  var isErr = false;
  var errMessage = "";
  var property_code = document.getElementById("property_code").value; //定価（割T）
  // IV/IVT物件情報の場合 
  if (property_code == '1') {
    var m_listprice = document.getElementById("m_listprice").value; //定価（材料）
    var m_cost = document.getElementById("m_cost").value; //原価（材料）
    var m_orders = document.getElementById("m_orders").value; //受注（材料）

    if (!isErr && m_listprice.length > 10) {
      errMessage = errMsgForLength("定価（材料）", "10");
      isErr = true;
    }

    if (!isErr && m_cost.length > 10) {
      errMessage = errMsgForLength("原価（材料）", "10");
      isErr = true;
    }

    if (!isErr && m_orders.length > 10) {
      errMessage = errMsgForLength("受注（材料）", "10");
      isErr = true;
    }
  } else {
    var wt_listprice = document.getElementById("wt_listprice").value; //定価（割T）
    var valve_listprice = document.getElementById("valve_listprice").value; //定価（バルブ）
    var wt_cost = document.getElementById("wt_cost").value; //原価（割T）
    var valve_cost = document.getElementById("valve_cost").value; //原価（バルブ）
    var wt_orders = document.getElementById("wt_orders").value; //受注（割T）
    var valve_orders = document.getElementById("valve_orders").value; //受注（バルブ）

    if (!isErr && wt_listprice.length > 10) {
      errMessage = errMsgForLength("定価（割T）", "10");
      isErr = true;
    }

    if (!isErr && valve_listprice.length > 10) {
      errMessage = errMsgForLength("定価（バルブ）", "10");
      isErr = true;
    }

    if (!isErr && wt_cost.length > 10) {
      errMessage = errMsgForLength("原価（割T）", "10");
      isErr = true;
    }

    if (!isErr && valve_cost.length > 10) {
      errMessage = errMsgForLength("原価（バルブ）", "10");
      isErr = true;
    }

    if (!isErr && wt_orders.length > 10) {
      errMessage = errMsgForLength("受注（割T）", "10");
      isErr = true;
    }

    if (!isErr && valve_orders.length > 10) {
      errMessage = errMsgForLength("受注（バルブ）", "10");
      isErr = true;
    }
  }
  
  var con_listprice = document.getElementById("con_listprice").value; //定価（工事）  
  var con_cost = document.getElementById("con_cost").value; //原価（工事）
  var card_no = document.getElementById("card_no").value; //カード№
  var ec_no = document.getElementById("ec_no").value; //工事番号
  var contact = document.getElementById("contact").value; //契約先  
  var con_orders = document.getElementById("con_orders").value; //受注（工事）
  var footnote = document.getElementById("footnote").value; //備考  

  if (!isErr && con_listprice.length > 10) {
    errMessage = errMsgForLength("定価（工事）", "10");
    isErr = true;
  }  

  if (!isErr && con_cost.length > 10) {
    errMessage = errMsgForLength("原価（工事）", "10");
    isErr = true;
  }

  if (!isErr && card_no.length > 10) {
    errMessage = errMsgForLength("カード№", "10");
    isErr = true;
  }

  if (!isErr && ec_no.length > 10) {
    errMessage = errMsgForLength("工事番号", "10");
    isErr = true;
  }

  if (!isErr && contact.length > 10) {
    errMessage = errMsgForLength("契約先", "20");
    isErr = true;
  }  

  if (!isErr && con_orders.length > 10) {
    errMessage = errMsgForLength("受注（工事）", "10");
    isErr = true;
  }

  if (!isErr && footnote.length > 10) {
    errMessage = errMsgForLength("備考", "50");
    isErr = true;
  }

  return errMessage;
}
