$(document).ready(function(){

	if($(this).width() > 1260) {

		// определяем высоту Хэдера
		var header_height = $('header').outerHeight();
		// определяем высоту блока Меню
		var menu_height = $('.main-navigation').outerHeight();
		// определяем высоту блока Футера
		var footer_height = $('footer').outerHeight();

		// минимальная высота центрального блока
		var min_height = $(window).height() - (header_height + menu_height + footer_height);

		// определяем высоту центрального блока
		var action_block_height = $('.js-action-block').outerHeight();
		if(action_block_height <= min_height) {
			action_block_height = min_height;
		}

		// вычисляем высоту для левого блока
		// определяем высоту блока с линками Новости-Акции
		var promo_links_height = $('.main-page_promo-links-block').outerHeight();
		// определяем высоту заголовка Новости
		var promo_header_height = $('.main-page_news-block_header').outerHeight();
		// назначаем высоту блока с новостями
		var promo_news_height = action_block_height - promo_links_height - promo_header_height;
		$('.main-page_news-block_news').css('height', promo_news_height);

		// вычисляем высоту для блока Последние поступления
		// определяем высоту заголовка
		var arrivals_header_height = $('.main-page_right-block_arrivals-title').outerHeight();
		// назначаем высоту блока с Последними поступлениями
		var arrivals_height = action_block_height / 2 - arrivals_header_height;
		$('.main-page_right-block_arrivals').css('height', arrivals_height);

		// вычисляем высоту для блока Обзоры
		// определяем высоту заголовка
		var reviews_header_height = $('.main-page_right-block_reviews-title').outerHeight();
		// назначаем высоту блока с Обзорами
		var reviews_height = action_block_height / 2 - reviews_header_height;
		$('.main-page_right-block_reviews').css('height', reviews_height);

	}
	console.log('управление блоками дом.страницы');

});