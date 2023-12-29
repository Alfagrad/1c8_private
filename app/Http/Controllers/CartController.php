<?php

namespace App\Http\Controllers;

use App\Actions\IsServiceCanBuy;
use App\Actions\RequestToUT;
use App\Models\CartServiceItem;
use App\Repositories\AgreementTypeRepository;
use App\Repositories\CartOrderRepository;
use App\Repositories\PartnerRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use SimpleXMLElement;
use App\Helpers\XMLHelper;

use App\Traits\MiniCartDataTrait;
use App\Traits\CategoryItemsTrait;
use App\Traits\ActionItemsTrait;
use App\Traits\CheapItemsTrait;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\RequestOptions;

use App\Models\Cart;
use App\Models\CartOrder;
use App\Models\Item;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Partner;
use App\Models\Agreement;
use App\Models\AgreementProduct;
use App\Models\DeliveryType;

class CartController extends Controller
{
    use MiniCartDataTrait, CategoryItemsTrait, ActionItemsTrait, CheapItemsTrait;

    public function index(Request $request, PartnerRepository $partnerRepository, AgreementTypeRepository $agreementTypeRepository, CartOrderRepository $cartOrderRepository)
    {

        $data = [];

        // профайл юзера
        $profile = $request->user()->profile()->first(['id', 'partner_uuid']);

        $data['cartOrders'] = $cartOrderRepository->all();

        // собираем товары в корзине
        $carts = Cart::where([['profile_id', $profile->id], ['cart_order_id', null]])->with('item')->get();

        // для вывода заказанного количества товара в каталожных выдачах
//        $data['in_cart'] = $cart->where('cart_order_id', Null)->pluck('count', 'item_1c_id');

        // собираем коллекцию корзин **************************************
        $collect = new Collection;

        // собираем 1c_id товаров в корзине Основная
        $cart_items = $carts->where('cart_order_id', Null)->pluck('item_1c_id');

        // собираем товары
        $items = $carts->map(fn(Cart $cart) => $cart->item);

        // добавляем в коллекцию items количество товара
//        foreach ($items as $item) {
//
//            // определяем количество
//            $count_item = $carts->where('cart_order_id', Null)->where('item_1c_id', $item->id_1c)->first()->count;
//
//            // добавляем в коллекцию
//            $item->setAttribute('count_in_cart', $count_item);
//
//        }

//        dd($count_all_items, $items);

        Log::info('Cart carts');


        $data['carts'] = $carts;
        // *********************************************************************
        // стандартные соглашения
        $data['common_agreements'] = $agreementTypeRepository->common();

        // с этим товаром покупают, не забудь купить
        $data = array_merge($data, $this->byWith($carts));

        // собираем акционные товары и спецпредложения (из трейта)
//        $actions = $this->getActionItems();
//
//        // собираем уцененные товары и добавляем к товарам
//        $actions = $this->getCheapItems($actions);
//
//        $data['actions'] = $actions;



        // // ранее купленные
        // $itemsFromOrder = $this->itemsFromOrder($request->user()->profile(), $cart);
        // $data['itemsFromOrder'] = $itemsFromOrder;

        // берем id корзины из куки
        if (isset($_COOKIE['cart_id'])) {
            $cart_id = $_COOKIE['cart_id'];
        } else {
            $cart_id = 0;
        }
        $data['cart_id'] = $cart_id;

        // // отправка содержимого текущей корзины менеджеру *******************************
        // // берем данные пользователя
        // $user_1c_id = $data['generalProfile']->{'1c_id'};
        // $user_company = $data['generalProfile']->company_name;

        // // берем данные по текужей корзине
        // $cart_data = array_values($data['carts']->where('cart_id', $cart_id)->toArray())[0];

        // // берем имя корзины
        // $cart_name = $cart_data['name'];

        // // берем товары корзины
        // $cart_items = $cart_data['items'];

        // // берем данные товар-количество корзины
        // if ($cart_id == 0) {
        //     $cart_id = Null;
        // }
        // $cart_counts = Cart::where('cart_order_id', $cart_id)->get(['item_1c_id', 'count'])->pluck('count', 'item_1c_id')->toArray();

        // // формируем запись
        // $message = "Клиент {$user_1c_id} - [B]{$user_company}[/B] вошел в корзину '{$cart_name}'\n";
        // $message .= "Товар в корзине:\n";

        // if($cart_items->count()) {
        //     // считаем сумму по корзине
        //     $total_price = 0;

        //     foreach($cart_items as $item) {
        //         $message .= $item->{'1c_id'}." - {$item->name} - {$cart_counts[$item->{'1c_id'}]} шт.\n";
        //         $total_price += $item->price_bel * $cart_counts[$item->{'1c_id'}];
        //     }

        //     $message .= "Сумма в корзине (ориентировочно): [B]{$total_price} руб.[/B]";
        // } else {
        //     $message .= "НЕТ";
        // }

        // // соответствие емайл->битрикс коду
        // $bitrix_codes_arr = [
        //     'pavel.grishkevich@alfagrad.com' => 38,
        //     'siarhey.makeenok@alfagrad.com' => 120,
        //     'alexander.kovalev@alfagrad.com' => 206,
        //     // 'ekaterina.dmitruk@alfagrad.com' => 200,
        //     'pavel.kalistratov@alfagrad.com' => 212,
        //     'artem.tsirganovich@alfagrad.com' => 348,
        //     'denis.samusenko@alfagrad.com' => 264,
        //     'alexandra.stankevich@alfagrad.com' => 202,
        //     'angelina.voitulevich@alfagrad.com' => 294,
        //     'elizaveta.chichina@alfagrad.com' => 416,
        //     'denis.vavilchenkov@alfagrad.com' => 420,
        //     'dmitry.dubrousky@alfagrad.com' => 26,
        //     'anastasia.pirko@alfagrad.com' => 446,
        // ];

        // // если не пусто
        // if(trim($data['generalProfile']->manager_email)) {
        //     // определяем email-ы ответственных
        //     $user_manager_email = explode(';', $data['generalProfile']->manager_email);

        //     foreach($user_manager_email as $email) {
        //         // переводим в нижний регистр и убираем пробел
        //         $email = trim(mb_strtolower($email));

        //         // определяем битрикс код ответственного
        //         if(array_key_exists($email, $bitrix_codes_arr)) {
        //             $menager_id = $bitrix_codes_arr[$email];
        //         } else {
        //             $menager_id = 174; // код Кулика
        //         }

        //         // отправляем сообщение
        //         $this->sendEvent($menager_id, $message);
        //     }
        // } else {
        //     $menager_id = 174; // код Кулика

        //     // отправляем сообщение
        //     $this->sendEvent($menager_id, $message);
        // }

        // // $this->sendEvent(270, $message);

        // *********************************************************************************************

        Log::info('Cart index render');

        return view('cart.index')->with($data);
    }

