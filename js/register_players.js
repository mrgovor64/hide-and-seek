function get_players_list(game){
  $.ajax({
    type: 'POST',
    url: 'php_scripts/players_list.php',
    dataType: 'JSON',
    data: 'game='+game,
    success: function(data){
      //$("#players_list").html(data);
      //$(window).scrollTop($('#body').outerHeight());
      var str = '';
      data.forEach(function(item,i){
        str += '<tr>';
        str += '<td class="center aligned">'+(i+1)+'</td>';
        str += '<td class="center aligned">';
        str += '<div class="car_cell">';
        if(item.car_mark_url){
          str += '<img src="/images/cars_marks/32x32/'+item.car_mark_url+'.png" class="car_mark"> ';
        }
        str += '<a class="car_model">'+item.car_model+'</a></div></td>';
        str += '<td class="center aligned">'+item.team_name+'</td>';
        str += '</tr>';
      });
      $('#players_list tbody').html(str);
    }
  });
}

$(document).ready(function(){
  // Отправка заявки
  $("#form_register").on('submit',function(){
    if($(this).find("button[type=submit]").is('.loading')){
      return false;
    }
    $(this).find("button[type=submit]").addClass('loading');

    $(this).find(".ui.pointing.red.basic.label").remove();
    $(this).find(".error").removeClass('error');

    form = $("#form_register").serialize();
    this_form = this;

    $.ajax({
      type: 'POST',
      url: 'php_scripts/register_players.php',
      dataType: 'JSON',
      data: form,
      success: function(data){
        if(data.error.length){
          data.error.forEach(function(item,i){
            if(item.name){
              $('[name='+item.name+']').parents('.field').addClass('error');
              $('[name='+item.name+']').parents('.field').append('<div class=\"ui pointing red basic label\">'+item.text+'</div');
            }else{
              alert(item.text);
            }
          });
        }else{
          $('#form_register').trigger('reset');
          get_players_list(game);
        }
        if(data.success.p_uniq && data.success.password){
          $(".ui.success.message").css('display','block');
          in_p = 'Ваши данные для входа в игру:<br />\nP_uniq: '+data.success.p_uniq+'<br />\nПароль: '+data.success.password+'<br /><br />\nВ случае утраты данных обратитесь к организаторам игры!<br /><br /><a class="ui button primary fluid" href="/?p_uniq='+data.success.p_uniq+'&pass='+data.success.password+'">Начать!</a>';
          $(".ui.success.message p").html(in_p);
          $(window).scrollTop(0);
        }
        $(this_form).find('button[type=submit]').removeClass('loading');
        $('input[name = captcha]').val('');
        $('img.captcha').removeAttr('src').attr('src','/images/captcha.php?height=37&width=240');
      }
    });
    return false;
  });

});
