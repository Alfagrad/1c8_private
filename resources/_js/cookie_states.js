if(!$.cookie('opt_state') || !$.cookie('purcent_state') || !$.cookie('mr_state')) {
    if(!$.cookie('opt_state')) {
        $.cookie('opt_state', 1, {expires: 7, path: '/'});
    }
    if(!$.cookie('purcent_state')) {
        $.cookie('purcent_state', 1, {expires: 7, path: '/'});
    }
    if(!$.cookie('mr_state')) {
        $.cookie('mr_state', 1, {expires: 7, path: '/'});
    }
    // перезагрузка
    document.location.reload(true);
}

// начальные установки куки для корзины товаров
if(!$.cookie('cart_id')) {
    $.cookie('cart_id', 0, {expires: 1, path: '/'});
    // перезагрузка
    document.location.reload(true);
}
