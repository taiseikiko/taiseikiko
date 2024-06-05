/*                          */
/* 社員検索子画面呼び出し   */
/*                          */
function emp_inq_open_() {
  window.open(
    "inq_employee.php",
    "new_window",
    "width=500,height=600,left=100,top=50"
  );
}

/*                   */
/* 選択された後処理    */
/*                   */
function inq_ent_(btn) {
  var x = btn; // 押されたボタンの配列の指標をセット
  var id = "";
  var employee_code_array = employee_cd; // 呼び出し元PHPで JSON_encode した配列
  var employee_name_array = employee_nm;
  var dept_code_array = dept_cd;
  var dept_name_array = dept_nm;
  var op_code_array = op_cd;
  var op_name_array = op_nm;

  // 配列の指標に従ってデータセット
  employee_cd = employee_code_array.splice(x, 1);
  employee_nm = employee_name_array.splice(x, 1);
  dept_cd = dept_code_array.splice(x, 1);
  dept_nm = dept_name_array.splice(x, 1);
  op_cd = op_code_array.splice(x, 1);
  op_nm = op_name_array.splice(x, 1);

  // 親画面に値を挿入
  var data = {
    employee_code: employee_cd,
    employee_name: employee_nm,
    department_code: dept_cd,
    department_name: dept_nm,
    office_position_name: op_cd,
    office_position_name: op_nm,
  };

  window.opener.receiveDataFromPopup(data);

  // ウィンドウを閉じる
  window.close();
}
