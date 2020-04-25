<?
if(!$iam_player[admin] && !$iam[account_admin]){
  include "pages/login.php";
}else{
  $game = mysql_query("SELECT * FROM games WHERE game = '$_GET[variable_3]'", $bdconnect) or die('die');
  $game = mysql_fetch_assoc($game);
  ?>
    <script src="https://maps.api.2gis.ru/2.0/loader.js"></script>
    <script type="text/javascript" src="js/jquery-3.1.1.min.js"></script>
    <script src="js/semantic.min.js"></script>
    <script src="js/main.js"></script>
    <script type="text/javascript" src="js/game_watch.js"></script>
    <script>
    $(document).ready(function() {
      game = "<? echo $game[game]; ?>";

      chatMessage(20, game);

      setInterval(function(){
        update_players_list(game);
        update_detected_list(game);
      }, 2000);
      setInterval(function(){
        chatMessage('', game);
      }, 1000);
    });
    </script>

    <div class="ui menu my_menu">
      <div class="item pointer active" data-type-group="modules" data-type-button="map">
        <i class="map icon"></i>
        <a class="menu_item">Карта</a>
      </div>
      <div class="item pointer" data-type-group="modules" data-type-button="players_list">
        <i class="users icon"></i>
        <a class="menu_item">Участники</a>
      </div>
      <div class="item pointer" data-type-group="modules" data-type-button="detected_list">
        <i class="crosshairs icon"></i>
        <a class="menu_item">Нашли</a>
      </div>
      <div class="item pointer" data-type-group="modules" data-type-button="chat">
        <i class="talk icon"></i>
        <a class="menu_item">Чат</a>
      </div>
        <div class="right item pointer" data-type-group="modules" data-type-button="setting">
          <i class="setting icon"></i>
          <a class="menu_item">Настройки</a>
        </div>
    </div>



    <div id="map" class="module active" data-type-group="modules" data-type-element="map"></div>
    <script>
      DG.then(function() {
        line = DG.featureGroup();
        hunters = DG.featureGroup();
        rabbits = DG.featureGroup();
        runnings = DG.featureGroup();
        var map = DG.map('map', {
            center: [51.5253431, 46.0244782],
            zoom: 15
        });
        border = "[<? echo $game[game_border]; ?>]";
        border = eval(border);
        border.splice(border.length,0,border[0]);

        // СОздаем иконки
        aim = DG.icon({
            iconUrl: '/images/icons/aim.png',
            iconSize: [24, 24]
        });
        running = DG.icon({
            iconUrl: '/images/icons/running.png',
            iconSize: [24, 24]
        });
        rabbit = DG.icon({
            iconUrl: '/images/icons/rabbit.png',
            iconSize: [24, 24]
        });
        user = DG.icon({
            iconUrl: '/images/icons/user.png',
            iconSize: [24, 24]
        });

        if(border.length > 1){
          polyline = DG.polyline(border, {color: 'blue'}).addTo(line);
          line.addTo(map);
          map.fitBounds(line.getBounds());
        }

        // Выводим всех охотников на карту
        var hunters_list = [];
        setInterval(get_hunters, 2000);
        function get_hunters(){
          $.ajax({
            type: 'POST',
            dataType: 'JSON',
            url: 'php_scripts/get_icons_on_map.php',
            data: 'game='+game+'&who=hunters',
            success: function(data){
              if(JSON.stringify(hunters_list) != JSON.stringify(data)){
                hunters.clearLayers();
                if(data != undefined){
                  data.forEach(function(item, i){
                    if(item.coords){
                      DG.marker(item.coords,{icon: aim}).addTo(hunters).bindLabel(item.team_name+" ("+item.car_number+")");
                    }
                  });
                }
                hunters.addTo(map);
              }
              hunters_list = data;
            }
          });
        }
        // Выводим всех зайцев на карту
        var rabbits_list = [];
        setInterval(get_rabbits, 2000);
        function get_rabbits(){
          $.ajax({
            type: 'POST',
            dataType: 'JSON',
            url: 'php_scripts/get_icons_on_map.php',
            data: 'game='+game+'&who=rabbits',
            success: function(data){
              if(JSON.stringify(rabbits_list) != JSON.stringify(data)){
                rabbits.clearLayers();
                if(data != undefined){
                  data.forEach(function(item, i){
                    if(item.coords){
                      DG.marker(item.coords,{icon: rabbit}).addTo(rabbits).bindLabel(item.team_name+" ("+item.car_number+")");
                    }
                  });
                }
                rabbits.addTo(map);
              }
              rabbits_list = data;
            }
          });
        }

        // Выводим все бумы на карту
        var runnings_list = [];
        setInterval(get_booms, 2000);
        function get_booms(){
          $.ajax({
            type: 'POST',
            dataType: 'JSON',
            url: 'php_scripts/get_icons_on_map.php',
            data: 'game=<? echo $game[game]; ?>&who=finding',
            success: function(data){
              if(JSON.stringify(runnings_list) != JSON.stringify(data)){
                runnings.clearLayers();
                data.forEach(function(item, i){
                  if(item.coords){
                    DG.marker(item.coords,{icon: running}).addTo(runnings).bindLabel(item.team_name+" ("+item.car_number+")");
                  }
                });
                runnings.addTo(map);
              }
              runnings_list = data;
            }
          });
        }

      });
    </script>

    <div id="players_list" data-type-group="modules" data-type-element="players_list">
      <table class="ui celled selectable table inverted">
        <thead>
          <tr>
            <th class="center aligned">#</th>
            <th class="center aligned">Машина</th>
            <th class="center aligned">Название команды</th>
            <th class="center aligned">Телефон</th>
            <th class="center aligned"></th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>

    <div id="detected_list" data-type-group="modules" data-type-element="detected_list">
      <table class="ui celled selectable table inverted">
        <thead>
          <tr>
            <th colspan="2" class="center aligned">Кто</th>
            <th colspan="2" class="center aligned">Кого</th>
            <th class="center aligned">Когда</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>

    <div class="article light" id="chat" data-type-group="modules" data-type-element="chat">
      <div id="chat_window"></div>
      <hr />
      <form method="post" id="chat_form">
        <input type="text" id="chat_line" name="text">
        <input type="image" src="images/send.png">
      </form>
    </div>

    <div data-type-group="modules" data-type-element="setting">
      <a onclick="chatMessage(-1,game);" class="ui button fluid" data-type-group="modules" data-type-button="chat">Загрузить все сообщения в чат</a><br />
      <a href="?exit=exit" class="ui button fluid">Выйти</a>
    </div>

<? } ?>