    // создаем корзину
    public function addCart(Request $request)
    {
        $name = trim($request->cart_name);

        // проверяем, есть ли такая корзина
        $cart_db = CartOrder::where([['profile_id', $request->profile_id], ['name', $name]])->first();
        if ($cart_db || $name == 'Основная') {
            $note = "Такая корзина у Вас есть! Введите другое имя.";
            return redirect()->back()->with(['note' => $note]);
        }

        // сохраняем
        $cartOrder = new CartOrder();
        $cartOrder->name = $name;
        $cartOrder->profile_id = $request->profile_id;
        $cartOrder->save();

        $note = "Корзина &laquo;{$name}&raquo; создана";
        return redirect()->back()->with(['note' => $note]);
    }

    // удаляем корзину
    public function deleteCart($cart_id)
    {
        CartOrder::where('id', $cart_id)->delete();
        Cart::where('cart_order_id', $cart_id)->delete();

        $note = "Корзина удалена!";
        return redirect()->back()->with(['note' => $note]);
    }

    // переносим товары из корзины в корзину
    public function relocateItems(Request $request)
    {

        // текущая корзина
        $current_cart_id = $request->current_cart_id;
        if ($current_cart_id == 0) {
            $current_cart_id = Null;
        }
        // целевая корзина
        $target_cart_id = $request->target_cart_id;
        if ($target_cart_id == 0) {
            $target_cart_id = Null;
        }
        // товары
        $items_array = $request->items;

        // id профайла юзера
        $profile_id = $request->user()->profile()->first(['id'])['id'];

        // собираем товары которые переносим
        $current_cart_items = Cart::where([['profile_id', $profile_id], ['cart_order_id', $current_cart_id]])
            ->whereIn('item_1c_id', $items_array)->get();

        // в целевой корзине собираем 1c_id из списка текущей
        $target_cart_items = Cart::where([['profile_id', $profile_id], ['cart_order_id', $target_cart_id]])
            ->whereIn('item_1c_id', $items_array)->get();
        $target_cart_items_id = $target_cart_items->pluck('item_1c_id')->toArray('item_1c_id');

        foreach ($current_cart_items as $item) {
            if (in_array($item->item_1c_id, $target_cart_items_id)) {
                // суммируем количество товара
                $item_count = $item->count + $target_cart_items->where('item_1c_id', $item->item_1c_id)->first()->count;
                // удаляем товар из текущей корзины
                Cart::where('id', $item->id)->delete();
                // обновляем количество в целевой корзине
                Cart::where([['cart_order_id', $target_cart_id], ['item_1c_id', $item->item_1c_id]])->update([
                    'count' => $item_count,
                ]);
            } else {
                // переназначаем id корзины с текущей на целевую
                Cart::where([['cart_order_id', $current_cart_id], ['item_1c_id', $item->item_1c_id]])->update([
                    'cart_order_id' => $target_cart_id,
                ]);
            }
        }
    }

