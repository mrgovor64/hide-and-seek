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
      url: 'php_scripts/register_accounts.php',
      dataType: 'JSON',
      data: form,
      success: function(data){
        if(data.error.length){
          data.error.forEach(function(item,i){
            if(item.name){
              $('[name='+item.name+']').parents('.field').addClass('error');
              $('[name='+item.name+']').parents('.field').append('<div class=\"ui pointing red basic label\">'+item.text+'</div');
            }else{
              alert(item.name);
            }
          });
        }else{
          $('#form_register').trigger('reset');
          $('#form_register').css('display','none');
          $('.ui.success.message').css('display','block');
        }
        $('button[type=submit]').removeClass('loading');
        $('input[name = captcha]').val('');
        $('img.captcha').removeAttr('src').attr('src','/images/captcha.php?height=37&width=240');
      }
    });
    return false;
  });
});
