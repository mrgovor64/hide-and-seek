<h1 class="ui dividing header inverted aligned center">Авторизация</h1>
<? echo $_SESSION[notification_error]; unset($_SESSION[notification_error]); ?>
<div class="ui form">
  <div class="row">
    <div class="col-xs-12 col-sm-6 aligned center" style="float: none; margin: 30px auto 0;">
      <? if(!$iam_player && !$iam){ ?>
      <form method="post">
        <input type="text" name="auth_login" placeholder="Логин / P_uniq" class="ui input">
        <input type="password" name="auth_pass" placeholder="Пароль" class="ui input">
        <input type="submit" name="auth_submit" value="Войти" class="ui button primary fluid">
      </form>
      <? }else{ ?>
        <div>
          <p>Вы авторизованы как <? echo $iam[login].$iam_player[p_uniq] ?></p>
          <p>Выйти</p>
        </div>
      <? } ?>
      <?
      $games = mysql_query("SELECT * FROM games WHERE game_date_register_start <= '$time' AND game_date_register_finish >= '$time'", $bdconnect) or die(mysql_error());
      while ($game = mysql_fetch_assoc($games)) {
        echo '<div class="ui button fluid" style="margin-top: 20px;"><a href="/reg/'.$game[game].'">Зарегистрироваться на игру "'.$game[game_name].'"</a></div>';
      }
      ?>
    </div>
  </div>
</div>
