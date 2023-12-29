<?php

Route::get('/login', 'RegController@login')->name('login');
Route::post('/login', 'RegController@postLogin')->name('login.post');

Route::get('/registration', 'RegController@registration')->name('registrationView');
Route::post('/registration', 'RegController@register');
Route::post('/registration/manager', 'RegController@toManager');

Route::get('/remember', 'RegController@remember');
Route::get('/logout', 'RegController@logout')->name('logout');
Route::post('/remember/restore', 'RegController@restore')->name('rememberRestore');

Route::get('/registration/accept/{profileId}', 'RegController@accept')->name('regAccept');
Route::get('/registration/refuse/{profileId}', 'RegController@refuse')->name('regRefuse');
Route::post('/registration/check/email', 'RegController@checkRegData')->name('regCheck');


// маршруты открытой части
Route::get('/', 'MainPageController@index')->name('open');
Route::get('/for-dealers', 'DealerPageController@index')->name('dealer.open');
Route::get('/brands', \App\Http\Controllers\Brand\Open::class)->name('brand.open');
Route::get('/services', 'ServicePageController@index')->name('service.open');
Route::get('/service-documents', 'ServicePageController@serviceDocs')->name('service.documents');
Route::get('/erip-pay', 'ServicePageController@eripPay')->name('erip.open');
Route::get('/repair-status', 'ServicePageController@repairStatus')->name('repair.status');
Route::post('/get-repair', 'ServicePageController@ajaxGetRepair');

Route::get('/contacts', \App\Http\Controllers\Contact\Open::class)->name('contact.open');
Route::get('/vacancy', \App\Http\Controllers\Vacancy\Open::class)->name('vacancy.open');
Route::post('/mail-to-us', 'FeedbackController@index')->name('openFeedback');

Route::group(['prefix' => 'price'], function () {
    Route::get('index', \App\Http\Controllers\Price\Index::class)->name('price.index');
    Route::post('download', \App\Http\Controllers\Price\Download::class)->name('price.download');
});





