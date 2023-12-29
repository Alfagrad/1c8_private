$(document).ready(function() {
    $('.js-write-us').click(function() {
        $('.js-popup').fadeIn('slow' , 'linear');
    });

    $('.js-popup-close').click(function() {
        $('.js-popup').fadeOut('slow' , 'linear');
    });

    $('.js-popup-note-close').click(function() {
        $('.js-popup-note').fadeOut('slow' , 'linear');
    });

    console.log('написать письмо');
});
