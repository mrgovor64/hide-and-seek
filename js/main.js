$(document).ready(function () {
  // Переключалка окон
  $("[data-type-button]").on('click',function () {
		elem = $(this).attr('data-type-button');
		group = $(this).attr('data-type-group');
		openClose(group,elem);
	});
});

function openClose(group,elem){
  if(group){
    $("[data-type-group = "+group+"]").removeClass('active');
    $("[data-type-button = "+elem+"]").addClass('active');
    $("[data-type-element = "+elem+"]").addClass('active');
  }
}
