function errMsgForLength(name, max) {
  return "「" + name + "」は文字数「" + max + "」以下で入力してください。";
}

function errMsgForEmpty(name) {
  return "「" + name + "」を入力してください。";
}

function checkValidation(event) {
  var isErr = false;
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
    alert(errMsgForLength("設計水圧", "15"));
    isErr = true;
  }

  if (!isErr && normal_water_puressure.length > 15) {
    alert(errMsgForLength("常圧", "15"));
    isErr = true;
  }

  if (!isErr && inner_film.length > 10) {
    alert(errMsgForLength("膜厚", "10"));
    isErr = true;
  }

  if (!isErr && outer_film.length > 10) {
    alert(errMsgForLength("膜厚", "10"));
    isErr = true;
  }

  if (!isErr && quantity.length > 10) {
    alert(errMsgForLength("数量", "10"));
    isErr = true;
  }

  if (!isErr && right_quantity.length > 10) {
    alert(errMsgForLength("右用", "10"));
    isErr = true;
  }

  if (!isErr && left_quantity.length > 10) {
    alert(errMsgForLength("左用", "10"));
    isErr = true;
  }

  if (!isErr && special_note.length > 80) {
    alert(errMsgForLength("特記仕様", "80"));
    isErr = true;
  }

  if (isErr) {
    event.preventDefault();
  }
}
