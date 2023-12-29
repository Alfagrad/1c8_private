$(document).ready(function(){

	$('.js-brand-elenent').click(function(){

console.log('нажал');

		$(this).find('.js-brand-desc').toggle(300);
		$(this).find('.js-brand-text').toggle(300);
		$(this).find('.js-brand-svg').toggleClass('active');
	});

	console.log('отображение текста брендов');

});