    // копируем товары из корзины в корзину
    public function copyItems(Request $request)
    {

        // текущая корзина
        $current_cart_id = $request->current_cart_id;
        if ($current_cart_id == 0) {
            $current_cart_id = Null;
        }
        // целевая корзина
        $target_cart_id = $request->target_cart_id;
        if ($target_cart_id == 0) {
            $target_cart_id = Null;
        }
        // товары
        $items_array = $request->items;

        // id профайла юзера
        $profile_id = $request->user()->profile()->first(['id'])['id'];

        // собираем товары которые копируем
        $current_cart_items = Cart::where([['profile_id', $profile_id], ['cart_order_id', $current_cart_id]])->whereIn('item_1c_id', $items_array)->get();

        // в целевой корзине собираем 1c_id из списка текущей
        $target_cart_items = Cart::where([['profile_id', $profile_id], ['cart_order_id', $target_cart_id]])
            ->whereIn('item_1c_id', $items_array)->get();
        $target_cart_items_id = $target_cart_items->pluck('item_1c_id')->toArray('item_1c_id');

        foreach ($current_cart_items as $item) {
            if (in_array($item->item_1c_id, $target_cart_items_id)) {
                // суммируем количество товара
                $item_count = $item->count + $target_cart_items->where('item_1c_id', $item->item_1c_id)->first()->count;
                // обновляем количество в целевой корзине
                Cart::where([['cart_order_id', $target_cart_id], ['item_1c_id', $item->item_1c_id]])->update([
                    'count' => $item_count,
                ]);
            } else {
                // добавляем в целевую корзину
                $cart_el = new Cart;
                $cart_el->profile_id = $profile_id;
                $cart_el->item_1c_id = $item->item_1c_id;
                $cart_el->count = $item->count;
                $cart_el->cart_order_id = $target_cart_id;
                $cart_el->save();
            }
        }
    }

    // удаляем выбранные товары из корзины
    public function deleteItems(Request $request)
    {
        // текущая корзина
        $current_cart_id = $request->current_cart_id;
        if ($current_cart_id == 0) {
            $current_cart_id = Null;
        }

        // товары
        $items_array = $request->items;

        // id профайла юзера
        $profile_id = $request->user()->profile()->first(['id'])['id'];

        // собираем и удаляем товары
        $current_cart_items = Cart::where([['profile_id', $profile_id], ['cart_order_id', $current_cart_id]])->whereIn('item_1c_id', $items_array)->delete();
    }

    // меняемся товарами с Основной корзиной
    public function swapItems(Request $request)
    {
        // текущая корзина
        $current_cart_id = $request->current_cart_id;

        // id профайла юзера
        $profile_id = $request->user()->profile()->first(['id'])['id'];

        // id резервной корзины
        $reserve_cart_id = 9999999;

        // собираем товары текущей корзины и перемещаем в резервную
        Cart::where([['profile_id', $profile_id], ['cart_order_id', $current_cart_id]])->update([
            'cart_order_id' => $reserve_cart_id,
        ]);

        // собираем товары Основной корзины и перемещаем в текущую
        Cart::where([['profile_id', $profile_id], ['cart_order_id', Null]])->update([
            'cart_order_id' => $current_cart_id,
        ]);

        // собираем товары резервной корзины и перемещаем в Основную
        Cart::where([['profile_id', $profile_id], ['cart_order_id', $reserve_cart_id]])->update([
            'cart_order_id' => Null,
        ]);
    }

