<?php

namespace App\Http\Controllers;

use App\Models\CalculationType;
use App\Models\Cart;
use App\Models\CartOrder;
use App\Models\Category;
use App\Models\DeliveryType;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use Auth;
use Illuminate\Http\Request;
use function GuzzleHttp\Promise\all;
use Intervention\Image\Facades\Image as Image;



class CartController extends Controller
{

    public function index(Request $request, $cartId = null)
    {
        $partial = $request->cart_partial;

        $data = $this->getUserData($request);
        $profile = $request->user()->profile()->with('address', 'contact')->first();

        $cart = Cart::with(['item.gift', 'item'])
            ->where('profile_id', $profile->id);

        if ($cartId != null && $cartId != 0) {
            $cart->where('cart_order_id', $cartId);
        } else {
            $cart->where('cart_order_id', null);
        }

        $products_yes = clone $cart;
        $spares_yes = clone $cart;

        if($profile->is_service == 1) {
            $products_yes = (bool)$products_yes->whereHas('item', function ($item) {
                $item->where('is_component', 2);
            })->get()->count();

           // выбираем коды категорий сервиса
            $service_category = Category::where('parent_1c_id', '20070')->get(['1c_id']);
            foreach ($service_category as $value) {
                $service_category_array[] = $value->{'1c_id'};
            }
        } else {
            $products_yes = (bool)$products_yes->whereHas('item', function ($item) {
                $item->where('is_component', 0);
            })->get()->count();
            $service_category_array = '';
        }

        $spares_yes = (bool)$spares_yes->whereHas('item', function ($item) {
            $item->where('is_component', 1);
        })->get()->count();


        // наценка/скидка markup. Учитывается в отображении цен на сайте
        $markup = $data['generalProfile']->markup;

        $cartNameId = $cartId ? CartOrder::where('id', $cartId)->first()->id : 0;
        $cartSelect = CartOrder::where('profile_id', $profile->id)->pluck('name', 'id')->toArray();
        $cartMain = ['Основная'];

        //заполним данные по каждой корзине для выпадающего меню

        $cartArray = $cartMain + $cartSelect;
        $cartArrayForSelect = $cartArray;

        foreach ($cartArray as $key => $value) {
            if ($key == 0) {
                $cartArray[$key] = 'Корзина <b>&laquo;'.$value.'&raquo;</b>: '.$this->getNameCountInCartByCartOrderId($profile->id, null, $markup);
            } else {
                $cartArray[$key] = 'Корзина <b>&laquo;'.$value.'&raquo;</b>: '.$this->getNameCountInCartByCartOrderId($profile->id, $key, $markup);
            }
        }

        $calcTypes = CalculationType::orderBy('created_at', 'DESC')->get();
        $deliveryTypes = DeliveryType::orderBy('created_at', 'DESC')->get();

        $buyWith = $this->byWith($cart->get());

        if($profile->is_service == 1) {
            if (isset($partial) && $partial == 1) {
                $view = 'service.cart_partial';
            } elseif (isset($partial) && $partial == 2) {
                $view = 'cart_with_partial';
            } else {
                $view = 'service.cart';
            }
        } else {
            if (isset($partial) && $partial == 1) {
                $view = 'cart_partial';
            } elseif (isset($partial) && $partial == 2) {
                $view = 'cart_with_partial';
            } else {
                $view = 'cart';
            }

        }

        $actions = Item::where([['is_action', 1], ['count', '>', 0]])
            ->orWhere([['discounted', '!=', 0], ['count', '>', 0]])
            ->orderBy('date_open_action', 'desc')
            ->get();


        $itemsFromOrder = $this->itemsFromOrder($request->user()->profile(), $cart->get());

        $idToCart = (New Cart)->where('profile_id', $profile->id)->pluck('count', 'item_1c_id');
// dd($idToCart);
        return view($view, [
            'cart' => $cart->get(),
            'cartArray' => $cartArray,
            'buyWith' => $buyWith['buyWith'],
            'itemsFromOrder' => $itemsFromOrder,
            // 'buyWithCategory' => $buyWith['buyWithCategory'],
            'buyForget' => $buyWith['buyForget'],
            'cartArrayForSelect' => $cartArrayForSelect,
            'actions' => $actions,
            'cartId' => $cartNameId,
            'personal_discount' => $profile->discount,
            'calcTypes' => $calcTypes,
            'deliveryTypes' => $deliveryTypes,
            'profile' => $profile,
            // 'gifts' => collect([]),
            'addCart' => true,
            'breadcrumbs' => [
                'Корзина' => '',
            ],
            'chipItems' => $this->getChipItemsFromCart($cart->get()),
            'token' => Auth::user()->login_token,
            'currency' => $request->get('currency', 'byn'),
            'spares_yes' => $spares_yes,
            'products_yes' => $products_yes,
            'data_markup' => $markup,
            'service_category_array' => $service_category_array,
            'idToCart' => $idToCart,
        ])->with($data);
    }


