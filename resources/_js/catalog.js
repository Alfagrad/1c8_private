$(document).ready(function(){

    $('.js-main-catalog').on('change', 'input[type=checkbox]', function () {
        var checkBox = $(this);
        if(checkBox.data('has_sub') == false){
            getCheckedCategory();
            return false;
        }

        if(checkBox.data('type') == 'category'){
            var category_id = $(this).data('category_id');
            if($(this).prop('checked')){
                $("input[data-category_id="+ category_id +"]").parents('label').removeClass('inset-half-checked');
                $("input[data-category_id="+ category_id +"]").prop('checked', true);
                //$("input[data-category_id="+ category_id +"]").parents('label').removeClass('inset-half-checked'););
            } else {
                $("input[data-category_id="+ category_id +"]").prop('checked', false);
                $("input[data-category_id="+ category_id +"]").parents('label').removeClass('inset-half-checked');
            }
        }

        if(checkBox.data('type') == 'subCategory'){
            var category_id = $(this).data('subcategory_id');

            $("input[data-subcategory_id="+ category_id +"]").parents('label').removeClass('inset-half-checked');

            if($(this).prop('checked')){
                $("input[data-subcategory_id="+ category_id +"]").prop('checked', true);
            } else {
                $("input[data-subcategory_id="+ category_id +"]").prop('checked', false);
            }


        }

        if(checkBox.data('type') == 'subSubCategory'){
            var category_id = $(this).data('subcategory_id');
            var subSubCategory = $("input[data-subcategory_id="+ category_id +"][data-type=subSubCategory]");
            var subSubCategoryChecked = $("input[data-subcategory_id="+ category_id +"][data-type=subSubCategory]:checked");

            $("input[data-subcategory_id="+ category_id +"][data-type=subCategory]").parents('label').removeClass('inset-half-checked');

            if(!subSubCategoryChecked.length){
                $("input[data-subcategory_id="+ category_id +"]").prop('checked', false);
            } else {
                if(subSubCategoryChecked.length == subSubCategory.length ){
                    $("input[data-subcategory_id="+ category_id +"]").prop('checked', true);
                } else {
                    $("input[data-subcategory_id="+ category_id +"][data-type=subCategory]").prop('checked', false);
                    $("input[data-subcategory_id="+ category_id +"][data-type=subCategory]").parents('label').addClass('inset-half-checked');
                }
            }
        }

        $('.js-b-is-cheap').removeClass('_active');
        $('.js-b-is-action').removeClass('_active');

        checkCategory($(this).data('category_id'));
        $('input').trigger('refresh');
        $('.content.preloader').show();
        getCheckedCategory()

    });

    function checkCategory(category_id) {
        var subSubCategory = $("input[data-category_id="+ category_id +"][data-type=subSubCategory]");
        var subSubCategoryChecked = $("input[data-category_id="+ category_id +"][data-type=subSubCategory]:checked");
        var parentCheckBox =  $("input[data-category_id="+ category_id +"][data-type=category]");
        parentCheckBox.parents('label').removeClass('inset-half-checked');

        if(!subSubCategoryChecked.length){
            parentCheckBox.prop('checked', false);
        } else {
            if(subSubCategory.length == subSubCategoryChecked.length ){
                parentCheckBox.prop('checked', true);
            } else {
                parentCheckBox.prop('checked', false);
                parentCheckBox.parents('label').addClass('inset-half-checked');
            }
        }
    }



    function checkCategoryFromSearch() {
        $("input[data-category_id="+ search_category +"]").prop('checked', true);
        $("input[data-subcategory_id="+ search_category +"]").prop('checked', true);
        $("input[data-subsubcategory_id="+ search_category +"]").prop('checked', true);

        var currentCategory = $("input[data-subcategory_id="+ search_category +"][data-type=subCategory]");
        //debugger;
        if(currentCategory){

            categoryId = currentCategory.data('category_id');

           var category = $("input[data-category_id="+ categoryId +"][data-type=category]");

            categoryChild = $("input[data-category_id="+ categoryId +"][data-type=subSubCategory]");
            categoryChildChecked = $("input[data-category_id="+ categoryId +"][data-type=subSubCategory]:checked");

            category.prop('checked', false);
            category.parents('label').addClass('inset-half-checked');

            if(!categoryChildChecked.length){
                category.prop('checked', false);
                category.parents('label').removeClass('inset-half-checked');
            }

            if(categoryChild.length == categoryChildChecked.length){
                category.prop('checked', true);
                category.parents('label').removeClass('inset-half-checked');
            }
        }

        restoreFromSearch(search_category);
        checkCategory(search_category);
        $('input').trigger('refresh');

    }


    if(search_category){
        //debugger;
        if( $('.js-b-usd').hasClass('_active') ){
            $('.td-price').removeClass('_byn');
            $('.td-price').addClass('_usd');

        } else{
            $('.td-price').removeClass('_usd');
            $('.td-price').addClass('_byn');
        }

        checkCategoryFromSearch();

    }



    $('.js-main-catalog').on('click', '.a-checkall.check', function (e) {
        $('.content.preloader').show();
        e.preventDefault();
        $('.w-left-catalog > .main-catalog  label').removeClass('inset-half-checked');
        $('.main-catalog input:checkbox').prop('checked', true);
        $('input').trigger('refresh');
        getCheckedCategory()
    });


    $('.js-main-catalog').on('click', '.a-checkall.uncheck', function (e) {
        $('.content.preloader').show();
        e.preventDefault();
        $('.main-catalog input:checkbox').prop('checked', false);
        $('.w-left-catalog > .main-catalog  label').removeClass('inset-half-checked');
        $('input').trigger('refresh');

        localStorage.setItem('category', false);
        localStorage.setItem('subSubCategory', false);

        $('.wrapper.w-table-check-all').hide();
        data = '<p class="change_category"><i>Выберите интересующие вас категории</i></p>';
        $('.wrapper.items-table.catalog-table').html(data);
        $('.content.preloader').hide();

        $('.js-b-is-cheap').removeClass('_active');
        $('.js-b-is-action').removeClass('_active');

        //getCheckedCategory()
    });


    $('body').on('change', 'select[name=catalog_sort]', function (e) {
        $('.content.preloader').show();
        getCheckedCategory()
    });




    function getCheckedCategory() {
        var categories = [];



        $('.wrapper.w-table-check-all').show();
        $('.wrapper.w-table-check-all .toggler-button._plus').show();

        $('.wrapper.w-table-uncheck-all').hide();
        $('.wrapper.w-table-uncheck-all .toggler-button._minus').hide();


        $('input[data-type=category][data-has_sub=true]:checked').each(function (e) {
            categories.push( $(this).data('category_id') );
        });

        var subCategories = [];
        $('input[data-type=subSubCategory]:checked').each(function (e) {
            //if(jQuery.inArray( $(this).data('category_id'), categories ) == -1){
                subCategories.push( $(this).data('subsubcategory_id') );
            //}
        });
        //debugger;
        // Добавляем в сессию
        localStorage.setItem('category', categories);
        localStorage.setItem('subSubCategory', subCategories);



        var options = {};
        options['categories'] = categories;
        options['subCategories'] = subCategories;
        options['sort'] = $('select[name=catalog_sort]').val();
        options['currency'] = 'usd';

        if($('.button.js-b-byn').hasClass('_active')){
            options['currency'] = 'byn';
        }

        resfesh_category_opt = options;


/*
        $.ajax({
            type: 'POST',
            url: "/catalog/refresh",
            data: options,
            success: function (data) {
                //console.log(data);
                if(data == 'false'){
                    $('.wrapper.w-table-check-all').hide();
                    data = '<p class="change_category"><i>Выберите интересующие вас категории</i></p>';

                }
                $('.wrapper.items-table.catalog-table').html(data)
            },
            async:true
        });
        */

    }

    var resfesh_category_opt = '';
    var resfesh_category_opt_old = '';
    function ajax_refresh() {


        if (resfesh_category_opt != '') {
                if (resfesh_category_opt == resfesh_category_opt_old) {
                    return true;
                }
            var categoryId = $('.categoryId').val();
            $(document).on('click','.categoryId', function(){
               return ($(this).val());
            });
            console.log(categoryId)
                $.ajax({
                    type: 'POST',
                    url: "/catalog/refresh",
                    data: {resfesh_category_opt,
                            'categoryId': categoryId},
                    success: function (data) {
                        if(data == 'false'){
                            $('.wrapper.w-table-check-all').hide();
                            data = '<p class="change_category"><i>Выберите интересующие вас категории</i></p>';
                        }
                        $('.wrapper.items-table.catalog-table').html(data);
                        $('.content.preloader').hide();

                        if( $('a.button.js-b-mrp').hasClass('_active')){
                            $('table td.td-price._mrp').addClass('_active')
                        }
                        search_filter(true);
                        //var childPath = "/catalog/";
                        //var pathname = window.location.pathname
                        //var isChildCatalog = pathname.indexOf(childPath) + 1;


                       //if(window.location.pathname == '/catalog/search' || isChildCatalog){

                       //  if(window.location.pathname != '/catalog'){
                       //     history.pushState('Каталог', 'Каталог', '/catalog');
                       // }

                        update_preview();

                        $('div[rel=tipsy]').tipsy({html: true});
/*
                        const template = document.querySelector('.w-hovered-content-image')
                        const clonedTemplate = template.cloneNode(true)

                        const tip =  tippy('.hovered-product', {
                            html: clonedTemplate,
                            arrow: true,
                            animation: 'fade',
                            distance: 15,
                            arrowTransform: 'scale(2)',
                            multiple: true
                        });
                        const el = document.querySelector('.root2')
                        const popper = tip.getPopperElement(el)
                        tip.show(popper)
*/
/*

                        tippy('.hovered-product', {
                            html: clonedTemplate,
                            arrow: true,
                            animation: 'fade',
                            distance: 15,
                            arrowTransform: 'scale(2)',
                            multiple: true
                        })
*/
                        if( $('.js-b-usd').hasClass('_active') ){
                           $('.td-price').removeClass('_byn');
                           $('.td-price').addClass('_usd');

                       } else{
                           $('.td-price').removeClass('_usd');
                           $('.td-price').addClass('_byn');
                       }

                    },
                    async:true
                });

            resfesh_category_opt_old = resfesh_category_opt;
        }

    }

    function update_preview() {
        $('a.hovered-product').each(function() {
            var imagePath = $(this).data('big');
            $(this).qtip({
                content: {
                    text: function(event, api) {
                        content = '<div id="pic_video_block" style="width:240px;height:240px;"><img src="'+api.elements.target.attr('data-big')+'"></div>';
                        return content;
                        api.set('content.text', content);

                        /*
                        $.ajax({
                            type: 'GET',
                            //dataType: 'image/jpg',
                            //url: imagePath // Use href attribute as URL
                            url: '/' // Use href attribute as URL
                        })
                            .then(function(content) {
                                content = '<div id="pic_video_block" style="width:320px;height:240px;"><img src="'+api.elements.target.attr('data-big')+'"></div>';
                                api.set('content.text', content);
                            }, function(xhr, status, error) {
                                // Upon failure... set the tooltip content to error
                                api.set('content.text', status + ': ' + error);
                            });
*/
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

    setInterval(ajax_refresh, 3000);

    // Получает из ls подкатегории и расставляет их.
        function restoreCategoryState() {
        //localStorage.setItem('category', categories);
        if (localStorage.getItem('subSubCategory') !== null && localStorage.getItem('subSubCategory') != '' ) {
            $('.content.preloader').show();
            var subcategories =  localStorage.getItem('subSubCategory').split(',');
            subcategories.forEach(function (subSubCatId) {

                var subSubCat = $("input[data-subsubcategory_id="+ subSubCatId +"][data-type=subSubCategory]").prop('checked', true);

                var subCategoryId = subSubCat.data('subcategory_id');
                var subCategory = $("input[data-subcategory_id="+ subCategoryId +"][data-type=subCategory]");


                // Если род категория есть - тогда проверяем
                if(subCategory){

                    subCategory.prop('checked', false);
                    subCategory.parents('label').addClass('inset-half-checked');

                    // Получаем все где есть эта подкатегория
                    var subCategoryChild = $("input[data-subcategory_id="+ subCategoryId +"][data-type=subSubCategory]");
                    var subCategoryChildChecked = $("input[data-subcategory_id="+ subCategoryId +"][data-type=subSubCategory]:checked");

                    if(!subCategoryChildChecked.length){
                        subCategory.prop('checked', false);
                        subCategory.parents('label').removeClass('inset-half-checked');
                    }

                    if(subCategoryChild.length == subCategoryChildChecked.length){
                        subCategory.prop('checked', true);
                        subCategory.parents('label').removeClass('inset-half-checked');
                    }

                }


                var categoryId = subSubCat.data('category_id');
                var category = $("input[data-category_id="+ categoryId +"][data-type=category]");

                if(category){

                    category.prop('checked', false);
                    category.parents('label').addClass('inset-half-checked');

                    // Получаем все где есть эта подкатегория
                    var categoryChild = $("input[data-category_id="+ categoryId +"][data-type=subSubCategory]");
                    var categoryChildChecked = $("input[data-category_id="+ categoryId +"][data-type=subSubCategory]:checked");

                    if(!categoryChildChecked.length){
                        category.prop('checked', false);
                        category.parents('label').removeClass('inset-half-checked');
                    }

                    if(categoryChild.length == categoryChildChecked.length){
                        category.prop('checked', true);
                        category.parents('label').removeClass('inset-half-checked');
                    }

                }

            });

            $('input').trigger('refresh');
        } else {

        }


    }



    function restoreFromSearch(subSubCatId) {

        var subSubCat = $("input[data-subsubcategory_id="+ subSubCatId +"][data-type=subSubCategory]").prop('checked', true);

        var subCategoryId = subSubCat.data('subcategory_id');
        var subCategory = $("input[data-subcategory_id="+ subCategoryId +"][data-type=subCategory]");


        // Если род категория есть - тогда проверяем
        if(subCategory){

            subCategory.prop('checked', false);
            subCategory.parents('label').addClass('inset-half-checked');

            // Получаем все где есть эта подкатегория
            var subCategoryChild = $("input[data-subcategory_id="+ subCategoryId +"][data-type=subSubCategory]");
            var subCategoryChildChecked = $("input[data-subcategory_id="+ subCategoryId +"][data-type=subSubCategory]:checked");

            if(!subCategoryChildChecked.length){
                subCategory.prop('checked', false);
                subCategory.parents('label').removeClass('inset-half-checked');
            }

            if(subCategoryChild.length == subCategoryChildChecked.length){
                subCategory.prop('checked', true);
                subCategory.parents('label').removeClass('inset-half-checked');
            }

        }


        var categoryId = subSubCat.data('category_id');
        var category = $("input[data-category_id="+ categoryId +"][data-type=category]");

        if(category){

            category.prop('checked', false);
            category.parents('label').addClass('inset-half-checked');

            // Получаем все где есть эта подкатегория
            var categoryChild = $("input[data-category_id="+ categoryId +"][data-type=subSubCategory]");
            var categoryChildChecked = $("input[data-category_id="+ categoryId +"][data-type=subSubCategory]:checked");

            if(!categoryChildChecked.length){
                category.prop('checked', false);
                category.parents('label').removeClass('inset-half-checked');
            }

            if(categoryChild.length == categoryChildChecked.length){
                category.prop('checked', true);
                category.parents('label').removeClass('inset-half-checked');
            }
        }


        var categories = [];
        $('input[data-type=category][data-has_sub=false]:checked').each(function (e) {
            categories.push( $(this).data('category_id') );
        });

        var subCategories = [];
        $('input[data-type=subSubCategory]:checked').each(function (e) {
            if(jQuery.inArray( $(this).data('category_id'), categories ) == -1){
                subCategories.push( $(this).data('subsubcategory_id') );
            }
        });

        // Добавляем в сессию

        localStorage.setItem('category', categories);
        localStorage.setItem('subSubCategory', subCategories);

    }


    if(without_refresh){
        $('.content.preloader').hide();
    }

    if(use_local_storage){
        restoreCategoryState();
        getCheckedCategory()
    }

    /**
     * Открыть в новом окне.
     */

    $('body').on('click', '.js-open-window', function (e) {
        e.preventDefault();
        //console.log($(this).prop('href'));
        open_window('item', $(this).prop('href'), 50, 60, 1000, 750, 0, 0, 0, 0, 0)
    });


    function open_window(name, url, left, top, width, height, toolbar, menubar, statusbar, scrollbar, resizable)
    {
        toolbar_str = toolbar ? 'yes' : 'no';
        menubar_str = menubar ? 'yes' : 'no';
        statusbar_str = statusbar ? 'yes' : 'no';
        scrollbar_str = scrollbar ? 'yes' : 'no';
        resizable_str = resizable ? 'yes' : 'no';
        window.open(url, name, 'left='+left+',top='+top+',width='+width+',height='+height+',toolbar='+toolbar_str+',menubar='+menubar_str+',status='+statusbar_str+',scrollbars='+scrollbar_str+',resizable='+resizable_str);
    }


    /**
     * FIX: Дубль в main.js
     */
    var search_filter_old = '';
    var search_filter_action = 0;
    var search_filter_cheap = 0;


    function search_filter(refresh){
        refresh = refresh || false;
        var filter_word = $('input[name=filter_word]').val();
        var filter_action = ($('.js-b-is-action').hasClass('_active'))?1:0;
        var filter_cheap = ($('.js-b-is-cheap').hasClass('_active'))?1:0;


        filter_word = filter_word.toLowerCase();

        // Если ничего не изменилось
        if(filter_word == search_filter_old && search_filter_action == filter_action && search_filter_cheap == filter_cheap && refresh == false){
            return false;
        }

        $('.td-name').each(function (e) {
            var code_value = $(this).data('name');
            var td_tag = $(this).parents('tr');
            if(code_value != undefined && filter_word != undefined && filter_cheap != undefined){
                code_value = code_value.toLowerCase();
                var is_show = 0;
                var is_show_action = 0;
                var is_show_cheap = 0;

                if(~code_value.indexOf(filter_word)){
                    is_show = 1;

                    //  if ( (td_tag.data('action') != filter_action && filter_action != 0) || (td_tag.data('cheap') != filter_cheap && filter_cheap != 0) ) {
                    //      is_show = 0
                    //  }


                    if(filter_action || filter_cheap){
                        if (td_tag.data('action') != filter_action && filter_action != 0) {
                            is_show_action = 1;
                        }

                        if ((td_tag.data('cheap') != filter_cheap && filter_cheap != 0 )) {
                            is_show_cheap = 1;
                        }
                    }

                    if(filter_action && filter_cheap){
                        if (is_show_cheap && is_show_action) {
                            is_show = 0;
                        }
                    } else {
                        if (is_show_cheap || is_show_action) {
                            is_show = 0;
                        }
                    }

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

                if (td_tag.data('action') == filter_action && filter_action != 0) {
                    is_action_show = 1
                }


                if ((td_tag.data('cheap') == filter_cheap && filter_cheap != 0)) {
                    is_cheap_show = 1
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
        search_filter_action = filter_action;
        search_filter_cheap = filter_cheap;

    }

    $('.js-main-catalog').on('click', '.js-all-actions', function (e) {
        e.preventDefault();
        $('.content.preloader').show();

        $('.js-b-is-cheap').addClass('_active');
        $('.js-b-is-action').addClass('_active');

        search_filter_action = 0;
        search_filter_cheap = 0;

        $('.w-left-catalog > .main-catalog  label').removeClass('inset-half-checked');
        $('.main-catalog input:checkbox').prop('checked', true);
        $('input').trigger('refresh');
        getCheckedCategory()
    });

    $('body').on('click', '.toggler-buttonfor-cart', function (e) {
        $(this).toggleClass('_minus');
        var categoryId = $(this).data('category');
        $('.category-id-'+categoryId).each(function () {
            $(this).toggleClass('_disable')
        });
    });
});

