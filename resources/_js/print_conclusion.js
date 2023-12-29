$(document).ready(function(){

	console.log('печать сервисных заключений');

	// поиск заключения
	$('.js-search-button').click(function(){

		var сonclusion_num = parseInt($('input[name=сonclusion_num]').val());

		// скрываем форму скачивания и редактирования, обнуляем val
		$('.js-download-conclusion, .js-edit-conclusion').hide().children('input[name=order_id]').val('');

		// обнуляем data-buh
		$(this).attr('data-buh', '');


		if(сonclusion_num) {

			var token = $('meta[name=csrf-token]').attr('content');

	        $.ajax({
	            type: 'post',
	            url: "/svetik/search-conclusion",
	            data: {
	                'сonclusion_num': сonclusion_num,
	                '_token': token,
	            },
	            success: function(data){
	            	if(data) {

	            		// добавляем в форму для скачивания и редактирования № заключения
	            		$('input[name=order_id]').val(сonclusion_num);

	            		// отображаем форму скачивания и редактирования
	            		$('.js-download-conclusion, .js-edit-conclusion').show();

	            		// отображаем заключение
		            	$('.js-result-block').html("<div class='content'>"+data+"</div>");

	            	} else {
	            		$('.js-result-block').html("<strong>Заключение №"+сonclusion_num+" - не найдено!</strong>");
	            	}
	            },
	        });
		}
	});

	// вызов формы для редактирования
	$('.js-edit-button').click(function(e){
		// запрещаем действие по умолчанию
		e.preventDefault();

		var order_id = $('input[name=order_id]').val();
		var token = $('meta[name=csrf-token]').attr('content');

        $.ajax({
            type: 'post',
            url: "/service/edit-conclusion",
            data: {
                'order_id': order_id,
                'buh': 1,
                '_token': token,
            },
            success: function(data){

        		// отображаем форму
            	$('.js-result-block').html(data);

            },
        });
	});

	// обновление заключения
	$('.js-update_butt').on('click', function(e){
		// запрещаем действие по умолчанию
		e.preventDefault();

		console.log('обновление');
	});

	// выводим заключение после обновления
	var buh = $('.js-search-button').data('buh');
	if(buh) {
		var сonclusion_num = parseInt($('input[name=сonclusion_num]').val());
		var token = $('meta[name=csrf-token]').attr('content');

        $.ajax({
            type: 'post',
            url: "/svetik/search-conclusion",
            data: {
                'сonclusion_num': сonclusion_num,
                '_token': token,
            },
            success: function(data){

        		// отображаем заключение
            	$('.js-result-block').html("<div class='content'>"+data+"</div>");

            },
        });

	}

});