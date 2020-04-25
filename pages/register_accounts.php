<link rel="stylesheet" href="css/register_players.css" type="text/css" />
<script type="text/javascript" src="js/jquery-3.1.1.min.js"></script>
<script type="text/javascript" src="js/jquery.maskedinput.js"></script>
<script src="js/main.js"></script>
<script src="js/register_accounts.js"></script>
<script>
$(document).ready(function(){
  $("input[name=mobile_number]").mask("+7 (999) 999-99-99");
});
</script>

<div>
  <h1 class="ui dividing header inverted aligned center">
    <p>Регистрация аккаунта</p>
  </h1>
</div>
<form class="ui form inverted" method="post" id="form_register">
  <div class="fields two">
    <div class="field">
      <label>Логин</label>
      <div class="ui input">
        <input type="text" name="login" placeholder="Логин">
      </div>
    </div>
    <div class="field">
      <label>Номер телефона</label>
      <div class="ui input">
        <input type="text" name="mobile_number" placeholder="Номер телефона">
      </div>
    </div>
  </div>
  <div class="fields two">
    <div class="field">
      <label>Пароль</label>
      <div class="ui input">
        <input type="password" name="first_password" placeholder="Пароль">
      </div>
    </div>
    <div class="field">
      <label>Повторение паролья</label>
      <div class="ui input">
        <input type="password" name="second_password" placeholder="Повторение паролья">
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
      <button class="ui button primary fluid" name="register_submit" value="1" type="submit" style="position:relative;top: 26px;">Создать аккаунт</button>
    </div>
  </div>
</form>

<div class="ui success message" style="display: none;">
  <div class="header">
    Регистрация прошла успешно!
  </div>
  <p>Теперь вы можете перейти на <a href="/">главную страницу</a> и авторизоваться на сайте!</p>
</div>
