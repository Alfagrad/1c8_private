$(document).ready(function(){

	console.log('обработчик Поступления, Обзоры, Новости для малых экранов');

	if($(window).width() <= 1260) {

		$('.js-arrivals-toggler').click(function(){

			$('.js-arrivals-block').slideToggle(300, 'linear');
			$(this).find('span').toggleClass('active');

			$('.js-reviews-block').css('display', 'none');
			$('.js-reviews-toggler span').removeClass('active');

			if($(window).width() <= 1023) {
				$('.js-news-block').css('display', 'none');
				$('.js-news-toggler span').removeClass('active');
			}

		});

		$('.js-reviews-toggler').click(function(e){

			e.preventDefault();
			$('.js-reviews-block').slideToggle(300, 'linear');
			$(this).find('span').toggleClass('active');

			$('.js-arrivals-block').css('display', 'none');
			$('.js-arrivals-toggler span').removeClass('active');

			if($(window).width() <= 1023) {
				$('.js-news-block').css('display', 'none');
				$('.js-news-toggler span').removeClass('active');
			}

		});

		$('.js-news-toggler').click(function(e){

			e.preventDefault();
			$('.js-news-block').slideToggle(300, 'linear');
			$(this).find('span').toggleClass('active');

			$('.js-arrivals-block, .js-reviews-block').css('display', 'none');
			$('.js-arrivals-toggler span, .js-reviews-toggler span').removeClass('active');

		});

        // сворачивание выпадашек, если кликаем вне
        $(document).click(function(e) {

            if (!$(e.target).closest($('.js-arrivals-toggler, .js-reviews-toggler, .js-news-toggler')).length) {

				$('.js-arrivals-block, .js-reviews-block, .js-news-block').slideUp('300');
				$('.js-arrivals-toggler span, .js-reviews-toggler span, .js-news-toggler span').removeClass('active');

            }

            e.stopPropagation();
        });

	}

});