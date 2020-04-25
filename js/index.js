var latitude, longitude;
$(document).ready(function(){
  resizeModules();
  scrollDown();
  chatMessage(20);

  // Отправка сообщения в чат
  chat_button = 1;
  $("#chat_form").on('submit',function(){
    form = $("#chat_form").serialize();
    if(form != "text=" && chat_button == 1){
      chat_button = 0;
      $.ajax({
        type: 'POST',
        url: 'php_scripts/chat.php',
        data: form,
        success: function(data){
          if(data){
            alert(data);
          }else{
            $("#chat_form").trigger('reset');
            scrollDown();
          }
          chat_button = 1;
        }
      });
    }
    return false;
  });

  // Опускаем чат вниз
  $("[data-type-button='chat']").on('click',function(){
    scrollDown();
  });

  // Открыть карту по кнопке спрятался
  $(document).on('click', '.button_hide_style', function(){
    openClose('modules','map');
    $(".hide_text").addClass('active');
    select_hide_place = 1;
    resizeModules();
  });
  // Отправить то что спрятался
  $(".button_hide").on('click', function(){
    $(".hide_text").removeClass('active');
    select_hide_place = 0;
    resizeModules();
    if(lat && lng){
      hide_coords("["+lat+","+lng+"]");
    }
  });

  // Отправка заявки на detection
  $(document).on('click',".detection",function(){
    detection = $(this).val();
    $.ajax({
      type: 'POST',
      url: 'php_scripts/detection.php',
      data: 'detection='+detection,
      success: function(data){
        if(data){
          alert(data);
        }
      }
    });
  });
});

$(window).resize(function(){
  resizeModules();
  scrollDown();
});

// Устанавливаем высоту блоков
function resizeModules(){
  h_outer = window.innerHeight - $("#body").outerHeight();
  h_body = $("#body").height();
  h_menu = $(".menu").outerHeight(true);
  h_w_map = $("[data-type-element=map]").outerHeight(true);
  h_w_chat = $("[data-type-element=chat]").outerHeight(true);

  h_map = h_outer + (h_body - h_menu - h_w_map) + $("#map").height();
  h_chat = h_outer + (h_body - h_menu - h_w_chat) + $("#chat_window").height();

  $("#chat_window").outerHeight(h_chat);
  $("#map").outerHeight(h_map);
}

// Обновление сообщений в чате
var last_message = 0;
var chat_ready = 1;
setInterval(chatMessage, 1000);
function chatMessage(read){
  if(chat_ready){
    chat_ready = 0;

    h1 = $("#chat_window").outerHeight();
    h2 = document.getElementById('chat_window').scrollHeight;
    scrollBottom = 0;

    if($("#chat_window").scrollTop() == h2-h1){
      scrollBottom = 1;
    }

    if(read){
      $("#chat_window").html('');
      post_read = 'read='+read;
      last_message = 0;
    }

    var post_last_message = '';
    if(last_message){
      post_last_message = '&last_message='+last_message;
    }

    $.ajax({
      type: 'POST',
      url: 'php_scripts/chat.php',
      dataType: 'JSON',
      data: post_read+post_last_message,
      success: function(data){
        chat_ready = 1;
        data.messages.forEach(function(item, i){
          if(item.team_name){
            var str = "<div>["+item.date_send+"] "+item.team_name+" ("+item.car_number+"): "+item.text+"</div>";
          }else{
            var str = "<div>["+item.date_send+"]: <a class=\"red\">"+item.text+"</a></div>";
          }
          $("#chat_window").append(str);
        });

        if(data.last_message){
          last_message = data.last_message;
        }
        if(scrollBottom){
          scrollDown();
        }
      }
    });
  }
}

// Опускаем скролл чата вниз
function scrollDown(){
  h1 = $("#chat_window").outerHeight();
  h2 = document.getElementById('chat_window').scrollHeight;
  $("#chat_window").scrollTop(h2-h1);
}

// Обновляем статистику
setInterval(update_statistic, 1000);
statistic_table_hunters = [];
statistic_table_rabbits = [];
statistic_table_game_out = [];
function update_statistic(){
  $.ajax({
    type: 'POST',
    url: 'php_scripts/statistic.php',
    dataType: 'JSON',
    data: 'page=statistic',
    success: function(data){

      function return_tbody(obj){
        var str = '';
        if(obj != undefined){
          obj.forEach(function(item,i){
            var var_class = '';
            if(item.class){
              var_class = ' class = "'+item.class+'"';
            }else{
              var_class = '';
            }
            str += '<tr'+var_class+'>';
            str += '<td><div class="car_cell">';
            str += ' <a class="gos_number">'+item.car_number+'</a>';
            str += '<img src="images/cars_marks/32x32/'+item.car_mark_url+'.png"> ';
            str += '<div class="car_model">'+item.car_model+'</div>';

            str += '</div></td>';
            if(item.button_time){
              str += '<td>'+item.button_time+'</td>';
            }else if(item.second_cell){
              str += '<td>'+item.second_cell+'</td>';
            }
            str += '</tr>';
          });
        }
        return str;
      }
      if(JSON.stringify(statistic_table_hunters) != JSON.stringify(data.hunters)){
        $("#statistic .statistic_table.hunters tbody").html( return_tbody(data.hunters) );
      }
      statistic_table_hunters = data.hunters;
      if(JSON.stringify(statistic_table_rabbits) != JSON.stringify(data.rabbits)){
        $("#statistic .statistic_table.rabbits tbody").html( return_tbody(data.rabbits) );
      }
      statistic_table_rabbits = data.rabbits;
      if(JSON.stringify(statistic_table_game_out) != JSON.stringify(data.game_out)){
        $("#statistic .statistic_table.game_out tbody").html( return_tbody(data.game_out) );
      }
      if(data.timer != undefined){
        $(".timer").html(data.timer);
      }
      statistic_table_game_out = data.game_out;
    }
  });
}

// запись места где спрятался
function hide_coords(coords){
  if(coords){
    $.ajax({
      type: 'POST',
      url: 'php_scripts/hide_coords.php',
      data: 'coords='+coords,
      success: function(data){
        if(data){
          alert(data);
        }
      }
    });
  }
}
