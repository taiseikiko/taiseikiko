function errMsgForLength(name, max) {
  return "「" + name + "」は文字数「" + max + "」以下で入力してください。";
}

function errMsgForEmpty(name, type) {
  return (
    "「" +
    name +
    "」を" +
    (type == "input" ? "入力してください。" : "選択してください。")
  );
}

function checkValidation(event) {
  var errMessage = '';
  var isErr = false;
  var zk_div_data = document.getElementById("zk_div_data").value;
  var zk_div_data_nm = "材工名仕様詳細";
  var zk_div_name = document.getElementById("zk_div_name").value;
  var zk_div_nm = "材工仕様";
  var zk_tp = document.getElementById("zk_tp").value;
  var zk_tp_nm = "区分１";
  var zk_no = document.getElementById("zk_no").value;
  var zk_no_nm = "区分２";  

  if (!isErr && zk_div_name == "") {
    errMessage = errMsgForEmpty(zk_div_nm, 'dropdown');
    isErr = true;
  }

  if (!isErr && zk_tp == "") {
    errMessage = errMsgForEmpty(zk_tp_nm, "dropdown");
    isErr = true;
  }

  if (!isErr && zk_no == "") {
    errMessage = errMsgForEmpty(zk_no_nm, "dropdown");
    isErr = true;
  }

  if (!isErr && zk_div_data.length > 50) {
    errMessage = errMsgForLength(zk_div_data_nm, "50");
    isErr = true;
  }

  if (!isErr && zk_div_data == "") {
    errMessage = errMsgForEmpty(zk_div_data_nm, "input");
    isErr = true;
  }

  return errMessage;
}
