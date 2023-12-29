// $(document).ready(function(){

    // функция расчета цен в корзине ********************************************
    // function cartLineCalculate(discount_percent = 1, agreement_items_str = '') {
    //
    //     // отображаем блок результатов
    //     $('.js-completion-block').show();
    //
    //     // id текущей корзины
    //     var current_cart_id = $('.js-completion-block').data('cartId');
    //
    //     // вставляем id корзины в input
    //     $('input[name="cart_id"]').val(current_cart_id);
    //
    //     // собираем товарные линии корзины
    //     var current_cart_lines = $('.js-cart-id-'+current_cart_id).find('.cart-item-line');
    //
    //     if(current_cart_lines.length) { // если есть товар в корзине
    //
    //         var total_weight = 0;
    //         var total_price = 0;
    //         var total_economy = 0;
    //         var item_input = "";
    //         var total_input = "";
    //
    //         // массив товаров соглашения
    //         var agreement_items_arr = [];
    //         // если строка товаров соглашения не пуста
    //         if (agreement_items_str != '') {
    //             // делим, преобразуем в массив
    //             agreement_items_arr = agreement_items_str.split(';').filter(element => element != '');
    //         }
    //
    //         current_cart_lines.each(function(){
    //
    //             // берем код товара
    //             var item_1c_id = $(this).data('id-1c');
    //             // стандартная цена
    //             var standard_price = parseFloat($(this).data('standartPrice'));
    //             // заказанное количество
    //             var item_count = parseFloat($(this).find('input[name="item_count"]').val());
    //             if(!item_count) {
    //                 item_count = 0;
    //             }
    //
    //             // товар соглашения
    //             var agr_item_price = 0;
    //             // если массив товаров соглашения не пустой
    //             if (agreement_items_arr.length) {
    //                 var items_arr = [];
    //                 $.each(agreement_items_arr, function(key, val){
    //                     // делим
    //                     items_arr = val.split('-').filter(element => element != '');
    //                     // если в корзине имеется код соглашения
    //                     if (item_1c_id == items_arr[0]) {
    //                         agr_item_price = items_arr[1];
    //                         // останавливаем цикл
    //                         return false;
    //                     }
    //                 });
    //             }
    //
    //             // определяем необходимые переменные
    //             var calc_price; // расчетная цена
    //             var min_price = parseFloat($(this).data('minPrice')); // минимальная цена
    //             var mr_price = parseFloat($(this).data('mrPrice')); // розничная цена
    //             var items_weight = parseFloat(parseFloat($(this).data('itemWeight')) * item_count).toFixed(2); // вес товаров в линии
    //             // var items_weight = number_format(parseFloat($(this).data('itemWeight')) * item_count, 2, '.',''); // вес товаров в линии
    //             var sum_line;
    //
    //             // массив дискаунта
    //             var discount_arr = $(this).data('discountString').split(';').filter(element => element != '');
    //
    //             // если товар из соглашения
    //             if (agr_item_price) {
    //                 calc_price = parseFloat(agr_item_price);
    //
    //                 if(discount_arr.length) {
    //                     var discount_el;
    //                     $.each(discount_arr, function (index, value) {
    //                         discount_el = value.split('-');
    //                         if(item_count >= parseFloat(discount_el[0])) {
    //                             calc_price = parseFloat(calc_price * (100 - parseFloat(discount_el[2])) / 100).toFixed(2);
    //                             // calc_price = number_format(calc_price * (100 - parseFloat(discount_el[2])) / 100, 2, '.','');
    //                         }
    //                     });
    //                 }
    //
    //             } else { // если нет
    //
    //                 // расчетная цена с учетом вида расчета
    //                 calc_price = standard_price * discount_percent;
    //
    //                 if(discount_arr.length) {
    //                     var discount_el;
    //                     $.each(discount_arr, function (index, value) {
    //                         discount_el = value.split('-');
    //
    //                         if(item_count >= parseFloat(discount_el[0])) {
    //                             calc_price = parseFloat(standard_price * discount_percent).toFixed(2) * (100 - discount_el[2]) / 100;
    //                             // calc_price = number_format(standard_price * discount_percent, 2, '.', '') * (100 - discount_el[2]) / 100;
    //                         }
    //                     });
    //                 }
    //             }
    //
    //             // если расчетная цена меньше минимальной
    //             if(calc_price < min_price) {
    //                 calc_price = min_price;
    //             }
    //
    //             // определяем процент
    //             var percent = parseFloat((((calc_price * 1.2) / (standard_price * 1.2))- 1) * 100).toFixed(0);
    //             // var percent = number_format((((calc_price * 1.2) / (standard_price * 1.2))- 1) * 100, 0);
    //
    //             // вставляем значение веса
    //             $(this).find('.js-weight').html(items_weight);
    //
    //             // вставляем значение полученной цены
    //             $(this).find('.js-calculated-price').html(parseFloat(calc_price * 1.2).toFixed(2));
    //             // $(this).find('.js-calculated-price').html(number_format(number_format(calc_price, 2, '.', '') * 1.2, 2, '.', ''));
    //
    //             // подсчитываем и вставляем процент
    //             // добавляем + перед значением, если надо
    //             var add_plus;
    //             if(percent > 0) {
    //                 add_plus = "+";
    //             } else {
    //                 add_plus = "";
    //             }
    //             $(this).find('.js-percent').html(add_plus + percent + '%');
    //
    //
    //             // опледеляем сумму по товарной линии
    //             // sum_line = number_format(item_count * calc_price, 2, '.','');
    //             // sum_line = number_format(number_format(calc_price, 2, '.', '') * item_count * 1.2, 2, '.', '');
    //             sum_line = parseFloat(calc_price * item_count * 1.2).toFixed(2);
    //             // вставляем значение полученной суммы
    //             $(this).find('.js-line-total-price').html(sum_line);
    //
    //             total_weight += parseFloat($(this).find('.js-weight').html());
    //             total_price += parseFloat($(this).find('.js-line-total-price').html());
    //             total_economy += parseFloat(standard_price * 1.2).toFixed(2) * item_count - sum_line;
    //
    //             // создаем и вставляем input с данными о товаре
    //             item_input = '<input type="hidden" name="items[]" value="'+item_1c_id+'-'+item_count+'-'+parseFloat(calc_price).toFixed(2)+'">';
    //             // item_input = '<input type="hidden" name="items[]" value="'+item_1c_id+'-'+item_count+'-'+number_format(calc_price, 2, '.', '')+'">';
    //             $('input[name="_token"]').after(item_input);
    //
    //         });
    //
    //         // вставляем значение общего веса
    //         // $('.js-cart-id-'+current_cart_id).find('.js-total-weight').html(number_format(total_weight, 2, '.',''));
    //         $('.js-cart-id-'+current_cart_id).find('.js-total-weight').html(parseFloat(total_weight).toFixed(2));
    //
    //         // вставляем значение полной стоимости
    //         // $('.js-cart-id-'+current_cart_id).find('.js-total-price').html(number_format(total_price, 2, '.',''));
    //         $('.js-cart-id-'+current_cart_id).find('.js-total-price').html(parseFloat(total_price).toFixed(2));
    //         // $('.js-result-price').html(number_format(total_price, 2, '.',''));
    //         $('.js-result-price').html(parseFloat(total_price).toFixed(2));
    //
    //         // вставляем значение экономии
    //         var economy_str = "";
    //         if(total_economy > 0) {
    //             // economy_str = "Ваша экономия: <span>"+number_format(total_economy, 2, '.','')+"</span> руб.";
    //             economy_str = "Ваша экономия: <span>"+parseFloat(total_economy).toFixed(2)+"</span> руб.";
    //         } else {
    //             total_economy = 0;
    //         }
    //         $('.js-total-saving').html(economy_str);
    //
    //         // создаем и вставляем input с данными вес-экономия-всего
    //         // var total_input_val = number_format(total_weight, 2, '.','')+'-'+number_format(total_economy, 2, '.','')+'-'+number_format(total_price, 2, '.','');
    //         var total_input_val = parseFloat(total_weight).toFixed(2)+'-'+parseFloat(total_economy).toFixed(2)+'-'+parseFloat(total_price).toFixed(2);
    //         total_input = '<input type="hidden" name="total" value="'+total_input_val+'">';
    //         $('input[name="_token"]').after(total_input);
    //
    //     } else {
    //         // скрываем блок результатов
    //         $('.js-completion-block').hide();
    //         $('.js-result-price').html(0);
    //     }
    //
    // }
    //
    // // пересчитываем корзину
    // cartLineCalculate();

    // перключение корзин ***************************************
    // console.log('переключение корзин');
    // $('.js-cart-button').click(function(){
    //
    //     // удаляем класс у всех кнопок
    //     $('.js-cart-button').removeClass('active');
    //     // добавляем класс у требуемой
    //     $(this).addClass('active');
    //
    //     // определяем id корзины
    //     var cart_id = $(this).data('cart-id');
    //     // определяем имя корзины
    //     var cart_name = $(this).data('cart-name');
    //     // определяем количество наименований
    //     var count_items = $(this).data('count-items');
    //     // определяем количество товаров всего
    //     var count_all_items = $(this).data('count-all-items');
    //
    //     // переписываем куку
    //     $.cookie('cart_id', cart_id, {expires: 1, path: '/'});
    //
    //     // скрываем блоки корзин
    //     $('.js-cart').hide();
    //     // отображаем нужную
    //     $('.js-cart-id-'+cart_id).show();
    //
    //     // переписываем данные в крошках и заголовке
    //     $('.js-cart-name').html(cart_name);
    //     $('.js-count-items').html(count_items);
    //     $('.js-count-all-items').html(count_all_items);
    //
    //     // показываем/скрываем кнопку Удалить корзину
    //     if(cart_id == 0) {
    //         $('.js-del-cart').hide();
    //     } else {
    //         $('.js-del-cart').show();
    //     }
    //     // прописываем id удаляемой корзины
    //     $('.js-del-cart').attr('data-cart-del-id', cart_id);
    //
    //     // переписываем id корзины в блоке результатов заказа
    //     $('.js-completion-block').data('cartId', cart_id);
    //
    //     // смотрим, нажата ли радиокнопка вида расчета, высчитывем скидку/надбавку
    //     if($('input[name="calc_type"]:checked').length) {
    //         var discount_percent = parseFloat($('input[name="calc_type"]:checked').data('calc_discount')) / 100 + 1;
    //     } else {
    //         discount_percent = 1;
    //     }
    //
    //     // удаляем input товаров и общих данных
    //     $('input[name="items[]"], input[name="total"]').remove();
    //
    //     // снимаем check со способа доставки, адресов, соглашений
    //     $('input[name=delivery_type], input[name=delivery_address], input[name=calc_type]').removeAttr("checked");
    //     // блокируем инпуты адресов
    //     $('input[name=delivery_address]').attr('disabled', 'disabled');
    //     // отображаем и блокируем инпуты индивидуальных соглашений
    //     $('.js-personal-agreement').show().find('input[name=calc_type]').attr('disabled', 'disabled');
    //
    //     // пересчитываем корзину
    //     cartLineCalculate(discount_percent);
    // });

    // добавление новой корзины **********************************
    console.log('создание новой корзины');
    $('.js-add-new-cart').click(function() {

        // запускаем всплывающее окно
        $('.js-add-cart-popup').fadeIn('slow' , 'linear');

        // вписываем заголовок
        $('.popup_title').html($(this).html());

    });

    // закрываем всплывающее окно
    $('.js-popup-close').click(function() {
        $('.js-add-cart-popup').fadeOut('slow' , 'linear');
    });
    // закрываем всплывающее окно
    $('.js-popup-note-close').click(function() {
        $('.js-popup-note').fadeOut('slow' , 'linear');
    });

    // удаление корзины ***************************************************************
    console.log('удаление корзины');
    $('.js-del-cart').click(function(){
        // определяем id корзины
        var cart_id = $(this).attr('data-cart-del-id');

        // узнаем, есть ли товары в корзине
        var count_in_cart = $('[data-cart-id="'+cart_id+'"]').data('countItems');

        // если есть, выдаем предупреждение
        if(count_in_cart > 0) {
            // имя корзины
            var cart_name = $('[data-cart-id="'+cart_id+'"]').data('cartName');
            if(!confirm('Внимание!\nВ корзине '+cart_name+' есть товары.\nУдалить корзину вместе с товарами?')) {
                return false;
            }
        }

        // переписываем куку на главную корзину
        $.cookie('cart_id', '0', {expires: 1, path: '/'});

        // отправляем запрос на удаление
        window.location.replace('/cart-page/del-cart/'+cart_id);
    });

    // перемещение товаров между корзинами ***********************************************
