/*                          */
/* 得意先検索子画面呼び出し   */
/*                          */
function child1_open() {
    window.open('inq_customer.php', 'new_window','width=1000,height=600,left=100,top=150');
}

/*                   */
/* 選択された後処理    */
/*                   */
function child1(btn){
    var x = btn; // 押されたボタンの配列の指標をセット
    var id = "";
    var cust_no_array = customer_number;  // 呼び出し元PHPで JSON_encode した配列
    var cust_nm_array = customer_name;
    var cust_no = "";
    var cust_nm = "";

    //console.log(x);
    //console.log("cust_nm_array=" + cust_nm_array);

    // 配列の指標に従ってデータセット
    cust_no = cust_no_array.splice(x,1);
    cust_nm = cust_nm_array.splice(x,1);
    //console.log("x=" + x);
    //console.log("cust_no=" + cust_no);
    //console.log("cust_nm=" + cust_nm);

    // 親画面に値を挿入
      window.opener.document.getElementById("customer_no").value = cust_no;
      window.opener.document.getElementById("customer_name").value = cust_nm;
      window.opener.document.getElementById("cust_no").value = cust_no;

  // 呼び出し元へ選択データ転送
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "estimate_entry.php");
  xhr.responseType = "json"; 
  xhr.addEventListener("load", () => {
    console.log(xhr.response.set1); // Uncaught TypeError: Cannot read properties of null (reading 'test1')
  });
  xhr.send(cust_no);

    // ウィンドウを閉じる
     window.close();
}

/*                        */
/* 品目検索子画面呼び出し   */
/*                        */
function child2_open() {
  window.open('inq_item.php', 'new_window','width=1000,height=600,left=100,top=150');
}

/*                  */
/* 選択された後処理  */
/*                  */
function child2(btn){
  var x = btn; // 押されたボタンの配列の指標をセット
  var id = "";
  var item_no_array = item_number;  // 呼び出し元PHPで JSON_encode した配列
  var item_nm_array = item_name;
  var description_array = description;
  var item_no = "";
  var item_nm = "";
  var dc = "";

  //console.log(x);
  //console.log("item_nm_array=" + item_nm_array);
  //console.log("description_array=" + description_array);

  // 配列の指標に従ってデータセット
  item_no = item_no_array.splice(x,1);
  item_nm = item_nm_array.splice(x,1);
  dc = description_array.splice(x,1);
  //console.log("x=" + x);
  //console.log("item_no=" + item_no);
  //console.log("item_nm=" + item_nm);
  //console.log("description=" + description);

  // 親画面に値を挿入
    window.opener.document.getElementById("item_no").value = item_no;
    window.opener.document.getElementById("item_name").value = item_nm;
    window.opener.document.getElementById("description").value = dc;


  // 呼び出し元へ選択データ転送
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "estimate_entry.php");
  xhr.responseType = "json"; 
  xhr.addEventListener("load", () => {
    console.log(xhr.response.set2); // Uncaught TypeError: Cannot read properties of null (reading 'test1')
  });
  //let date = new FormData();
  //date.append(item_no);
  //xhr.send(date);
  xhr.send(item_no);

  // ウィンドウを閉じる
   window.close();
}