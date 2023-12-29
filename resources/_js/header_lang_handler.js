$(document).ready(function(){

	if($(this).width() > 1023) {

		$('#js-lang-block').hover(function(){
			$('.open_main-page_header_languige-element').slideToggle(100, 'linear');
		});

		$('#js-lang-block').hover(function(){
			$(this).addClass('active');
		}, function(){
			$(this).removeClass('active');
		});

	}

	console.log('выпадашка языков в хэдере');

});