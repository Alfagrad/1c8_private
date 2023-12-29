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
            console.log(category_id);

            $("input[data-subcategory_id="+ category_id +"]").parents('label').removeClass('inset-half-checked');

            if($(this).prop('checked')){
                $("input[data-subcategory_id="+ category_id +"]").prop('checked', true);
            } else {
                $("input[data-subcategory_id="+ category_id +"]").prop('checked', false);
            }

            console.log($("input[data-subcategory_id="+ category_id +"][type=subSubCategory]"));

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
       /*
        if( $('.js-b-usd').hasClass('_active') ){
            $('.td-price').removeClass('_byn');
            $('.td-price').addClass('_usd');

        } else{
            $('.td-price').removeClass('_usd');
            $('.td-price').addClass('_byn');
        }
*/

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

//        console.log(categories);
//        console.log(subCategories);
    }

    var resfesh_category_opt = '';
    var resfesh_category_opt_old = '';
    function ajax_refresh() {
        console.log(123);
        if(resfesh_category_opt != ''){
            if( resfesh_category_opt == resfesh_category_opt_old){
                return true;
            }
                $.ajax({
                    type: 'POST',
                    url: "/catalog/refresh",
                    data: resfesh_category_opt,
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

                        //var childPath = "/catalog/";
                        //var pathname = window.location.pathname
                        //var isChildCatalog = pathname.indexOf(childPath) + 1;


                       //if(window.location.pathname == '/catalog/search' || isChildCatalog){
                       if(window.location.pathname != '/catalog'){
                           console.log(window.location.pathname);
                           console.log(window.location.hostname);
                           history.pushState('Каталог', 'Каталог', '/catalog');
                       }
                       // if( $('.js-b-usd').hasClass('_active') ){
                       //     $('.td-price').removeClass('_byn');
                       //     $('.td-price').addClass('_usd');
                       //
                       // } else{
                       //     $('.td-price').removeClass('_usd');
                       //     $('.td-price').addClass('_byn');
                       // }
                        
                    },
                    async:true
                });

            resfesh_category_opt_old = resfesh_category_opt;
        }

    }

    setInterval(ajax_refresh, 3000);

    // Получает из ls подкатегории и расставляет их.
        function restoreCategoryState() {
        //localStorage.setItem('category', categories);
        console.log(localStorage.getItem('subSubCategory') == '');
        if (localStorage.getItem('subSubCategory') !== null && localStorage.getItem('subSubCategory') != '' ) {
            $('.content.preloader').show();
            var subcategories =  localStorage.getItem('subSubCategory').split(',');
            console.log(localStorage.getItem('subSubCategory'));
            subcategories.forEach(function (subSubCatId) {

                console.log('Категория из LS', subSubCatId);
                var subSubCat = $("input[data-subsubcategory_id="+ subSubCatId +"][data-type=subSubCategory]").prop('checked', true);

                var subCategoryId = subSubCat.data('subcategory_id');
                var subCategory = $("input[data-subcategory_id="+ subCategoryId +"][data-type=subCategory]");

                console.log('Родительская категория', subCategoryId);

                // Если род категория есть - тогда проверяем
                if(subCategory){

                    subCategory.prop('checked', false);
                    subCategory.parents('label').addClass('inset-half-checked');

                    // Получаем все где есть эта подкатегория
                    var subCategoryChild = $("input[data-subcategory_id="+ subCategoryId +"][data-type=subSubCategory]");
                    console.log('Количество дочерних', subCategoryChild.length );
                    var subCategoryChildChecked = $("input[data-subcategory_id="+ subCategoryId +"][data-type=subSubCategory]:checked");
                    console.log('Количество дочерних checked', subCategoryChildChecked.length );

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
                console.log('Коренная категория', category);

                if(category){

                    category.prop('checked', false);
                    category.parents('label').addClass('inset-half-checked');

                    // Получаем все где есть эта подкатегория
                    var categoryChild = $("input[data-category_id="+ categoryId +"][data-type=subSubCategory]");
                    console.log('Количество дочерних', categoryChild.length );
                    var categoryChildChecked = $("input[data-category_id="+ categoryId +"][data-type=subSubCategory]:checked");
                    console.log('Количество дочерних checked', categoryChildChecked.length );

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

        console.log('Категория из LS', subSubCatId);
        var subSubCat = $("input[data-subsubcategory_id="+ subSubCatId +"][data-type=subSubCategory]").prop('checked', true);

        var subCategoryId = subSubCat.data('subcategory_id');
        var subCategory = $("input[data-subcategory_id="+ subCategoryId +"][data-type=subCategory]");

        console.log('Родительская категория', subCategoryId);

        // Если род категория есть - тогда проверяем
        if(subCategory){

            subCategory.prop('checked', false);
            subCategory.parents('label').addClass('inset-half-checked');

            // Получаем все где есть эта подкатегория
            var subCategoryChild = $("input[data-subcategory_id="+ subCategoryId +"][data-type=subSubCategory]");
            console.log('Количество дочерних', subCategoryChild.length );
            var subCategoryChildChecked = $("input[data-subcategory_id="+ subCategoryId +"][data-type=subSubCategory]:checked");
            console.log('Количество дочерних checked', subCategoryChildChecked.length );

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
        console.log('Коренная категория', category);

        if(category){

            category.prop('checked', false);
            category.parents('label').addClass('inset-half-checked');

            // Получаем все где есть эта подкатегория
            var categoryChild = $("input[data-category_id="+ categoryId +"][data-type=subSubCategory]");
            console.log('Количество дочерних', categoryChild.length );
            var categoryChildChecked = $("input[data-category_id="+ categoryId +"][data-type=subSubCategory]:checked");
            console.log('Количество дочерних checked', categoryChildChecked.length );

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
        console.log(categories);
        console.log(subCategories);

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



});