$(document).ready(function() {

    // если есть ссылка c линком на запчасти
    var spares_link = $("#js-spares-view").data('spares-link');
    if(spares_link) {
        var top = $("#js-spares-view").offset().top;
        if($(window).width() <= 1260) {
            top -= 150;
        }


        $('body,html').animate({scrollTop: top}, 1000);

        $('.clap, .js-down-block').removeClass('active');
        $('.block-spare, .clap-spare').addClass('active');
    }

    //************************************************
    // отработка кнопки Запчасти в прайс-блоке
    $("#js-spares").click(function () {
        var top = $("#js-spares-view").offset().top;
        if($(window).width() <= 1260) {
            top -= 150;
        }

        $('body,html').animate({scrollTop: top}, 1000);

        $('.clap, .js-down-block').removeClass('active');
        $('.block-spare, .clap-spare').addClass('active');

    });

    //************************************************
    console.log('обработка кликов по кнопкам внизу');

    $('.clap').click(function(){
        $('.clap, .js-down-block').removeClass('active');
        $(this).addClass('active');
        if($('.clap-about').hasClass('active')) {
            $('.block-about').addClass('active');
        }
        if($('.clap-spare').hasClass('active')) {
            $('.block-spare').addClass('active');
        }
        if($('.clap-analog').hasClass('active')) {
            $('.block-analog').addClass('active');
        }
        if($('.clap-buy-with').hasClass('active')) {
            $('.block-buy-with').addClass('active');
        }
        if($('.clap-comes-to').hasClass('active')) {
            $('.block-comes-to').addClass('active');
        }
        if($('.clap-services').hasClass('active')) {
            $('.block-services').addClass('active');
        }
    });

    // отображаем схемы 
    $('.js-spare-tab').click(function(){

        $('.js-spare-tab').removeClass('active');
        $(this).addClass('active');

        // номер схемы
        var num_scheme = $(this).data('num-scheme');
        // id схемы
        var scheme_id = $(this).data('scheme_id');

        // скрываем все схемы и товары схемы
        $('.js-scheme, .js-spares-block').hide();

        // отображаем соответствующую схему и товары схемы
        $('.js-scheme-'+num_scheme+', .js-scheme-'+scheme_id).show();

        // // собираем все запчасти
        // var spare_lines = $('.scheme_item_line');
        // // отображаем все
        // spare_lines.show();
        // // прячем не соответствующие номеру схемы
        // spare_lines.each(function(){
        //     if($(this).find('.js-scheme-num').data('scheme_id') != scheme_id) {
        //         $(this).hide();
        //     }
        // });

        // // отображение по на жатию Все запчасти
        // if($('.js-spare-tab.js-all-spares').hasClass('active')) {
        //     spare_lines.show();
        // }

    });

    // скрыть-показать изображение схемы
    $('.js-img-view-toggler').click(function(){

        $(this).toggleClass('toggled');
        if($(this).hasClass('toggled')) {
            $(this).children('span').html('Показать схему');
            $(this).next('.view-img').slideUp();
        } else {
            $(this).children('span').html('Скрыть схему');
            $(this).next('.view-img').slideDown();
        }

    });

    //************************************************
    // console.log('показываем/прячем линии после 4-й');

    // // собираем блоки с товарными линиями
    // var lines_blocks = $('.js-four-lines-block');
    // // прячем линии после 4-й
    // lines_blocks.each(function(){
    //     var lines = $(this).children('.js-item-element');
    //     lines.each(function(i){
    //         if(i > 3) {
    //             $(this).hide();
    //         }
    //     });
    // });

    // $('.js-cat-four-toggler').click(function(){
    //     // удаляем/добавляем класс
    //     $(this).toggleClass('toggled');

    //     // меняем + на -
    //     if($(this).hasClass('toggled')) {
    //         $(this).html('-');
    //     } else {
    //         $(this).html('+');
    //     }

    //     // узнаем индекс категории
    //     var cat_four_index = $(this).data('cat_index');

    //     // собираем товарные линии категории
    //     var item_lines = $('.line_four_'+cat_four_index);

    //     // показываем/прячем по клику
    //     var i = 1;
    //     item_lines.each(function(){
    //         if(i > 4) {
    //             $(this).slideToggle('600');
    //         }
    //         i++;
    //     });
    //     $('.js-cat-'+cat_four_index+'-title').toggle(1);
    //     $('.analog-line').hide();

    // });

});
