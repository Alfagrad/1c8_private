/**
 * Created by Ast on 16.03.2017.
 */
$(document).ready(function(){

    jQuery.validator.addMethod("accept", function(value, element, param) {
        return value.match(new RegExp("." + param + "$"));
    });

    $('.w-contacts-info').on('click', '.b-add-contact a', function (e) {
        e.preventDefault();
        $('.b-add-contact input[name=contacts_phone]').removeClass('error')
        $('.b-add-contact input[name=contacts_name]').removeClass('error')

        var phone = $('.b-add-contact input[name=contacts_phone]').val();
        var name = $('.b-add-contact input[name=contacts_name]').val();

        var error = false
        if(phone == ''){
            $('.b-add-contact input[name=contacts_phone]').addClass('error')
            error = true;
        }

        if(name == ''){
            $('.b-add-contact input[name=contacts_name]').addClass('error')
            error = true
        }
        if(error == true){return false}
        var template = '<div class="wrapper w-ready-contact-wrapp block_phones">' +
        '<a href="" class="close js-close-block-contact">×</a>' +
        '<div class="tel">'+ phone +'</div>'+
        '<div class="description">'+ name +'</div>'+
        '<input type="hidden" name="contact[phone][]" value="'+ phone +'">'+
        '<input type="hidden" name="contact[name][]" value="'+ name+'">'+
        '</div>';
        var block_phones = $('.w-contacts-info').find('.block_phones');

        if(block_phones.length){
            ($('.block_phones').last().after(template));
        } else {
            $('.insert_phones').after(template);

        }





        $(this).parent('div').parent('div').find('input[type=text]').val('');
        $(this).parent('div').parent('div').hide()
        $('.w-contacts-info').find('.left .js-add-contact').show();
        return false;
    });

    $('input[name=unp]').bind("change keyup input click", function() {
        if (this.value.match(/[^0-9]/g)) {
            this.value = this.value.replace(/[^0-9]/g, '');
        }
    });
    $('._phone-mask, *[name^=contacts_phone]').mask('+375(99)999-99-99');


    $('.w-contacts-info').on('click', '.js-add-contact', function (e) {
        e.preventDefault();
        $(this).parent('div').find('.b-add-contact').show();
        $(this).hide();
    });

    $('.w-contacts-info').on('click', '.js-delete-contact', function (e) {
        e.preventDefault();
        $(this).parent('div').find('input[type=text]').val('');
        $(this).parent('div').hide()
        //$('.w-contacts-info').find('.left .js-add-contact').show();
        console.log(block_phones);
        var block_phones = $('.w-contacts-info').find('.block_phones');
        if(block_phones.length){
            $('.w-contacts-info').find('.left .js-open-add-contact').show();
        } else {
            $('.w-contacts-info').find('.left .b-add-contact').show();
            $('.w-contacts-info').find('.left .js-open-add-contactt').hide();
        }

    });

    $('.w-contacts-info').on('click', '.js-close-block-contact', function (e) {
        e.preventDefault();
        $(this).parent('div').remove();
    });



/******************************************************/

    $('.w-contacts-info').on('click', '.b-add-address a', function (e) {
        e.preventDefault();

        $('.b-add-address input[name=addresses_address]').removeClass('error')
        $('.b-add-address input[name=addresses_comment]').removeClass('error')


        var address = $('.b-add-address input[name=addresses_address]').val();
        var comment = $('.b-add-address input[name=addresses_comment]').val();


        var error = false
        if(address == ''){
            $('.b-add-address input[name=addresses_address]').addClass('error')
            error = true;
        }

        if(comment == ''){
            $('.b-add-address input[name=addresses_comment]').addClass('error')
            error = true
        }
        if(error == true){return false}


        var template = '<div class="wrapper w-ready-contact-wrapp block_addresses">' +
            '<a href="" class="close js-close-block-address">×</a>' +
            '<div class="tel">'+ address +'</div>'+
            '<div class="description">'+ comment +'</div>'+
            '<input type="hidden" name="addresses[address][]" value="'+ address +'">'+
            '<input type="hidden" name="addresses[comment][]" value="'+ comment+'">'+
            '</div>';
        var block_phones = $('.w-contacts-info').find('.block_addresses');

        if(block_phones.length){
            ($('.block_addresses').last().after(template));
        } else {
            $('.insert_addresses').after(template);

        }
        $(this).parent('div').parent('div').find('input[type=text]').val('');
        $(this).parent('div').parent('div').hide()
        $('.w-contacts-info').find('.right .js-add-address').show();
    });

    function closeContactForm(e) { }


    $('.w-contacts-info').on('click', '.js-add-address', function (e) {
        e.preventDefault();
        $(this).parent('div').find('.b-add-address').show();
        $(this).hide();
    });

    $('.w-contacts-info').on('click', '.js-delete-address', function (e) {
        e.preventDefault();
        $(this).parent('div').find('input[type=text]').val('');
        $(this).parent('div').hide()
        $('.w-contacts-info').find('.right .js-add-address').show();
    });

    $('.w-contacts-info').on('click', '.js-close-block-address', function (e) {
        e.preventDefault();
        $(this).parent('div').remove();

        var block_phones = $('.w-contacts-info').find('.block_addresses');
        if(block_phones.length){
            $('.w-contacts-info').find('.right .js-add-address').show();
        } else {
            $('.w-contacts-info').find('.right .b-add-address').show();
            $('.w-contacts-info').find('.right .js-add-address').hide();
        }
    });

    $("#form-registration").validate({
        ignore: [],
        rules: {
            name: {required: true},
            email: {required: true, email:true,
                remote: {
                    url: "/registration/check/email",
                    type: "post",
                    data: {
                        email: function () {
                            return $('#form-registration [name="email"]').val();
                        }
                    }
                }
            },
            password: {required: true},

            company_name: {required: true},
            company_address: {required: true},
            unp: {required: true, minlength:9, maxlength:9,  number: true,
                remote: {
                    url: "/registration/check/email",
                    type: "post",
                    data: {
                        unp: function () {
                            return $('#form-registration [name="unp"]').val();
                        }
                    }
                }},
            //bank_account: { accept: "[a-zA-Z0-9]+", minlength:28, maxlength:28  },
            //bank_name: {accept: "[a-zA-Z0-9]+", minlength:8, maxlength:8 },
            //trade_object: {required: true},
            //shops: {required: true},
            //coverage_area: {required: true},

            contacts_phone: {
                required: function(element) {
                    return ( !$('input[name="contact[phone][]"]').length);
                },
            },


            //contacts_name: {
            //    required: function(element) {
            //        return ( !$('input[name="contact[name][]"]').length);
            //    },
            //},



            //addresses_address: {
            //    required: function(element) {
            //        return ( !$('input[name="addresses[address][]"]').length);
            //    },
            //},


            //addresses_comment: {
            //    required: function(element) {
            //        return ( !$('input[name="addresses[comment][]"]').length);
            //    },
            //},

            messages: {
                email: {
                    email: 'Пожалуйста, проверьте корректность введеного email',
                    remote: 'Данный email используется на сайте.'
                },
                password_confirmation: {
                    equalTo: 'Должен совпадать с паролем',
                }

            }
        },


        showErrors: function(errorMap, errorList) {
            console.log('errorMap', errorMap);
            console.log('errorList', errorList);
            var remoteError = '';
            for(err in errorList){
                console.log(err);
                console.log(errorList[err]);
                if(errorList[err].method == 'remote'){
                    console.log('remote', errorList[err].message)
                    remoteError += '    Пользователь с указанным емейлом существует.'
                        + '<br>' + 'Для решения проблемы обратитесь по телефонам' + '<br>'
                }
            }

            if(remoteError != ''){
                swal({
                    title: "Извините, ",
                    text: remoteError,
                    confirmButtonColor: '#870020',
                    html: true
                });

            }
            this.defaultShowErrors();
        },

        submitHandler: function(form) {

            var form_error = false;
            if((!$('input[name="contact[phone][]"]').length &&  !$('input[name="contact[name][]"]').length)){

                if($('.b-add-contact input[name=contacts_phone]').val() != '' || $('.b-add-contact input[name=contacts_name]' ).val() != ''){
                    $('.left a.button').addClass('error');
                    form_error = true;
                }
            }


            if((!$('input[name="addresses[address][]"]').length && !$('input[name="addresses[comment][]"]').length)){
                if($('.b-add-address input[name=addresses_address]').val() != '' || $('.b-add-address input[name=addresses_comment]' ).val() != ''){
                    $('.right a.button').addClass('error');
                    form_error = true;
                }
            }

            if(form_error){
                form_error = false;
                return false;

            }
            form.submit();


        },



        errorElement: "em",
        errorClass: "error",
        validClass: "successMessages",





    });




});