$(document).ready(function(){

    console.log('управление корзиной');

    var timeCounterItem = {};
    var ajax = undefined;
    var ajax_tmp = 0;
    var timerId = '';
    var items_ajax = new Map();

    $('body').on('click', '.js-item-remove-from-cart', function (e) {
        e.preventDefault();

            var item_count = $(this).parent().find('input[name=item_count]');

              var package_step = item_count.attr('step');
              console.log("отняли " + package_step + " шт");
              var step;

              if(package_step > 1) {
                step = package_step;
              } else {
                step = 1;
              }

            if (item_count.val() > 0) {
                item_count.val(parseInt(item_count.val()) - Number(step));
            }

            if(item_count.val() % package_step) {
                item_count.val(Math.ceil(item_count.val() / package_step) * package_step);
                console.log(item_count.val());
            }

            timeCounterItem.count = item_count;
            timeCounterItem.action = 'remove';

            updateCountItemInCart(timeCounterItem.count, timeCounterItem.action)


    });

    $('body').on('click', '.js-item-add-to-cart',  function (e) {
        e.preventDefault();

              var item_count = $(this).parent().find('input[name=item_count]');
              var count = +parseInt(item_count.val());
              var package_step = item_count.attr('step');
              console.log("добавили " + package_step + " шт");
              var step;

              if(package_step > 1) {
                step = package_step;
              } else {
                step = 1;
              }

                if(isNaN(count)) {
                    count  = 0;
                }
                if(count < 999){
                    item_count.val(+ parseInt(count) + Number(step));

                    if(item_count.val() % package_step) {
                        item_count.val(Math.floor(item_count.val() / package_step) * package_step);
                        console.log(item_count.val());
                    }

                    timeCounterItem.count = item_count;
                    timeCounterItem.action = 'add';

                    // Помешаем в глобальную
                   // updateCountItemInCart(item_count, 'add');

                   updateCountItemInCart(timeCounterItem.count, timeCounterItem.action)
                 }

        return false;

    });

    $('body').on('click', '.js-item-add-to-cart-package',  function (e) {
        e.preventDefault();

              var item_count = $(this).parent().find('input[name=item_count]');
              var count = +parseInt(item_count.val());

                if(isNaN(count)) {
                    count  = 0;
                }
                if(count < 999){

                    timeCounterItem.count = item_count;
                    timeCounterItem.action = 'add';

                    // Помешаем в глобальную
                   // updateCountItemInCart(item_count, 'add');

                   updateCountItemInCart(timeCounterItem.count, timeCounterItem.action)
                 }

        return false;

    });


    $('body').on("change", 'input[name=item_count]', function() {
    //$('body').on("change keyup input click", 'input[name=item_count]', function() {
    //$('input[name=item_count]').bind("change keyup input click", function() {

        console.log('ввод количества вручную');

        if (this.value.match(/[^0-9]/g)) {
            this.value = this.value.replace(/[^0-9]/g, '');
        }

        if(this.value >  999){
            this.value = 999;
        }

        // // если ввод вручную первый раз, то округляем до пачек
        // var st_index = $(this).attr('step_index');
        // var package = $(this).attr('step');

        // if(st_index == "0") {
            // //округляем если вводят вручную
            // if ((this.value % package)) {
            //     if ((this.value / package) < 1) {
            //         this.value = package;
            //     }
            //     this.value =  Math.round(this.value / package) * package;
            // }
        // }

        // $(this).attr({step_index: +st_index + 1,});

        updateCountItemInCart($(this), 'add');

    });


    // $('body').on('click', '.js-clear-cart',  function (e) {
    //     e.preventDefault();
    //     var options = {};
    //     $.ajax({
    //         type: 'POST',
    //         url: "/cart/clear",
    //         data: options,
    //         success: function (data) {
    //             $('.w-cart .pcs').text(data.count);
    //             $('.w-cart .cart-count').text(data.price.toFixed(2) + ' руб.');
    //             $('input[name=item_count]').val(0)

    //             if(window.location.pathname == '/cart'){
    //                 window.location.href = '/cart';
    //             }

    //         },
    //         async:false
    //     });
    // });

    //setInterval(updateTest, 1000);

    function updateTest(){
        updateCountItemInCart(timeCounterItem.count, timeCounterItem.action)
    }

    function updateCountItemInCart(counter, action) {
       /* items_ajax = new Map(); //очищаем массив
              var jon = $(document).find('input[name=item_count]');

              for (var i = 0; i < jon.size(); i++) {
                if(parseInt(jon[i].value) > 0)
                {
                    var id = jon[i].getAttribute('data-item_id');
                    items_ajax.set( id.toString(), parseInt(jon[i].value) );
                    //items_ajax.push({ id.toString() : parseInt(jon[i].value)});
                    //.data('item_id');
                }
              }


        clearTimeout(timerId);
        timerId = setTimeout(function(){
        }, 1000);*/

        ajax_tmp++;
        var ajax_top = ajax_tmp;

        if(counter == undefined){
            return;
        }

        var options = {};

        options['value'] = +counter.val();
        if(isNaN(options['value'])) {
            options['value'] = 0;

        }
        if(!options['value']){
            action = 'remove';
        }
        options['itemId'] = counter.data('item_id');
        options['item1cId'] = counter.data('1cid');
        options['action'] = action;
        options['cartId'] = $('#currentCart').val();

        options['cart_partial'] = $('#cartPartial').val();

        $('input[data-item_id='+ options['itemId'] +']').val(options['value']);

        ajax = $.ajax({
            type: 'POST',
            url: "/cart/update",
            data: options,
            beforeSend : function() {

                if(ajax) {
                    //ajax.abort();
                }
            },
            success: function (data) {

                        // Если не найдено
                    var existEl =  $('.cart-table input[data-item_id ='+options['itemId']+']');
                    // if(window.location.pathname == '/cart' && !existEl.length){
                    //     window.location.href = '/cart';
                    // }
                    if(data.item_price != undefined)
                    {
                       existEl.parents('.js-pos-item').find('.js-item-price').data('price', data.item_price);
                    }
                    else
                    {
                        existEl.parents('.js-pos-item').find('.js-item-price').data('price', 0);
                    }

                    $('.w-cart .pcs').text(data.count)

                    if(data.price != undefined)
                    {
                        $('.w-cart .cart-count').text(data.price.toFixed(2) + ' руб.')
                    }
                    else
                    {
                         $('.w-cart .cart-count').text('0' + ' руб.')
                    }

                    updateCart();
                    ajax = undefined;
                //}


            },
            async: true
        });


        console.log(options['cart_partial']);



        if( options['cart_partial'] == 1){

            if(options['cartId'] != 0) {
                var url =  "/cart/"+options['cartId']+"?cart_partial=1"
            }
            else {
                var url =  "/cart?cart_partial=1"
            }

            ajax = $.ajax({
                type: 'GET',
                url: url,
                data: options,
                success: function (data) {
                    $('.update_content').html(data);
                    var cart = generate_cart();
                    cart.set_discount_price();


                    $('tr.js-pos-item').each(function (e) {
                        cart.recalc_item(this);
                    });

                    cart.set_gift();
                    cart.set_general_info();



                updateCart();
                }


        })
    }


        timeCounterItem = {};
    }

    function generate_cart() {
        var discount = parseFloat($('input[name=calc_type]:checked').data('calc_discount'));
        if(!discount) {
           discount = 0;
        }

        var personal_discount = parseFloat($('input[name=delivery_type]:checked').data('delivery_discount'));
        if(!personal_discount) {
               personal_discount = 0;
        }
        var plus;
        var total_discount;
        var overprice;
        var count_position_in_cart = 0;
        var count_items_in_cart = 0;
        var payment_type_discount = 0;
        var total_saving_info = '';
        var cart = {

            // Общие скидки
            discount: {
                personal: $('.td-total-sale-info').data('personal_discount'),
                pay: discount,
                delivery: personal_discount,
            },

            list_gifts: {},

            get_general_discount: function () {

              return this.discount.personal + this.discount.pay +this.discount.delivery
            },

            general: {
                weight: 0,
                price: 0,
                discount: 0,
                price_saving: 0
            },

            // Общая цена
            // Общая скидка
            // Общий вес

            get_count: function(e){

                var count = +parseInt($(e).find('input[name=item_count]').val());
                if (isNaN(count)) {
                    return 0;
                }
                return count;
            },

            get_data_item: function(e){

                return {
                    price:  $(e).find('.js-item-price').data('price'),
                    discount:  - $(e).find('.js-item-price').data('item_discount'),
                    count:  this.get_count(e),
                    weight:  (parseFloat($(e).find('.td-weight').data('weight'))),
                    gift_id: $(e).find('.js-item-gift').data('gift_id'),
                    oldPrice:  parseFloat($(e).find('.content-show-old-price').text()) || 0,
                    is_active:  parseInt($(e).find('.js-item-price').data('item_is_action')),
                    price_min:  parseFloat($(e).find('.js-item-price').data('item_min_price')),
                };
            },

            set_item: function (e, item) {

                // $(e).find('td.td-price.overprice').text(number_format(Math.ceil(Math.floor(item.price_with_discount*10000))/10000 , 2, '.',''));
                $(e).find('td.td-price.overprice').text(parseFloat(Math.ceil(Math.floor(item.price_with_discount*10000))/10000).toFixed(2));
                $(e).find('.js-price-rel').val(item.price);

                overprice = $(e).find('td.td-price.overprice').text();
                payment_type_discount += (overprice - item.price) * item.count;
                this.general.price += overprice * item.count;
                count_items_in_cart += item.count;
                count_position_in_cart++;

                // $(e).find('td.js-item-total-price').text(number_format(overprice * item.count , 2, '.',''));
                $(e).find('td.js-item-total-price').text(parseFloat(overprice * item.count).toFixed(2));
                // $(e).find('.td-weight').text(number_format(item.general_weight , 2, '.','') + ' кг');
                $(e).find('.td-weight').text(parseFloat(item.general_weight).toFixed(2) + ' кг');

                total_discount = -Math.round((1 - item.price_with_discount / parseFloat($(e).find('.js-item-price').data('price'))) * 100);
                if(total_discount > 0) {
                    plus = '+';
                } else {
                    plus = '';
                }
                $(e).find('.js-item-discount').text(plus + total_discount + '%');
                // }

            },

            set_general_info: function () {

                if(payment_type_discount < 0) {
                    // total_saving_info = 'Ваша экономия: <b>' + number_format(Math.abs(payment_type_discount) , 2, '.',' ')  + '</b> руб';
                    total_saving_info = 'Ваша экономия: <b>' + parseFloat(Math.abs(payment_type_discount) , 2, '.',' ')  + '</b> руб';
                }

                $('.total-sale-info').html(total_saving_info);

                $('.w-cart-total-price-cel input[name=total_price]').val(this.general.price);
                $('.w-cart-total-price-cel input[name=total_savings]').val(this.general.price_saving);

                $('td.js-total-weight-info span').text(parseFloat(  this.general.weight ).toFixed(2)  + ' кг');
                // $('td.js-total-weight-info span').text(number_format(  this.general.weight , 2, ',',' ')  + ' кг');
                $('td.js-total-price').text(parseFloat(this.general.price).toFixed(2)  + ' руб.');
                // $('td.js-total-price').text(number_format(this.general.price , 2, '.',' ')  + ' руб.');
                $('.total-price-info b').text(parseFloat(this.general.price).toFixed(2)  + ' руб.');
                // $('.total-price-info b').text(number_format(this.general.price , 2, '.',' ')  + ' руб.');
                $('#js-total-in-cart span.summ_in_cart').text(parseFloat(this.general.price).toFixed(2));
                // $('#js-total-in-cart span.summ_in_cart').text(number_format(this.general.price , 2, '.',' '));
                $('#js-total-in-cart span.count_position_in_cart').text(count_position_in_cart);
                $('#js-total-in-cart span.count_items_in_cart').text(count_items_in_cart);
            },

            set_list_gift: function (item) {
                if(item.gift_id == undefined || item.gift_id == ""){
                    return false;
                }

                if(item.gift_id == ""){
                    item.gift_id = 0;
                }

                if (item.gift_id in this.list_gifts) {
                    this.list_gifts[item.gift_id] += item.count;
                } else {
                    this.list_gifts[item.gift_id] = item.count;
                }

            },

            set_gift: function (item) {

                for (key in this.list_gifts) {
                    var tr_gift = $('tr[data-tr_gift_id='+key+']');
                    var tr_gift_weight = tr_gift.find('.td-weight').data('weight');
                    tr_gift.find('.td-pcs').text(this.list_gifts[key]);
                    tr_gift.find('.td-weight').text(parseFloat (this.list_gifts[key] * tr_gift_weight).toFixed(2) + ' кг');
                    // tr_gift.find('.td-weight').text(number_format( (this.list_gifts[key] * tr_gift_weight) , 2, '.',' ') + ' кг');
                }

                var list_gifts = this.list_gifts;
                $('.gift-table .table-body tr.tr_gift').each(function (e) {
                    if (!($(this).data('tr_gift_id') in list_gifts )) {
                        $(this).hide();
                    }

                });

            },

            recalc_item: function (e) {

                item = this.get_data_item(e);
                this.set_list_gift(item);
                item.general_weight =  item.weight * item.count;




                // Акционный товар
                //item.general_price = parseFloat(item.price * item.count);
                item.price_with_discount = parseFloat(item.price);

                //COM: Сверить цены


                if(item.oldPrice == item.price || !item.oldPrice) {
                    if(item.is_active){
                        item.price_with_discount = parseFloat(parseFloat(item.price) + (item.price * (this.get_general_discount()/100)),2);
                    } else {
                        item.price_with_discount = parseFloat(parseFloat(item.price) + (item.price * (this.get_general_discount()/100)),2);
                        // this.general.price_saving += parseFloat(parseFloat(item.price) * (this.get_general_discount()/100) * item.count);

                    }
                }
                else {
                    if(item.is_active){
                        if(this.get_general_discount() > 0){

                            item.price_with_discount = parseFloat(parseFloat(item.price) + (item.price * (this.get_general_discount()/100)),2);
                        } else {
                            item.price_with_discount = item.price;
                        }
                    } else {
                        item.price_with_discount = parseFloat(parseFloat(item.price) + (item.price * (this.get_general_discount()/100)),2);
                    }

                }

                if(item.price_with_discount < item.price_min){
                    item.price_with_discount = item.price_min;
                }

                this.general.weight += item.general_weight;

                this.set_item(e, item);
            },


            set_discount_price: function () {
                $('thead td.td-price span').html(this.get_general_discount() + '%');
            },

            // set_discount_price: function () {
            //     $('.js-item-discount').html(this.get_general_discount() + '%');
            // },


            // не используется с 12.11.20
            round: function(value) {
                //return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
                // return +value.toFixed(2);
                //return Number(fixed(value));
                //return Number(Math.round(value+'e'+1)+'e-'+1);
            }

        };
        return cart;


    }


    function updateCart() {


        var cart = generate_cart();
        cart.set_discount_price();


        $('tr.js-pos-item').each(function (e) {
            cart.recalc_item(this);
        });

        cart.set_gift();
        cart.set_general_info();

    }

    updateCart();

    $('body').on('change', 'input[name=calc_type], input[name=delivery_type]', function (e) {
        updateCart();
    })


    $('body').on('click', '.js-link-add-address',  function (e) {
        e.preventDefault();
        $('.js-add-address-block').show();
        $(this).hide();
    });

    $('body').on('click', '.js-cancel-address-block',  function (e) {
        e.preventDefault();
        $('.js-link-add-address').show();
        $('.js-add-address-block').hide();
    });

    $('body').on('click', '.js-send-address-block',  function (e) {
        e.preventDefault();

        var address_block = $(this).parents('.js-add-address-block');
        var thisElement = $(this);

        var options = {};
        options['address'] = address_block.find('input[name=address]').val();
        options['comment'] = address_block.find('input[name=comment]').val();

        //if( !options['address'] || !options['comment'] ){
        if( !options['address'] ){
            if(!options['address']){
                address_block.find('input[name=address]').addClass('error')
            } else{
                address_block.find('input[name=address]').removeClass('error')
            }
/*
            if(!options['comment']){
                address_block.find('input[name=comment]').addClass('error')
            } else {
                address_block.find('input[name=address]').removeClass('error')
            }
*/
            return false;

        }

        $.ajax({
            type: 'POST',
            url: "/profile/address/add",
            data: options,
            success: function (data) {
                if ($('.delivery-option-block>label').length){
                    $('.delivery-option-block>label').last().after(data);
                } else {
                    $('.wrapper.delivery-option-block p').after(data);
                    $('.wrapper.delivery-option-block input:first-child').prop('checked', true);
                }
            },
            async:false
        });

        $('.js-link-add-address').show();
        $('.js-add-address-block').hide();

    });



    // $(document).on('click', '.js-delete-item-from-cart', function () {

    // });


    //COM: Удалить элемент из корзины
    $('body').on('click', '.js-delete-item-from-cart',  function (e) {

        e.preventDefault();

        var url;
        var options = {};

        options ['cartId'] = $('#currentCart').val();


        if(options['cartId'] != 0) {
            url =  "/cart/"+options['cartId']+"?cart_partial=2"
        }
        else {
            url =  "/cart?cart_partial=2"
        }
        ajax = $.ajax({
            type: 'GET',
            url: url,
            data: options,
            success: function (data) {
                $('.forgot_with').html(data);
            }
        })

        options['item1cId'] = $(this).data('1c_id');
        var thisElement = $(this);

        $.ajax({
            type: 'POST',
            url: "/cart/delete",
            data: options,
            success: function (data) {
                $('.w-cart .pcs').text(data.count)
                $('.w-cart .cart-count').text(data.price.toFixed(2) + ' руб.')
                thisElement.parents('tr').remove();
            },
            async:false
        });

        console.log('Удаляем элемент корзины');

        updateCart();
    });

    //COM: Target
    $('body').on('click', '.js-open-window', function (e) {
        e.preventDefault();
        open_window('item', $(this).prop('href'), 50, 60, 1000, 750, 0,0,0,0,0)
    });


    $('body').on('click', '.js-open-window', function (e) {
        e.preventDefault();
        open_window('item', $(this).prop('href'), 50, 60, 1000, 750, 0,0,0,0,0)
    });


    function open_window(name, url, left, top, width, height, toolbar, menubar, statusbar, scrollbar, resizable)
    {
        toolbar_str = toolbar ? 'yes' : 'no';
        menubar_str = menubar ? 'yes' : 'no';
        statusbar_str = statusbar ? 'yes' : 'no';
        scrollbar_str = scrollbar ? 'yes' : 'no';
        resizable_str = resizable ? 'yes' : 'no';
        window.open(url, name, 'left='+left+',top='+top+',width='+width+',height='+height+',toolbar='+toolbar_str+',menubar='+menubar_str+',status='+statusbar_str+',scrollbars='+scrollbar_str+',resizable='+resizable_str);
    }


    var checked = false;

    $('.for-checkbox-select-all').click(function() {
        if (checked) {
            $(':checkbox').each(function() {
                $(this).prop('checked', false).trigger('refresh');
            });
            checked = false;
        } else {
            $(':checkbox').each(function() {
                $(this).prop('checked', true).trigger('refresh');
            });
            checked = true;
        }
        return false;
    });

    $('.change-position').click(function(e) {
        e.preventDefault();
        var currentCart = $('#currentCart').val();
        var type = $(this).data("action");
        var name =  type === 1 ?  'Перенести' : 'Копировать';
        var isConfirm =  confirm(name+ " выбранные товары?");
        var cart = $(this).data("idcart");
        var arrayOfItems = [];

        $('tbody :checkbox').each(function() {
            if(this.checked ) {
                var itemObject = {
                    'item1cId' : this.value,
                    'count' : $('#item-'+this.value).val()
                };
                arrayOfItems.push(itemObject);
            }
        });

console.log(arrayOfItems);
        if(isConfirm) {
        $.ajax({
            type: 'POST',
            url: "/changeItemCart",
            data: {
                'cart': cart,
                'currentCart': currentCart,
                'type': type,
                'items':arrayOfItems
            },
            success: function (e) {
                document.location.reload(true);
            },
        });
        }


    });


    $('.change-position-with-main').click(function(e) {
        e.preventDefault();
        var currentCart = $('#currentCart').val();

        var isConfirm =  confirm( "Поменять местами с основной корзиной все товары?");
        var cart = $(this).data("idcart");
        var arrayOfItems = [];

        $('tbody :checkbox').each(function() {
            if(this.checked ) {
                var itemObject = {
                    'itemId' : this.value,
                    'count' : $('#item-'+this.value).val()
                };
                arrayOfItems.push(itemObject);
            }
        });


        if(isConfirm) {
        $.ajax({
            type: 'POST',
            url: "/changeItemCartWithMain",
            data: {
                'cart': cart,
                'currentCart': currentCart,
                'items':arrayOfItems
            },
            success: function (e) {
                document.location.reload(true);
            },
        });
        }


    });


    $('.delete-position').click(function(e) {
        e.preventDefault();
        var currentCart = $('#currentCart').val();
        var type = $(this).data("action");
        var isConfirm =  confirm("Удалить выбранные товары из корзины?");
        var cart = $(this).data("idcart");
        var arrayOfItems = [];

        $('tbody :checkbox').each(function() {
            if(this.checked ) {
                var itemObject = {
                    'itemId' : this.value,
                    'count' : $('#item-'+this.value).val()
                };
                arrayOfItems.push(itemObject);
            }
        });

        if(isConfirm) {
        $.ajax({
            type: 'POST',
            url: "/cart/deleteFew",
            data: {
                'cart': cart,
                'currentCart': currentCart,
                'type': type,
                'items':arrayOfItems
            },
            success: function (e) {
                document.location.reload(true);
            },
        });
        }


    });

    // обработка "скрыть-показать" "Не забудь купить" и др.
    $('body').on('click', '.toggler-buttonfor-cart', function (e) {
        console.log('жмем плюсик');
        $(this).toggleClass('_minus');
        var categoryId = $(this).data('category');
        $('.category-id-'+categoryId).each(function () {
            $(this).toggleClass('_disable')
        });
    });
    $('body').on('click', '.toggler-buttonfor-cart-2', function (e) {
        console.log('жмем надпись');
        var categoryId = $(this).data('category');
        $('#'+ categoryId).toggleClass('_minus');
        console.log(categoryId);
        $('.category-id-'+categoryId).each(function () {
            $(this).toggleClass('_disable')
        });
    });


});


