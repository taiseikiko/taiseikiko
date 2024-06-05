<?php

/* sales_request_input01_set.php                      */
/*　・初期処理                                         */
/*　・画面データ採取、画面フィールド変数へデータセット     */
  include("receptionist_input_data_set.php");

/* header.html                                        */  
/*   ・ヘッダー記述                                    */  
/*   ・メニュー記述                                    */  
/*   ・サイドバー記述                                  */  
  include("header1.php");

?>
<main>
  <h3>【　営業依頼書処理：受付担当入力　】</h3>

    <h4>部署毎の受付担当者を入力します。</h4>

    <form class="row g-3" method="POST" name="sq_form1">

    <table>
    <tr>
    <td>
      <div class="field-row">
        <label for="text26">部署名　　　　　</label>
        <select name="dept" onchange="submit(this.form)">
          <option value="" <?php if($dept_code == ""){ echo 'selected';}else{ echo '';}
            ?>>選択して下さい。</option>
        <?php
          $opt = "";
          $x = count($dp_code);
          for($y=0; $y<$x; $y++){
            $opt .= '<option value='.$dp_code[$y].'';
            if($dept_code == $dp_code[$y]){
              $opt .= ' selected>'.$dp_name[$y].'</option>';
              }
               else{
              $opt .= '>'.$dp_name[$y].'</option>';
                } 
          }
          echo $opt;
          ?>
          </select>
      </div>
    </td>
    </tr>
    </table>
    </form>

<?php
if(!empty($_POST['dept'])){
$html = '
  <form class="row g-3" method="POST" action="receptionist_update.php">
    <table>';

for($i=1; $i<6; $i++){
  $html .='
    <tr>
    <td>
      <div class="field-row">
        <label for="text26">受付担当者'.$i.'　　</label>
        <select name="recept1">
          <option value=""';
          $r_code = '$recept'.$i.'_code';

           if($$r_code == ""){ $html .=' selected';}
            $html .= '選択して下さい。</option>';
          $opt = "";
          $x = count($emp_code);
          for($y=0; $y<$x; $y++){
            $opt .= '<option value='.$emp_code[$y].'';
            if($$r_code == $emp_code[$y]){
              $opt .= ' selected>'.$emp_name[$y].'</option>';
              }
               else{
              $opt .= '>'.$emp_name[$y].'</option>';
                } 
          }
          $html .= $opt;

$r_cm = '$r_com'.$i;
$html .= '</select>
      </div>
    </td>
    <td>
      <div class="field-row">
        <label for="text26">　　受付担当者'.$i.'コメント</label>
        <input type="text" id="text26" name="r_com1" style="width: 450px;"';
          if(isset($$r_cm)){$html .= ' value='."'{$$r_cm}'";} 
    $html .= '>
      </div>
    </td>
  </tr>';
}
$html .= '
  </table>
  <input type="hidden" name="dept_code" value="'.$_POST['dept'].'">';

  echo $html;
  }
?>
  <table>
    <tr>
      <td>
        <button name="return">戻　る</button>
      </td>
      <td>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      </td>
      <td>
        <button class="default" name="submit" onclick="return ps_check();">更　新</button>
      </td>
    </tr>
  </table>
  </form>
  </main>

  <script>
    function sq_fm_reset(){
      document.sq_form1.reset();
    }
  </script>

<?php
/* footer.html                                        */  
/*   ・フッター記述                                    */  
  include("footer.html");
?>