    public function itemsFromOrder($request, $cart)
    {
        $items = [];

        $profile = $request->with('orders.items')->first();

        foreach ($profile->orders as $order) {
            foreach ($order->items as $item) {
                if($item->{'item_1c_id'} != null) {
                    $items[] = $item->item_1c_id;
                }
            }
        }

        $itemsinCart = [];

        foreach ($cart as $item){
            $itemsinCart[] = $item->item_id;
        }

       return Item::whereIn('1c_id',  array_unique($items))->whereNotIn('id', $itemsinCart)->where('count', '>', 0)->get();
    }


    public function byWith($cart)
    {
        $byWith = '';
        $forgetBuy = '';
        foreach ($cart as $c) {

            $item = $c->item;
            if ($item != null) {

                strlen($item->buy_with) == 0 ?: $byWith = $byWith . $item->buy_with . ', '; // собираем все товары из buy_with
                strlen($item->forget_buy) == 0 ?: $forgetBuy = $forgetBuy . $item->forget_buy . ', '; // собираем все товары из forget_buy
            }
        }

        $new_array = array_filter(explode(',', $byWith), function ($element) {
            return !empty(trim($element));
        });


        $new_array_forget_buy = array_filter(explode(',', $forgetBuy), function ($element) {
            return !empty(trim($element));
        });


        foreach ($new_array as $key => $value) {
            $new_array[$key] = trim($value);
        }

        foreach ($new_array_forget_buy as $key => $value) {
            $new_array_forget_buy[$key] = trim($value);
        }

        $dataBuyWith = array_unique($new_array);


        $new_array_forget_buy = array_unique($new_array_forget_buy);

        // $buyWithCategory = Category::with('items')->whereIn('1c_id', $dataBuyWith)->get();
        $buyWithItem = Item::whereIn('1c_id', $dataBuyWith)->where('count', '>', 0)->orderBy('default_sort')->get();

        $buyForget = Item::whereIn('1c_id', $new_array_forget_buy)->where('count', '>', 0)->orderBy('default_sort')->get();

        $buyWith['buyWith'] = $buyWithItem;
        // $buyWith['buyWithCategory'] = $buyWithCategory;
        $buyWith['buyForget'] = $buyForget;

        return $buyWith;
    }


    public function getNameCountInCartByCartOrderId($profileId, $id = null, $markup = 1)
    {
        //расчетный marcup
        $markup = $markup / 100 + 1;
        $cart = Cart::with('item.gift')->where('cart_order_id', $id)->where('profile_id', $profileId)->get();
        $count = 0;
        $countPosition = $cart->count();
        $price = 0;
        $inCart['price'] = 0;
        $inCart['count'] = 0;

        foreach ($cart as $c) {
            // проверим наличие товара в базе данных
            if(!$c->item) {
                // если не существует, удаляем из корзины и пропускаем
                Cart::where([['profile_id', $c->profile_id], ['item_1c_id', $c->item_1c_id]])->delete();
                continue;
            }
            // максимальный markup
            if($c->item->price_bel != 0) {
                $maxMarkup = $c->item->price_mr_bel / $c->item->price_bel;
                // если расчетный больше, используем максимальный
                if($markup > $maxMarkup) $item_markup = $maxMarkup;
                    else $item_markup = $markup;
            } else {
                $item_markup = 1;
            }

            $cPrice = ceil($c->item->priceFromCountBYN($c->count) * $item_markup * 100) / 100;

            if ($c->item !== null) {
                if ($cPrice < $c->item->price_min_bel) { //FIX: Возникате ошибка
                    $price = $c->item->price_min_bel;
                } else {
                    $price = $cPrice;
                }
            } else {
                $price = 0;
            }

            try {
                $inCart['price'] += $c->count * $price;
                $inCart['count'] += $c->count;
            } catch (\Exception $exception) {
            }

            if ($c->item !== null && $c->item->gift) {
                $inCart['count'] += $c->count;
            }
        }

        return '<b><span class="count_position_in_cart">' . $countPosition . '</span></b> ' . $this->getWordPosition($countPosition) . ', <b><span class="count_items_in_cart">' . $inCart['count'] . '</span></b> ' . $this->getWordItem($inCart['count']) . ', на сумму <b><span class="summ_in_cart">' . $inCart['price'] . '</span></b> руб.';
    }


