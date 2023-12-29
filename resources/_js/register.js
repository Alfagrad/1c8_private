$(document).ready(function(){

    console.log('регистрация');

    // при нажатии на тип клиента скрываем/показываем блок с магазинами
    $('#js-saler').click(function() {
        $('.registration-page_shops-data-block').css('display', 'flex');
    });
    $('#js-service').click(function() {
        $('.registration-page_shops-data-block').css('display', 'none');
    });

    // сохраняем и отображаем контакт
    $('#js-save-contact').click(function(){

        // запоминаем телефон и имя
        var contacts_phone = $('#contacts_phone').val();
        var contacts_name = $('#contacts_name').val();

        if(contacts_phone) {
            // формируем блок для вставки
            var template = '<div class="added_contact">' +
            '<div class="close js-close-block-contact" title="Удалить">×</div>' +
            '<div class="tel">'+ contacts_phone +'</div>'+
            '<div class="description">'+ contacts_name +'</div>'+
            '<input type="hidden" name="contact[phone][]" value="'+ contacts_phone +'">'+
            '<input type="hidden" name="contact[name][]" value="'+ contacts_name +'">'+
            '</div>';

            // вставляем
            var block_phones = $('#js-added-contacts').find('.added_contact');
            if(block_phones.length){
                ($('.added_contact').last().after(template));
            } else {
                $('#js-added-contacts').html(template);
            }

            // отображаем линк для вставки доп.блока
            $('#js-add-contact-link').css('display', 'inline-block');
            // удаляем аттрибут обязательности для телефона
            $('#contacts_phone').removeAttr('required');
            // прячем форму добавления адреса
            $('#js-add-contact-form').css('display', 'none');
        }

    });

    // отображаем форму добавления контакта
    $('#js-add-contact-link').click(function(){

        // отображаем
        $('#js-add-contact-form').css('display', 'block');
        // прячем линк для вставки доп.блока
        $('#js-add-contact-link').css('display', 'none');

        // очищаем введенные ранее значения
        $('#contacts_phone, #contacts_name').val('');

    });

    // удаление блока с доп.контактом
    $('#js-added-contacts').on('click', '.js-close-block-contact', function(){
        $(this).parent('.added_contact').remove();

        // если не осталось ни одного контакта, добавляем аттрибут обязательности для телефона
        var block_phones = $('#js-added-contacts').find('.added_contact');
        if(!block_phones.length){
            $('#contacts_phone').attr('required', '');
        }
    });


    // сохраняем и отображаем адрес 
    $('#js-save-address').click(function(){

        // запоминаем телефон и имя
        var addresses_address = $('#addresses_address').val();
        var addresses_comment = $('#addresses_comment').val();

        if($.trim(addresses_address)) {
            // формируем блок для вставки
            var template = '<div class="added_address">' +
            '<div class="close js-close-block-address" title="Удалить">×</div>' +
            '<div class="address">'+ addresses_address +'</div>'+
            '<div class="description">'+ addresses_comment +'</div>'+
            '<input type="hidden" name="addresses[address][]" value="'+ addresses_address +'">'+
            '<input type="hidden" name="addresses[comment][]" value="'+ addresses_comment +'">'+
            '</div>';

            // вставляем
            var block_addresses = $('#js-added-addresses').find('.added_address');
            if(block_addresses.length){
                ($('.added_address').last().after(template));
            } else {
                $('#js-added-addresses').html(template);
            }

            // отображаем линк для вставки доп.блока
            $('#js-add-address-link').css('display', 'inline-block');
            // прячем форму добавления адреса
            $('#js-add-address-form').css('display', 'none');
        }

    });

    // отображаем форму добавления адреса
    $('#js-add-address-link').click(function(){

        // отображаем
        $('#js-add-address-form').css('display', 'block');
        // прячем линк для вставки доп.блока
        $('#js-add-address-link').css('display', 'none');

        // очищаем введенные ранее значения
        $('#addresses_address, #addresses_comment').val('');

    });

    // удаление блока с доп.адресом
    $('#js-added-addresses').on('click', '.js-close-block-address', function(){
        $(this).parent('.added_address').remove();
    });

    // проверка email на присутствие в бд
    $('#js-email').on('focusout', function() {

        var email = $(this).val();

        console.log(email);

        if($.trim(email)) {

            var options = {};
            options['email'] = email;

            $.ajax({
                type: 'post',
                url: "/registration/check/email",
                data: options,
                success: function (data) {
                    if(data == 'true') {
                        $('#js-email').val('');
                        alert("E-mail - "+email+" уже зарегистрирован! Попробуйте другой");
                    }
                },
                async:true
            });

        }
    });

    // отображаем/скрываем символы пароля
    $('.js-pass-eye').click(function(){

        $(this).toggleClass('toggled');

        if($(this).hasClass('toggled')) {
            $('input[name="password"]').attr('type', 'text');
        } else {
            $('input[name="password"]').attr('type', 'password');
        }

    });


});