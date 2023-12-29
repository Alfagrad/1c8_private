$(document).ready(function(){

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

                search_keywords_old = search_keywords;
                options._token = $('meta[name="csrf-token"]').attr('content');

                ajax = $.ajax({
                    type: 'POST',
                    url: "/ajax-search",
                    data: options,
                    beforeSend : function() {
                        if(ajax) {
                            ajax.abort();
                        }
                    },
                    success: function (data) {

                        if(data != 'false'){
                            $('.search-results ' + s_type).html(data);
                            $('.search-results').fadeIn();
                            //$('div[rel=tipsy]').tipsy({html: true});
                        } else {
                            //$('.search-results').hide();
                            $('.search-results').fadeIn();
                            $('.search-results ' + search_type).html("<div class='no-results'>По данному запросу ничего не найдено</div>");
                        }
                        ajax = undefined;
                    },
                    error: function(data){
                        // console.log(data);
                    },
                    async:true
                });
            } else {
                search_keywords_old = '';
                $('.search-results').fadeOut();
            }
        }

    }

    setInterval(ajax_search, 500);


    // отображаем панель результатов поиска
    $(document).click(function(event) {
        if ($(event.target).closest(".search-results").length) return true;
        if ($(event.target).closest(".js-search").length){
             if(search_keywords.length > 2){
                $('.search-results').fadeIn();
                return true;
             }
        }
        $('.search-results').fadeOut();
    });

    // назначаем тип товаров в input
    $('.spares').on('click',function (e) {
        $('.typeSearch').val('spares')
    } );
    $('.products').on('click',function (e) {
        $('.typeSearch').val('products')
    } );

    // переключение табов типа товаров для поиска
    var search_type = '#products'; // тип по умолчанию
    $(".search-results-tabs a").on("click", function(e) {
            e.preventDefault();
            var link = $(this);
            var tab = $(this).attr("href");
            search_type = tab;
            $(".search-results-tabs li").removeClass("active");
            link.parent("li").addClass("active");
            $(".search-results .result-content").removeClass("active");
            $(tab).addClass("active");
            search_keywords_old = '';
            ajax_search();
    });

    // отображение описания
    $('body').on('mouseenter', '.row.dropper', function () {
        $(this).addClass('active');
        $(this).next('.inset').css('display', 'block');
        if($(window).width() > 1023) {
            $(this).children('.arrow').css('display', 'block');
        }
    });
    $('body').on('mouseleave', '.row.dropper', function () {

        if($(this).next('.inset:hover').length == 0) {
            $(this).removeClass('active');
            $('.inset').css('display', 'none');
            if($(window).width() > 1023) {
                $('.arrow').css('display', 'none');
            }
        }
    });
    $('body').on('mouseleave', '.inset', function () {
        $('.row.dropper').removeClass('active');
        $('.inset').css('display', 'none');
        if($(window).width() > 1023) {
            $('.arrow').css('display', 'none');
        }
    });

    // переход на страницу поиска
    $('body').on('click','.js-redirect-search', function (e) {
        e.preventDefault();
        var jsType = $('.typeSearch').val();
        var jsSearchText = $.trim($('.js-search').val());
        if($(this).data('is_service') == '1') {
            // если сервис
            $(location).attr('href', '/service/search?type='+jsType+'&search_keywords='+jsSearchText)
        } else {
            $(location).attr('href', '/catalogue/search?type='+jsType+'&search_keywords='+jsSearchText)
        }
    });



    $('.js-search').keypress(function(event){
        var keycode = (event.keyCode ? event.keyCode : event.which);
        var jsType = $('.typeSearch').val();
        if(keycode == '13' && event.target.value.length > 2){
            event.preventDefault();
            var jsSearchText = $.trim($('.js-search').val());
            if($('.js-redirect-search').data('is_service') == '1') {
                // если сервис
                $(location).attr('href', '/service/search?type='+jsType+'&search_keywords='+jsSearchText)
            } else {
                console.log(1212121);
                $(location).attr('href', '/catalogue/search?type='+jsType+'&search_keywords='+jsSearchText)
            }
        }
    });

    $('body').on('keyup change keyup input click paste', '.js-search', function (e) {
        search_keywords = $.trim($(this).val());
        if(search_keywords.length < 2){
            $('.search-results').fadeOut();
        }

        ajax_search();
    });


	console.log('поиск в хэдере');

});