    // удаляем 1 товар из корзины (нажатие на крестик)
    public function delItem(Request $request)
    {
        // id профайла юзера
        $profile_id = $request->user()->profile()->first(['id'])['id'];

        // id корзины
        $current_cart_id = $request->current_cart_id;
        if ($current_cart_id == 0) {
            $current_cart_id = Null;
        }

        // id товара для удаление
        $item_1c_id = $request->item_1c_id;

        // удаляем
        Cart::where([['profile_id', $profile_id], ['cart_order_id', $current_cart_id], ['item_1c_id', $item_1c_id]])->delete();
    }

    // очистка корзины (нажатие на Очистить корзину)
    public function emptyCart(Request $request)
    {
        // id профайла юзера
        $profile_id = $request->user()->profile()->first(['id'])['id'];

        // id корзины
        $current_cart_id = $request->current_cart_id;
        if ($current_cart_id == 0) {
            $current_cart_id = Null;
        }

        // удаляем
        Cart::where([['profile_id', $profile_id], ['cart_order_id', $current_cart_id]])->delete();
    }

    // очистка всех корзин (нажатие на Очистить все корзины)
    public function emptyAllCarts(Request $request)
    {
        if ($request->empty_carts == 1) {

            // id профайла юзера
            $profile_id = $request->user()->profile()->first(['id'])['id'];

            // удаляем
            Cart::where('profile_id', $profile_id)->delete();
        }
    }

    // ajax обновление корзины
    public function updateCart(Request $request)
    {
        Log::info('UpdateCart start');
        // id профайла юзера
        $profile_id = \Auth::user()->profile->id;

        // id корзины
        $cart_id = $request->cart_id;
        if ($cart_id == 0) {
            $cart_id = Null;
        }

        // 1c id товара
        $item_1c_id = $request->item_1c_id;

        // количество товара
        $item_count = $request->count;

        Log::info('UpdateCart update');
        if ($item_count > 0) {
            Cart::updateOrCreate(
                ['profile_id' => $profile_id, 'item_1c_id' => $item_1c_id, 'cart_order_id' => $cart_id],
                ['count' => $item_count]
            );

        } else {
            Cart::where([['profile_id', $profile_id], ['item_1c_id', $item_1c_id], ['cart_order_id', $cart_id]])->delete();
        }

        Log::info('UpdateCart minicart');
        $mini_cart = $this->getCartData();

        // // собираем товары корзины
        // $carts = Cart::where('profile_id', $profile_id)->get(['item_1c_id', 'count']);

        // $counts = 0;
        // $prices = 0;

        // // курс доллара
        // $usd_opt = setting('header_usd');

        // foreach($carts as $cart) {
        //     $counts += $cart->count;

        //     // берем товар
        //     $item = Item::where('id_1c', $cart->item_1c_id)->first(['adjustable', 'price_usd', 'price_rub', 'discount_str']);

        //     // берем цену
        //     if ($item->adjustable == 1) {

        //         $price = $cart->count * $item->price_rub;

        //     } else {

        //         $price = $cart->count * number_format($item->price_usd * $usd_opt, '2', '.', '');
        //     }

        //     // смотрим есть ли дисконт
        //     if ($item->discount_str) {

        //         // делим скидки
        //         $discounts = explode(';', $item->discount_str);

        //         foreach ($discounts as $element) {

        //             // делим элементы скидки
        //             $element = explode('|', $element);

        //             // мин кол-во на которое действует
        //             $condition = $element[0];
        //             // цена
        //             $discount_price = $element[1];
        //             // дата начала
        //             $date_start = $element[2];
        //             // дата окончания
        //             $date_end = $element[3];

        //             if (date('Y-m-d') >= $date_start && ($date_end == '0000-00-00' || date('Y-m-d') <= $date_end)) {

        //                 // если количество заказанного товара больше(равно) количества со скидкой
        //                 if ($cart->count >= $condition) {
        //                     $price = $cart->count * $discount_price;
        //                 }
        //             }
        //         }
        //     }

        //     // общая стоимость
        //     $prices += $price;
        // }
        Log::info('UpdateCart end');
        return $mini_cart;

    }

