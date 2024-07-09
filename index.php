<?php
session_start();
/* index_data_set.php                      */
/*　・初期処理                                         */
/*　・画面データ採取、画面フィールド変数へデータセット     */
  include("index_data_set.php");

/* header.html                                        */  
/*   ・ヘッダー記述                                    */  
/*   ・メニュー記述                                    */  
/*   ・サイドバー記述                                  */  
  include("header1.php");

?>
<main>

<!-- 画像スライダー -->
<div class="img-box">
  <div></div>
  <div></div>
  <div></div>
</div>

</main>

<?php
/* footer.html                                        */  
/*   ・フッター記述                                    */  
  include("footer.html");
?>
