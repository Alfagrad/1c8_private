$(document).ready(function(){

    $('.js-want-cheaper').click(function(e) {

        // запускаем всплывающее окно
        $('.js-popup-want-cheaper').fadeIn('slow' , 'linear');

        // вписываем в заголовок наименование товара
        $('.js-item-name').html($(this).data('item_name'));

        // вписываем в input код товара
        $('input[name=id_1c]').val($(this).data('item_id'));

    });

    // закрываем всплывающее окно
    $('.js-popup-close').click(function() {
        $('.js-popup-want-cheaper').fadeOut('slow' , 'linear');
    });

    // меняем "Прикрепить файл" на название файла
    $("input[type=file]").change(function(){

        var filename = $(this).val().replace(/.*\\/, "");

        // если слишком большой файл, выдаем предупреждение
        if(this.files[0].size > 20*1024*1024 ){
            $(this).val('');
            alert('Ошибка! Размер файла должен быть менее 20 мб');

        } else {
            $('.popup_attach-file span').text(filename);
        }

    });

    console.log('всплывашка письмо - Если дорого, нажми');

});