    public function orderCreate(XMLHelper $XMLHelper, Request $request)
    {
        Log::info('OrderCreate start');
        // собираем данные профайла
        $profile = $request->user()->profile()->first();

        // текущая корзина
        $currentCartId = $request->cart_id == 0 ? Null : $request->cart_id;

        // формируем массив с моделями заказанных товаров
        $orderItemModels = [];
        if (count($request->items)) {

            foreach ($request->items as $value) {

                // делим данные
                $item_arr = explode('-', $value);

                // цена с ндс
                $item_price = number_format($item_arr[2] * 1.2, '2', '.', '');
                // стоимость с НДС
                $item_sum_price = number_format($item_arr[2] * $item_arr[1] * 1.2, '2', '.', '');

                $orderItem['item_1c_id'] = $item_arr[0];
                $orderItem['item_name'] = Item::where('id_1c', $item_arr[0])->first(['name'])->name;
                $orderItem['item_count'] = $item_arr[1];
                $orderItem['item_price'] = $item_price;
                $orderItem['item_sum_price'] = $item_sum_price;

                $orderItemModels[] = new OrderItem($orderItem);
            }
        } else {
            return redirect()->back();
        }

        Log::info('OrderCreate agreements');

        // берем uuid соглашения
        $agreement_uuid = $request->calc_type;
        // берем имя соглашение
        $agreement_name = Agreement::where('uuid', $agreement_uuid)->first(['name'])->name;

        // способ доставки
        $delivery = DeliveryType::where('id', $request->delivery_type)->first();

        // если Доставка
        if ($delivery->id == 2) {
            if ($request->delivery_address) {
                // берем uuid партнера для доставки
                $delivery_partner_uuid = $request->delivery_address;
                // берем адрес доставки
                $delivery_address = Partner::where('uuid', $delivery_partner_uuid)->first(['address'])->address;
            } else {
                $delivery_partner_uuid = '';
                $delivery_address = '';
            }
        } else {
            $delivery_partner_uuid = '';
            $delivery_address = '';
        }

        // берем общие данные по заказу
        $order['profile_id'] = $profile->id;
        $order['agreement_uuid'] = $agreement_uuid;
        $order['calculation'] = $agreement_name;
        $order['delivery'] = $delivery->text;
        $order['delivery_partner_uuid'] = $delivery_partner_uuid;
        $order['address'] = $delivery_address;
        $order['comment'] = $request->comment_to_order;

        if ($request->total) {

            // делим данные
            $total_arr = explode('-', $request->total);

            $order['weight'] = $total_arr[0];
            $order['savings'] = $total_arr[1];
            $order['price'] = $total_arr[2];

        } else {
            return redirect()->back();
        }

        Log::info('OrderCreate order create');
        // записываем в БД заказ
        $order = Order::create($order);

        // записываем в бд товары заказа
        foreach ($orderItemModels as $key => $model) {
            // добавляем в модель номер заказа
            $model->order_id = $order->id;

            // проверяем, есть ли такая запись в БД
            $order_item = OrderItem::where([['order_id', $model->order_id], ['item_1c_id', $model->item_1c_id]])->first(['id']);

            // если нет
            if (!$order_item) {
                // пишем
                $model->save();
            } else {
                // удаляем повтор
                unset($orderItemModels[$key]);
            }
        }
        Log::info('OrderCreate cart clear');
        // Очистка корзины
        Cart::where('profile_id', $profile->id)->where('cart_order_id', $currentCartId)->delete();

        // хэдеры
        $email_headers['subject'] = "Заказ " . $order->id . " от Контрагента";
        $email_headers['email_from'] = setting('email_from');
        $email_headers['headers_from'] = 'Alfastok';

        // если есть емайл менеджеров
        if ($profile->manager_email) {
            // Если не один емайл, делим
            $manager_emails = explode(';', $profile->manager_email);
        } else {
            $manager_emails[] = setting('email_to_other'); // руководителю отдела продаж
            // $manager_emails[] = 'Lisan@tut.by';
        }

        // $manager_emails = explode(';', 'Lisan@tut.by');

        Log::info('OrderCreate managers mail');
        // отправляем менеджерам
        foreach ($manager_emails as $email) {
            // хэдер
            $email_headers['email_to'] = trim($email);
            // отправляем
            \Mail::send('emails.order', ['profile' => $profile, 'order' => $order], function ($message) use ($email_headers) {
                $message->from($email_headers['email_from'], $email_headers['headers_from']);
                $message->to($email_headers['email_to'])->subject($email_headers['subject']);
            });
            unset($email_headers['email_to']);
        }

        // отправляем клиенту
        $email_headers['subject'] = "Заказ " . $order->id . " на Alfastok.by";

        if ($profile->subscribe->copy_order) {
            // хэдеры
            $email_headers['email_to'] = trim($profile->email);
            // отправляем
            \Mail::send('emails.orderToClient', ['profile' => $profile, 'order' => $order], function ($message) use ($email_headers, $profile) {
                $message->from($email_headers['email_from'], $email_headers['headers_from']);
                $message->to($email_headers['email_to'])->subject($email_headers['subject']);
            });
        }
        Log::info('OrderCreate xml to ut');
        // формируем xml для отправки в 1с
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><data></data>');
        $xml->addAttribute('version', '1.0');
        $xml->addChild('type', 'request');
        $xml->addChild('id', $order->id);
        if ($delivery_partner_uuid) {
            $xml->addChild('partner', $delivery_partner_uuid);
        } else {
            $xml->addChild('partner');
        }
        $xml->addChild('email', $profile->email);
        $xml->addChild('agreement', $agreement_uuid);
        $xml->addChild('sum', $total_arr[2]);
        $xml->addChild('comment', $request->comment_to_order);
        $xml->addChild('products');

        foreach ($request->items as $value) {

            // делим данные
            $item_arr = explode('-', $value);

            // стоимость с НДС
            $item_sum_price = number_format($item_arr[2] * $item_arr[1] * 1.2, '2', '.', '');

            $product = $xml->products->addChild('product');

            $product->addChild('uuid', Item::where('id_1c', $item_arr[0])->first(['uuid'])->uuid);
            $product->addChild('amount', $item_arr[1]);
            $product->addChild('sum', $item_sum_price);
        }
        // dd($XMLHelper->beauty_xml($xml), $xml->asXML());

        // преобразуем в строчный вид
        // $xml = $xml->asXML();
        $xml = $XMLHelper->beauty_xml($xml);

//        // отправка заказа в 1с8
//        $client = new GuzzleClient();
//        // $credentials = base64_encode($log.':'.$pass);
//        $credentials = base64_encode('UT-site:51645');
//
//        try {
//
//            // $response = $client->post('http://93.125.106.243/UT_Site/hs/site.exchange/updInf', [
//            // $response = $client->get('http://93.125.106.243/UT_Copy/hs/site.exchange/test', [
//            $response = $client->post('http://93.125.106.243/UT_Copy/hs/site.exchange/updInf', [
//                'connect_timeout' => 10,
//                'headers' => [
//                    'objectType' => 'request',
//                    'Authorization' => 'Basic ' . $credentials,
//                    'Content-Type' => 'application/xml',
//                ],
//                'body' => $xml,
//            ]);
//
//            // получаем код ответа
//            $status_code = $response->getStatusCode();
//            // $body = $response->getContent();
//
//        } catch (GuzzleException $e) {
//            // ничего не делаем, просто пропускаем...
//            Log::error('Order not sended', ['message' => $e->getMessage()]);
//            $status_code = '';
//
//        }
        Log::info('OrderCreate send to ut');
        $status_code = (new RequestToUT())->__invoke($xml);

        // если запрос отправлен успешно (код 200)
        if ($status_code == 200) {
            // меняем статус в БД
            Order::where('id', $order->id)->update(['is_send' => 1]);
        }
        Log::info('OrderCreate ends');
        return redirect('pages/blagodarim-za-zakaz'); // Редирект на страницу с благодарностью
    }

