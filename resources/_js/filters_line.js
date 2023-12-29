$(document).ready(function() {

    console.log('фильтры каталожной выдачи');

    // поиск по слову ***********************************************
    $('.js-word-filter').on('change input', function(){
        // получаем слово
        var filter_word = $(this).val();
        // ищем
        search_filter_word(filter_word);
    })

    function search_filter_word(filter_word) {

        // переводим в нижний регистр
        filter_word = filter_word.toLowerCase();

        $('.js-item-element.js-filtred-item').each(function (е) {
            // берем имя товара, переводим в нижний регистр
            var item_name = $(this).data('name').toLowerCase();
            // ищем подстроку, если нет, прячем позицию
            if(item_name.indexOf(filter_word) !== -1){
                $(this).slideDown('100');
            } else {
                $(this).slideUp('100');
            }
        });

        console.log(filter_word);
    }

    // фильтры отображения цен **************************************
    // нажимаем кнопку фильтра ОПТ
    $('.js-opt-button').click(function(){
        // переключаем класс
        $('.js-opt-button').toggleClass('active');

        // переопределяем куку
        if($(this).hasClass('active')) {
            $.cookie('opt_state', 1, {expires: 7, path: '/'});
        } else {
            $.cookie('opt_state', 0, {expires: 7, path: '/'});
        }

        // переключаем заголовок цен
        if($(window).width()  > 1023) {
            $('.js-opt-head').fadeToggle('100');
        }

        // переключаем цену каталожной выдачи
        $('.js-opt').fadeToggle('100');
        // переключаем цену в карточке товара
        $('.js-item-card-opt').toggle();

    });

    // нажимаем кнопку фильтра %
    $('.js-purcent-button').click(function(){

        // переключаем класс
        $('.js-purcent-button').toggleClass('active');

        // переопределяем куку
        if($(this).hasClass('active')) {
            $.cookie('purcent_state', 1, {expires: 7, path: '/'});
        } else {
            $.cookie('purcent_state', 0, {expires: 7, path: '/'});
        }

        // переключаем заголовок цен
        if($(window).width()  > 1023) {
            $('.js-purcent-head').fadeToggle('100');
        }

        // переключаем видимость % каталожной выдачи
        $('.js-purcent').fadeToggle('100');

    });

    // нажимаем кнопку фильтра МРЦ
    $('.js-mr-button').click(function(){
        // переключаем класс
        $('.js-mr-button').toggleClass('active');

        // переопределяем куку
        if($(this).hasClass('active')) {
            $.cookie('mr_state', 1, {expires: 7, path: '/'});
        } else {
            $.cookie('mr_state', 0, {expires: 7, path: '/'});
        }

        if($(window).width()  > 1023) {
            $('.js-mr-head').fadeToggle('100');
        }

        // переключаем заголовок цен
        $('.js-mr').fadeToggle('100');
        // переключаем цену в карточке товара
        $('.js-item-card-mr').toggle();

    });

    // скрываем цены дисконта и дешевых товаров, если нажата только МРЦ
    $('.js-opt-button, .js-purcent-button, .js-mr-button').click(function(){
        if($.cookie('mr_state') == 1 && $.cookie('purcent_state') == 0 && $.cookie('opt_state') == 0) {
            $('.js-action-string, .js-cheep-price').hide();
        } else {
            $('.js-action-string, .js-cheep-price').show();
        }
    });

    // прячем заголовки на малых экранах
    if($(window).width() <= 1023) {
        console.log('прячем заголовки на малых экранах');
        $('.js-head, .js-opt-head, .js-purcent-head, .js-mr-head, .js-cart-head').hide();
    }

    // фильтры отображения товаров **************************************
    // отображение архивных
    $('.js-archive-button').click(function(){
        $(this).toggleClass('active');
        $('.js-cat-header.is_archive_cat').slideToggle('100');
        $('.js-cat-header.is_archive_cat').each(function(){
            if(!$(this).find('.js-catalog-toggler').hasClass('toggled')) {
                $(this).next('.items-line-block').slideToggle('100');
            }
        });
    });

    // новинки и акции
    $('.js-new-button, .js-action-button').click(function(){

        // переключатель активности кнопок
        if(!$(this).hasClass('active')) {
            $(this).addClass('active');
        } else {
            $(this).removeClass('active');
        }

        // прячем все строки, удаляем класс js-filtred-item
        $('.js-item-element').removeClass('js-filtred-item').hide();

        // если не активны кнопки, отображаем все позици
        if(!$('.js-new-button, .js-action-button, .js-in-price-button, .js-in-way-button, .js-reserve-button').hasClass('active')) {
            $('.js-all-button').addClass('active');

            // добавляем всем элементам класс js-filtred-item, отображаем
            $('.js-item-element').addClass('js-filtred-item').slideDown('100');

            // прячем аналоги, если товар есть в наличии
            $('.js-sort-item.js-count-1').each(function() {
                $('.js-item-' + $(this).data('id-1c')).hide();
            });

        }

        // отображение новинок
        if($('.js-new-button').hasClass('active')) {

            // отключаем фильтр наличия
            $('.js-all-button, .js-in-price-button, .js-in-way-button, .js-reserve-button').removeClass('active');

            // добавляем класс js-filtred-item, если его нет
            $('.js_new_item').each(function(){
                if(!$(this).hasClass('js-filtred-item')) {
                    $(this).addClass('js-filtred-item');
                }
            });

            // показываем новинки
            $('.js_new_item').slideDown('100');

        }

        // отображаем акции
        if($('.js-action-button').hasClass('active')) {

            // отключаем фильтр наличия
            $('.js-all-button, .js-in-price-button, .js-in-way-button, .js-reserve-button').removeClass('active');

            // добавляем класс js-filtred-item, если его нет
            $('.js_action').each(function(){
                if(!$(this).hasClass('js-filtred-item')) {
                    $(this).addClass('js-filtred-item');
                }
            });

            // показываем акции
            $('.js_action').slideDown('100');

        }

        // применяем фильтр по слову
        search_filter_word();

    });


    $('.js-in-price-button, .js-in-way-button, .js-reserve-button').click(function(){

        // переключатель активности кнопок
        if(!$(this).hasClass('active')) {
            $(this).addClass('active');
        } else {
            $(this).removeClass('active');
        }

        // прячем все строки
        $('.js-item-element').removeClass('js-filtred-item').hide();

        // если не активны кнопки, отображаем все позици
        if(!$('.js-new-button, .js-action-button, .js-in-price-button, .js-in-way-button, .js-reserve-button').hasClass('active')) {
            $('.js-all-button').addClass('active');

            // добавляем всем элементам класс js-filtred-item, отображаем
            $('.js-item-element').addClass('js-filtred-item').slideDown('100');

            // прячем аналоги, если товар есть в наличии
            $('.js-sort-item.js-count-1').each(function() {
                $('.js-item-' + $(this).data('id-1c')).hide();
            });
        }

        // отображение В наличии
        if($('.js-in-price-button').hasClass('active')) {

            // отключаем фильтр все, новинки, акции
            $('.js-all-button, .js-new-button, .js-action-button').removeClass('active');

            // добавляем класс js-filtred-item, если его нет
            $('.js-avalible-item').each(function(){
                if(!$(this).hasClass('js-filtred-item')) {
                    $(this).addClass('js-filtred-item');
                }
            });

            // показываем в наличии
            $('.js-avalible-item').slideDown('100');

        }

        // отображение В пути
        if($('.js-in-way-button').hasClass('active')) {

            // отключаем фильтр все, новинки, акции
            $('.js-all-button, .js-new-button, .js-action-button').removeClass('active');

            // добавляем класс js-filtred-item, если его нет
            $('.js-soon').each(function(){
                if(!$(this).hasClass('js-filtred-item')) {
                    $(this).addClass('js-filtred-item');
                }
            });

            // показываем в те что прибудут
            $('.js-soon').slideDown('100');

        }

        // отображение В резерве
        if($('.js-reserve-button').hasClass('active')) {

            // отключаем фильтр все, новинки, акции
            $('.js-all-button, .js-new-button, .js-action-button').removeClass('active');

            // добавляем класс js-filtred-item, если его нет
            $('.js-reserve').each(function(){
                if(!$(this).hasClass('js-filtred-item')) {
                    $(this).addClass('js-filtred-item');
                }
            });

            // показываем в те что в резерве
            $('.js-reserve').slideDown('100');

        }

        // применяем фильтр по слову
        search_filter_word();

    });

    // нажатие кнопки Все
    $('.js-all-button').click(function(){

        $(this).addClass('active');

        // добавляем класс js-filtred-item, если его нет
        $('.js-item-element').each(function(){
            if(!$(this).hasClass('js-filtred-item')) {
                $(this).addClass('js-filtred-item');
            }
        });

        // показываем все элементы
        $('.js-item-element').slideDown('100');

        // прячем аналоги, если товар есть в наличии
        $('.js-sort-item.js-count-1').each(function() {
            $('.js-item-' + $(this).data('id-1c')).hide();
        });

        // отключаем все фильтры
        $('.js-new-button, .js-action-button, .js-in-price-button, .js-in-way-button, .js-reserve-button').removeClass('active');

        // применяем фильтр по слову
        search_filter_word();

    });


    // сортировка товаров **************************************
    $('.js-a-z, .js-price-a-z, .js-price-z-a').click(function(){

        $('.js-a-z, .js-price-a-z, .js-price-z-a').removeClass('active');

        $(this).addClass('active');

        function showDiv() {
            var dfd = $.Deferred();

            $('.js-working').fadeIn(1000, dfd.resolve);

            return dfd.promise();
        }

        var item_elements_in_stock,
        item_elements_out_stock,
        all_items,
        array_in_stock_items,
        array_out_stock_items,
        array_all_items,
        item_analogs,
        cat_blocks,
        item_id_1c;
console.log($('.js-lines-block'));
        // сортировка по алфавиту
        if($('.js-a-z').hasClass('active')) {

            function sortAZ() {

                // собираем категории
                cat_blocks = $('.js-lines-block');

                cat_blocks.each(function(){

                    // собираем все элементы категории
                    all_items = $(this).find('.js-sort-item');
                    // собираем те что в наличии
                    item_elements_in_stock = $(this).find('.js-sort-item.js-count-1');
                    // собираем те что нет в наличии
                    item_elements_out_stock = $(this).find('.js-sort-item.js-count-0');
                    // преобразуем в массив
                    array_in_stock_items = $.makeArray(item_elements_in_stock);
                    array_out_stock_items = $.makeArray(item_elements_out_stock);


                    // сортируем те что есть в наличии
                    array_in_stock_items.sort(function(a, b) {
                        var compA = $(a).data('name').toUpperCase();
                        var compB = $(b).data('name').toUpperCase();
                        return (compA < compB) ? -1 : (compA > compB) ? 1 : 0;
                    });

                    // сортируем те что нет в наличии
                    array_out_stock_items.sort(function(a, b) {
                        var compA = $(a).data('name').toUpperCase();
                        var compB = $(b).data('name').toUpperCase();
                        return (compA < compB) ? -1 : (compA > compB) ? 1 : 0;
                    });

                    // объединяем
                    array_all_items = $.merge(array_in_stock_items, array_out_stock_items);


                    // перезаписываем
                    $(array_all_items).appendTo($(this));

                    // аналоги
                    all_items.each(function(){

                        // собираем аналоги товара
                        item_id_1c = $(this).data('id-1c');
                        item_analogs = $.makeArray($('.js-item-' + item_id_1c));

                        // сортируем
                        item_analogs .sort(function(a, b) {
                            var compA = $(a).data('name').toUpperCase();
                            var compB = $(b).data('name').toUpperCase();
                            return (compA < compB) ? -1 : (compA > compB) ? 1 : 0;
                        });

                        // переписываем
                        $(this).after(item_analogs);

                    });
                });

            }

            $.when(showDiv()).then(function(){
                sortAZ();
            });
        }

        // сортировка по ценам от меньшей
        if($('.js-price-a-z').hasClass('active')) {

            function sortPriceAZ() {
                // собираем категории
                cat_blocks = $('.js-lines-block');

                cat_blocks.each(function(){

                    // собираем все элементы категории
                    all_items = $(this).find('.js-sort-item');
                    // собираем те что в наличии
                    item_elements_in_stock = $(this).find('.js-sort-item.js-count-1');
                    // собираем те что нет в наличии
                    item_elements_out_stock = $(this).find('.js-sort-item.js-count-0');
                    // преобразуем в массив
                    array_in_stock_items = $.makeArray(item_elements_in_stock);
                    array_out_stock_items = $.makeArray(item_elements_out_stock);

                    // сортируем те что есть в наличии
                    array_in_stock_items.sort(function(a, b) {
                        return $(a).data("price") - $(b).data("price");
                    });

                    // сортируем те что нет в наличии
                    array_out_stock_items.sort(function(a, b) {
                        return $(a).data("price") - $(b).data("price");
                    });

                    // объединяем
                    array_all_items = $.merge(array_in_stock_items, array_out_stock_items);


                    // перезаписываем
                    $(array_all_items).appendTo($(this));

                    // аналоги
                    all_items.each(function(){

                        // собираем аналоги товара
                        item_id_1c = $(this).data('id-1c');
                        item_analogs = $.makeArray($('.js-item-' + item_id_1c));

                        // сортируем
                        item_analogs .sort(function(a, b) {
                            return $(a).data("price") - $(b).data("price");
                        });

                        // переписываем
                        $(this).after(item_analogs);

                    });
                });

            }

            $.when(showDiv()).then(function(){
                sortPriceAZ();
            });


        }

        // сортировка по ценам от большей
        if($('.js-price-z-a').hasClass('active')) {

            function sortPriceZA() {

                // собираем категории
                cat_blocks = $('.js-lines-block');

                cat_blocks.each(function(){

                    // собираем все элементы категории
                    all_items = $(this).find('.js-sort-item');
                    // собираем те что в наличии
                    item_elements_in_stock = $(this).find('.js-sort-item.js-count-1');
                    // собираем те что нет в наличии
                    item_elements_out_stock = $(this).find('.js-sort-item.js-count-0');
                    // преобразуем в массив
                    array_in_stock_items = $.makeArray(item_elements_in_stock);
                    array_out_stock_items = $.makeArray(item_elements_out_stock);

                    // сортируем те что есть в наличии
                    array_in_stock_items.sort(function(a, b) {
                        return $(b).data("price") - $(a).data("price");
                    });

                    // сортируем те что нет в наличии
                    array_out_stock_items.sort(function(a, b) {
                        return $(b).data("price") - $(a).data("price");
                    });

                    // объединяем
                    array_all_items = $.merge(array_in_stock_items, array_out_stock_items);


                    // перезаписываем
                    $(array_all_items).appendTo($(this));

                    // аналоги
                    all_items.each(function(){

                        // собираем аналоги товара
                        item_id_1c = $(this).data('id-1c');
                        item_analogs = $.makeArray($('.js-item-' + item_id_1c));

                        // сортируем
                        item_analogs .sort(function(a, b) {
                            return $(b).data("price") - $(a).data("price");
                        });

                        // переписываем
                        $(this).after(item_analogs);

                    });
                });

            }

            $.when(showDiv()).then(function(){
                sortPriceZA();
            });

        }

        $('.js-working').fadeOut(100);

    });

    // управление выпадашками на малых экранах ***************************************
    if($(window).width() <= 1260) {

        // отображаем выпадашку
        $('.js-item-filter-button, .js-view-price-button, .js-item-sort-button').click(function(){
            $(this).next().slideToggle('100');
        });

        // сворачивание выпадашек, если кликаем вне
        $(document).click(function(e) {
            var container = $('.js-item-filter-button, .js-view-price-button, .js-item-sort-button');
            container.each(function(){
                if (!($(e.target).closest($(this)).length || $(this).next().has(e.target).length)) {
                    $(this).next().slideUp('100');
                }
            });

            e.stopPropagation();
        });
    }

});
