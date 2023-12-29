<?php

namespace App\Traits;

use App\Models\Cart;
use App\Models\Item;

trait MiniCartDataTrait
{
    public function getCartData()
    {
        // id профайла юзера
        $profile_id = \Auth::user()->profile->id;

        // курс доллара
        $usd_opt = setting('header_usd');

        // берем товары корзины
        $cart = Cart::where('profile_id', $profile_id)->with('item')->get();

        // количество единиц товара
        $position_count = 0;
        // количество единиц товара
        $item_count = 0;
        // стоимость всего товара
        $item_price = 0;

        foreach ($cart as $value) {
            // берем товар
//            $item = Item::where('id_1c', $value->item_1c_id)->first(['adjustable', 'price_usd', 'price_rub', 'discount_str']);
            $item = $value->item;

            // если не пусто
            if ($item) {

                // добавляем количество позиций
                $position_count ++;

                // добавляем количество товаров
                $item_count += $value->count;

                // берем цену
                if ($item->adjustable == 1) {

                    $price = $item->price_rub;

                } else {

                    $price = number_format($item->price_usd * $usd_opt, '2', '.', '');
                }

                // смотрим есть ли дисконт
                if ($item->discount_str) {

                    //берем минимальную цену
                    $min_price = number_format($item->price_min_usd * $usd_opt, '2', '.', '');

                    // делим скидки
                    $discounts = explode(';', $item->discount_str);

                    foreach ($discounts as $element) {

                        // делим элементы скидки
                        $element = explode('|', $element);

                        // мин кол-во на которое действует
                        $condition = $element[0];
                        // дисконтная цена
                        $discount_price = $element[1];
                        // дата начала
                        $date_start = $element[2];
                        // дата окончания
                        $date_end = $element[3];

                        if (date('Y-m-d') >= $date_start && ($date_end == '0000-00-00' || date('Y-m-d') <= $date_end)) {

                            // если количество заказанного товара больше(равно) количества со скидкой
                            if ($value->count >= $condition) {
                                $price = $discount_price;
                                // $price = number_format($price * (100 - $element[4]) / 100, '2', '.', '');

                                // если минимальная цена выше
                                if ($min_price > $price) {
                                    // берем минимальную
                                    $price = $min_price;
                                }
                            }
                        }
                    }
                }

                // добавляем к общей стоимости
                $item_price += number_format($price * $value->count * 1.2, '2', '.', '');

            } else { // если товара нет (удален)

                // удаляем товар из корзины
                Cart::where('id', $value->id)->delete();

            }

        }

        // записываем в дату
        $data['position_count'] = $position_count;
        $data['item_count'] = $item_count;
        $data['item_price'] = number_format($item_price, '2', '.', '');

        return $data;
    }
}
