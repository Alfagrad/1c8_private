$(document).ready(function() {

    $('#captcha-validate').submit(function(e) {

        console.log('валидатор капчи');

        var captcha = $('input[name=grecaptcha]').val();
     
        if (captcha) {
            return true;
        } else {
            return false;
        }

    });

});