// маршруты закрытой части
Route::group(['middleware' => ['check.role']], function () {
    Route::get('/home', 'HomeController@index');

    Route::get('/pages/repair-status', 'ServicePageController@repairStatus')->name('closeRepairStatus');
    Route::get('/pages/{alias}', 'PagesController@show')->name('pages');

    Route::get('/guides', 'PagesController@guides')->name('guides');
    Route::get('/certificates', 'PagesController@certificates')->name('certificates');

//    Route::get('/catalog', 'CatalogController@index')->name('catalogView');
//    Route::get('/catalog/search', 'CatalogController@search')->name('catalogSearch');
//    Route::post('/catalog/refresh', 'CatalogController@refresh')->name('catalogRefresh');
//    Route::get('/catalog/{id}', 'CatalogController@currentCategory')->name('catalogCurrentCategory');
//    Route::get('/catalogprof', 'CatalogController@profile');
//    Route::get('/catalog/item/{itemId}', 'CatalogController@item')->name('itemVies');
//
//    Route::get('/new-items', 'CatalogController@newItems')->name('newItems');
//    Route::get('/all-actions', 'CatalogController@allActions')->name('allActions');

    // Route::post('/profile/update', 'ProfileController@profileUpdate')->name('profileUpdate');
    Route::post('/profile/repairs/refresh', 'ProfileController@repairsRefresh')->name('repairsRefresh');
    // Route::post('/profile/address/add', 'ProfileController@addressAdd')->name('profileAddressAdd');


    Route::group(['prefix' => 'catalogue'], function () {
        Route::get('/new-items', \App\Http\Controllers\Catalogue\NewItems::class)->name('catalogue.newItems');
        Route::get('/all-actions', \App\Http\Controllers\Catalogue\Actions::class)->name('catalogue.actions');

        Route::get('/single-power', \App\Http\Controllers\Catalogue\SinglePower::class)->name('catalogue.singlePower');
        Route::get('/discounted', \App\Http\Controllers\Catalogue\Discounted::class)->name('catalogue.discounted');
        Route::get('/season-items', \App\Http\Controllers\Catalogue\Season::class)->name('catalogue.season');
        Route::get('/new-year', \App\Http\Controllers\Catalogue\NewYear::class)->name('catalogue.newYear');

        Route::get('/search', \App\Http\Controllers\Catalogue\Search::class)->name('catalogue.search');
//        Route::get('/search', 'NewCatalogController@search')->name('newItemVies');
        Route::get('/{id?}', \App\Http\Controllers\Catalogue\Index::class)->name('catalogue.index')->where('id', '[0-9]+');
    });

    Route::get('/item/{itemId}', \App\Http\Controllers\Item\Index::class)->name('item.index');

    Route::group(['prefix' => 'cart-page'], function () {
        Route::get('', 'NewCartController@index')->name('newCartView');
//        Route::post('/add-cart', 'NewCartController@addCart');
//        // Route::post('/cart-del', 'NewCartController@deleteCart');
//        Route::get('/del-cart/{cart_id}', 'NewCartController@deleteCart');
//        Route::post('/relocate-items', 'NewCartController@relocateItems');
//        Route::post('/copy-items', 'NewCartController@copyItems');
//        Route::post('/delete-items', 'NewCartController@deleteItems');
//        Route::post('/del-item', 'NewCartController@delItem');
//        Route::post('/swapping', 'NewCartController@swapItems');
//        Route::post('/empty-cart', 'NewCartController@emptyCart');
//        Route::post('/empty-all-carts', 'NewCartController@emptyAllCarts');
//        Route::post('/update-cart', 'NewCartController@updateCart');
//        Route::post('/order-create', 'NewCartController@orderCreate');
        Route::get('/toggleService', 'NewCartController@toggleService')->name('cart.toggleService');
    });

    Route::get('/service-cart-page', 'ServiceCartController@index')->name('serviceCartView');
    Route::post('/new-repair', 'ServiceCartController@createRepair');


    Route::resource('news', \App\Http\Controllers\NewsController::class)->parameters(['news' => 'news:alias'])->only(['index', 'show']);
//    Route::get('/news', 'NewsController@index')->name('listNews');
//    Route::get('/news/{alias}', 'NewsController@show')->name('oneNews');
    Route::get('/reviews', 'ReviewsController@index')->name('listReviews');
    Route::get('/reviews/{alias}', 'ReviewsController@show')->name('oneReview');

    Route::group(['prefix' => 'profile'], function (){
        Route::get('/index', 'ProfileController@index')->name('profileIndex');
        Route::get('/orders', 'ProfileController@orders')->name('profileOrders');
        Route::get('/repairs', 'ProfileController@repairs')->name('profileRepairs');
        Route::get('/subscribes', 'ProfileController@subscribes')->name('profileSubscribes');
        Route::post('/subscribes/save', 'ProfileController@subscribesSave')->name('profileSubscribesSave');
    });

    Route::get('/svetik', 'Service\ServiceController@сonclusionPrint')->name('conclusionPrint');
    Route::post('/svetik/search-conclusion', 'Service\ServiceController@conclusionSearch')->name('conclusionSearch');



// *******************************************************************************************

    Route::get('/pricetag/form/{itemId}', 'PricetagController@pricetag')->name('pricetag');
    Route::post('/pricetag/download/{itemId}', 'PricetagController@pdfDownload')->name('pdfDownload');

    Route::post('/changeItemCart', 'CartController@changeItemCart');
    Route::post('/changeItemCartWithMain', 'CartController@changeItemCartWithMain');

    Route::get('/cart/{id?}', 'CartController@index')->name('cartView');
    Route::get('/cart/clear/{id?}', 'CartController@clear')->name('cartClear');
    Route::post('/cart/update/{id?}', 'CartController@update')->name('cartUpdate');
    Route::post('/cart/delete/{id?}', 'CartController@delete')->name('cartDelete');
    Route::post('/cart/addCart/', 'CartController@addCart')->name('addCart');
    Route::post('/cart/deleteFew', 'CartController@deleteFew');
    Route::get('/cart/deleteCart/{id}/{main?}', 'CartController@deleteCart');

    Route::post('/order/create', [\App\Http\Controllers\CartController::class, 'createOrder'])->name('createOrder');


    Route::post('/search', 'HomeController@search')->name('search');
    Route::post('/ajax-search', [\App\Http\Controllers\AjaxSearchController::class, 'ajaxSearch'])->name('ajax-search');

    Route::post('/ajax/get_discount', 'HomeController@getDiscount')->name('getAjaxDiscount');
    Route::post('/ajax/update_password', 'HomeController@updatePassword')->name('updatePassword');
    Route::get('/price', 'HomeController@excelPrice')->name('getPrice');

    // Route::get('/clear', function() {
    //     Artisan::call('cache:clear');
    //     Artisan::call('config:cache');
    //     Artisan::call('view:clear');
    //     Artisan::call('route:clear');
    //     Artisan::call('backup:clean');
    //     return "Кэш очищен.";
    // });

    // Route::get('/managers', 'ManagerController@index'); // для добавление помощников. Только в dev!!!

    // // маршруты страниц сервис-центра
    // Route::post('/order/edit-pic', 'CartController@serviceEditPic')->name('service-edit-pic');
    // Route::post('/order/del-pic', 'CartController@serviceDeletePic')->name('service-del-pic');

     Route::group(['namespace' => 'Service', 'prefix' => 'service'], function() {
    //     Route::get('/', 'MainPageController@getIndex')->name('service-main-page');
    //     Route::get('/delivery', 'DeliveryPageController@index');
    //     Route::get('/{id}', 'MainPageController@currentCategory')->name('service-category')->where('id', '[0-9]+');
    //     Route::get('/item/{itemId}', 'MainPageController@item')->name('service-item-view');
    //     Route::get('/search', 'MainPageController@search')->name('service-search');
    //     Route::post('/refresh', 'MainPageController@refresh')->name('catalogRefresh');

    //     Route::post('/make-conclusion', 'ServiceController@makeConclusion');
    //     Route::post('/conclusion/save', 'ServiceController@saveConclusion')->name('save-conclusion');
    //     Route::post('/edit-conclusion', 'ServiceController@editConclusion');
    //     Route::post('/conclusion/update', 'ServiceController@updateConclusion')->name('update-conclusion');
    //     Route::post('/download-conclusion', 'ServiceController@downloadConclusion');

    //     Route::post('/make-act', 'ServiceController@makeAct');
    //     Route::post('/act/save', 'ServiceController@saveAct')->name('save-act');
    //     Route::post('/edit-act', 'ServiceController@editAct');
    //     Route::post('/act/update', 'ServiceController@updateAct')->name('update-act');
    //     Route::post('/download-act', 'ServiceController@downloadAct');

         Route::get('/item-search', [\App\Http\Controllers\Service\ItemSearchController::class, 'postIndex']);

     });

});

