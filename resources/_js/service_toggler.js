$(document).ready(function(){

	$('.js-center').click(function() {

		var center_num = $(this).data('center');

		$('.js-center, .service-page_service-center').removeClass('active');
		$(this).addClass('active');
		$('.js-sr-' + center_num).addClass('active');

	});

	console.log('перключатель сервисных центров');

});