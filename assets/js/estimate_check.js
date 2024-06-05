/*               */
/* 入力チェック   */
/*               */
function estimate_check() {

    // 画面HTML要素を取得
    //var estimate_no = document.getElementById("estimate_no").value;
    var customer_no = document.getElementById("customer_no").value;
    var item_no = document.getElementById("item_no").value;

    // 得意先№エラー
    if(customer_no == ""){
      alert("Customer number is required!");
      return false;  
    }

    // 品番エラー
    if(item_no == ""){
      alert("Item number is required!");
      return false;  
    }

    // 船便or航空便選択エラー（チェックボックス判定）
    var flag = "false";
    if(document.estimate1.by_air_or_sea[0].checked){
      flag = "true";
    }
    if(document.estimate1.by_air_or_sea[1].checked){
      flag = "true";
    }

    if(flag == "false"){
      alert("by_air_or_sea is required!");
      return false;  
    }

  }