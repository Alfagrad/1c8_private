$(document).ready(function(){

    console.log('управление товарными категориями в выдаче (-/+)');

    // управление видимостью каталожной выдачей ************************************
    // управление одним каталогом
    $('.js-catalog-toggler').click(function(){
        var cat_index = $(this).data('cat_index');
        $('.js-index-' + cat_index).slideToggle('300');
        $(this).toggleClass('toggled');
        if($(this).hasClass('toggled')) {
            $(this).html('+');
        } else {
            $(this).html('-');
        }
    });

    // управление всеми каталогами
    $('.js-all-catalog-toggler').click(function(){
        $(this).toggleClass('toggled');
        if($(this).hasClass('toggled')) {
            $(this).html('+');
            $('.js-catalog-toggler').addClass('toggled').html('+');

            $('.js-lines-block').each(function(){
                if($(this).hasClass('is_archive_cat')) {
                    if($('.js-archive-button').hasClass('active')) {
                        $(this).slideUp('300');
                    }
                } else {
                    $(this).slideUp('300');
                }
            });

        } else {
            $(this).html('-');
            $('.js-catalog-toggler').removeClass('toggled').html('-');

            $('.js-lines-block').each(function(){
                console.log('открываем');
                if($(this).hasClass('is_archive_cat')) {
                    if($('.js-archive-button').hasClass('active')) {
                        $(this).slideDown('300');
                    }
                } else {
                    $(this).slideDown('300');
                }
            });
        }
    });

});