//     console.log('перемещение товаров между корзинами');
//     // чекаем товар для действий
//     // чекаем один товар
//     $('.js-item-checker').click(function(){
//
//         // отображаем/скрываем птичку, устанавливаем/снимаем отметку для линии
//         $(this).toggleClass('active').parents('.js-item-element').toggleClass('checked');
//
//         // удаляем птичку у заголовков, снимаем отметку с корзины
//         $(this).parents('.js-cart').removeClass('checked').find('.js-all-items-checker').removeClass('active');
//
//     });
//
//     // чекаем весь товар
//     $('.js-all-items-checker').click(function(){
//
//         // отмечаем текущую корзину
//         $(this).parents('.js-cart').toggleClass('checked');
//
//         // скрываем все птички, отменяем отметку для всех линий корзины
//         $('.js-item-checker').removeClass('active');
//
//         // отмечаем/скрываем птички всех товаров
//         if($(this).parents('.js-cart').hasClass('checked')) {
//             $(this).parents('.js-cart').find('.js-item-checker, .js-all-items-checker').addClass('active');
//             $(this).parents('.js-cart').find('.js-item-element').addClass('checked');
//         } else {
//             $(this).parents('.js-cart').find('.js-item-checker, .js-all-items-checker').removeClass('active');
//             $(this).parents('.js-cart').find('.js-item-element').removeClass('checked');
//         }
//     });
//
//     // кнопка Переместить
//     $('.js-relocate').click(function(){
//
//         // собираем отмеченный товар
//         var checked_items = $(this).parents('.js-cart').find('.js-item-element.checked');
//
//         // если ничего не отмечено, выдаем предупреждение
//         if(!checked_items.length) {
//             alert('Не отмечен товар для переноса!');
//             return false;
//         }
//
//         // id текущей корзины
//         var current_cart_id = $(this).parents('.js-cart').data('cartId');
//
//         // id целевой корзины
//         var target_cart_id = $(this).data('cartId');
//
//         // массив 1c_id товаров для переноса
//         var item_id, items_array = [];
//         checked_items.each(function(){
//             item_id = $(this).data('id-1c');
//             items_array.push(item_id);
//         });
//
//         // переносим с предупреждением
//         var сonfirm_relocate =  confirm("Перенести выбранные товары?");
//         if(сonfirm_relocate) {
//             $.ajax({
//                 type: 'post',
//                 url: "/cart-page/relocate-items",
//                 data: {
//                     'current_cart_id': current_cart_id,
//                     'target_cart_id': target_cart_id,
//                     'items': items_array,
//                 },
//                 success: function(){
//                     // console.log(data);
//                     document.location.reload(true);
//                 },
//             });
//         }
//     });
//
//     // кнопка Копировать
//     $('.js-copy').click(function(){
//
//         // собираем отмеченный товар
//         var checked_items = $(this).parents('.js-cart').find('.js-item-element.checked');
//
//         // если ничего не отмечено, выдаем предупреждение
//         if(!checked_items.length) {
//             alert('Не отмечен товар для копирования!');
//             return false;
//         }
//
//         // id текущей корзины
//         var current_cart_id = $(this).parents('.js-cart').data('cartId');
//
//         // id целевой корзины
//         var target_cart_id = $(this).data('cartId');
//
//         // массив 1c_id товаров для копирования
//         var item_id, items_array = [];
//         checked_items.each(function(){
//             item_id = $(this).data('id-1c');
//             items_array.push(item_id);
//         });
//
//         // копируем с предупреждением
//         var сonfirm_relocate =  confirm("Копировать выбранные товары?");
//         if(сonfirm_relocate) {
//             $.ajax({
//                 type: 'post',
//                 url: "/cart-page/copy-items",
//                 data: {
//                     'current_cart_id': current_cart_id,
//                     'target_cart_id': target_cart_id,
//                     'items': items_array,
//                 },
//                 success: function(){
//                     // console.log(data);
//                     document.location.reload(true);
//                 },
//             });
//         }
//     });
//
//     // кнопка Удалить выбранное из корзины
//     $('.js-delete-items').click(function(){
//
//         // собираем отмеченный товар
//         var checked_items = $(this).parents('.js-cart').find('.js-item-element.checked');
//
//         // если ничего не отмечено, выдаем предупреждение
//         if(!checked_items.length) {
//             alert('Не отмечен товар для удаления!');
//             return false;
//         }
//
//         // id текущей корзины
//         var current_cart_id = $(this).parents('.js-cart').data('cartId');
//
//         // массив 1c_id товаров для удаления
//         var item_id, items_array = [];
//         checked_items.each(function(){
//             item_id = $(this).data('id-1c');
//             items_array.push(item_id);
//         });
//
//         // удаление с предупреждением
//         var сonfirm_relocate =  confirm("Удалить выбранные товары?");
//         if(сonfirm_relocate) {
//             $.ajax({
//                 type: 'post',
//                 url: "/cart-page/delete-items",
//                 data: {
//                     'current_cart_id': current_cart_id,
//                     'items': items_array,
//                 },
//                 success: function(){
//                     // console.log(data);
//                     document.location.reload(true);
//                 },
//             });
//         }
//     });
//
//     // кнопка Поменять местами с Основной корзиной
//     $('.js-swapping').click(function(){
//
//         // id текущей корзины
//         var current_cart_id = $(this).parents('.js-cart').data('cartId');
//
//         // обмен с предупреждением
//         var сonfirm_relocate =  confirm("Поменяться товаром с Основной корзиной?");
//         if(сonfirm_relocate) {
//             $.ajax({
//                 type: 'post',
//                 url: "/cart-page/swapping",
//                 data: {
//                     'current_cart_id': current_cart_id,
//                 },
//                 success: function(){
//                     // console.log(data);
//                     document.location.reload(true);
//                 },
//             });
//         }
//     });
//
    // отображение/скрытие выпадашки на малых экранах
    if($(window).width() > 1260) {
        $('.js-drop-down-title').hover(function(){
            $('.js-drop-down').toggle();
        });

    } else {
        $('.js-drop-down-title').click(function(){
            $('.js-drop-down').toggle();
        });

        // сворачивание выпадашки, если кликаем вне
        $(document).click(function(e) {
            if (!$('.js-drop-down-title').has(e.target).length) {
                $('.js-drop-down').hide();
            }

            e.stopPropagation();
        });
    }
