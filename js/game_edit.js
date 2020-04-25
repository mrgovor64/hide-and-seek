function border_edit(game, border){
  border_str = "";
  border.forEach(function(item,i){
    if(i){
      border_str += ",";
    }
    border_str += "["+item+"]";
  });
  
  $.ajax({
    type: 'POST',
    url: 'php_scripts/border_edit.php',
    data: 'game='+game+'&border='+border_str,
    success: function(data){
      if(data){
        alert(data);
      }
    }
  });

}
