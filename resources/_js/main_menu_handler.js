$(document).ready(function(){

	if($(window).width() <= 1260) {

		$('.js-no-link-category').click(function(){
			$(this).find('.js-sub-links').slideToggle('100');
		});

	}

    $('#js-burger').click(function() {

        $('#js-lines').toggleClass('active');

		$('.js-link-category, .js-no-link-category').css('display', 'block');
		$('#js-links-block').slideToggle('100').removeClass('catalog_toggled');

        // закрываем меню категорий
        $('.js-catalog').slideUp('100');
        // переворачиваем стрелку
        $('.js-catalog-arrow').removeClass('active');

    });

    // делаем активной родительскую категорию
   	$('.js-sub-links li.active').parent().parent().addClass('active');

	console.log('главное меню');

	if($(window).width() <= 1260) {

        // сворачивание выпадашек, если кликаем вне
        $(document).click(function(e) {

            if (!($(e.target).closest($('#js-burger')).length
            		|| $(e.target).closest($('.js-no-link-category')).length
            		|| $(e.target).closest($('.js-catalog-button')).length
            		|| $(e.target).closest($('.js-main-category')).length
            	)) {

		        $('#js-lines').removeClass('active');
				$('#js-links-block').slideUp('100').removeClass('catalog_toggled');
				$('.js-link-category, .js-no-link-category').css('display', 'block');

		        // закрываем меню категорий
		        $('.js-catalog').slideUp('100');
		        // переворачиваем стрелку
		        $('.js-catalog-arrow').removeClass('active');
            }

            e.stopPropagation();
        });

	}

});