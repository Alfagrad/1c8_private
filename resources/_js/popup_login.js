$(document).ready(function() {

    // $('.js-dealer-enter').click(function(){
    //     $('.js-popup-login').fadeIn('slow' , 'linear');
    // });

    $('.js-login-close').click(function(){
        $('.js-popup-login').fadeOut('slow' , 'linear');
        setTimeout(function(){
            $('.js-result-wrapper').css('display' , 'none');
            $('.js-result-note').html('');
        }, 600);
    });

    $('.js-forgot-pass').click(function(){
        $('.js-popup-login').fadeOut('slow' , 'linear');
        setTimeout(function(){
            $('.js-popup-forgot-pass').fadeIn('slow' , 'linear');
        }, 500);
    });
    $('.js-forgot-close').click(function(){
        $('.js-popup-forgot-pass').fadeOut('slow' , 'linear');
        setTimeout(function(){
            $('.js-result-wrapper').css('display' , 'none');
            $('.js-result-note').html('');
        }, 600);
    });

    $("#form-login").submit(function(e) {

        let emailField = $('#form-login input[name="email"]');
        let passField = $('#form-login input[name="password"]');
        let tokenField = $('#form-login input[name="_token"]');

        var options = {};
        options['email'] = emailField.val();
        options['password'] = passField.val();
        options['_token'] = tokenField.val();



        var ajax = $.ajax({
            type: $(this).attr('method'),
            url: $(this).attr('action'),
            data: $(this).serialize(),
            success: function (data) {

                if(data.ajax) {
                    $('.js-result-wrapper').css('display' , 'block');
                    $('.js-result-note').html(data.note);
                }
            },
            async:false
        });

        if(ajax.responseJSON.ajax) {
            return false;
        } else {
            return true;
        }

        console.log('восстановление пароля');

    });

    $("#form-remember-password").submit(function() {

        emailField = $('#form-remember-password input[name="email"]');
        var options = {};
        options['email'] = emailField.val();
        $.ajax({
            type: 'POST',
            url: "/remember/restore",
            data: options,
            success: function (data) {

                $('.js-result-wrapper').css('display' , 'block');

                if(data != 'false'){
                    $('.js-result-note').html('Отлично!<br>Новый пароль отправлен Вам на почту');
                } else {
                    $('.js-result-note').html('Ошибка!<br>Такой почты не существует!');
                }
            },
            async:false
        });

        console.log('восстановление пароля');
        return false;

    });


    console.log('вход дилера, восстановление пароля');
});
