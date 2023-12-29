$(document).ready(function(){

    console.log('поиск ремонтов в кабинете');

    // Поиск своих ремонтов
    $('#js-repair-search-input').on('change input', function() {

        var repair_code = $(this).val();

        $('.js-repair-wrapper').each(function (e) {
            var code_value = $(this).find('.js-receipt-num').text();
            if (~code_value.indexOf(repair_code)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });

    });

    // // Поиск по номеру ремонта
    // $('#js-all-repair-search-input, #js-all-repair-search-button').on('click change input', function() {

    //     var code = $('#js-all-repair-search-input').val();

    //     if(code.length > 3) {

    //         var options = {};
    //         options['code'] = code;

    //         $.ajax({
    //             type: 'post',
    //             url: "/profile/repairs/refresh",
    //             data: options,
    //             success: function (data) {
    //                 if(data == 'false'){
    //                     $('.b-repairs tr').remove();
    //                     data = "<tr><td style='color: red; font-weght: 600;'>Ремонт с номером &laquo;"+code+"&raquo; не найден</td>";
    //                 }
    //                 $('.b-repairs').html(data);
    //             },
    //             async:true
    //         });

    //     }
    // });

    // открытие закрытие деталей ремонта
    $('.js-repair-arrow').click(function(){
        // открываем-закрываем блок
        $(this).parents('.js-repair-wrapper').find('.js-drop-down-block').slideToggle();
        // добавляем-удаляем класс к кнопке
        $(this).toggleClass('active');
    });


});