function errMsgForLength(name, max) {
  return "「" + name + "」は文字数「" + max + "」以下で入力してください。";
}

function checkValidation(event) {
  var isErr = false;
  var item_name = document.getElementById("item_name").value; //件名
  var cust_dept = document.getElementById("cust_dept").value; //担当部署
  var cust_pic = document.getElementById("cust_pic").value; //担当者
  var pf_dept = document.getElementById("pf_dept").value; //担当部署
  var pf_pic = document.getElementById("pf_pic").value; //担当者
  var daily_report_url = document.getElementById("daily_report_url").value; //営業日報
  var note = document.getElementById("note").value; //備考

  if (!isErr && item_name.length > 200) {
    alert(errMsgForLength("件名", "200"));
    isErr = true;
  }

  if (!isErr && cust_dept.length > 40) {
    alert(errMsgForLength("担当部署", "40"));
    isErr = true;
  }

  if (!isErr && cust_pic.length > 40) {
    alert(errMsgForLength("担当者", "40"));
    isErr = true;
  }

  if (!isErr && pf_dept.length > 40) {
    alert(errMsgForLength("担当部署", "40"));
    isErr = true;
  }

  if (!isErr && pf_pic.length > 40) {
    alert(errMsgForLength("担当者", "40"));
    isErr = true;
  }

  if (!isErr && daily_report_url.length > 50) {
    alert(errMsgForLength("営業日報", "50"));
    isErr = true;
  }

  if (!isErr && note.length > 80) {
    alert(errMsgForLength("備考", "80"));
    isErr = true;
  }

  if (isErr) {
    event.preventDefault();
  }
}