    public function byWith($cart)
    {

        $byWith = '';
        $forgetBuy = '';
        // собираем коды
        $forget_codes_arr = [];
        $buywith_codes_arr = [];


        foreach ($cart as $c) {

            $item = $c->item;

            if ($item) {

                // если в бд есть коды С этим товаром покупают
                if ($item->forget_buy) {

                    // берем массив кодов товара
                    $forget_codes = explode(',', trim($item->forget_buy));

                    // если не пусто
                    if (count($forget_codes)) {

                        // убираем нули
                        foreach ($forget_codes as $key => $val) {
                            $forget_codes[$key] = intval(explode('-', $val)[1]);
                        }

                        // добавляем в массив, берем уникальные
                        $forget_codes_arr = array_unique(array_merge($forget_codes_arr, $forget_codes));
                    }
                }

                // если в бд есть коды Не забудь купить
                if ($item->buy_with) {

                    // берем массив кодов товара
                    $buywith_codes = explode(',', trim($item->buy_with));

                    // если не пусто
                    if (count($buywith_codes)) {

                        // убираем нули
                        foreach ($buywith_codes as $key => $val) {
                            $buywith_codes[$key] = intval(explode('-', $val)[1]);
                        }

                        // добавляем в массив, берем уникальные
                        $buywith_codes_arr = array_unique(array_merge($buywith_codes_arr, $buywith_codes));
                    }
                }
            }
        }

        // собираем коллекцию Категория - Товары и товары для Не забудь купить
        $collect = $this->getCategoryItems($forget_codes_arr);
        $data['buy_forget_cat'] = $collect;

        // если 1с код товар
        $buy_forget = Item::whereIn('id_1c', $forget_codes_arr)
            ->where('in_archive', 0)
            ->where(function ($query) {
                $query->where('amount', '>', 0)->orWhere('locked', '>', 0);
            })
            ->orderBy('name')
            // ->take(4)
            ->get();
        $data['buy_forget'] = $buy_forget;

        // собираем коллекцию Категория - Товары и товары для С этим товаром покупают
        $collect = $this->getCategoryItems($buywith_codes_arr);
        $data['buy_with_cat'] = $collect;

        // если 1с код товар
        $buy_with = Item::whereIn('id_1c', $buywith_codes_arr)
            ->where('in_archive', 0)
            ->where(function ($query) {
                $query->where('amount', '>', 0)->orWhere('locked', '>', 0);
            })
            ->orderBy('name')
            // ->take(4)
            ->get();
        $data['buy_with'] = $buy_with;

        return $data;
    }

