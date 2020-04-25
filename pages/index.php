<?
if(!$iam_player[p_uniq] && !$iam[id]){
  include "pages/login.php";
}else{
  $game = mysql_query("SELECT * FROM games WHERE game = '$iam_player[game]'",$bdconnect) or die('mysql error 1');
  $game = mysql_fetch_assoc($game);
  ?>

  <script src="https://maps.api.2gis.ru/2.0/loader.js"></script>
  <script type="text/javascript" src="js/jquery-3.1.1.min.js"></script>
  <script src="js/main.js"></script>
  <script src="js/index.js"></script>
  <script src="js/geo.js"></script>

  <div class="ui menu my_menu">
    <div class="item pointer active" data-type-group="modules" data-type-button="map">
      <i class="map icon"></i>
      <a class="menu_item">Карта</a>
    </div>
    <div class="item pointer" data-type-group="modules" data-type-button="statistic">
      <i class="crosshairs icon"></i>
      <a class="menu_item">Статистика</a>
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

  <div class="active" data-type-group="modules" data-type-element="map">
    <div class="hide_text">Отметьте на карте, где вы спрятались и нажмите <button class="button_hide">ОК</button></div>
    <div id="map"></div>
  </div>
  <script>
    // Строим карту
    DG.then(function() {
      hunters = DG.featureGroup();
      runnings = DG.featureGroup();
      line = DG.featureGroup();
      var map = DG.map('map', {
          center: [51.5253, 46.0244],
          zoom: 15
      });
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

      <?
      // Выводим границы
      if($game[game_border]){
        echo "border = [".$game[game_border]."];";
        echo "border.splice(border.length,0,border[0]);";
        echo "polyline = DG.polyline(border, {color: 'red'}).addTo(line);";
        echo "line.addTo(map);";
        echo "map.fitBounds(line.getBounds());";
      }
      if($iam_player[hide_coords] && $iam_player[player_status] == 2){
        echo "DG.marker(".$iam_player[hide_coords].",{icon: rabbit}).addTo(map);";
      }
      ?>


      // // Выводим всех охотников на карту
      // var hunters_list = [];
      // setInterval(get_hunters, 2000);
      // function get_hunters(){
      //   $.ajax({
      //     type: 'POST',
      //     dataType: 'JSON',
      //     url: 'php_scripts/get_icons_on_map.php',
      //     data: 'game=<? echo $game[game]; ?>&who=hunters',
      //     success: function(data){
      //       if(JSON.stringify(hunters_list) != JSON.stringify(data)){
      //
      //         hunters.clearLayers();
      //         data.forEach(function(item, i){
      //           if(item.coords){
      //             DG.marker(item.coords,{icon: aim}).addTo(hunters);
      //           }
      //         });
      //         hunters.addTo(map);
      //       }
      //       hunters_list = data;
      //     }
      //   });
      // }

      // Выводим все last_find на карту
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


      // Даем возможность указать, где спрятался
      var mark_hide;
      select_hide_place = 0
      map.on('click', function(e) {
        if(select_hide_place == 1){
          lat = e.latlng.lat.toFixed(5);
          lng = e.latlng.lng.toFixed(5);
          if(mark_hide){
            mark_hide.setLatLng([lat, lng]);
          }else{
            mark_hide = DG.marker([lat, lng],{icon: rabbit}).addTo(map);
          }
        }
      });

      // выводим геоданные на карту
      var my_geo;
      setInterval(geo_mark, 2000);
      function geo_mark(){
        if(latitude && longitude){
          if(my_geo){
            my_geo.setLatLng([latitude, longitude]);
          }else{
            my_geo = DG.marker([latitude, longitude],{icon: user}).addTo(map);
          }
        }
      }


    });
  </script>

  <div id="statistic" data-type-group="modules" data-type-element="statistic">
    <h2 style="width:200px;margin: 0 auto;">Таймер: <a class="timer"></a></h2>
    <table class="statistic_table hunters" border="1">
      <thead>
        <tr>
          <th colspan="3">Охотники</th>
        </tr>
        <tr>
          <th>Машина</th>
          <th>Счет</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
    <table class="statistic_table rabbits" border="1">
      <thead>
        <tr>
          <th colspan="3">Зайцы</th>
        </tr>
        <tr>
          <th>Машина</th>
          <th>Статус</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
    <table class="statistic_table game_out" border="1">
      <thead>
        <tr>
          <th>Вне игры</th>
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
    <a onclick="chatMessage(-1);" class="ui button fluid">Загрузить все сообщения в чат</a><br />
    <a href="?exit=exit" class="ui button fluid">Выйти</a>
  </div>
<? } ?>
