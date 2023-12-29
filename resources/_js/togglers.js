$(document).ready(function() {

    // Скрипт для отображения-скрытия аналогов
    $('.js-spare-analogs').click(function() {
        var item_id = $(this).data('id');
        console.log(item_id);
        $('.js-item-' + item_id).slideToggle('200');
    });

    // Скрипт для отображения-скрытия уцененных товаров
    $('.js-down').click(function() {
        var parent_id = $(this).data('parent_id');
        console.log(parent_id);
        $('.js-chip-' + parent_id).css('display', 'block');
        $(this).css('display', 'none');
        $('.js-chip-up-' + parent_id).css('display', 'block');
    });
    $('.js-up').click(function() {
        var parent_id = $(this).data('parent_id');
        $('.js-chip-' + parent_id).css('display', 'none');
        $(this).css('display', 'none');
        $('.js-chip-down-' + parent_id).css('display', 'block');
    });

});
