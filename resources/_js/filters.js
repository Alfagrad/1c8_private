$(document).ready(function() {

    $('.js-but-is-news, .js-but-is-action, .filter_archive, .filter_in-stock, .filter_soon, .filter_reserve').click(function(){

	    // по любому нажатию, кроме на Все, удаляем класс "filtred", прячем все строки
        $('.js-item-line, .w-table').removeClass('filtered').hide();

        // переключатель активности кнопок
        if(!$(this).hasClass('_active')) $(this).addClass('_active');
        	else $(this).removeClass('_active');

        // если не активны кнопки, отображаем все позици, кроме архивных
        if(!$('.js-but-is-news, .js-but-is-action, .filter_in-stock, .filter_soon, .filter_reserve').hasClass('_active')) {
        	$('.filter_all').addClass('_active');
        	$('.js-item-line').addClass('filtered').css('display', 'table-row');
        	$('.w-table').show();
        }

        // отображаем новинки
        if($('.js-but-is-news').hasClass('_active')) {
        	$('.w-table[data-new-item != 0]').show();
        	$('tr[data-new = 1]').addClass('filtered').css('display', 'table-row');
        	// отключаем фильтр наличия
        	$('.filter_all, .filter_archive, .filter_in-stock, .filter_soon, .filter_reserve').removeClass('_active');
        }

        // отображаем акции
        if($('.js-but-is-action').hasClass('_active')) {
        	$('.w-table[data-action-item != 0]').show();
        	$('tr[data-action = 1]').addClass('filtered').css('display', 'table-row');
        	// отключаем фильтр наличия
        	$('.filter_all, .filter_archive, .filter_in-stock, .filter_soon, .filter_reserve').removeClass('_active');
        }

        // отображение архива
        if(
        	$('.js-but-is-action').hasClass('_active')
        	|| $('.js-but-is-news').hasClass('_active')
        	|| $('.filter_in-stock').hasClass('_active')
        	|| $('.filter_soon').hasClass('_active')
        	|| $('.filter_reserve').hasClass('_active')
          ) {} else {
	        if($('.filter_archive').hasClass('_active')) {
	        	$('.js-archive-items').show();
	        	$('.js-archive-items .js-item-line').addClass('filtered');
	        } else {
	        	$('.js-archive-items').hide();
	        	$('.js-archive-items .js-item-line').removeClass('filtered');
	        } 
        }

        // отображение В наличии
        if($('.filter_in-stock').hasClass('_active')) {
        	$('.filter_all').removeClass('_active');
        	$('.w-table[data-in-stock-count != 0]').show();
        	if(!$('.filter_archive').hasClass('_active')) $('.js-archive-items').hide();
        	$('.js-avalible-item').addClass('filtered').css('display', 'table-row');
        }
        // отображение В пути
        if($('.filter_soon').hasClass('_active')) {
        	$('.filter_all').removeClass('_active');
        	$('.w-table[data-soon-count != 0]').show();
        	if(!$('.filter_archive').hasClass('_active')) $('.js-archive-items').hide();
        	$('.js-soon').addClass('filtered').css('display', 'table-row');
        }
        // отображение В резерве
        if($('.filter_reserve').hasClass('_active')) {
        	$('.filter_all').removeClass('_active');
        	$('.w-table[data-reserve-count != 0]').show();
        	if(!$('.filter_archive').hasClass('_active')) $('.js-archive-items').hide();
        	$('.js-reserve').addClass('filtered').css('display', 'table-row');
        }

		search_filter_word();

	    console.log('фильтры');

        return false;
    });

    // нажатие кнопки Все
    $('.filter_all').click(function(){
    	$(this).addClass('_active');
    	$('.js-item-line').addClass('filtered').css('display', 'table-row');
    	$('.w-table').show();
    	if(!$('.filter_archive').hasClass('_active')) $('.js-archive-items').hide();
    	// отключаем фильтр Новинки и Акции
    	$('.js-but-is-action, .js-but-is-news, .filter_in-stock, .filter_soon, .filter_reserve').removeClass('_active');
        search_filter_word();
	    console.log('фильтры');
    });

    // поиск по слову
    $('.js-filter-search-button').on('change input', function(){
		search_filter_word();
    })

    function search_filter_word() {
    	// получаем слово
        var filter_word = $('input[name=filter_word]').val();

        // переводим в нижний регистр
        filter_word = filter_word.toLowerCase();

        $('.js-item-line.filtered').each(function (е) {
        	// берем имя товара, переводим в нижний регистр
        	var item_name = $(this).children('.td-name').data('name').toLowerCase();
        	// ищем подстроку, если нет, прячем позицию
        	if(item_name.indexOf(filter_word) !== -1){
        		$(this).show();
        	} else {
        		$(this).hide();
        	}
        });

		console.log(filter_word);
    }

});
