$(document).ready(function(){

	// поиск изделия в форме корзины
	var search_keyword;
	var searched_item;
    $('body').on('click change input paste', '#js-item-search', function (e) {
        search_keyword = $.trim($(this).val());
        if(search_keyword.length < 2){
            $('.item-serch_result-block').hide();
        }

        if(search_keyword != searched_item) {
        	$('#item_1c_id').val(0);
        }
		console.log('введено: ' + search_keyword);
		console.log('выбрано из списка: ' + searched_item);
		console.log('1c_id - ' + $('#item_1c_id').val());

        ajax_item_search();

		var ajax = undefined;
	    function ajax_item_search() {
	        if(search_keyword != ''){
	            if(search_keyword.length > 2 ){

	                // console.log('в поиске: ' + search_keyword);

	                ajax = $.ajax({
	                    type: 'get',
	                    url: "service/item-search?search=" + search_keyword,
	                    beforeSend : function() {
	                        if(ajax) {
	                            ajax.abort();
	                        }
	                    },
	                    success: function (data) {
	                        if(data != 'false'){
	                        	$('.item-serch_result-block').html(data);
	                            $('.item-serch_result-block').show();
	                        }
	                        ajax = undefined;
	                        // console.log(data);
	                    },
                        error: function (data) {
                            console.log(data);
                        },
	                    async:true
	                });
	            } else {
	                $('.item-serch_result-block').hide();
	            }
	        }
	    }
    });

    // скрываем результат поиска при клике вне блока
	$('body').mouseup(function (e){
		var div = $('.item-serch_result-block');
		if (!div.is(e.target) && div.has(e.target).length === 0) {
			div.hide();
		}
	});


	// добавляем имя выбранного изделия в input
	$('body').on('click', '.item-serch_result-item', function(e){
		$('#js-item-search').val($(this).html());
		$('#item_1c_id').val($(this).data('id'))
		searched_item = $('#js-item-search').val();
	});

});