// Скачать прайс по прямой ссылке
Route::get('/direct/price', 'HomeController@excelPriceFromDirect')->name('getPriceFromHash');
Route::get('/direct/price_z', 'HomeController@excelPriceFromDirectZoomas')->name('getPriceFromHashZoomas');

//Получить запчасти ajax
Route::post('/spares_search', 'CatalogController@spares_search'); // для опта
Route::post('/srv_spares_search', 'Service\MainPageController@spares_search'); // для сервиса


Route::get('/check', 'CheckStatusController@index')->name('CheckStatus');



Route::get('/profile/accept/{profileId}', 'ProfileController@accept')->name('profileAccept');
Route::get('/profile/refuse/{profileId}', 'ProfileController@refuse')->name('profileRefuse');

Route::post('/email/manager', 'HomeController@emailFeedback')->name('emailFeedback');





/**
 * Роуты API
 */

Route::group(['prefix' => 'api/v1'], function() {
    Route::post('/items/updateCount', 'Api\ItemController@postCreateOrUpdatePartial');

    Route::post('/category/createOrUpdate', 'Api\CategoryController@postCreateOrUpdate');
    Route::post('/category/delete', 'Api\CategoryController@postDelete');
    Route::post('/category/truncate', 'Api\CategoryController@postTruncate');
    Route::post('/items/createOrUpdate', 'Api\ItemController@postCreateOrUpdate');
    Route::post('/items/updatePrice', 'Api\ItemController@postUpdatePrice');
    Route::post('/items/delete', 'Api\ItemController@postDelete');
    Route::post('/items/truncate', 'Api\ItemController@postTruncate');

    Route::post('/users/createOrUpdate', 'Api\UserController@postCreateOrUpdate');
    Route::post('/users/blocked', 'Api\UserController@postBlock');
    Route::post('/users/unblocked', 'Api\UserController@postUnBlock');

    Route::post('/users/show/new', 'Api\UserController@postNewUser');

    Route::post('/orders/show/new', 'Api\OrderController@postNewOrder');

    Route::post('/repairs/createOrUpdate', 'Api\RepairController@postCreateOrUpdate');
    Route::post('/repairs/delete', 'Api\RepairController@postDelete');
    Route::post('/repairs/truncate', 'Api\RepairController@postTruncate');

    Route::post('/depts/createOrUpdate', 'Api\DeptController@postCreateOrUpdate');

    Route::post('/depts/delete', 'Api\DeptController@postDelete');

    Route::post('/depts/truncate', 'Api\DeptController@postTruncate');

    Route::post('/currency/createOrUpdate', 'Api\CurrencyController@postCreateOrUpdate');

    Route::post('/brand/createOrUpdate', 'Api\BrandController@postCreateOrUpdate');
    Route::post('/brand/delete', 'Api\BrandController@postDelete');
    Route::post('/brand/truncate', 'Api\BrandController@postTruncate');
});

