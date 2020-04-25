<?
if($iam_player[p_uniq]){
  ?>
  <script>
    location.href = "/";
  </script>
  <?
}
$_GET[game] = $_GET[variable_2];
if($_GET[game]){ ?>
  <script type="text/javascript" src="js/jquery-3.1.1.min.js"></script>
  <script type="text/javascript" src="js/jquery.maskedinput.js"></script>
  <script src="js/main.js"></script>
  <script src="js/register_players.js"></script>
  <? echo "<script>game = ".$_GET[game].";</script>"; ?>
  <script>
  $(document).ready(function(){
    $("input[name=mobile_number]").mask("+7 (999) 999-99-99");
    // Загрузка списка участников
    get_players_list(game);
  });
  </script>

  <div>
    <h1 class="ui dividing header inverted aligned center">
      <p>Запись на  игру</p>
    </h1>
  </div>
  <div class="ui success message" style="display: none;">
    <div class="header">
      Регистрация на игру прошла успешно!
    </div>
    <p></p>
  </div>
  <form class="ui form inverted" method="post" id="form_register">
    <input type="hidden" name="game" value="<? echo $_GET[game]; ?>">
    <div class="fields two">
      <div class="field">
        <label>Название команды</label>
        <div class="ui input">
          <input type="text" name="team_name" placeholder="Название команды">
        </div>
      </div>
      <div class="field">
        <label>Номер телефона</label>
        <div class="ui input">
          <input type="text" name="mobile_number" placeholder="Номер телефона">
        </div>
      </div>
    </div>
    <div class="fields four">
      <div class="field">
        <label>Марка машины</label>
        <select class="ui dropdown" name="car_mark"><option value="0">Выберите макрку</option>
          <?
          $cars_marks = mysql_query("SELECT * FROM cars_marks ORDER BY car_mark_name", $bdconnect) or die("Таблица 13 не найдена!!!");
          while($car_mark = mysql_fetch_array($cars_marks)){
            echo "<option value=\"".$car_mark[car_mark]."\">".$car_mark[car_mark_name]."</option>\n";
          }
          ?>
        </select>
      </div>
      <div class="field">
        <label>Модель машины</label>
        <div class="ui input">
          <input type="text" name="car_model" placeholder="Модель машины">
        </div>
      </div>
      <div class="field">
        <label>Цвет машины</label>
        <div class="ui input">
          <input type="text" name="car_color" placeholder="Цвет машины">
        </div>
      </div>
      <div class="field">
        <label>Гос. номер</label>
        <div class="ui input">
          <input type="text" name="car_number" placeholder="a999aa 64">
        </div>
      </div>
    </div>
    <div class="fields three">
      <div class="field">
        <label>Пример</label>
        <img src="images/captcha.php?height=37&width=240" style="border-radius:.28571429rem;" class="captcha" alt="">
      </div>
      <div class="field">
        <label>Решение примера</label>
        <div class="ui input">
          <input type="text" name="captcha" placeholder="Решение примера">
        </div>
      </div>
      <div class="field">
        <button class="ui button primary fluid" type="submit" style="position:relative;top: 26px;">Подать заявку</button>
      </div>
    </div>
  </form>

  <h1 class="ui header dividing inverted aligned center"><p>Список заявок на игру</p></h1>
  <div id="players_list">
    <table class="ui selectable table inverted unstackable">
      <thead>
        <tr>
          <th class="center aligned collapsing">#</th>
          <th class="center aligned">Машина</th>
          <th class="center aligned">Название команды</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div>
<? } ?>