    public function getWordPosition($count)
    {
        $letter = substr($count, -1);
        if (in_array($letter, [2, 3])) {
            return 'позиции';
        } elseif (in_array($letter, [1])) {
            return 'позиция';
        } else {
            return 'позиций';
        }
    }

    public function getWordItem($count)
    {
        $letter = substr($count, -1);
        if (in_array($letter, [2, 3])) {
            return 'товара';
        } elseif (in_array($letter, [1])) {
            return 'товар';
        } else {
            return 'товаров';
        }
    }

    public function addCart(Request $request)
    {
        $profile = $request->user()->profile()->first();
        $cart = $request->cart;
        $cartOrder = new CartOrder();
        $cartOrder->name = $cart;
        $cartOrder->profile_id = $profile->id;
        $cartOrder->save();
        return back();
    }

    public function deleteCart($id, $main = null)
    {
        CartOrder::where('id', $id)->delete();
        Cart::where('cart_order_id', $id)->delete();
        if ($main) {
            return redirect('/cart');
        }
        return back();
    }

    public function update(Request $request)
    {
        // echo '---' . $request->value . '---';
        $profile = $request->user()->profile()->first();

        if ($request->cartId && $request->cartId != 0) {
            $cartId = $request->cartId;
        } else {
            $cartId = null;
        }

        if ($request->value and $request->item1cId and $request->action) {
            if ($request->value > 0) {
                Cart::updateOrCreate(
                    ['profile_id' => $profile->id, 'item_1c_id' => $request->item1cId, 'cart_order_id' => $cartId],
                    ['profile_id' => $profile->id, 'item_1c_id' => $request->item1cId, 'count' => $request->value]
                );

            } else {
                Cart::where('profile_id', $profile->id)->where('item_1c_id', $request->item1cId)->delete();
            }
        }

        if (!$request->value and $request->item1cId and $request->action == 'remove') {
            Cart::where('profile_id', $profile->id)->where('item_1c_id', $request->item1cId)->delete();
        }

        $cart = Cart::with('item.gift')->where('profile_id', $profile->id)->get();
        $inCart = [
            'price' => 0,
            'count' => 0,
        ];

        if ($request->action and $request->item1cId and ($request->value > 0)) {

            $item = Item::where('1c_id', $request->item1cId)->first();

            // наценка/скидка markup. Учитывается в отображении цен на сайте
            // расчетный markup
            $markup = $profile['markup'] / 100 + 1;
            // максимальный markup
            $maxMarkup = $item->price_mr_bel/$item->bel_price;
            // если расчетный больше, используем максимальный
            if($markup > $maxMarkup) $markup = $maxMarkup;

            $itemPrice = ceil($item->priceFromCountBYN($request->value) * $markup * 100) / 100;
            if ($itemPrice < $item->price_min_bel) {
                $inCart['item_price'] = $item->price_min_bel;
            } else {
                $inCart['item_price'] = $itemPrice;
            }
        }
        foreach ($cart as $c) {

            // расчетный markup
            $markup = $profile['markup'] / 100 + 1;
            // максимальный markup
            if($c->item->bel_price != 0) {
                $maxMarkup = $c->item->price_mr_bel/$c->item->bel_price;
                // если расчетный больше, используем максимальный
                if($markup > $maxMarkup) $markup = $maxMarkup;
            } else {
                $markup = 1;
            }

            $cPrice = ceil($c->item->priceFromCountBYN($c->count) * $markup * 100) / 100;

            if ($cPrice < $c->item->price_min_bel) { //FIX: Возникате ошибка
                $price = $c->item->price_min_bel;
            } else {
                $price = $cPrice;
            }
            $inCart['price'] += $c->count * $price;
            $inCart['count'] += $c->count;


            if ($c->item->gift) {
                $inCart['count'] += $c->count;
            }
        }

        return $inCart;

    }


