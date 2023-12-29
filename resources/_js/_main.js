$(document).ready(function(){


//input styler
	$(window).on("load",function(){
    	$('input[type=checkbox] , select').styler();
    	});
//mobile-contacts toggle
    $('.b-mobile-contacts, .js-close-h-contacts').on('click', function (e) {
        $('.class').slideToggle();
    });

//hovered-catalog-menu-li and _active-on-mobile
    $('ul.main-catalog>.inset').on('mouseover', function () {
        $(this).prev('li.li_dropper').addClass('hover');
    });
    $('ul.main-catalog>.inset').on('mouseleave', function (e) {
        if(e.relatedTarget == null){
            $(this).addClass('hover');
        } else {
            $(this).removeClass('hover');
            $(this).prev('li.li_dropper').removeClass('hover');
        };
    });


    $('ul.main-catalog>li.li_dropper .b-main-catalog-dropper').on('click', function () {
        $(this).parent('li.li_dropper').toggleClass('_toggled');
        $(this).parent('li.li_dropper').next('.inset').slideToggle();
    });



    $('body').on('mouseenter', '.w-hidden-search-results .inset', function () {
        $(this).prev('.dropper').addClass('hover');
    });

    $('body').on('mouseleave', '.w-hidden-search-results .inset', function (e) {

        if($(e.fromElement).hasClass('js-item-remove-from-cart')
            || $(e.fromElement).hasClass('js-item-add-to-cart')
            || $(e.fromElement).hasClass('table-price-input')

        ){
            return false;
        }

        $(this).prev('.dropper').removeClass('hover');
    });


//MAIN-MENU-mobile-propper-on-PLUS
    $('nav>ul>li').on('click', function () {
        $(this).toggleClass('_pressed');
        $(this).children('.inset').slideToggle();
    });


//custom-scroller
    $(window).on("load",function(){
       // $(".p-catalog .w-main-table>.right").mCustomScrollbar({scrollInertia:100, mouseWheel:{
       //     deltaFactor: 25
       // }});
            $(".w-new-items, .b-news .w-news, .w-reviews").mCustomScrollbar({scrollInertia:100});
    });


//MAIN-CATALOG-TOGGLER
    $('.b-main-catalog .icon').on('click', function (e) {
        $('.w-main-catalog').slideToggle();
        $('.w-main-catalog').toggleClass('_toggled');
        $('a.a-toggle-catalog').toggleClass('_active')
        $('.p-catalog .w-main-table>.right, .p-cart .w-main-table>.right').toggleClass('_fullwidth');
        $('.b-news').toggleClass('hidden');
        return false;
    });
    $('a.a-toggle-catalog').on('click', function (e) {
        $('.w-main-catalog').slideToggle();
        $('.w-main-catalog').toggleClass('_toggled');
        $(this).toggleClass('_active');
        $(this).siblings('a').toggleClass('_active');
        $('.p-catalog .w-main-table>.right, .p-cart .w-main-table>.right').toggleClass('_fullwidth');
        return false;
    });


//mobile-contacts-toggler mobile-menu-toggler
    $('.js-mobile-contacts-button, .w-contacts .close-contacts a').on('click', function (e) {
            $('.w-contacts').slideToggle();
            return false;
        });
    $('.js-mobile-menu-button, nav .close-main-menu').on('click', function (e) {
            $('.w-main-navigation nav').slideToggle();
            return false;
        });


//toggle mobile-class
    $('.js-mobile-contacts-button').on('click', function (e) {
            $('.w-contacts').addClass('_mobile');
            return false;
        });
    $('.b-mobile-button._menu').on('click', function (e) {
            $(this).toggleClass('_active');
            $('.w-main-navigation nav').toggleClass('_active');
            return false;
        });


//POPUPS
    $('.js-b-write-to-us').on('click', function (e) {
            $('.s-popup, .w-popup').css('display' , 'none');
            $('.s-popup, .dark-bg').css('display' , 'block');
            $('._write-to-us').css('display' , 'inline-block');
            $('.w-contacts._mobile').css('display' , 'none');
            return false;
        });
    $('.js-b-to-director').on('click', function (e) {
            $('.s-popup, .w-popup').css('display' , 'none');
            $('.s-popup, .dark-bg').css('display' , 'block');
            $('._to-director').css('display' , 'inline-block');
            $('.w-contacts._mobile').css('display' , 'none');
            return false;
        });
    $('.js-b-demping').on('click', function (e) {
            $('.s-popup, .w-popup').css('display' , 'none');
            $('.s-popup, .dark-bg').css('display' , 'block');
            $('._demping').css('display' , 'inline-block');
            $('.w-contacts._mobile').css('display' , 'none');
            return false;
        });

    $('body').on('click','.js-b-discount', function (e) {
        $('.s-popup, .w-popup').css('display' , 'none');
        $('.s-popup, .dark-bg').css('display' , 'block');

        console.log($(this).data('item_id'));
        console.log($(this).data('item_name'));

        $('._p-discount input[name=id_1c]').val($(this).data('item_id'));
        $('._p-discount span.demping-item-name').text($(this).data('item_name'));

        $('._p-discount').css('display' , 'inline-block');
        $('.w-contacts._mobile').css('display' , 'none');
        return false;
    });

    $('.w-pop .close, .dark-bg').on('click', function (e) {
            $('.s-popup, .w-popup, .dark-bg').css('display' , 'none');
            return false;
        });

//ITEM-TABLE-TOGGLED
    $('body').on('click', '.toggler-button', function (e) {
        $(this).parents('.w-table').toggleClass('_toggled');
        $(this).parents('.w-table').children('.table-body').slideToggle();
        $(this).parents('.w-table').children('.table-body').toggleClass('_toggled');
            return false;
        });
    $('.w-table-check-all, .w-table-check-all .toggler-button').on('click', function (e) {
        $('.w-table').removeClass('_toggled').children('.table-body').css('display' , 'none').removeClass('_toggled');
        $('.w-table-check-all, .w-table-check-all .toggler-button').hide();
        $('.w-table-uncheck-all, .w-table-uncheck-all .toggler-button').css('display' , 'inline-block');
            return false;
        });
    $('.w-table-uncheck-all, .w-table-uncheck-all .toggler-button').on('click', function (e) {
        $('.w-table').addClass('_toggled').children('.table-body').css('display' , 'block').addClass('_toggled');
        $('.w-table-uncheck-all, .w-table-uncheck-all .toggler-button').hide();
        $('.w-table-check-all, .w-table-check-all .toggler-button').css('display' , 'inline-block');
            return false;
        });


//ITEM-TABLE-USD-TOGGLER
    $('.js-b-usd').on('click', function (e) {
        $(this).siblings('.button').addClass('_active');
        $(this).removeClass('_active');
        $('.td-price').toggleClass('_byn');
        $('.td-price').toggleClass('_usd');
            return false;
        });
    $('.js-b-byn').on('click', function (e) {
        $(this).siblings('.button').addClass('_active');
        $(this).removeClass('_active');
        $('.td-price').toggleClass('_byn');
        $('.td-price').toggleClass('_usd');
            return false;
        });
    $('.js-b-mrp').on('click', function (e) {
        $(this).toggleClass('_active');
        $('.td-price._mrp').toggleClass('_active');
            return false;
        });

//MOBILE SEARCH TOGGLE BUTTON
    $('.js-b-search').on('click', function (e) {
        $(this).toggleClass('_active');
        $('.p-catalog .h-search').toggleClass('_active');
        $('#js-input-search').focus();

        return false;
        });

// PRODUCT CLAPS SWITCHER
    if ($('.w-product-claps').length) {
            $('.w-claps .clap').on('click', function () {
               $('.w-claps .clap').removeClass('_active').eq($(this).index()).addClass('_active');
               $('.clap-content').removeClass('_active').eq($(this).index()).addClass('_active');
               return false;
                });
    };

//ITEM SLIDER
    $('.item-slider').bxSlider({
        startSlide: 0,
        pause: 100,
        controls: true,
        auto: false,
        pause: 8000,
        speed: 800,
        tickerHover: true,
        infiniteLoop: false,
        pagerCustom: '.item-slider-pager'
    });


    $("#form-remember-password").validate({
        ignore: [],

        rules: {
            email: {
                required: true,
                email:true
            },

            messages:{
                email: {
                    email: 'Пожалуйста, проверьте корректность введеного email',
                },
            },
        },


        submitHandler: function(form) {
            emailField = $('#form-remember-password input[name="email"]');
            var options = {};
            options['email'] = emailField.val();
            $.ajax({
                type: 'POST',
                url: "/remember/restore",
                data: options,
                success: function (data) {
                    //console.log(data);
                    if(data != 'false'){
                        swal({
                            title: "Отлично",
                            text: 'Новый пароль был отправлен вам на почту',
                            confirmButtonColor: '#870020',
                            html: true
                        });

                    } else {
                        swal({
                            title: "Ошибка",
                            text: 'Такой почты не существует!',
                            confirmButtonColor: '#870020',
                            html: true
                        });
                    }
                },
                async:false
            });

        },
        errorElement: "em",
        errorClass: "_error",
        validClass: "successMessages"
    });




    $('.w-cabinet-repairs').on('click', '.js-repair-search-button',  function (e) {
        e.preventDefault();
        var repair_code = $('input[name=repair_code]').val();

        $('.js-repair-code-value').each(function (e) {
            var code_value = $(this).text();
            if (~code_value .indexOf(repair_code)) {
                $(this).parents('tr').show();
            } else {
                $(this).parents('tr').hide();
            }
        });


        console.log(repair_code)
    });

    $("#form-send-email-from-reg").validate({
        ignore: [],
        rules: {
            name: {required: true},
            email: {

            required: function(element) {
                return (!$('#form-send-email-from-reg [name="phone"]').hasClass('successMessages'));
            },
                email:true
            },
            phone: {
                required: function(element) {
                    return (!$('#form-send-email-from-reg [name="email"]').hasClass('successMessages'));
                },
            },
            comment: {required: true},

            messages: {
                email: {
                    email: 'Пожалуйста, проверьте корректность введеного email',
                },

            }
        },

        errorElement: "em",
        errorClass: "error",
        validClass: "successMessages",

    });



    $('body').on('click','.js-redirect-search', function (e) {
        e.preventDefault();
        var jsSearchText = $('.js-search').val();
        console.log(jsSearchText);
        $(location).attr('href', 'http://alfastok.by/catalog/search?search_keywords='+jsSearchText)
    });




    $('.p-catalog').on('change keyup input click', '.js-filter-search-button',  function (e) {
        e.preventDefault();
        var filter_word = $('input[name=filter_word]').val();
        filter_word = filter_word.toLowerCase();

        /*
        $('.td-name').each(function (e) {
            var code_value = $(this).data('name');
            if(code_value != undefined){
                code_value = code_value.toLowerCase();
                if (~code_value.indexOf(filter_word)) {
                    $(this).parents('tr').show();
                } else {
                    $(this).parents('tr').hide();
                }
            }
        });

        $('.w-table._toggled').each(function(e){
            //console.log($(this).find('.table-body._toggled tr:not([style="display: none;"])').length);
            if($(this).find('.table-body._toggled tr:not([style="display: none;"])').length < 1) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });
        */
        //console.log(repair_code)
    });


/*
    $('.p-catalog').on('change keyup input click', '.js-filter-search-button',  function (e) {
        e.preventDefault();
        var filter_word = $('input[name=filter_word]').val();
        if(filter_word = search_filter_old){
            return false;
        }
        filter_word = filter_word.toLowerCase();

        $('.td-name').each(function (e) {
            var code_value = $(this).data('name');
            if(code_value != undefined){
                code_value = code_value.toLowerCase();
                if (~code_value.indexOf(filter_word)) {
                    $(this).parents('tr').show();
                } else {
                    $(this).parents('tr').hide();
                }
            }
        });

        $('.w-table._toggled').each(function(e){
            //console.log($(this).find('.table-body._toggled tr:not([style="display: none;"])').length);
            if($(this).find('.table-body._toggled tr:not([style="display: none;"])').length < 1) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });

        //console.log(repair_code)
    });
*/

    var search_filter_old = '';

    function search_filter(){
        var filter_word = $('input[name=filter_word]').val();

        filter_word = filter_word.toLowerCase();

        if(filter_word == search_filter_old){
            return false;
        }

        $('.td-name').each(function (e) {
            var code_value = $(this).data('name');
            if(code_value != undefined){
                code_value = code_value.toLowerCase();
                if (~code_value.indexOf(filter_word)) {
                    $(this).parents('tr').show();
                } else {
                    $(this).parents('tr').hide();
                }
            }


        });

        $('.w-table._toggled').each(function(e){
            if($(this).find('.table-body._toggled tr:not([style="display: none;"])').length < 1) {
                $(this).hide();
            } else {
                $(this).show();
            }
        });

        search_filter_old = filter_word;

    }
    setInterval(search_filter, 2000);



    var search_keywords = '';
    var search_keywords_old = '';
    function ajax_search() {
        if(search_keywords != ''){
            if(search_keywords.length > 2 ){
                if( search_keywords == search_keywords_old){
                    return true;
                }
                //console.log('Обновил');

                var options = {};
                options['search_keywords'] = search_keywords;
                search_keywords_old = search_keywords;
                $.ajax({
                    type: 'POST',
                    url: "/search",
                    data: options,
                    success: function (data) {
                        if(data != 'false'){
                            $('.w-hidden-search-results').html(data);
                            $('.w-hidden-search-results').show();
                        } else {
                            $('.w-hidden-search-results').hide();
                        }
                    },
                    async:true
                });
            } else {
                $('.w-hidden-search-results').hide();
            }
        }

    }

    setInterval(ajax_search, 500);

    //clearTimeout(timerId);
    //alert(timerId); // всё ещё число, оно не обнуляется после отмены


    $('body').on('keyup change keyup input click paste', '.js-search', function (e) {
        search_keywords = $.trim($(this).val());
        if(search_keywords.length < 2){
            $('.w-hidden-search-results').hide();
        }
       // event.stopPropagation();
    });

    $(document).click(function(event) {
        if ($(event.target).closest(".w-hidden-search-results").length) return true;
        //console.log($(event.target)[0].tagName);
        //console.log($(this));
        //console.log($(event.currentTarget));
        //console.log($(event));
        //console.log('close');
        $('.w-hidden-search-results').hide();
        //event.stopPropagation();

    });

    $(".s-popup input[type=file]").change(function(e){
        var filename = $(this).val().replace(/.*\\/, "");
        if(this.files[0].size > 20*1024*1024 ){
            $(this).val('');
            swal({
                title: 'Ошибка',
                text: 'Размер файла должен быть менее 20 мб',
                confirmButtonColor: '#870020',
                html: true
            });

        } else {
            $(this).parent().find('.file_path').text(filename);
        }

    });



    // Почему так дорого
    $('body').on('click', '.js-get-discount',  function (e) {
        e.preventDefault();

        var options = {};
        options['item_id'] = $(this).data('item_id');
        $.ajax({
            type: 'POST',
            url: "/ajax/get_discount",
            data: options,
            success: function (data) {
                console.log(data);
                if(data != 'false'){
                    swal({
                        title: popup_if_expensite_title,
                        text: popup_if_expensite_text,
                        confirmButtonColor: '#870020',
                        html: true
                    });
                }
            },
            async:true
        });


    });


    if ($('.w-product-main-info ul.item-slider').length > 0) {
        $("a.grouped_elements").fancybox();
    }

    $('body').on('click','.no-link', function (e) {
        e.preventDefault();
    })




});













