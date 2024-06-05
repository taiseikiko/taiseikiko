/*                          */
/* 社員検索子画面呼び出し   */
/*                          */
function emp_inq_open() {
    window.open('issue_employee.php', 'new_window','width=800,height=600,left=100,top=50');
}

/*                   */
/* 選択された後処理    */
/*                   */
function inq_ent(btn){
//function child11(btn,e_code,e_name){
    var x = btn; // 押されたボタンの配列の指標をセット
    var id = "";
    var employee_code_array = employee_cd;  // 呼び出し元PHPで JSON_encode した配列
    var employee_name_array = employee_nm;
    var dept_code_array = dept_cd;  
    var dept_name_array = dept_nm;
    var op_code_array = op_cd;  
    var op_name_array = op_nm;
    //var employee_cd = "";
    //var employee_nm = "";

    //var employee_cd = e_code;
    //var employee_nm = e_name;

      //console.log(x);
      //console.log("employee_name_array=" + employee_name_array);

    // 配列の指標に従ってデータセット
    employee_cd = employee_code_array.splice(x,1);
    employee_nm = employee_name_array.splice(x,1);
    dept_cd = dept_code_array.splice(x,1);
    dept_nm = dept_name_array.splice(x,1);
    op_cd = op_code_array.splice(x,1);
    op_nm = op_name_array.splice(x,1);
      //console.log("x=" + x);
      //console.log("employee_cd=" + employee_cd);
      //console.log("employee_nm=" + employee_nm);
      //console.log("dept_cd=" + dept_cd);
      //console.log("dept_nm=" + dept_nm);
      //console.log("op_cd=" + op_cd);
      //console.log("op_nm=" + op_nm);

    // 親画面に値を挿入
      window.opener.document.getElementById("contact_person").value = employee_cd;
      window.opener.document.getElementById("cp_name").value = employee_nm;
      window.opener.document.getElementById("dept_code").value = dept_cd;
      window.opener.document.getElementById("dept_name").value = dept_nm;
      window.opener.document.getElementById("office_position_code").value = op_cd;
      window.opener.document.getElementById("op_name").value = op_nm;

  // 呼び出し元へ選択データ転送
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "issue_ent2.php");
  xhr.responseType = "json"; 
  xhr.addEventListener("load", () => {
    console.log(xhr.response.set1); // Uncaught TypeError: Cannot read properties of null (reading 'test1')
  });
  xhr.send(employee_cd);
  //xhr.send(employee_nm);
  //xhr.send(dept_cd);
  //xhr.send(dept_nm);
  //xhr.send(op_cd);
  //xhr.send(op_nm);

    // ウィンドウを閉じる
     window.close();
}
