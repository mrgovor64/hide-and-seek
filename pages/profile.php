<?
if(preg_match("/[\d]+/",$_GET[variable_2])){
  $id = mysql_query("SELECT * FROM accounts LEFT JOIN profiles USING (id) WHERE id='$_GET[variable_2]'", $bdconnect) or die("Error BD!");
  $id = mysql_fetch_assoc($id);
} elseif(preg_match("/[a-zA-Z]{1}[a-zA-Z\d_]+/",$_GET[variable_2])){
  $id = mysql_query("SELECT * FROM accounts LEFT JOIN profiles USING (id) WHERE login='$_GET[variable_2]'", $bdconnect) or die("Error BD!");
  $id = mysql_fetch_assoc($id);
} else {
  $id = $iam;
}

if(!$id[id]){
  echo "Ошибка страницы!123";
}else{
  include "php_scripts/functions.php";
  $words['time'] = array('раз','раза','раз');
  $words['game'] = array('игре','играх','играх');
  $words['rabbit'] = array('заяца','зайцев','зайцев');
  $words['day'] = array('день','дня','дней');

  function padeg($num, $ar){
    echo $num." ".word_num($num,$ar);
  }

?>

<div class="row">
  <div class="col-sm-6 col-xs-12">
    <img src="http://semantic-ui.com/images/avatar2/large/matthew.png" alt="" class="w_100">
    <div class="table w_100 m_b-20">
      <div class="table_cell left"><p><? echo $id[name]; ?></p></div>
      <div class="table_cell right"><p>Стаж игры <? echo  padeg(round(($time - $id[account_date_reg])/86400) ,$words['day']); ?></p></div>
    </div>
  </div>
  <div class="col-sm-6 col-xs-12">
    <h2 class="m_b-20 divided_b-white center">Послужной список:</h2>
      <div class="table divided_b-white w_100">
        <div class="table_cell left">Участвовал в</div>
        <div class="table_cell right"><? echo  padeg(rand(1,20),$words['game']); ?></div>
      </div>
      <div class="table divided_b-white w_100">
        <div class="table_cell left">Занял первое место</div>
        <div class="table_cell right"><? echo  padeg(rand(1,20),$words['time']); ?></div>
      </div>
      <div class="table divided_b-white w_100">
        <div class="table_cell left">Оставался последним зайцем</div>
        <div class="table_cell right"><? echo  padeg(rand(1,20),$words['time']); ?></div>
      </div>
      <div class="table divided_b-white w_100">
        <div class="table_cell left">Нашел</div>
        <div class="table_cell right"><? echo  padeg(rand(1,20),$words['rabbit']); ?></div>
      </div>
      <div class="table divided_b-white w_100">
        <div class="table_cell left">Был пойман</div>
        <div class="table_cell right"><? echo  padeg(rand(1,20),$words['time']); ?></div>
      </div>
  </div>
</div>

<? } ?>
