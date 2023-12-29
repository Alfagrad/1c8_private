$(document).ready(function(){

    console.log('меняем количество товара');

    // функция обновления корзины *********************************************
    function updateCart(cart_id, item_1c_id, count, rout_name) {

        // берем токен

        var token = $('input[name=_token]').val();

        $.ajax({
            type: 'post',
            url: "/cart-page/update-cart",
            data: {
                'cart_id': cart_id,
                'item_1c_id': item_1c_id,
                'count': count,
                '_token': token,
                // 'rout_name': rout_name,
            },
            success: function(data){

                // обновляем данные в мини корзине
                $('.js-mini-cart-count').html(data['position_count']+'/'+data['item_count']);
                $('.js-mini-cart-price').html(data['item_price']);
                $('.js-mini-cart-title').html('Жми! В корзинах: позиций - '+data['position_count']+', товаров - '+data['item_count']+', на сумму '+data['item_price']+' руб');

                if(rout_name == 'newCartView') {

                    // общая скидка
                    var discount_percent = 1;
                    // товары соглашения
                    var agreement_items_str = '';

                    // берем чекнутое соглашение
                    var agreement_line = $('input[name="calc_type"]:checked');
                    // если есть
                    if(agreement_line.length) {
                        // переопределяем общую скидку
                        discount_percent = Number(agreement_line.data('calc_discount')) / 100 + 1;
                        // переопледеляем товары соглашения
                        agreement_items_str = agreement_line.data('agreement_items');

                    }
                    cartLineCalculate(discount_percent, agreement_items_str); // обработчик в new_cart.js
                }
            },
            error: function(data){
                console.log(data);
            }
        });
    }

    // добавляем / убавляем товар в каталожной выдаче (кнопки +/-, "В упаковке") *************
    $('.js-item-add-to-cart, .js-item-remove-from-cart, .js-input-packaging').click(function(){
        // берем товарную линию
        var item_line = $(this).parents('.js-item-element');

        // определяем input
        var input = item_line.find('.js-count-input');

        // определяем текущее количество
        var count = Number(input.val());

        // определяем тип клиента
        var is_service = item_line.data('is_service');

        // если не сервис
        if (is_service != 1) {

            // определяем количество в упаковке
            var block = Number(input.data('step'));

            // определяем имя текущего маршрута
            var rout_name = input.data('rout_name');

            // вставляем в input новое значение
            var firstCeil = count/block;
            var ceil = Math.trunc(count/block);
            var new_val;

            if($(this).hasClass('plus')) {
                if (firstCeil < 1) {
                    new_val = block;
                }
                else {
                    new_val = ceil*block+block;
                }
            } else if($(this).hasClass('minus')) {
                if (firstCeil < 1) {
                    new_val = 0;
                } else {
                    if(count > ceil*block) {
                        new_val = ceil*block;
                    } else {
                        new_val = ceil*block-block;
                    }
                }

            } else {
                return false;
            }

            if(rout_name == 'newCartView' && new_val == 0) {
                input.val(1);
            } else {
                input.val(new_val);
            }

            // берем измененное соличество
            count = Number(input.val());

            // 1c_id товара
            var item_1c_id = input.data('id_1c');

            // id корзины для обновления
            var cart_id;
            if(rout_name == 'newCartView') {
                cart_id = $(this).parents('.js-cart').data('cartId');
            } else {
                cart_id = 0;
            }

            // обновляем корзину
            updateCart(cart_id, item_1c_id, count, rout_name);
        } else {


            console.log(count);
        }



    });

    // добавляем / убавляем товар в поисковой выпадашке (кнопки +/-, "В упаковке") **************
    $('.js-search-content').on('click', '.js-search-add-to-cart, .js-search-remove-from-cart, .js-search-packaging', function(){

        // определяем input
        var input = $(this).parents('.js-item-element').find('.js-count-input');

        // определяем текущее количество
        var count = Number(input.val());

        // определяем количество в упаковке
        var block = Number(input.data('step'));

        // вставляем в input новое значение
        var firstCeil = count/block;
        var ceil = Math.trunc(count/block);
        if($(this).hasClass('plus')) {
            if (firstCeil < 1) {
                input.val(block);
            }
            else {
                input.val(ceil*block+block);
            }
        } else if($(this).hasClass('minus')) {
            if (firstCeil < 1) {
                input.val(0);
            }
            else {
                if(count > ceil*block) {
                    input.val(ceil*block);
                } else {
                    input.val(ceil*block-block);
                }
            }
        } else {
            return false;
        }

        // берем измененное соличество
        count = Number(input.val());

         // определяем имя текущего маршрута
        var rout_name = input.data('rout_name');

        // 1c_id товара
        var item_1c_id = input.data('id_1c');

        // id корзины для обновления
        var cart_id;
        if(rout_name == 'newCartView') {
            cart_id = $('.js-completion-block').data('cartId');
        } else {
            cart_id = 0;
        }

        // обновляем корзину
        updateCart(cart_id, item_1c_id, count, rout_name);

    });


    // добавляем / убавляем товар в карточке товара (кнопки +/-, "В упаковке") **************
    $('.js-icard-add-to-cart, .js-icard-remove-from-cart, .js-icard-packaging').click(function(){

        // определяем input
        var input = $(this).parents('.js-price-block').find('.js-count-input');

        // определяем текущее количество
        var count = Number(input.val());

        // определяем количество в упаковке
        var block = Number(input.data('step'));

        // вставляем в input новое значение
        var firstCeil = count/block;
        var ceil = Math.trunc(count/block);

        if($(this).hasClass('plus')) {
            if (firstCeil < 1) {
                input.val(block);
            }
            else {
                input.val(ceil*block+block);
            }
        } else if($(this).hasClass('minus')) {
            if (firstCeil < 1) {
                input.val(0);
            }
            else {
                if(count > ceil*block) {
                    input.val(ceil*block);
                } else {
                    input.val(ceil*block-block);
                }
            }
        } else {
            return false;
        }

        // берем измененное соличество
        count = Number(input.val());

         // определяем имя текущего маршрута
        var rout_name = input.data('rout_name');

        // 1c_id товара
        var item_1c_id = input.data('id_1с');

        // id корзины для обновления
        var cart_id;
        if(rout_name == 'newCartView') {
            cart_id = $('.js-completion-block').data('cartId');
        } else {
            cart_id = 0;
        }

        // обновляем корзину
        updateCart(cart_id, item_1c_id, count, rout_name);

    });

    // обновление корзины при ручном вводе количества
    $('input[name="item_count"]').on('focusout', function(){

        var input = $(this);

        // определяем имя текущего маршрута
        var rout_name = input.data('rout_name');

        // определяем введенное значение, переводим в целое число
        var count = Number(input.val());
        // если не число назначаем 1
        if(count == NaN || (rout_name == 'newCartView' && count < 1)) {
            count = 1;
        } else if(count < 0) {
            // если отрицательное
            count = 0;
        } else {
            // округляем, если не целое
            count = count.toFixed();
        }

        // обновляем input
        input.val(count);

        // 1c_id товара
        var item_1c_id = input.data('id_1c');

        // id корзины для обновления
        var cart_id;
        if(rout_name == 'newCartView') {
            cart_id = $(this).parents('.js-cart').data('cartId');
        } else {
            cart_id = 0;
        }

        // обновляем корзину
        updateCart(cart_id, item_1c_id, count, rout_name);

    });

    //**********************************************
    console.log('вывод title для миникорзины');

    $('.js-mini-cart-block').mouseenter(function(){
        $('.js-mini-cart-title').fadeIn();
    }).mouseleave(function(){
        $('.js-mini-cart-title').fadeOut();
    });

});


