$(document).ready(function() {

    $('.js-clear-alert').click(function(){

        console.log('Очистка корзины'); // Метка

        var tag_text = $(this).text().trim();
        var confirm_text = 'Внимание!\r\nВы собираетесь ' + tag_text + '!\r\nВы уверены?';

        return confirm(confirm_text);

    });

});