Route::group(['prefix' => 'api/v1', 'middleware' => ['check.admin']], function() {

    Route::get('/category/createOrUpdate', 'Api\CategoryController@getCreateOrUpdate');
    Route::get('/category/delete', 'Api\CategoryController@getDelete');
    Route::get('/category/truncate', 'Api\CategoryController@getTruncate');

    Route::get('/items/createOrUpdate', 'Api\ItemController@getCreateOrUpdate');
    Route::get('/items/updatePrice', 'Api\ItemController@getUpdatePrice');
    Route::get('/items/delete', 'Api\ItemController@getDelete');
    Route::get('/items/truncate', 'Api\ItemController@getTruncate');

    Route::get('/users/createOrUpdate', 'Api\UserController@getCreateOrUpdate');
    Route::get('/users/blocked', 'Api\UserController@getBlock');
    Route::get('/users/unblocked', 'Api\UserController@getUnBlock');

    Route::get('/users/show/new', 'Api\UserController@getNewUser');

    Route::get('/orders/show/new', 'Api\OrderController@getNewOrder');

    // Подделка под пост
    Route::get('/users/show/post/new/', 'Api\UserController@postNewUser');
    Route::get('/orders/show/post/new', 'Api\OrderController@postNewOrder');

    Route::get('/repairs/createOrUpdate', 'Api\RepairController@getCreateOrUpdate');
    Route::get('/repairs/delete', 'Api\RepairController@getDelete');
    Route::get('/repairs/truncate', 'Api\RepairController@getTruncate');

    Route::get('/depts/createOrUpdate', 'Api\DeptController@getCreateOrUpdate');

    Route::get('/depts/delete', 'Api\DeptController@getDelete');

    Route::get('/depts/truncate', 'Api\DeptController@getTruncate');


    Route::get('/currency/createOrUpdate', 'Api\CurrencyController@getCreateOrUpdate');

    Route::get('/brand/createOrUpdate', 'Api\BrandController@getCreateOrUpdate');
    // Route::get('/brand/delete', 'Api\BrandController@getDelete');
    // Route::get('/brand/truncate', 'Api\BrandController@getTruncate');

});

/**
 * Сервисные роуты, для временного использования
 */

Route::get('/test/enterAsUser', 'HomeController@enterFromUser');

Route::get('/shops', 'GoogleSheetController@shops');
Route::get('/shopsvec', 'GoogleSheetController@index');
//Route::group(['prefix' => 'admin'], function () {
//    Voyager::routes();
//});

// Route::get('/google', 'NewGoogleSheetController@create_google');


