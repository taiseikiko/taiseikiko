function errMsgForLength(name, max) {
    return "「" + name + "」は文字数「" + max + "」以下で入力してください。";
  }
  
  function errMsgForEmpty(name) {
    return "「" + name + "」を入力してください。";
  }
  
  function checkValidation(event) {
    var errMessage = '';
    var isErr = false;
    var code_name = document.getElementById("code_name").value;
    var class_nm = "コード名";
  
    if (!isErr && code_name.length > 50) {
      errMessage = errMsgForLength(class_nm, "50");
      isErr = true;
    }
  
    if (!isErr && code_name == "") {
      errMessage = errMsgForEmpty(class_nm);
      isErr = true;
    }
  
    return errMessage;
  }
  