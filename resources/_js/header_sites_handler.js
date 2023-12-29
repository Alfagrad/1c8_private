// import './open.js'

$(document).ready(function(){

	if($(this).width() > 1023) {

		$('#js-sites-block').hover(function(){
			$('.open_main-page_header_site-link').slideToggle(100, 'linear');
		});

		$('#js-sites-block').hover(function(){
			$(this).addClass('active');
		}, function(){
			$(this).removeClass('active');
		});

	}

	console.log('выпадашка сайтов в хэдере');

});
