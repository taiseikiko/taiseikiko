/*               */
/* 入力チェック   */
/*               */
function ps_check() {

    // 画面HTML要素を取得
    var replacement_dev = document.getElementById("replacement_dev").value;
    var r_person_name = document.getElementById("rp_name").value;
    var moving = document.getElementById("moving").value;
    var moving_com = document.getElementById("moving_com").value;

    // 後任区分チェック
    if(replacement_dev == "1" && r_person_name == ""){
      alert("[後任適任者] を選択して下さい。");
      return false;  
    }
    if(replacement_dev == "0" && r_person_name != ""){
      alert("後任不要の場合は、[後任適任者] を入力しないで下さい。");
      return false;  
    }

    // 転居考慮の要・不要
    if(moving == "1" && moving_com == ""){
      alert("「転居の考慮が必要な理由」を入力して下さい。");
      return false;  
    }
    // 転居考慮の要・不要
    if(moving == "0" && moving_com != ""){
      alert("転居の考慮が不要の場合は、「転居の考慮が必要な理由」を入力しないで下さい。");
      return false;  
    }


  }