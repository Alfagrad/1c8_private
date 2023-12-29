import('./jquery.js')

$(document).ready(function(){

    $('.js-show-password').click(function(e) {
        console.log(3434);
        e.preventDefault();
       $('.js-add-pass-block').slideDown();
    });

    $('.js-cancel-password').click(function() {
        // скрываем инпут для ввода пароля
        $('.js-add-pass-block').slideUp();

        // скрываем строку сообщений
        $('.js-note-string').slideUp().text('');
    });

    $('.js-password-save').click(function() {

        var password = $('input[name=new_password]').val();

        if(password.length < 6){
            // отображаем строку сообщений
            $('.js-note-string').slideDown().text('Введите минимум 6 символов!');
            return false;
        }

        $.ajax({
            type: 'POST',
            url: "/ajax/update_password",
            data: {
                'password': password,
            },
            success: function (data) {
                console.log(data);
                if(data == 'true'){
                    // отображаем строку сообщений
                    $('.js-note-string').slideDown().html('Ваш новый пароль: <strong>'+password+'</strong');
                }
            },
        });

    });

    console.log('история покупок');
    // отображение заказанных товаров
    $('.js-items-block-button').click(function(){
        $(this).parent('.js-order-block').next('.js-items-block').slideToggle();
        $(this).parent('.js-order-block').find('.js-svg').toggleClass('toggled');
    });













});
