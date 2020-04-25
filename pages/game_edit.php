<?
if(!$iam_player[admin] && !$iam[account_admin]){
  include "pages/login.php";
}else{
  $game = mysql_query("SELECT * FROM games WHERE game = '$_GET[variable_3]'", $bdconnect) or die('die =(');
  $game = mysql_fetch_assoc($game);
  ?>
    <h1>Границы игры</h1>
    <script src="https://maps.api.2gis.ru/2.0/loader.js"></script>
    <script type="text/javascript" src="js/jquery-3.1.1.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/game_edit.js"></script>
    <script src="js/geo.js"></script>

    <div id="map" class="active" data-type-group="modules" data-type-element="map"></div>
    <script>
      DG.then(function() {
        line = DG.featureGroup();
        markers = DG.featureGroup();
        var map = DG.map('map', {
            center: [51.5253431, 46.0244782],
            zoom: 15
        });
        <?
        if($game[game_border]){
          echo "border = [".$game[game_border]."];\n";
          echo "create_polyline();\n";
          echo "map.fitBounds(line.getBounds());\n";
        } else {
          echo "border = [];";
        }
        ?>

        // Добавляем маркер
        map.on('click', function(e){
          lat = e.latlng.lat.toFixed(5);
          lng = e.latlng.lng.toFixed(5);
          latlng = [lat,lng];
          line.clearLayers();
          markers.clearLayers();
          border.splice(border.length,0,latlng);
          border_edit(<? echo $game[game]; ?>,border);
          create_polyline();
        });

        // Функция рисовки границы
        function create_polyline(){
          marker = [];
          polyline = DG.polyline(border, {color: 'blue'}).addTo(line);

          border.forEach(function(item,i){
            marker[i] = DG.marker(item).addTo(markers);
            // При клике по маркеру удаляет его
            marker[i].on('click', function(e){
              line.clearLayers();
              markers.clearLayers();
              border.splice(i,1);
              border_edit(<? echo $game[game]; ?>,border);
              create_polyline();
            });
          });
          line.addTo(map);
          markers.addTo(map);
        }
      });
    </script>
<? } ?>
