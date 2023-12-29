$(document).ready(function(){

	if($(window).width() <= 1260) {

		// отображаем выпадашки в хедере на мобильных
		$('.js-mobile-ico').click(function(){
			$(this).next().slideToggle('100');
		});

		$('.js-phones-ico').click(function(){
			$('.js-phones-block').slideToggle('100');
		});

	    // сворачивание выпадашек, если кликаем вне
		$(document).click(function(e) {
			$('.js-mobile-ico').each(function(){
				if (!$(e.target).closest(this).length) {
					$(this).next().slideUp('100');
				}
			});

			if (!$(e.target).closest('.js-phones-ico').length) {
				$('.js-phones-block').slideUp('100');
			}

			e.stopPropagation();
		});

	}

	console.log('выпадашки при нажатии на иконки в хэдере в мобильных');
});