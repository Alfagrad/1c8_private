$(document).ready(function(){

	$('.js-vacancy-block').click(function(){
		$(this).children('.js-vacancy-text').toggle(300);
		$(this).find('.js-vacancy-svg').toggleClass('active');
	});

	console.log('отображение текста вакансий');

});