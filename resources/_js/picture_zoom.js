$(document).ready(function() {

    console.log('увеличим картинку при наведении');

    $('.js-zoom-wrapper').hover(function(){
        var big_img_path = $(this).children('.js-zoom-pic').data('path');
        $(this).addClass('hovered')
            .children('.js-zoom-pic')
            .html("<img src='/storage/ut_1c8/item-images/" + big_img_path + "'><div class='bump js-bump'></div>");

        var bottom_interval = ($(document).height() - ($(this).offset().top + 300));
        if(bottom_interval < 175) {
            $(this).children('.js-zoom-pic').css('bottom', '5px').children('.bump').css('bottom', '30px');
        } else {
            $(this).children('.js-zoom-pic').css('top', '5px').children('.bump').css('top', '30px');
        }

    }, function(){
        $(this).removeClass('hovered').children('.js-zoom-pic').html("");
        $(this).children('.js-zoom-pic').css('top', 'unset').children('.bump').css('top', 'unset');
    });

    // для мобил отключение картинки по нажатию
    $('.js-zoom-pic').click(function(){
        $(this).parent('.js-zoom-wrapper').removeClass('hovered');
    });

});