// Route::get('/scan', 'ResizeController@scan');
// Route::get('/resizeHandle', 'ResizeController@resizeHandle');
Route::get('/img-first-resize', 'ImageResizeController@first'); // только для первого использования!
Route::get('/img-resize', 'ImageResizeController@index');


Route::get('/analog', 'AnalogListController@index');

Route::get('/orders-resend', 'OrderResendController@resendFromDB');






Route::group(['prefix' => 'api/v2'], function() {

    Route::post('/category/createorupdate', 'Api_1c8\CategoryController@postCreateOrUpdate');
    Route::post('/category/delete', 'Api_1c8\CategoryController@postDelete');

    Route::post('/product/createorupdate', 'Api_1c8\ItemController@postCreateOrUpdate');
    Route::post('/product/delete', 'Api_1c8\ItemController@postDelete');
    Route::post('/product/price', 'Api_1c8\ItemController@postAddPrice');
    Route::post('/product/amount', 'Api_1c8\ItemController@postAddCount');
    Route::post('/files/upload', 'Api_1c8\ItemController@itemFiles');

    Route::post('/brands/createorupdate', 'Api_1c8\BrandController@postCreateOrUpdate');

    Route::post('/characteristics/createorupdate', 'Api_1c8\CharacteristicController@postCreateOrUpdate');

    Route::post('/analogs/createorupdate', 'Api_1c8\SparePartController@postAnalogueCreateOrUpdate');

    Route::post('/scheme/createorupdate', 'Api_1c8\SparePartController@postSchemeCreateOrUpdate');

    Route::post('/users/delete', 'Api_1c8\UserController@deleteUser');
    Route::post('/users/createorupdate', 'Api_1c8\UserController@postUserUpdate');
    Route::post('/employee/createorupdate', 'Api_1c8\UserController@postManagerCreateOrUpdate');
    Route::post('/users/blocked', 'Api_1c8\UserController@blockUser');

    Route::post('/partners/createorupdate', 'Api_1c8\PartnerController@postCreateOrUpdate');
    Route::post('/partners/delete', 'Api_1c8\PartnerController@postDelete');

    Route::post('/agreement/createorupdate', 'Api_1c8\AgreementController@postCreateOrUpdate');
    Route::post('/agreement/delete', 'Api_1c8\AgreementController@deleteAgreement');

    Route::post('/debt/createorupdate', 'Api_1c8\DebtController@postCreateOrUpdate');

    Route::post('/discounts/createorupdate', 'Api_1c8\DiscountController@discountCreateOrUpdate');
    Route::post('/discounts/agreements', 'Api_1c8\DiscountController@agreementCreateOrUpdate');
    Route::post('/discounts/products', 'Api_1c8\DiscountController@productCreateOrUpdate');
    // Route::post('/discounts/delete', 'Api_1c8\DiscountController@deleteDiscount');

    // синхронизация
    Route::post('/sync/delete', 'Api_1c8\CategoryController@deleteItems');
    Route::post('/sync/categories', 'Api_1c8\CategoryController@syncCategories');
    Route::post('/sync/products', 'Api_1c8\ItemController@syncItems');
    Route::post('/currency', 'Api_1c8\ItemController@currency');
    Route::post('/sync/files', 'Api_1c8\ItemController@syncFiles');
    Route::post('/sync/brands', 'Api_1c8\BrandController@firstUpload');
    Route::post('/sync/characteristics', 'Api_1c8\CharacteristicController@firstUpload');
    Route::post('/sync/schemes', 'Api_1c8\SparePartController@sсhemesFirstUpload');
    Route::post('/sync/analogs', 'Api_1c8\SparePartController@analogsFirstUpload');

    // новое

    Route::post('/sync/debts', 'Api_1c8\DebtController@postTruncate');
    Route::post('/sync/price', 'Api_1c8\ItemController@postTruncatePrice');
    Route::post('/sync/amount', 'Api_1c8\ItemController@postTruncateAmount');

    Route::post('/sync/discounts', 'Api_1c8\DiscountController@syncDiscounts');






});

//require __DIR__.'/../vendor/barryvdh/laravel-debugbar/src/debugbar-routes.php';


//require __DIR__.'/auth.php';