    public function itemsFromOrder($request, $cart)
    {

        $items = [];

        $profile = $request->with('orders.items')->first();

        foreach ($profile->orders as $order) {
            foreach ($order->items as $item) {
                if ($item->{'item_1c_id'} != null) {
                    $items[] = $item->item_1c_id;
                }
            }
        }

        $itemsinCart = [];

        foreach ($cart as $item) {
            $itemsinCart[] = $item->item_id;
        }

        return Item::whereIn('1c_id', array_unique($items))->whereNotIn('id', $itemsinCart)->where('count', '>', 0)->get();
    }
    public function toggleService(IsServiceCanBuy $isServiceCanBuy): RedirectResponse
    {
        $isServiceCanBuy->toggle();
        return back();
    }


    public function sendEvent($menager_id, $message)
    {
        // $queryUrl = 'https://alfastok.bitrix24.by/rest/386/r7el2b50ll7no1qg//im.message.add.json';
        // $queryData = http_build_query(array(
        //     'USER_ID' => $menager_id,
        //     'MESSAGE' => $message,
        // ));

        // $curl = curl_init();

        // curl_setopt_array($curl, array(
        //     CURLOPT_SSL_VERIFYPEER => 0,
        //     CURLOPT_POST => 1,
        //     CURLOPT_HEADER => 0,
        //     CURLOPT_RETURNTRANSFER => 1,
        //     CURLOPT_URL => $queryUrl,
        //     CURLOPT_POSTFIELDS => $queryData,
        // ));

        // $result = curl_exec($curl);

        // curl_close($curl);
    }

    public function service(Request $request)
    {

        // id профайла юзера
        $profile_id = Auth::user()->profile->id;

        // корзины юзера
        $user_carts = CartOrder::where('profile_id', $profile_id)->get();

        // собираем товары в корзине
        $items = CartServiceItem::where('profile_id', $profile_id)->get();

// dd($user_carts, $items);


        return view('service_cart');
    }
}
