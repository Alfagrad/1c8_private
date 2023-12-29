$(document).ready(function(){

    
    // Функция отвечающая за поиск брендов

    var brandsTextOld = '';
    var checkedBrandsOld = [];

    function guidesSearch() {

        console.log(123123123)

        var brandsText = $('.js-brands-search-form').find('.js-brands-text').val();

        var checkedBrands = [];
        $('.js-brands-list input:checkbox:checked').each(function (e) {
            checkedBrands.push($(this).val());
        });

        console.log(brandsText, brandsTextOld );

        if(brandsText == brandsTextOld && checkedBrands.toString() == checkedBrandsOld.toString() ){
            return false;
        }

        console.log('Изменения');

        // Получаем все бренды, и тоже сравниваем с прошлым
        $('.table-brands-download tbody tr').each(function (e) {
            var brand_id = String($(this).data('brand_id'));
            var code_value = $(this).find('.js-brand-name').text();
            console.log(brand_id, checkedBrands);

            if(checkedBrands.length){

                if(checkedBrands.indexOf(brand_id)== -1){
                    console.log('test');
                    $(this).hide();
                } else {
                    var code_value = $(this).find('.js-brand-name').text();
                    console.log(code_value, brandsText);
                    if(code_value != undefined){
                        code_value = code_value.toLowerCase();
                        if (~code_value.indexOf(brandsText.toLowerCase())) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    }
                }
            } else {
                if(code_value != undefined && brandsText != undefined){
                    code_value = code_value.toLowerCase();
                    if (~code_value.indexOf(brandsText.toLowerCase())) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                }
            }

        });
        brandsTextOld = brandsText;
        checkedBrandsOld = checkedBrands;
    }
    //setInterval(guidesSearch, 2000);


    $('.js-brands-list').on('change', 'input', function (e) {
        e.preventDefault();
        guidesSearch();
    });

    $('.js-brands-search-form').on('click', '.button', function (e) {
        e.preventDefault();
        guidesSearch();
    });

    $('.js-brands-text').on('keyup', function (e) {
          guidesSearch();
    });


    /*

    $('.js-brands-list').on('change', 'input', function (e) {
        e.preventDefault();

        var checkedBrands = [];
        $('.js-brands-list input:checkbox:checked').each(function (e) {
            checkedBrands.push($(this).val());
        });
        console.log(checkedBrands);
        if(checkedBrands.length){
            $('.table-brands-download tbody tr').each(function (e) {
                console.log($(this).data('brand_id'));
                if(checkedBrands.indexOf(String($(this).data('brand_id')))== -1){
                    $(this).hide();
                } else{
                    $(this).show();
                }
            })
        } else {
            $('.table-brands-download tbody tr').show();
        }
        $('.js-brands-search-form').find('.js-brands-text').val('');

    });


    $('.js-brands-search-form').on('click', '.button', function (e) {
        e.preventDefault();
        var brandsText = $(this).parents('.js-brands-search-form').find('.js-brands-text').val();

        var checkedBrands = [];
        $('.js-brands-list input:checkbox:checked').each(function (e) {
            checkedBrands.push($(this).val());
        });

            $('.table-brands-download tbody tr').each(function (e) {
                var brand_id = String($(this).data('brand_id'));
                var code_value = $(this).find('.js-brand-name').text();
                console.log(brand_id, checkedBrands);

                if(checkedBrands.length){

                    if(checkedBrands.indexOf(brand_id)== -1){
                        console.log('test');
                        $(this).hide();
                    } else {
                        var code_value = $(this).find('.js-brand-name').text();
                        console.log(code_value, brandsText);
                        if(code_value != undefined){
                            code_value = code_value.toLowerCase();
                            if (~code_value.indexOf(brandsText.toLowerCase())) {
                                $(this).show();
                            } else {
                                $(this).hide();
                            }
                        }
                    }
                } else {
                        if(code_value != undefined){
                            code_value = code_value.toLowerCase();
                            if (~code_value.indexOf(brandsText.toLowerCase())) {
                                $(this).show();
                            } else {
                                $(this).hide();
                            }
                        }
                    }

            })






        console.log('text', brandsText.val());
    });


*/
});