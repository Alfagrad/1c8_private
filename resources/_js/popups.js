$(document).ready(function() {
    $('.js-b-write-to-us').on('click', function (e) {
        console.log('нажал');
        $('.s-popup, .w-popup').css('display' , 'none');
        $('.s-popup, .dark-bg').css('display' , 'block');
        $('._write-to-us').css('display' , 'inline-block');
        $('.w-contacts._mobile').css('display' , 'none');
        return false;
    });

    console.log('написать письмо');
});