//
//
// // *******************************************************************************
//
//     // удаление 1 товара из корзины (нажатие на крестик) **************************
//     $('.js-del-item').click(function(){
//
//         // id текущей корзины
//         var current_cart_id = $(this).parents('.js-cart').data('cartId');
//
//         // 1с_id товара
//         var item_1c_id = $(this).parents('.js-item-element').data('id-1c');
//
//         $.ajax({
//             type: 'post',
//             url: "/cart-page/del-item",
//             data: {
//                 'current_cart_id': current_cart_id,
//                 'item_1c_id': item_1c_id,
//             },
//             success: function(){
//                 // console.log(data);
//                 document.location.reload(true);
//             },
//         });
//     });
//
//     // очистка корзины (нажатие на Очистить корзину) ******************************
//     $('.js-empty-cart').click(function(){
//
//         // id текущей корзины
//         var current_cart_id = $(this).parents('.js-cart').data('cartId');
//
//         // текст предупреждения
//         var tag_text = $(this).text().trim();
//
//         // очистка с предупреждением
//         var сonfirm_delete =  confirm('Внимание!\r\nВы собираетесь ' + tag_text + '!\r\nВы уверены?');
//         if(сonfirm_delete) {
//             $.ajax({
//                 type: 'post',
//                 url: "/cart-page/empty-cart",
//                 data: {
//                     'current_cart_id': current_cart_id,
//                 },
//                 success: function(){
//                     // console.log(data);
//                     document.location.reload(true);
//                 },
//             });
//         }
//     });
//
//     // очистка всех корзин (нажатие на Очистить все корзины) ************************
//     $('.js-empty-all-carts').click(function(){
//
//         // текст предупреждения
//         var tag_text = $(this).text().trim();
//
//         // очистка с предупреждением
//         var сonfirm_delete =  confirm('Внимание!\r\nВы собираетесь ' + tag_text + '!\r\nВы уверены?');
//         if(сonfirm_delete) {
//             $.ajax({
//                 type: 'post',
//                 url: "/cart-page/empty-all-carts",
//                 data: {
//                     'empty_carts': 1,
//                 },
//                 success: function(){
//                     // console.log(data);
//                     document.location.reload(true);
//                 },
//             });
//         }
//     });
//
//     // обработка видов расчета **********************************************************
//     $('.js-paying-type').click(function() {
//
//         // определяем скидку/надбавку
//         var discount_percent = parseFloat($('input[name="calc_type"]:checked').data('calc_discount')) / 100 + 1;
//
//         // берем строку товаров соглашения
//         var agreement_items_str = $('input[name="calc_type"]:checked').data('agreement_items');
//
//         // удаляем input товаров
//         $('input[name="items[]"], input[name="total"]').remove();
//
//         // пересчитываем корзину
//         cartLineCalculate(discount_percent, agreement_items_str);
//
//     });
//
//     // снимаем обязательность нажатия на Пункт доставки ******************************
//     // отображаем, скрываем индивидуальные соглашения ********************************
//     // в зависимости от Способа доставки
//     // если жмем самовывоз
//     console.log('вывод соглашений')
//     $('.js-pickup').click(function(){
//         // снимаем обязательность и блокируем адреса доставок
//         $('input[name="delivery_address"]').removeAttr("required").removeAttr("checked").attr('disabled', 'disabled');
//         console.log('снимаем required');
//
//         // отображаем все индивидуальные соглашения
//         $('.js-personal-agreement').fadeIn().find('input[name="calc_type"]').removeAttr("disabled").attr("required", "required");
//
//         // пересчитываем корзину
//         cartLineCalculate();
//     });
//     // иначе
//     $('.js-no-pickup').click(function(){
//         // устанавливаем обязательность и разблокируем адреса доставок
//         $('input[name="delivery_address"]').attr("required", "required").removeAttr("disabled");
//         console.log('устанавливаем required')
//
//         // скрываем все индивидуальные соглашения
//         $('.js-personal-agreement').fadeOut().find('input[name="calc_type"]').removeAttr("disabled").removeAttr("required").removeAttr("checked");
//
//         // пересчитываем корзину
//         cartLineCalculate();
//     });
//
//     // отображаем индивидуальное соглашение, соответствующее адресу ******************
//     $('.js-delivery-address').click(function(){
//         // берем uuid партнера (адреса)
//         var partner_uuid = $(this).val();
//
//         // берем все индивидуальные соглашения
//         var agreements = $('.js-personal-agreement');
//
//         // скрываем, снимаем checked
//         agreements.fadeOut().find('input[name="calc_type"]').removeAttr("checked");
//
//         agreements.each(function(){
//             // берем uuid партнера соглашения
//             var agr_partner_uuid = $(this).find('input[name="calc_type"]').data('partner_uuid');
//
//             // если равны
//             if(partner_uuid == agr_partner_uuid) {
//                 // отображаем соглашение
//                 $(this).fadeIn();
//             }
//         });
//
//         // пересчитываем корзину
//         cartLineCalculate();
//     });
//
//
//     //************************************************
//     console.log('обработка кликов по кнопкам внизу');
//
//     $('.clap').click(function(){
//         $('.clap, .js-d-block').removeClass('active');
//
//         $(this).addClass('active');
//
//         if($('.clap.clap-n-forget').hasClass('active')) {
//             $('.block-n-forget').addClass('active');
//         }
//         if($('.clap.clap-b-with').hasClass('active')) {
//             $('.block-b-with').addClass('active');
//         }
//     });
//
//     //**********************************************
//     console.log('предотвращение повторнрого нажатия Отправить заказ');
//
//     $('.js-order-form').submit(function(e){
//
//         // берем сумму заказа
//         var sum = Number($($('.js-result-price')[0]).text());
//
//         // если меньше, сообщение
//         if (sum < 100) {
//             alert('Заказ должен быть от 100 руб!');
//             return false;
//         }
//
//         $('.js-submit-order').prop('disabled', true);
//     });


// });


