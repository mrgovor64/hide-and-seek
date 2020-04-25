$(document).ready(function(){
  resizeModules();
  scrollDown();

  // Отправка сообщения в чат
  $("#chat_form").on('submit',function(){
    form = $("#chat_form").serialize();
    if(game){
      form += '&game='+game;
    }
    if(form != "text="){
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
        }
      });
    }
    return false;
  });

  // Опускаем чат вниз
  $("[data-type-button='chat']").on('click',function(){
    scrollDown();
  });

  // Открываем и закрываем выбор статуса участника
  $(document).on('click',".watching-player_status img , .watching-player_status div.points",function(){
    if( $(this).parents('.watching-player_status').children('.select_status_drop_down').is('.active') ){
      $(".watching-player_status .select_status_drop_down").removeClass('active');
    }else{
      $(".watching-player_status .select_status_drop_down").removeClass('active');
      $(this).parents('.watching-player_status').children('.select_status_drop_down').addClass('active');
    }
  });
  // Выбор статуса участника
  $(document).on('click',"[data-p_uniq] [data-do]",function(){
    p_uniq = $(this).parents('[data-p_uniq]').attr('data-p_uniq');
    p_uniq_do = $(this).attr('data-do');

    var data = 'game='+game+'&p_uniq='+p_uniq+'&p_uniq_do='+p_uniq_do;
    $.ajax({
      type: 'POST',
      url: 'php_scripts/set_status_player.php',
      dataType: 'html',
      data: data,
      success: function(data){
        if(data){
          alert(data);
        }
      },
      error: function(jqXHR, textStatus){
        alert('Ошибка! Статус не изменен! '+textStatus);
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
function chatMessage(read, game){
  if(chat_ready){
    chat_ready = 0;

    h1 = $("#chat_window").outerHeight();
    h2 = document.getElementById('chat_window').scrollHeight;
    scrollBottom = 0;

    if($("#chat_window").scrollTop() == h2-h1){
      scrollBottom = 1;
    }

    post_read = '';
    if(read){
      $("#chat_window").html('');
      post_read += 'read='+read;
      last_message = 0;
    }

    if(game){
      if(post_read){
        post_read += '&';
      }
      post_read += 'game='+game;
    }

    var post_last_message = '';
    if(last_message){
      post_read += '&last_message='+last_message;
    }
    // alert(post_read);
    $.ajax({
      type: 'POST',
      url: 'php_scripts/chat.php',
      dataType: 'JSON',
      data: post_read,
      success: function(data){
        chat_ready = 1;
        // alert(JSON.stringify(data));
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

// Обновляем список участников
player_list = [];
function update_players_list(game){
  data_f = 'game='+game+'&type=all';
  $.ajax({
    type: 'POST',
    url: 'php_scripts/players_list.php',
    dataType: 'JSON',
    data: data_f,
    success: function(data){
      if(JSON.stringify(data) != JSON.stringify(player_list)){
        var str = '';
        data.forEach(function(item, i){
          str += '<tr>';
          str += '<td class="center aligned">'+(i+1)+'</td>';
          str += '<td class="center aligned"><div class="car_cell">';
          str += ' <a class="gos_number" onclick="alert(\'p_uniq: '+item.p_uniq+'\\npass: '+item.pass+'\');">'+item.car_number+'</a>';
          if(item.car_mark_url){
            str += '<img src="/images/cars_marks/32x32/'+item.car_mark_url+'.png"> ';
          }
          str += '<div class="car_model">'+item.car_model+'</div> ';
          //str += item.car_color+' ';
          str += '</div></td>';
          str += '<td class="center aligned">'+item.team_name+'</td>';
          str += '<td class="center aligned"><a href="tel:'+item.mobile_number.replace(/[\D]*/g,'')+'">'+item.mobile_number+'</a></td>';

          str += '<td class="center aligned"><div class="watching-player_status">';
          if(item.player_status == 3){
            str += '<img src="images/icons/aim.png">';
            if(item.points){
              str += '<div class="points">'+item.points+'</div>';
            }
          }else if(item.player_status == 2 && item.hide_coords){
            str += '<img src="images/icons/rabbit_g.png">';
          }else if(item.player_status == 2 && !item.hide_coords){
            str += '<img src="images/icons/rabbit_r.png">';
          }else if(item.player_status == 1){
            str += '<img src="images/icons/cross.png">';
          }else if(item.player_status == 0){
            str += '<img src="images/icons/user.png">';
          }
          str += '<div data-p_uniq="'+item.p_uniq+'" class="select_status_drop_down"><div class="select_status_drop_down-element">';
          str += '<div data-do="1"><img src="images/icons/cross.png"></div>';
          str += '<div data-do="0"><img src="images/icons/user.png"></div>';
          str += '<div data-do="2_0"><img src="images/icons/rabbit_r.png"></div>';
          str += '<div data-do="2"><img src="images/icons/rabbit_g.png"></div>';
          str += '<div data-do="3"><img src="images/icons/aim.png"></div>';
          str += '</div></div>';
          str += '</div></td>';
          str += '</tr>';
        });
        $("#players_list tbody").html(str);
      }
      player_list = data;
    }
  });
}
detect_list = [];
function update_detected_list(game){
  data_f = 'game='+game+'&type=all';
  $.ajax({
    type: 'POST',
    url: 'php_scripts/detected_list.php',
    dataType: 'JSON',
    data: data_f,
    success: function(data){
      if(JSON.stringify(data) != JSON.stringify(detect_list)){
        var str = '';
        data.forEach(function(item, i){
          str += '<tr>';
          str += '<td class="center aligned"><div class="car_cell">';
          str += '<a class="gos_number">'+item.who_detect_car_number+'</a>';
          if(item.who_detect_car_mark){
            str += '<img src="/images/cars_marks/32x32/'+item.who_detect_car_mark+'.png"> ';
          }
          str += item.who_detect_car_model;
          str += '</div></td>';
          str += '<td class="center aligned">'+item.who_detect_team_name+'</td>';
          str += '<td class="center aligned"><div class="car_cell">';
          str += '<a class="gos_number">'+item.detect_car_number+'</a>';
          if(item.detect_car_mark){
            str += '<img src="/images/cars_marks/32x32/'+item.detect_car_mark+'.png"> ';
          }
          str += item.detect_car_model;
          str += '</div></td>';

          str += '<td class="center aligned">'+item.detect_team_name+'</td>';
          if(item.detect_status == 1){
            str += '<td class="center aligned">'+item.detect_time+'</td>';
          }else{
            str += '<td class="center aligned error">'+item.detect_time+'</td>';
          }
          str += '</tr>';
        });
        $("#detected_list tbody").html(str);
      }
      detect_list = data;
    }
  });
}