    /**
     * @param Request $request
     */
    public function changeItemCart(Request $request)
    {
        $profile = $request->user()->profile()->first();
        $cartOrderId = $request->cart == 0 ? null : $request->cart;
        $currentCartId = $request->currentCart == 0 ? null : $request->currentCart;


        foreach ($request->items as $item) {

            //проверка на существование товара в данной корзине
            $itemInCart = Cart::where('profile_id', $profile->id)->where('item_1c_id', $item['item1cId'])->where('cart_order_id', $cartOrderId)->first();

            //  если есть то добавим нужное количество а если нет то создадим
            if ($itemInCart) {
                $itemInCart->count += (int)$item['count'];
                $itemInCart->save();
            } else {
                $itemInCart = new Cart();
                $itemInCart->count = $item['count'];
                $itemInCart->profile_id = $profile->id;
                $itemInCart->item_1c_id = $item['item1cId'];
                $itemInCart->cart_order_id = $cartOrderId;
                $itemInCart->save();
            }


            if ($request->type == 1) {
                Cart::where('profile_id', $profile->id)->where('item_1c_id', $item['item1cId'])
                    ->where('cart_order_id', $currentCartId)
                    ->delete();
            }

        }


    }

    public function changeItemCartWithMain(Request $request)
    {
        $profile = $request->user()->profile()->first();
        $currentCartId = $request->currentCart == 0 ? null : $request->currentCart;

        $itemInCartMain = Cart::where('profile_id', $profile->id)->where('cart_order_id', null)->get();
        Cart::where('cart_order_id', $currentCartId)->update(['cart_order_id' => null]);

        foreach ($itemInCartMain as $item) {
            $item->update(['cart_order_id' => $currentCartId]);
        }
    }

    /**
     * @param Request $request
     */
    public function deleteFew(Request $request)
    {
        $profile = $request->user()->profile()->first();
        $currentCartId = $request->currentCart == 0 ? null : $request->currentCart;
        foreach ($request->items as $item) {
            Cart::where('profile_id', $profile->id)->where('item_id', $item['itemId'])
                ->where('cart_order_id', $currentCartId)
                ->delete();
        }
    }

    public function clear(Request $request, $id)
    {

        $profile = $request->user()->profile()->first();

        if($id != 'all') {
            $id = intval($id);
            if($id == 0) $id = Null;
            Cart::where([['profile_id', $profile->id], ['cart_order_id', $id]])->delete();
        } else {
            Cart::where('profile_id', $profile->id)->delete();
        }

        return redirect()->back();
    }

    public function delete(Request $request)
    {
        $profile = $request->user()->profile()->first();

        if ($request->cartId == 0) $request->cartId = Null;

        Cart::where([['profile_id', $profile->id], ['item_1c_id', $request->item1cId], ['cart_order_id', $request->cartId]])->delete();

        $cart = Cart::with('item.gift')->where('profile_id', $profile->id)->get();
        $inCart = [
            'price' => 0,
            'count' => 0,
            'profile_id' => $profile->id,
            'item_1c_id' => $request->item1cId,
            'cart_order_id' => $request->cartId,
        ];

        foreach ($cart as $c) {

            // наценка/скидка markup. Учитывается в отображении цен на сайте
            // расчетный markup
            $markup = $profile['markup'] / 100 + 1;
            // максимальный markup
            if($c->item->bel_price != 0) {
                $maxMarkup = $c->item->price_mr_bel/$c->item->bel_price;
                // если расчетный больше, используем максимальный
                if($markup > $maxMarkup) $markup = $maxMarkup;
            } else {
                $markup = 1;
            }

            $price = ceil($c->item->priceFromCountBYN($c->count) * $markup * 100) / 100;

            $inCart['price'] += $c->count * $price;
            $inCart['count'] += $c->count;
            if ($c->item->gift) {
                $inCart['count'] += $c->count;
            }
        }

        return $inCart;

    }

