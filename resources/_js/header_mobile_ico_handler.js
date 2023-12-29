$(document).ready(function(){

	if($(this).width() <= 1023) {

		$('.js-mobile-ico').click(function(){

			$(this).next().slideToggle(100, 'linear').toggleClass('toggled');

			$('.js-mobile-ico').next().css('display', 'none');

			if($(this).next().hasClass('toggled')) {
				$('.js-mobile-ico').next().removeClass('toggled');
				$(this).next().css('display', 'block').addClass('toggled');
			}

		});

	}

	console.log('выпадашки при нажатии на иконки в мобильном');

});