$(document).ready(function(){
  geo();
});

// Функция поиска геолокации
function geo(){
  if (navigator.geolocation) {
    navigator.geolocation.watchPosition(geolocationSuccess, geolocationFailure);
    //alert("Поиск начался");
  } else {
    alert("Ваш браузер не поддерживает геолокацию");
  }
}

last_date = new Date(0,0,0,0,0,0,0);
// успех геолокации
function geolocationSuccess(position){
  date = new Date();

  if(date.getTime() - last_date.getTime() >= 2500){
    latitude = position.coords.latitude.toFixed(5);
    longitude = position.coords.longitude.toFixed(5);
    //alert("Последний раз вас засекали здесь: [" + latitude +", " + longitude + "]");
    $.ajax({
      type: 'POST',
      url: 'php_scripts/geo_script.php',
      data: 'coords=['+latitude+','+longitude+']',
      success: function(data){
        last_date = date;
        if(data){
          alert(data);
        }
      }
    });
  }
}

// Феил геолокации
function geolocationFailure(positionError) {
  if(positionError.code == 1) {
    alert("Вы решили не предоставлять данные о своем местоположении, но это не проблема. Мы больше не будем запрашивать их у вас.");
  }
  else if(positionError.code == 2) {
    alert("Проблемы с сетью или нельзя связаться со службой определения местоположения по каким-либо другим причинам.");
  }
  else if(positionError.code == 3) {
    alert("He удалось определить местоположение в течение установленного времени.");
  }  else {
    alert("Загадочная ошибка.");
  }
}
