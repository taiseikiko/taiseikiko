function errMsgForLength(name, max) {
  return "「" + name + "」は文字数「" + max + "」以下で入力してください。";
}

function errMsgForEmpty(name) {
  return "「" + name + "」を入力してください。";
}

function checkValidation(event) {
  var isErr = false;
  var errMessage = "";
  var ec_number = document.getElementById("ec_number"); //工事番号
  var specification_number = document.getElementById("specification_number"); //仕様書番号
  var scene_water_pressure = document.getElementById("scene_water_pressure"); //現場水圧
  var slant = document.getElementById("slant"); //傾斜
  var m_cost = document.getElementById("m_cost"); //原価（材料）
  var m_orders = document.getElementById("m_orders"); //受注（材料）
  var wt_cost = document.getElementById("wt_cost"); //原価（割T）
  var valve_cost = document.getElementById("valve_cost"); //原価（バルブ）
  var wt_orders = document.getElementById("wt_orders"); //受注（割T）
  var valve_orders = document.getElementById("valve_orders"); //受注（バルブ）
  var con_cost = document.getElementById("con_cost"); //原価（工事）
  var con_orders = document.getElementById("con_orders"); //受注（工事）
  var cause = document.getElementById("cause"); //原因
  var gross_footnote = document.getElementById("gross_footnote"); //粗利備考
  var quantity = document.getElementById("quantity"); //数量
  var footnote1 = document.getElementById("footnote1"); //備考
  var shape = document.getElementById("shape"); //形
  var ec_extension = document.getElementById("ec_extension"); //施工延長
  var ec_name = document.getElementById("ec_name"); //工事名称
  var water_pressure = document.getElementById("water_pressure"); //水圧
  var footnote2 = document.getElementById("footnote2"); //水圧

  if (ec_number) {
    if (!isErr && ec_number.value.length > 10) {
      errMessage = errMsgForLength("工事番号", "10");
      isErr = true;
    }
  }

  if (specification_number) {
    if (!isErr && specification_number.value.length > 10) {
      errMessage = errMsgForLength("仕様書番号", "10");
      isErr = true;
    }
  }

  if (scene_water_pressure) {
    if (!isErr && scene_water_pressure.value.length > 10) {
      errMessage = errMsgForLength("現場水圧", "10");
      isErr = true;
    }
  }

  if (slant) {
    if (!isErr && slant.value.length > 10) {
      errMessage = errMsgForLength("傾斜", "10");
      isErr = true;
    }
  }

  if (m_cost) {
    if (!isErr && m_cost.value.length > 10) {
      errMessage = errMsgForLength("原価（材料）", "10");
      isErr = true;
    }
  }

  if (m_orders) {
    if (!isErr && m_orders.value.length > 10) {
      errMessage = errMsgForLength("受注（材料）", "10");
      isErr = true;
    }
  }

  if (wt_cost) {
    if (!isErr && wt_cost.value.length > 10) {
      errMessage = errMsgForLength("原価（割T）", "10");
      isErr = true;
    }
  }

  if (valve_cost) {
    if (!isErr && valve_cost.value.length > 10) {
      errMessage = errMsgForLength("原価（バルブ）", "10");
      isErr = true;
    }
  }

  if (wt_orders) {
    if (!isErr && wt_orders.length > 10) {
      errMessage = errMsgForLength("受注（割T）", "10");
      isErr = true;
    }
  }

  if (valve_orders) {
    if (!isErr && valve_orders.value.length > 10) {
      errMessage = errMsgForLength("受注（バルブ）", "10");
      isErr = true;
    }
  }

  if (con_cost) {
    if (!isErr && con_cost.value.length > 10) {
      errMessage = errMsgForLength("原価(工事)", "10");
      isErr = true;
    }
  }

  if (con_orders) {
    if (!isErr && con_orders.value.length > 10) {
      errMessage = errMsgForLength("受注（工事）", "10");
      isErr = true;
    }
  }

  if (cause) {
    if (!isErr && cause.value.length > 100) {
      errMessage = errMsgForLength("原因", "100");
      isErr = true;
    }
  }

  if (gross_footnote) {
    if (!isErr && gross_footnote.value.length > 100) {
      errMessage = errMsgForLength("粗利備考", "100");
      isErr = true;
    }
  }

  if (quantity) {
    if (!isErr && quantity.value.length > 10) {
      errMessage = errMsgForLength("数量", "10");
      isErr = true;
    }
  }

  if (footnote1) {
    if (!isErr && footnote1.value.length > 100) {
      errMessage = errMsgForLength("備考", "100");
      isErr = true;
    }
  }

  if (shape) {
    if (!isErr && shape.value.length > 50) {
      errMessage = errMsgForLength("形", "50");
      isErr = true;
    }
  }

  if (ec_extension) {
    if (!isErr && ec_extension.value.length > 10) {
      errMessage = errMsgForLength("施工延長", "10");
      isErr = true;
    }
  }

  if (ec_name) {
    if (!isErr && ec_name.value.length > 100) {
      errMessage = errMsgForLength("工事名称", "100");
      isErr = true;
    }
  }

  if (water_pressure) {
    if (!isErr && water_pressure.value.length > 10) {
      errMessage = errMsgForLength("水圧", "10");
      isErr = true;
    }
  }

  if (footnote2) {
    if (!isErr && footnote2.value.length > 100) {
      errMessage = errMsgForLength("備考２", "100");
      isErr = true;
    }
  }

  return errMessage;
}
