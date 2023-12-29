$(document).ready(function(){

    $('.left').on('click', '.js-open-edit-profile',  function (e) {
        e.preventDefault();
        $('.w-main-info .left>div.row').hide();
        $('._change-password').hide();

        $('.w-main-info .w-edit-profile').show();
    });

    $('.left').on('click', '.js-close-edit-profile',  function (e) {
        e.preventDefault();

        //var email = $('.w-main-info .w-edit-profile input[name=email]').val();
        //$('.row-profile-view-email').text(email);

        var email =  $('.row-profile-view-email').text();
        $('.w-main-info .w-edit-profile input[name=email]').val(email);

        var name =  $('.row-profile-view-name').text();
        $('.w-main-info .w-edit-profile input[name=profile_name]').val(name);

        var company_name =  $('.row-profile-view-company-name').text();
        $('.w-main-info .w-edit-profile input[name=company_name]').val(company_name);

        var unp =  $('.row-profile-view-unp').text();
        $('.w-main-info .w-edit-profile input[name=unp]').val(unp);



        var bank_account =  $('.row-profile-view-bank-account').text();
        $('.w-main-info .w-edit-profile input[name=bank_account]').val(bank_account);


        var bank_name =  $('.row-profile-view-bank-name').text();
        $('.w-main-info .w-edit-profile input[name=bank_name]').val(bank_name);
        /*
        var name = $('.w-main-info .w-edit-profile input[name=name]').val();
        $('.row-profile-view-name').text(name);

        var company_name = $('.w-main-info .w-edit-profile input[name=company_name]').val();
        $('.row-profile-view-company-name').text(company_name);

        var unp = $('.w-main-info .w-edit-profile input[name=unp]').val();
        $('.row-profile-view-unp').text(unp);

        var bank_account = $('.w-main-info .w-edit-profile input[name=bank_account]').val();
        $('.row-profile-view-bank-account').text(bank_account);

        var bank_name = $('.w-main-info .w-edit-profile input[name=bank_name]').val();
        $('.row-profile-view-bank-name').text(bank_name);

*/


        $('.w-main-info .left>div.row').show();
        $('.w-main-info .w-edit-profile').hide();
    });




    // Открывает блок для редактирования адреса
    $('.left').on('click', '.js-edit-contact',  function (e) {
       e.preventDefault();
        var tel = $(this).parent().find('div.tel');
        var name = $(this).parent().find('div.description');


        var edit_contact_div = '<div class="wrapper w-add-wrapp-form">'+
            '<div class="section-name secondary">Редактировать контакт</div>'+
            '<div class="row">'+
                '<div class="input w_50">'+
                    '<label>Телефон<span>*</span></label>'+
                    '<input class="thin" type="text" placeholder="+375 29 333 22 44" name="phone" value="'+ tel.text()+'">'+
                '</div>'+
                '<div class="input w_50">'+
                    '<label>Имя<span>*</span></label>'+
                    '<input class="thin" type="text" placeholder="Васили Иванович (директор)" name="name" value="'+ name.text()+'">'+
                '</div>'+
            '</div>'+
            '<div class="input">'+
                '<a type="submit" class="button _red js-update-contact">Обновить</a>'+
            '</div></div>';
        $('.left .w-ready-contact-wrapp .w-add-wrapp-form').remove();
        $(this).parent().find('.description').after(edit_contact_div);
          $('._phone-mask, *[name^=phone]').mask('+375 (99)999-99-99');
        console.log($(this).parent().find('description').text())


    });

    $('.left').on('click', '.js-open-add-contact',  function (e) {
       e.preventDefault();
        $('.left .w-add-wrapp-form.one-form').show();
        $('.js-open-add-contact').hide();


    });



    $('.left').on('click', '.js-add-contact',  function (e) {
        e.preventDefault();
       var name =  $(this).parent().parent().find('input[name=name]').val();
       var phone = $(this).parent().parent().find('input[name=phone]').val();
       console.log(name);

        var error = false
        if(phone == ''){
            $('.b-add-contact input[name=phone]').addClass('error')
            error = true;
        } else {
            $('.b-add-contact input[name=phone]').removeClass('error')

        }


        if(error == true){return false}



        var templ =
        '<div class="w-ready-contact-wrapp">' +
            '<a href="" class="edit js-edit-contact">Редактировать</a>'+
            '<a href="" class="close js-delete-contact">×</a>'+
            '<div class="tel">'+ phone +'</div>'+
            '<div class="description">'+ name +'</div>'+
            '<input type="hidden" name="contact[phone][]" value="'+phone+'">'+
            '<input type="hidden" name="contact[name][]" value="'+name+'">'+
        '</div>';



        var block_phones = $('.left .w-ready-contact-wrapp');

        if(block_phones.length){
            $('.left .w-ready-contact-wrapp').last().after(templ);
        } else {
            $('.left .insert_contact').after(templ);

        }



        $(this).parent().parent().find('input[name=name]').val('');
        $(this).parent().parent().find('input[name=phone]').val('');

        $('.left > .w-add-wrapp-form').hide();
        $('.js-open-add-contact').show();


    });

    $('.left').on('click', '.js-update-contact',  function (e) {
        e.preventDefault();
        var name =  $(this).parent().parent().find('input[name=name]').val();
        var phone = $(this).parent().parent().find('input[name=phone]').val();


        var error = false
        if(phone == ''){
            $(this).parent().parent().find('input[name=phone]').addClass('error')
            error = true;
        } else {
           $(this).parent().parent().find('input[name=phone]').removeClass('error')
        }


        if(error == true){return false}

        $(this).parent().parent().parent().find('div.tel').text(phone);
        $(this).parent().parent().parent().find('div.description').text(name);


        $(this).parent().parent().parent().find('input[type=hidden][name="contact[phone][]"]').val(phone);
        $(this).parent().parent().parent().find('input[type=hidden][name="contact[name][]"]').val(name);

        $(this).parents('.w-ready-contact-wrapp').find('.w-add-wrapp-form').hide();
        //$('.js-open-add-contact').show();
    });





    $('.right').on('click', '.js-edit-address',  function (e) {
        e.preventDefault();
        var address = $(this).parent().find('div.address');
        var comment = $(this).parent().find('div.description');
        var edit_address_div = '<div class="wrapper w-add-wrapp-form">'+
            '<div class="section-name secondary">Редактировать адрес</div>'+
            '<div class="row">'+
            '<div class="input">'+
            '<label>Адрес<span>*</span></label>'+
            '<input class="thin" type="text" placeholder="г. Минск, ул. Пушкина 17 офис 41" name="address" value="'+ address.text()+'">'+
            '</div>'+
            '<div class="input">'+
            '<label>Дополнительный комментарий<span>*</span></label>'+
            '<input class="thin" type="text" placeholder="Интернет магазин-склад" name="comment" value="'+ comment.text()+'">'+
            '</div>'+
            '</div>'+
            '<div class="input">'+
            '<a href="" class="button _red js-update-address">Обновить</a>'+
            '</div></div>';

        $('.right > .w-ready-contact-wrapp .w-add-wrapp-form').remove();
        $(this).parent().find('.description').after(edit_address_div);

    });

    $('.right').on('click', '.js-update-address',  function (e) {
        e.preventDefault();
        var address =  $(this).parent().parent().find('input[name=address]').val();
        var comment = $(this).parent().parent().find('input[name=comment]').val();


        var error = false
        if(address == ''){
            $(this).parent().parent().find('input[name=address]').addClass('error')
            error = true;
        } else {
            $(this).parent().parent().find('input[name=address]').removeClass('error')
        }

        //if(comment == ''){
        //    $(this).parent().parent().find('input[name=comment]').addClass('error')
        //    error = true
        //} else {
         //   $(this).parent().parent().find('input[name=comment]').removeClass('error')
        //}
        if(error == true){return false}



        $(this).parent().parent().parent().find('div.address').text(address);
        $(this).parent().parent().parent().find('div.description').text(comment);


        $(this).parent().parent().parent().find('input[type=hidden][name="addresses[address][]"]').val(address);
        $(this).parent().parent().parent().find('input[type=hidden][name="addresses[comment][]"]').val(comment);

        $(this).parents('.w-ready-contact-wrapp').find('.w-add-wrapp-form').hide();
        //$('.js-open-add-contact').show();
    });

    $('.right').on('click', '.js-open-add-address',  function (e) {
        e.preventDefault();
        $('.right > .w-add-wrapp-form.one-form').show();
        $('.js-open-add-address').hide();
    });

    $('.right').on('click', '.js-add-address',  function (e) {

        e.preventDefault();
        var address =  $(this).parent().parent().find('input[name=address]').val();
        var comment = $(this).parent().parent().find('input[name=comment]').val();

        var error = false
        if(address == ''){
            $('.b-add-address input[name=address]').addClass('error')
            error = true;
        } else {
            $('.b-add-address input[name=address]').removeClass('error')

        }

        if(error == true){return false}


        var templ =
            '<div class="w-ready-contact-wrapp">' +
                 '<a href="" class="edit js-edit-address">Редактировать</a>'+
                 '<a href="" class="close js-delete-address">×</a>'+
                 '<div class="address">'+ address +'</div>'+
                 '<div class="description">'+ comment +'</div>'+
                 '<input type="hidden" name="addresses[address][]" value="'+address+'">'+
                 '<input type="hidden" name="addresses[comment][]" value="'+comment+'">'+
            '</div>';


        var block_phones = $('.right > .w-ready-contact-wrapp');

        if(block_phones.length){
            $('.right > .w-ready-contact-wrapp').last().after(templ);
        } else {
            $('.right .insert_address').after(templ);

        }


        //$('.right .w-ready-contact-wrapp').last().after(templ);

        $(this).parent().parent().find('input[name=address]').val('');
        $(this).parent().parent().find('input[name=comment]').val('');

        $('.right > .w-add-wrapp-form').hide();
        $('.js-open-add-address').show();
        return false;

    });


    $('.wrapper.w-contacts-info').on('click', '.js-delete-contact',  function (e) {
        e.preventDefault();
        $(this).parent().remove()

        var block_phones = $('.w-contacts-info').find('.block_phones');
        if(block_phones.length){
            $('.w-contacts-info').find('.left .js-open-add-contact').show();
        } else {
            $('.w-contacts-info').find('.left .b-add-contact').show();
            $('.w-contacts-info').find('.left .js-open-add-contact').hide();
        }


    });

    $('.wrapper.w-contacts-info').on('click', '.js-delete-address',  function (e) {
        e.preventDefault();
        $(this).parent().remove();

        var block_phones = $('.w-contacts-info').find('.block_addresses');
        console.log(block_phones)
        if(block_phones.length){
            $('.w-contacts-info').find('.right .js-open-add-address').show();
        } else {
            $('.w-contacts-info').find('.right .b-add-address').show();
            $('.w-contacts-info').find('.right .js-open-add-address').hide();
        }


    });

    $('input[name=unp]').bind("change keyup input click", function() {
        if (this.value.match(/[^0-9]/g)) {
            this.value = this.value.replace(/[^0-9]/g, '');
        }
    });
    
    
    $('._phone-mask, *[name^=phone]').mask('+375 (99)999-99-99');

    $("#form-profile").validate({
        ignore: [],
        rules: {
            profile_name: {required: true},
            email: {required: true, email:true},
            password: {required: true},

            company_name: {required: true},
            company_address: {required: true},
            unp: {required: true},

            //bank_account: {required: true},
            //bank_name: {required: true},
            //trade_object: {required: true},
            //shops: {required: true},
            //coverage_area: {required: true},

            phone: {
                required: function(element) {
                    return ( !$('input[name="contact[phone][]"]').length);
                },
            },


            //name: {
            //    required: function(element) {
            //        return ( !$('input[name="contact[name][]"]').length);
            //    },
            //},



            //address: {
            //    required: function(element) {
            //        return ( !$('input[name="addresses[address][]"]').length);
            //    },
            //},

            //comment: {
            //    required: function(element) {
            //        return ( !$('input[name="addresses[comment][]"]').length);
            //    },
            //},

            messages: {
                email: {
                    email: 'Пожалуйста, проверьте корректность введеного email',
                    remote: 'Данный email не принадлежит покупателю.'
                },
                phone: {
                    remote: 'Данный телефон не принадлежит покупателю.'
                },
                password_confirmation: {
                    equalTo: 'Должен совпадать с паролем',
                }

            }
        },

        submitHandler: function(form) {

            var form_error = false;
            if((!$('input[name="contact[phone][]"]').length &&  !$('input[name="contact[name][]"]').length)){

                if($('.b-add-contact input[name=contacts_phone]').val() != '' && $('.b-add-contact input[name=contacts_name]' ).val() != ''){
                    $('a.js-add-contact').addClass('error');
                    form_error = true;
                }
            }


            //if((!$('input[name="addresses[address][]"]').length &&  !$('input[name="addresses[comment][]"]').length)){

                if($('.b-add-address input[name=addresses_address]').val() != undefined && $('.b-add-address input[name=addresses_comment]' ).val() != undefined){
                    $('a.js-add-address').addClass('error');
                    form_error = true;
                }
            //}

            if(form_error){
                form_error = false;
                return false;

            }
            form.submit();

            $('.s-popup__background').hide();
            $('.s-popup').hide();
            $('.w-pop-add-adress').hide();
            return false;
        },

        errorElement: "em",
        errorClass: "error",
        validClass: "successMessages",

    });



    $('.wrapper.w-main-info').on('click', '.js-show-password',  function (e) {
        e.preventDefault();
       $('._change-password').show();
       $('.row-change-password').hide();
    });


    $('.wrapper.w-main-info').on('click', '.js-password-save-cancel',  function (e) {
        e.preventDefault();
        $('._change-password').hide();
        $('.row-change-password').show();
    });

    $('.wrapper.w-main-info').on('click', '.js-password-save',  function (e) {
        e.preventDefault();

        var options = {};
        options['password'] = $('input[name=new_password]').val();

        if(options['password'].length < 6){
            swal({
                title: '',
                text: 'Пароль должен содержать больше 5 символов.',
                confirmButtonColor: '#870020',
                html: true
            });
        return false;
        }

        $('._change-password').hide();
        $('.row-change-password').show();


        $.ajax({
            type: 'POST',
            url: "/ajax/update_password",
            data: options,
            success: function (data) {
                console.log(data);
                if(data != 'false'){
                    swal({
                        title: 'Пароль обновлен',
                        text: 'Ваш новый пароль: <b>' + options['password'] + '</b>',
                        confirmButtonColor: '#870020',
                        html: true
                    });
                }
            },
            async:false
        });



    });














});