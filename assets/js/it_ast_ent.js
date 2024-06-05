/*                          */
/* 社員検索子画面呼び出し   */
/*                          */
function emp_open() {
    window.open('inq_employee2.php', 'new_window','width=800,height=600,left=100,top=50');
}

/*                   */
/* 選択された後処理    */
/*                   */
function child14(btn){
    var x = btn; // 押されたボタンの配列の指標をセット
    var id = "";
    var employee_code_array = employee_cd;  // 呼び出し元PHPで JSON_encode した配列
    var employee_name_array = employee_nm;
    //var employee_cd = "";
    //var employee_nm = "";

    //var employee_cd = e_code;
    //var employee_nm = e_name;

      //console.log(x);
      //console.log("employee_name_array=" + employee_name_array);

    // 配列の指標に従ってデータセット
    employee_cd = employee_code_array.splice(x,1);
    employee_nm = employee_name_array.splice(x,1);
      //console.log("x=" + x);
      //console.log("employee_cd=" + employee_cd);
      //console.log("employee_nm=" + employee_nm);

    // 親画面に値を挿入
      window.opener.document.getElementById("it_user_cd").value = employee_cd;
      window.opener.document.getElementById("it_user_nm").value = employee_nm;

  // 呼び出し元へ選択データ転送
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "it_ast_ent_detail.php");
  xhr.responseType = "json"; 
  xhr.addEventListener("load", () => {
    console.log(xhr.response.set1); // Uncaught TypeError: Cannot read properties of null (reading 'test1')
  });
  xhr.send(employee_cd);

    // ウィンドウを閉じる
     window.close();
}