    public function createOrder(Request $request)
    {

        $profile = $request->user()->profile()->with('address', 'contact', 'subscribe')->first();

        if($profile->is_service == 1) {
            // если сервис, проверяем пришло ли изображение 1
            if (!$request->file('image')[1]) {
                return back()->with('no_pic', 'no_pic');
            }

            // валидация изображений
            $this->validate($request, [
                'image.*' => 'image|mimes:jpeg|max:10500',
            ]);
        }

        $currentCartId = $request->cart_id == 0 ? null : $request->cart_id;
        $data = $this->getUserData($request);
        $cart = Cart::with('item')->where('profile_id', $profile->id)->where('cart_order_id', $currentCartId)->get();

        $delivery = DeliveryType::where('id', $request->delivery_type)->first();
        $calc = CalculationType::where('id', $request->calc_type)->first();
        $address = $profile->address()->where('id', $request->delivery_address)->first();
        $orderItemModels = [];

        $generalPrice = 0;
        $generalWeight = 0;
        $generalSaving = 0;

        $general_discount = $delivery->action + $calc->action + $profile->discount;

        if (!count($cart)) {
            return redirect('/');
        }

        //проверяем есть ли товар из корзины на складе, если закончился, записываем эту поз. в массив
        // $cart_alert = [];
        // foreach ($cart as $c) {
        //     $item_count = Item::where('id', $c->item_id)->first(['count']);
        //     if($item_count->count == 0) $cart_alert[] = $c->item_id;
        // }
        // редиректим назад, если количество к корзине больше чем на складе
        // if(!empty($cart_alert)) {
        //     return back()->with('cart_alert', $cart_alert);
        // }

        foreach ($cart as $c) {
            if (!isset($c->item)) {
                continue;
            }

            if ($profile->is_service != 1) {
                foreach ($request->price as $id => $pr) {
                    if ($id == $c->item->{'1c_id'}) {
                        $price = $pr;
                        break;
                    }
                }
            } else {
                $price = 0;
            }

            // $orderItem['item_id'] = $c->item->id;
            $orderItem['item_1c_id'] = $c->item->{'1c_id'};
            $orderItem['item_name'] = $c->item->name;
            $orderItem['item_price'] = $price;
            // $orderItem['item_price'] = $c->item->priceFromCountBYN($c->count);
            $orderItem['item_count'] = $c->count;

            if ($c->item->discounted) {
                // Если есть скидка
                $priceWithCalc = $orderItem['item_price'];
                //$priceWithCalc = $orderItem['item_price'] + ($orderItem['item_price'] * ($general_discount / 100)) ;
                //$generalSaving += ($orderItem['item_price'] * ($general_discount / 100)) * $c->count;
                // Потестить.

                //dd($general_discount, $c->item->getMinCount(), $c->count, $c->item );

                // Если товар акционный, а общая скидка минусовая, и количество товара больше или равно акцонному,
                // то другие скидки не применяются.

                if ($general_discount < 0 and ($c->count >= (int)$c->item->getMinCount()) and $c->item->is_action) {
                    $priceWithCalc = $orderItem['item_price'];
                    $generalSaving += 0;
                } else {
                    //$priceWithCalc = $orderItem['item_price'] ;
                    $priceWithCalc = $orderItem['item_price'] + ($orderItem['item_price'] * ($general_discount / 100));

                    if ($general_discount) {
                        if ($priceWithCalc < ($c->item->price_min_bel)) {
                            $priceWithCalc = $c->item->price_min_bel;
                            $generalSaving += 0;
                        }
                    }

                    $generalSaving += 0;
                }
            } else {

                $priceWithCalc = $orderItem['item_price'] + ($orderItem['item_price'] * ($general_discount / 100));
                $generalSaving += ($orderItem['item_price'] * ($general_discount / 100)) * $c->count;

                if ($general_discount) {
                    if ($priceWithCalc < ($c->item->price_min_bel)) {
                        $priceWithCalc = $c->item->price_min_bel;
                        $generalSaving += 0;
                    }
                }
            }
//
//
            $orderItem['item_price'] = $priceWithCalc;

            /*
             *
            if($c->item->discounted){
                $priceWithCalc = $c->item->bel_price;
                if($general_discount > 0){
                    $priceWithCalc = $c->item->bel_price_ + ($c->item->bel_price * ($general_discount / 100)) ;
                    $generalSaving += ($c->item->bel_price * ($general_discount / 100)) * $c->count;
                }
            } else {
                $priceWithCalc = $c->item->bel_price + ($c->item->bel_price * ($general_discount / 100)) ;
                $generalSaving += ($c->item->bel_price * ($general_discount / 100)) * $c->count;
            }

//             */
//
            $orderItem['item_sum_price'] = round($priceWithCalc, 2) * $c->count;

            $generalPrice += $orderItem['item_sum_price'];
            $generalWeight += $c->item->weight * $c->count;
//
            $orderItemModels[] = new OrderItem($orderItem);
//
            if ($c->item->gift) {
                // $orderItem['item_id'] = $c->item->gift->id;
                $orderItem['item_1c_id'] = $c->item->gift->{'1c_id'};
                $orderItem['item_name'] = $c->item->gift->name;
                $orderItem['item_price'] = $c->item->gift->bel_price;
                $orderItem['item_count'] = $c->count;
                $orderItem['item_sum_price'] = 0;
                $generalWeight += $c->item->gift->weight * $c->count;
//
                $orderItemModels[] = new OrderItem($orderItem);
            }
        }

        $order['profile_id'] = $profile->id;
        $order['delivery'] = $delivery->text;
        $order['calculation'] = $calc->text;
        $order['general_discount'] = $delivery->action + $calc->action + $profile->discount;

        $order['weight'] = $generalWeight;
        $order['savings'] = $generalSaving;
        $order['price'] = $generalPrice;

        if ($address) {
            $order['address'] = $address->address;
        }
        $order['personal_discount'] = $profile->discount;
        $order['comment'] = $request->comment_to_order;

        // дополнительные поля для Сервиса
        if($profile->is_service == 1) {
            $order['client_name'] = $request->client_name;
            $order['client_phone'] = $request->client_phone;
            $order['item_name'] = $request->item_name;
            $order['item_1c_id'] = intval($request->item_1c_id);
            $order['item_sn'] = $request->item_sn;
            $order['item_sale_date'] = $request->item_sale_date;
            $order['item_defect'] = $request->item_defect;
            $order['item_diagnostic'] = $request->item_diagnostic;
        }

        $order = Order::create($order);
        $order->items()->saveMany($orderItemModels);

        // если сервис, ресайзим и записываем изображения
        if($profile->is_service == 1) {

            //пути куда пишем файлы
            $path_big_pic = public_path().'/storage/service-images/'.$order->id.'/';
            $path_small_pic = public_path().'/storage/service-images-trumbs/'.$order->id.'/';
            // создаем директории
            mkdir($path_big_pic);
            mkdir($path_small_pic);

            foreach ($request->file('image') as $key => $value) {

                // формируем имя для файла
                $filename = $order->id.'_'.$key.'.'.strtolower($value->getClientOriginalExtension());
                // полные пути к файлам
                $image_big_path = $path_big_pic.$filename;
                $image_small_path = $path_small_pic.$filename;
                // ресайзим и записываем
                $img_result_big = Image::make($value->getRealPath())
                    ->resize(2000, null, function($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    })
                    ->save($image_big_path);
                $img_result_sm = Image::make($value->getRealPath())
                    ->resize(null, 150, function($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->save($image_small_path);
            }
        }

// dd('СТОП');

        // Очистка корзины
        Cart::where('profile_id', $profile->id)->where('cart_order_id', $currentCartId)->delete();

        // Отправляем сообщения
        if($profile->is_service == 1) {
            $client_type_str = " от Сервисного центра";
        } else {
            $client_type_str = " от Контрагента";
        }

        // хэдеры
        $email_headers['subject'] = "Заказ ".$order->id.$client_type_str;
        $email_headers['email_from'] = setting('email_from');
        $email_headers['headers_from'] = 'Alfastok';

        // Если не один емайл, делим
        $manager_emails = explode(';', $profile->manager_email);

        if (!$profile->manager_email) {
            if($profile->is_service == 1) {
                // хэдер
                $email_headers['email_to'] = setting('email_to_service'); // руководителю сервисного отдела
            } else {
                // хэдер
                $email_headers['email_to'] = setting('email_to_other'); // руководителю отдела продаж
            }
            // отправляем
            \Mail::send('emails.order', ['profile' => $profile, 'order' => $order], function ($message) use ($email_headers) {
                $message->from($email_headers['email_from'], $email_headers['headers_from']);
                $message->to($email_headers['email_to'])->subject($email_headers['subject']);
            });
            unset($email_headers['email_to']);
        } else {
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
        }

        // отправляем клиенту (кроме Сервиса)
        $email_headers['subject'] = "Заказ ".$order->id." на Alfastok.by";

        if($profile->is_service != 1) {
            // если несколько адресов, отправляем на первый
            $client_emails = explode(';', $profile->email);

            if ($profile->subscribe->copy_order and $client_emails[0]) {
                // хэдеры
                $email_headers['email_to'] = trim($client_emails[0]);
                // отправляем
                \Mail::send('emails.orderToClient', ['profile' => $profile, 'order' => $order], function ($message) use ($email_headers, $profile) {
                    $message->from($email_headers['email_from'], $email_headers['headers_from']);
                    $message->to($email_headers['email_to'])->subject($email_headers['subject']);
                });
            }
        }

        return redirect('pages/blagodarim-za-zakaz'); // Редирект на страницу с благодарностью
    }


    public function serviceEditPic(Request $request) {
        // валидация изображения
        $this->validate($request, [
            'image' => 'image|mimes:jpeg|max:10500',
        ]);

        //пути куда пишем файлы
        $path_big_pic = public_path().'/storage/service-images/'.$request->order.'/';
        $path_small_pic = public_path().'/storage/service-images-trumbs/'.$request->order.'/';

        // формируем имя для файла
        $filename = $request->order.'_'.$request->index.'.'.strtolower($request->file('image')->getClientOriginalExtension());

        // полные пути к файлам
        $image_big_path = $path_big_pic.$filename;
        $image_small_path = $path_small_pic.$filename;
        // ресайзим и записываем
        $img_result_big = Image::make($request->file('image')->getRealPath())
            ->resize(2000, null, function($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            })
            ->save($image_big_path);
        $img_result_sm = Image::make($request->file('image')->getRealPath())
            ->resize(null, 150, function($constraint) {
                $constraint->aspectRatio();
            })
            ->save($image_small_path);

        $res = "Изображение успешно загружено<br><span style='font-size: 0.8em'>(Внимание! Чтобы увидеть результат, необходимо обновить &laquo;кэш&raquo; браузера. Нажмите сочетание клавиш &laquo;Ctrl + F5&raquo;)</span>";

        return back()->with('res', $res);
    }

    public function serviceDeletePic(Request $request) {
        $path = public_path().'/storage/service-images/'.$request->order.'/'.$request->order.'_'.$request->index.'.jpg';
        unlink($path);
        $path = public_path().'/storage/service-images-trumbs/'.$request->order.'/'.$request->order.'_'.$request->index.'.jpg';
        unlink($path);

        $res = "Изображение удалено!";

        return back()->with('res', $res);
    }

    protected function getChipItemsFromCart($cart)
    {
        $categoryIds = $cart->map(function ($cat) {
            if ($cat->item) {
                return $cat->item->{'1c_id'};
            }

        });


        $cheapsList = Item::whereIn('1c_id', $categoryIds)->where('cheap_goods', '<>', '')->pluck('cheap_goods');
        $cheapIds = [];
        foreach ($cheapsList as $chStr) {
            $chIds = explode(',', $chStr);
            foreach ($chIds as $chId) {
                $cheapIds[] = $chId;
            }
        }

        return Item::whereIn('1c_id', $cheapIds)->get();
    }

}
