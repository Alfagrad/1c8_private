$(document).ready(function(){

    console.log('выбираем категории для прайса');

    // открываем-скрываем подкатегории
    $('.js-arrow').click(function(e){

        // запрещаем действие по умолчанию
        e.preventDefault();

        // открываем-скрываем подкатегории
        $(this).parents('.js-category-block').find('.js-sub-category-block').slideToggle();

        // добавляем класс к кнопке
        $(this).toggleClass('active');
    });

    // чекбокс ВСЕ
    $('.js-all-category').click(function(){

        // если включен
        if($('input[name=all]').prop('checked')) {
            // чекаем все категории
            $('.js-for-all').prop('checked', true);
        } else {
            // снимаем чек со всех категорий
            $('.js-for-all').prop('checked', false);
        }
    });

    // чекбокс главных категорий
    $('.js-category').click(function(){
        // если включен
        if($(this).find('input').prop('checked')) {
            // чекаем все категории
            $(this).parents('.js-category-block').find('.js-for-category').prop('checked', true);
        } else {
            // снимаем чек со всех категорий
            $(this).parents('.js-category-block').find('.js-for-category').prop('checked', false);
        }

        // // собираем чекнутые инпуты категорий
        // var inputs = $(this).parents('.js-all-category-block').find('.js-category input:checked');

        // // если есть
        // if(inputs.length) {
        //     // чекаем категорию
        //     $(this).parents('.js-all-category-block').find('.js-all-category input').prop('checked', true);
        // } else {
        //     // снимаем чек с под-категории
        //     $(this).parents('.js-all-category-block').find('.js-all-category input').prop('checked', false);
        // }

    });

    // чекбокс под-категорий
    $('.js-sub-category').click(function(){
        // если включен
        if($(this).find('input').prop('checked')) {
            // чекаем все категории
            $(this).parents('.js-sub-category-block').find('.js-for-sub-category').prop('checked', true);
        } else {
            // снимаем чек со всех категорий
            $(this).parents('.js-sub-category-block').find('.js-for-sub-category').prop('checked', false);
        }

        // // собираем чекнутые инпуты под-категорий
        // var sub_inputs = $(this).parents('.js-category-block').find('.js-sub-category input:checked');

        // // если есть
        // if(sub_inputs.length) {
        //     // чекаем категорию
        //     $(this).parents('.js-category-block').find('.js-category input').prop('checked', true);
        // } else {
        //     // снимаем чек с под-категории
        //     $(this).parents('.js-category-block').find('.js-category input').prop('checked', false);
        // }

        // // собираем чекнутые инпуты категорий
        // var inputs = $(this).parents('.js-all-category-block').find('.js-category input:checked');

        // // если есть
        // if(inputs.length) {
        //     // чекаем категорию
        //     $(this).parents('.js-all-category-block').find('.js-all-category input').prop('checked', true);
        // } else {
        //     // снимаем чек с под-категории
        //     $(this).parents('.js-all-category-block').find('.js-all-category input').prop('checked', false);
        // }

    });

    // чекбокс под-под-категорий
    $('.js-sub-sub-category').click(function(){

        // если включен
        if($(this).find('input').prop('checked')) {
            // чекаем все категории
            $(this).parents('.js-sub-sub-category-block').find('.js-for-sub-sub-category').prop('checked', true);
        } else {
            // снимаем чек со всех категорий
            $(this).parents('.js-sub-sub-category-block').find('.js-for-sub-sub-category').prop('checked', false);
        }

        // // собираем чекнутые инпуты под-под-категорий
        // var sub_sub_inputs = $(this).parents('.js-sub-category-block').find('.js-sub-sub-category input:checked');

        // // если есть
        // if(sub_sub_inputs.length) {
        //     // чекаем под-категорию
        //     $(this).parents('.js-sub-category-block').find('.js-sub-category input').prop('checked', true);
        // } else {
        //     // снимаем чек с под-категории
        //     $(this).parents('.js-sub-category-block').find('.js-sub-category input').prop('checked', false);
        // }

        // // собираем чекнутые инпуты под-категорий
        // var sub_inputs = $(this).parents('.js-category-block').find('.js-sub-category input:checked');

        // // если есть
        // if(sub_inputs.length) {
        //     // чекаем категорию
        //     $(this).parents('.js-category-block').find('.js-category input').prop('checked', true);
        // } else {
        //     // снимаем чек с под-категории
        //     $(this).parents('.js-category-block').find('.js-category input').prop('checked', false);
        // }

        // // собираем чекнутые инпуты категорий
        // var inputs = $(this).parents('.js-all-category-block').find('.js-category input:checked');

        // // если есть
        // if(inputs.length) {
        //     // чекаем категорию
        //     $(this).parents('.js-all-category-block').find('.js-all-category input').prop('checked', true);
        // } else {
        //     // снимаем чек с под-категории
        //     $(this).parents('.js-all-category-block').find('.js-all-category input').prop('checked', false);
        // }

    });

});

