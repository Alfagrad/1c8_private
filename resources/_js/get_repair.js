$(document).ready(function(){

    console.log('статус ремонта');

    $('.js-get-receipt, .js-get-serial').click(function() {
        // определяем кнопку
        var butt;
        if($(this).hasClass('js-get-receipt')) {
            butt = 'receipt';
        } else if($(this).hasClass('js-get-serial')) {
            butt = 'serial';
        }
        getRepair(butt);
    });

    // по enter
    $('input[name=receipt_number]').keydown(function(e) {
        if(e.keyCode === 13) {
            getRepair('receipt');
        }
    });
    $('input[name=serial_number]').keydown(function(e) {
        if(e.keyCode === 13) {
            getRepair('serial');
        }
    });

    function getRepair(butt) {

        // берем номер квитанции
        var receipt_number = $.trim($('input[name=receipt_number]').val());
        // берем серийный номер
        var serial_number = $.trim($('input[name=serial_number]').val());

        // берем токен
        var token = $('input[name=_token]').val();

        // если номер не пустой
        if(receipt_number || serial_number) {

            $.ajax({
                type: 'post',
                url: "/get-repair",
                data: {
                    'receipt_number': receipt_number,
                    'serial_number': serial_number,
                    'search': butt,
                    '_token': token,
                },
                success: function(data){

                    // прячем блок с инфой
                    $('.js-repair-info').slideUp();

                    // вставляем html
                    $('.js-repair-info').html(data);

                    // отображаем блоук с инфой
                    $('.js-repair-info').slideDown();

                },
            });
        }
    }
});

