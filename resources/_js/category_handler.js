$(document).ready(function(){

	console.log('меню категорий');

	if($(window).width() > 1260) {

	    // отображение подкатегорий
	    $('body').on('mouseenter', '.js-main-category', function () {
	        $(this).addClass('act');
	        $(this).next('.js-category-inset').css('display', 'block');
	    });


	    $('body').on('mouseleave', '.js-main-category', function () {

	        if($(this).next('.js-category-inset:hover').length == 0) {
	            $(this).removeClass('act');
	            $('.js-category-inset').css('display', 'none');
	        }
	    });

	    $('body').on('mouseleave', '.js-category-inset', function () {
	        $('.js-main-category').removeClass('act');
	        $('.js-category-inset').css('display', 'none');
	    });

		// выставляем минимальную высоту для вставки
		var inset_min_height = $('.catalog-page_catalog-links-block').innerHeight();
		$('.js-category-inset').css('min-height', inset_min_height);

	} else {

        // на малых экранах открываем закрываем меню каталога ***************************
        $('.js-catalog-button').click(function(e){

            // убиваем событие по умолчанию
            e.preventDefault();

            // скролимся к началу страницы
            $(window).scrollTop(0);

            // открываем-закрываем меню
            $('.js-catalog').slideToggle('100');

            // переворачиваем стрелку
            $('.js-catalog-arrow').toggleClass('active');

            // скрываем / отображаем ссылки главного меню
            $('#js-links-block').toggleClass('catalog_toggled');
            $('.js-link-category, .js-no-link-category').fadeToggle('50');

        });

        // управление меню категорий ***************************************************
        $('.js-main-category').click(function(e){

            // убиваем событие по умолчанию (переход по ссылке)
            e.preventDefault();

            // открываем подкатегории
            $(this).toggleClass('toggled').next('.js-category-inset').slideToggle('100');

            // поворачиваем стрелку
            $(this).children('.js-link-arrow').toggleClass('active');

        });

	}

	// делаем активной родительскую категорию
	$('.js-category-inset li.active').parent().parent().prev('.js-main-category').addClass('active');

});