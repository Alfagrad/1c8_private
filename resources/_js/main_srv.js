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

    $('.js-b-new-cart').on('click', function (e) {
            $('.s-popup, .w-popup').css('display' , 'none');
            $('.s-popup, .dark-bg').css('display' , 'block');
            $('._add_cart').css('display' , 'inline-block');
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
    $('.js-b-mrp').on('click', function () {
        $(this).toggleClass('_active');
        console.log('ЖМУ');
        $('.td-price._mrp').toggleClass('_active');
            return false;
        });


    $('.js-b-is-news').on('click', function (e) {
        $(this).toggleClass('_active');
        search_filter()
        return false;
    });

    $('.js-b-is-action').on('click', function (e) {
        $(this).toggleClass('_active');
        search_filter()
        return false;
    });


    $('.js-b-is-cheap').on('click', function (e) {
        $(this).toggleClass('_active');
        //if($(this).hasClass('_active')){
        //    $('.wrapper.items-table.catalog-table').find('tr').hide();
        //    $('.wrapper.items-table.catalog-table').find('tr[data-cheap="1"]').show();
        //} else {
        //    $('.wrapper.items-table.catalog-table').find('tr').show();
        //}
        //return false;
        search_filter()
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
    $('body').on('click', '.w-claps .clap', function () {
        if ($('.w-product-claps').length) {
                $('.w-claps .clap').removeClass('_active in').eq($(this).index()).addClass('_active in');
                $('.clap-content').removeClass('_active in').eq($(this).index()).addClass('_active in');
                return false;
        }
        ;
    });
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
        pagerCustom: '.for-slider-pager'
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
                    console.log(data);
                    if(data != 'false'){
                        swal({
                            title: "Отлично",
                            text: 'Новый пароль был отправлен вам на почту',
                            confirmButtonColor: '#870020',
                            html: true
                        }, function (isConfirm) {
                            // $(location).attr('href', 'http://alpha.loc/');
                        })
                        //$(location).attr('href', 'http://alfastok.by/');

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
            grecaptcha: {required: true},

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
/*-------------------------------------------------------------------------*/

    // переход в поиск по кнопке или Enter 
    $('body').on('click','.js-redirect-search', function (e) {
        e.preventDefault();
        var jsSearchText = $('.js-search').val();
        var jsType = $('.typeSearch').val();
        var jsSearchText = $('.js-search').val();
            // если сервис
            $(location).attr('href', '/service/search?type='+jsType+'&search_keywords='+jsSearchText)
    });

    $('.js-search').keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        var jsType = $('.typeSearch').val();
        if(keycode == '13' && event.target.value.length > 2){
            event.preventDefault();
            var jsSearchText = $('.js-search').val();
            // если сервис
            $(location).attr('href', '/service/search?type='+jsType+'&search_keywords='+jsSearchText)
        }
    });

    $('.spares').on('click',function (e) {
        $('.typeSearch').val('spares')
    } );
    $('.products').on('click',function (e) {
        $('.typeSearch').val('products')
    } );


    /**
    * Поиск по каталогу
    * */

    $('.p-catalog').on('change keyup input click', '.js-filter-search-button',  function (e) {
        e.preventDefault();
        var filter_word = $('input[name=filter_word]').val();
        filter_word = filter_word.toLowerCase();
        console.log('жму');

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

    // поиск по запчастям

    


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
    var search_filter_new = 0;
    var search_filter_action = 0;
    var search_filter_cheap = 0;


    function search_filter(){
        var filter_word = $('input[name=filter_word]').val();
        var filter_new = ($('.js-b-is-news').hasClass('_active'))?1:0;
        var filter_action = ($('.js-b-is-action').hasClass('_active'))?1:0;
        var filter_cheap = ($('.js-b-is-cheap').hasClass('_active'))?1:0;



        filter_word = filter_word.toLowerCase();

        // Если ничего не изменилось
        if(filter_word == search_filter_old && search_filter_new == filter_new && search_filter_action == filter_action && search_filter_cheap == filter_cheap){
            return false;
        }

        $('.td-name').each(function (e) {
            var code_value = $(this).data('name');
            var td_tag = $(this).parents('tr');

            if(code_value != undefined && filter_word != undefined && filter_cheap != undefined){
                code_value = code_value.toLowerCase();

                var is_show = 0;
                var is_show_new = 0;
                var is_show_action = 0;
                var is_show_cheap = 0;

                if(~code_value.indexOf(filter_word)){
                    is_show = 1;

                  //  if ( (td_tag.data('action') != filter_action && filter_action != 0) || (td_tag.data('cheap') != filter_cheap && filter_cheap != 0) ) {
                  //      is_show = 0
                  //  }


                    if(filter_action || filter_cheap || filter_new) {
                        if (td_tag.data('action') != filter_action && filter_action != 0) {
                            is_show_action = 1;
                        }

                        if ((td_tag.data('cheap') != filter_cheap && filter_cheap != 0 )) {
                            is_show_cheap = 1;
                        }

                        if ((td_tag.data('new') != filter_new && filter_new != 0 )) {
                            is_show_new = 1;
                        }
                    }

                    if(filter_action || filter_cheap || filter_new) {
                        if (is_show_cheap || is_show_action || is_show_new) {
                            is_show = 0;
                        }
                    }

                    // } else {
                    //     if (is_show_cheap || is_show_action) {
                    //         is_show = 0;
                    //     }
                    // }



                    if (is_show) {
                        td_tag.show();
                    } else {
                        td_tag.hide();
                    }

                } else {
                    td_tag.hide();

                }
                is_show = 0


            } else {
                // Если текста нету тогда
                var is_action_show = 0;
                var is_cheap_show = 0;
                var is_new_show = 0;

                if (td_tag.data('action') == filter_action && filter_action != 0) {
                    is_action_show = 1
                }

 
                if ((td_tag.data('cheap') == filter_cheap && filter_cheap != 0)) {
                    is_cheap_show = 1
                }

                if ((td_tag.data('new') == filter_new && filter_new != 0)) {
                    is_new_show = 1
                }


            }


        });

        $('.w-table._toggled').each(function(e){
            if($(this).find('.table-body._toggled tr:not([style="display: none;"])').length < 1) {
                $(this).hide();
                $('.spare-analog_line').css('display', 'none'); // чтобы не высвичивали аналоги
                console.log('АН-1');
            } else {
                $(this).show();
                $('.spare-analog_line').css('display', 'none'); // чтобы не высвичивали аналоги
                console.log('АН-2');
            }
        });

        search_filter_old = filter_word;
        search_filter_action = filter_action;
        search_filter_cheap = filter_cheap;
        search_filter_new = filter_new;

    }
    setInterval(search_filter, 2000);

/**
 * Общий поиск по сайту
 * */

    var search_type = '#products'; // тип по умолчанию

    $(".search-results-tabs a").on("click", function(e) {
            e.preventDefault();
            var link = $(this);
            var tab = $(this).attr("href");
            search_type = tab;
            $(".search-results-tabs li").removeClass("active");
            link.parent("li").addClass("active");
            $(".w-hidden-search-results .tab-pane").removeClass("in active");
            $(tab).addClass("in active");
            search_keywords_old = '';
            ajax_search();
    });


    var search_keywords = '';
    var search_keywords_old = '';
    var ajax = undefined;
    function ajax_search() {
        if(search_keywords != ''){
            if(search_keywords.length > 2 ){
                if( search_keywords == search_keywords_old){
                    return true;
                }
                var s_type = search_type;
                var options = {};
                options['search_keywords'] = search_keywords;
                if(search_type == '#products')
                {
                    options['type'] = 'products';
                }
                else
                {
                     options['type'] = 'spares';
                }

                console.log('Поиск');

                search_keywords_old = search_keywords;
                ajax = $.ajax({
                    type: 'POST',
                    url: "/search",
                    data: options,
                    beforeSend : function() {
                        if(ajax) {
                            console.log('Прервал запрос');
                            ajax.abort();
                        }
                    },
                    success: function (data) {
                        if(data != 'false'){
                            $('.w-hidden-search-results ' + s_type).html(data);
                            $('.w-hidden-search-results').show();
                            //$('div[rel=tipsy]').tipsy({html: true});
                        } else {
                            //$('.w-hidden-search-results').hide();
                            $('.w-hidden-search-results').show();
                            $('.w-hidden-search-results ' + search_type).html("<span class='no-results'>По данному запросу ничего не найдено</span>");
                        }
                        ajax = undefined;
                    },
                    async:true
                });
            } else {
                search_keywords_old = '';
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

        ajax_search();
       // event.stopPropagation();
    });


    $()

    $(document).click(function(event) {
        if ($(event.target).closest(".w-hidden-search-results").length) return true;
        if ($(event.target).closest(".js-search").length){
             if(search_keywords.length > 2){
                $('.w-hidden-search-results').show();
                return true;
             }
        }
        $('.w-hidden-search-results').hide(); // Если нажали на пустое пространство убрать окошко
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


    $(function() {
        $('div[rel=tipsy]').tipsy({html: true});
    });

});


function spares_view(item){
       if( $('.js-view-spares').length ) {


            $('#spares-clap').html('');

            var item_obj = $(item);
            var id = item_obj.attr("data-id");
            var token = item_obj.attr("data-token");
            var csrf = item_obj.attr("data-csrf");

            console.log('Стрелочка вниз');
            console.log(item_obj);


            var options = {};

                options['id'] = id;
                options['token'] = token;
                options['_token'] = csrf;


                $.ajax({
                    type: 'POST',
                    url: "/srv_spares_search",
                    data: options,
                    success: function (data) {
                        if(data != 'false'){
                            $('#spares-clap').html(data);

                            function update_preview() {
                                    $('a.hovered-product').each(function() {
                                        var imagePath = $(this).data('big');
                                        $(this).qtip({
                                            content: {
                                                text: function(event, api) {
                                                    content = '<div id="pic_video_block" style="width:240px;height:240px;"><img src="'+api.elements.target.attr('data-big')+'"></div>';
                                                    return content;
                                                    api.set('content.text', content);

                                                    return 'Загрузка...';
                                                }
                                            },
                                            position: {
                                                viewport: $(window)
                                            },
                                            style: 'qtip-light'
                                        });
                                    });
                                }
                                update_preview();

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

                                $('.js-collapse-toggle').on('click', function (e) {
                                    console.log("collapse");
                                    $(this).parents(".product-block-sheme").toggleClass('active').find(".product-block-sheme__body").collapse("toggle");
                                   /* $(".product-block-sheme__body").on("shown.bs.collapse", function(){
                                        document.querySelector('.product-block-sheme__img img').dispatchEvent(new CustomEvent('wheelzoom.destroy'));
                                        wheelzoom(document.querySelector('.product-block-sheme__img img'));
                                    });*/
                                });


                        } else {
                            $('#spares-clap').html("<h3>Запчасти</h3><div class='no-results'>При загрузке данных произошла ошибка</div>");
                        }
                    },
                    async:true
                });

            $("html, body").animate({
                scrollTop: $("#w-product-claps").offset().top - 50 + "px"
            },{duration:500, easing:"swing"});

            $(".clap, .clap-content").removeClass("_active in");
            $("#spares-clap-link, #spares-clap").addClass("_active in");


    }
}


function spares_link(_id, _token, _crsf){

         if( $('.js-view-spares').length ) {
                    //$('#spares-clap').html('');

                    var id = _id;
                    var token = _token;
                    var csrf = _crsf;


                    var options = {};

                        options['id'] = id;
                        options['token'] = token;
                        options['_token'] = csrf;


                        $.ajax({
                            type: 'POST',
                            url: "/srv_spares_search",
                            data: options,
                            success: function (data) {
                                if(data != 'false'){
                                    $('#spares-clap').html(data);

                                    function update_preview() {
                                            $('a.hovered-product').each(function() {
                                                var imagePath = $(this).data('big');
                                                $(this).qtip({
                                                    content: {
                                                        text: function(event, api) {
                                                            content = '<div id="pic_video_block" style="width:240px;height:240px;"><img src="'+api.elements.target.attr('data-big')+'"></div>';
                                                            return content;
                                                            api.set('content.text', content);

                                                            return 'Загрузка...';
                                                        }
                                                    },
                                                    position: {
                                                        viewport: $(window)
                                                    },
                                                    style: 'qtip-light'
                                                });
                                            });
                                        }
                                        update_preview();

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

                                        $('.js-collapse-toggle').on('click', function (e) {
                                            console.log("collapse");
                                            $(this).parents(".product-block-sheme").toggleClass('active').find(".product-block-sheme__body").collapse("toggle");
                                            /*$(".product-block-sheme__body").on("shown.bs.collapse", function(){
                                                document.querySelector('.product-block-sheme__img img').dispatchEvent(new CustomEvent('wheelzoom.destroy'));
                                                wheelzoom(document.querySelector('.product-block-sheme__img img'));

                                            });*/
                                        });

                                } else {
                                    $('#spares-clap').html("<h3>Запчасти</h3><div class='no-results'>При загрузке данных произошла ошибка</div>");
                                }
                            },
                            async:true
                        });

                    if(params['spares'] == "true")
                    {
                        $("html, body").animate({
                            scrollTop: $("#w-product-claps").offset().top - 50 + "px"
                        },{duration:500, easing:"swing"});

                        $(".clap, .clap-content").removeClass("_active in");
                        $("#spares-clap-link, #spares-clap").addClass("_active in");
                    }


            }
}


function spares_down()
{
    $("html, body").animate({
        scrollTop: $("#w-product-claps").offset().top - 50 + "px"
    },{duration:500, easing:"swing"});

    $(".clap, .clap-content").removeClass("_active in");
    $("#spares-clap-link, #spares-clap").addClass("_active in");
}


//извлечение GET параметра
var params = window
        .location
        .search
        .replace('?','')
        .split('&')
        .reduce(
            function(p,e){
                var a = e.split('=');
                p[ decodeURIComponent(a[0])] = decodeURIComponent(a[1]);
                return p;
            },
            {}
        );





$(document).ready(function(){
      function update_preview() {
        $('a.hovered-product').each(function() {
            var imagePath = $(this).data('big');
            $(this).qtip({
                content: {
                    text: function(event, api) {
                        content = '<div id="pic_video_block" style="width:240px;height:240px;"><img src="'+api.elements.target.attr('data-big')+'"></div>';
                        return content;
                        api.set('content.text', content);
                        return 'Загрузка...'; // Set some initial text
                    }
                },
                position: {
                    viewport: $(window)
                },
                style: 'qtip-light'
            });
        });
    }
    update_preview();


    //
    $(document).ready(function(){
        $('.imageResize')
            .wrap('<span style="display:inline-block"></span>')
            .css('display', 'block')
            .parent()
            .zoom();
    });


});

function update(id, block) {
    var count =  $('#item-'+id).val();
    var firstCeil = count/block;
    console.log(block)
    var ceil = Math.trunc(count/block);
    if (firstCeil < 1) {
        $('#item-'+id).val(block)
    }
    else {
        console.log(ceil)
        $('#item-'+id).val(ceil*block+block)
    }





}

