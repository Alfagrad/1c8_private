$(document).ready(function(){

    $('#js-burger').click(function() {
        if(!$('#js-main-menu').hasClass('active')) {
            $('#js-main-menu, #js-lines').addClass('active');
        } else {
            $(this).removeClass('active');
            $('#js-main-menu, #js-lines').removeClass('active');
        }
    });

	console.log('выпадашка главного меню');

});