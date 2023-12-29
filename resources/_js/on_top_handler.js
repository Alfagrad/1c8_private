$(document).ready(function(){

    $(window).scroll(function () {
        // Если отступ сверху больше 100px то показываем кнопку "Вверх"
        if ($(this).scrollTop() > 100) {
            $('.on-top-button').addClass('active');
        } else {
            $('.on-top-button').removeClass('active');
        }
    });
    
    /** При нажатии на кнопку мы перемещаемся к началу страницы */
    $('.on-top-button').click(function () {
        $('body,html').animate({
            scrollTop: 0
        }, 500);
        return false;
    });

	console.log('кнопка вверх');

});