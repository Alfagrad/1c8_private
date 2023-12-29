<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

class Item extends Model
{
    protected $guarded = [];

    protected $primaryKey = 'uuid';

    protected $fillable = ['id_1c','uuid','category_id_1c','category_uuid','type','edition','in_price','name','price_usd','price_rub','price_min_usd','price_min_rub',
        'price_mr_usd','price_mr_rub','norm_hour','spec_price','amount','reserve','locked','expected','expected_date','synonyms','in_archive','is_component',
        'is_new_product','date_new_product','content','more_about','apply','equipment','brand_uuid','factory','country','vendor_code','barcode','codeTNVD','packaging',
        'weight','netto','width','depth','height','life_time','guarantee_period','forget_buy','buy_with','cheap_goods','video','cert_name','cert_end_date',
        'schemes','image','image_mid','image_sm','importer','adjustable','analogue_container_uuid'];

    protected $casts = [
        'is_new_item' => 'boolean',
        'in_archive' => 'boolean',
        'in_price' => 'boolean',
        'is_component' => 'boolean',
        'spec_price' => 'boolean',
        'adjustable' => 'boolean',
        'edition' => 'integer',
        'packaging' => 'integer',
        'expected_date' => 'date',
        'certificate_exp' => 'date',
    ];

//vendor_code

    public function brand()
    {
        return $this->hasOne('App\Models\Brand', 'uuid', 'brand');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category', '1c_category_id', 'id_1c');
    }

    public function discounts(): BelongsToMany
    {
        return $this->belongsToMany(Discount::class, 'discount_products', 'item_uuid', 'discount_uuid', 'uuid', 'uuid')
            ->where('date_start', '<=', date('Y-m-d'))
            ->where(
                fn(Builder $query): Builder => $query
                    ->where('date_end', '>=', date('Y-m-d'))
                    ->orWhere('date_end', '0000-00-00')

            )
            ->with('values');
    }

    public function getDiscountValuesAttribute(): Collection
    {
        $values = collect();
        foreach ($this->discounts as $discount) {
            $values = $values->merge($discount->values);
        }
        $values = $values->groupBy('condition')->map(fn($groupValues) => $groupValues->sortBy('value')->first());
        return $values;
    }

    public function guides()
    {
        return $this->hasMany('App\Models\ItemGuide', 'item_uuid', 'uuid');
    }

    public function images()
    {
        return $this->hasMany('App\Models\ItemImage', 'item_uuid', 'uuid');
    }

    public function charValues()
    {
        return $this->hasMany('App\Models\CharacteristicItem', 'item_1c_id', 'id_1c')->with('charName');
    }

    public function analogs()
    {
        return $this->hasMany('App\Models\ItemAnalogue', 'container_uuid', 'analog_container_uuid');
    }

    public function getPriceRubAttribute($price): float
    {
        return $this->addNDS($this->calculatePrice($price, 'price_usd'));
    }

//    public function getPriceUsdAttribute($price): float
//    {
//        return $this->addNDS($price);
//    }

    public function getPriceMrRubAttribute($price): float
    {
        return $this->calculatePrice($price, 'price_mr_usd');
    }

    public function getMinPriceAttribute($price)
    {
        return $this->calculatePrice($price, 'price_min_usd');
    }

    private function calculatePrice(?float $priceRub, string $usd): float
    {
        return $this->adjustable ? $priceRub : ($this->getOriginal($usd) * setting('header_usd'));
    }

    private function addNDS(float $price): float
    {
        return $price * 1.2;
    }

    public function getIsComponentAttribute(int $isComponent): int
    {
        $categories = [16320, 18093, 21650];
        if ($isComponent == 0 && $this->category && in_array($this->category->id_1c, $categories)) {
            $isComponent = 1;
        }
        return $isComponent;
    }

//     /**
//      * @description Получаем из строки количество и цену
//      * @return array|bool
//      */
//     public function createListPricesUSD()
//     {
//         ///0-5.6;5-16.4;

//         if ($this->discounted) {
//             $listDiscount = [];
//             $prevlistDiscounts = explode(';', $this->discounted);
//             $prevlistDiscounts = array_diff($prevlistDiscounts, ['', ""]);
//             foreach ($prevlistDiscounts as $pLD) {
//                 list($count, $price) = explode('-', $pLD);
//                 $listDiscount[] = [
//                     'count' => $count,
//                     'price' => $price
//                 ];
//             }
//             return $listDiscount;
//         }
//         return false;
//     }

//     /**
//      * @description Получаем из строки количество и цену
//      * @return array|bool
//      */
//     public function createListPricesBYN()
//     {
//         ///0-5.6;5-16.4;
//         if ($this->discounted_rub) {

//             $listDiscount = [];
//             $prevlistDiscounts = explode(';', $this->discounted_rub);
//             // Убрать пустой массив

//             $prevlistDiscounts = array_diff($prevlistDiscounts, ['', ""]);
//             foreach ($prevlistDiscounts as $pLD) {
//                 if ($pLD) {
//                     list($count, $price) = explode('-', $pLD);
//                     $listDiscount[] = [
//                         'count' => $count,
//                         'price' => $price
//                     ];
//                 }

//             }
//             return $listDiscount;
//         }
//         return false;
//     }

//     protected $appends = ['giftCount'];

//     static $priceType = 0;

//     /* Новые связи и функции */
//     public function getChilds()
//     {
//         return $this->hasMany('App\Models\Item', '1c_parent_id', '1c_id');
//     }

//     public function getParent()
//     {
//         return $this->hasMany('App\Models\Item', '1c_id', '1c_parent_id');
//     }

//     // public function setAnalogListAttribute($value)
//     // {
//     //     $this->attributes['analog_list'] = implode(',', $value);
//     // }

//     // public function getAnalogListAttribute($value)
//     // {
//     //     return explode(",", $value);
//     // }

//     public function getScheme()
//     {
//         return $this->hasMany('App\Models\Scheme', 'item_id', '1c_id');
//     }

//     public function getSchemeSpare()
//     {
//         return $this->hasMany('App\Models\SchemeParts', 'spare_id', '1c_id');
//     }

//     public function getSchemeParent()
//     {
//         return $this->hasMany('App\Models\SchemeParts', 'parent_id', '1c_id');
//     }

//     /*********************************************/

//     public function category()
//     {
//         return $this->belongsTo('App\Models\Category', '1c_category_id', '1c_id');
//     }

//     public function actions()
//     {
//         return $this->hasMany('App\Models\ItemAction', 'item_1c_id', '1c_id');
//     }


//     public function gift()
//     {
//         return $this->hasOne('App\Models\Item', '1c_id', 'as_gift');
//     }


//     public function cheap_good()
//     {
//         return $this->hasOne('App\Models\Item', '1c_id', 'cheap_goods');
//     }

//     public function characteristics()
//     {
//         return $this->belongsToMany('App\Models\Characteristic', 'characteristic_item', 'item_1c_id', 'characteristic_1c_id')->withPivot('value');
//     }

//     public function getBelPriceAttribute()
//     {
//         if (self::$priceType == 0) {
//             return $this->price_bel_1;
//         } else {
//             return $this->price_bel_1;
//         }
//     }

//     public function getUsdPriceAttribute()
//     {
//         if (self::$priceType == 0) {
//             return $this->price_usd_1;
//         } else {
//             return $this->price_usd_1;
//         }
//     }


//     public function getPriceMinDiscountAttribute($currency)
//     {
//         //COM: Расчет скидки, берем последнюю цену. А нужно большее количество
//         if ($currency == 'BYN') {
//             $prices = $this->createListPricesBYN();
//         } else {
//             $prices = $this->createListPricesUSD();
//         }

//         if ($prices) {
//             $end = end($prices);
//             return round((float)$end['price']);
//         }
//         return 0;
//     }


//     public function priceMinDiscount($currency)
//     {
//         //COM: Расчет скидки, берем последнюю цену. А нужно большее количество
//         if ($currency == 'BYN') {
//             $prices = $this->createListPricesBYN();
//         } else {
//             $prices = $this->createListPricesUSD();
//         }

//         if ($prices) {
//             $end = end($prices);
//             return round((float)$end['price'], 2);
//         }
//         return 0;
//     }

//     public function getPriceMinDiscountUsdAttribute()
//     {
//         return $this->priceMinDiscount('USD');
//     }


//     public function getPriceMinDiscountBynAttribute()
//     {
//         return $this->priceMinDiscount('BYN');
//     }

//     /**
//      * @description Вычисляем максимальную скидку
//      * Валюта тут не важна
//      */
//     public function getMaxDiscount()
//     {
//         $prices = $this->createListPricesUSD();

//         //COM: Можно использовать getPriceMinDiscountAttribute
//         if ($prices) {
//             $end = end($prices);
//             //(1-125/150)*100=17%

//             if ($end['price'] and $this->price_usd) {
//                 $maxDiscount = (1 - (float)$end['price'] / $this->price_usd) * 100;
//             } else {
//                 $maxDiscount = 0;
//             }

//             return round($maxDiscount);
//         }
//     }


//     public function getDiscountValueAttribute()
//     {
//         if ($this->discounted) {
//             return $this->getMaxDiscount();
//         }
//         return '';
//     }

//     public function getDiscountValueTextAttribute()
//     {
//         $discountValue = $this->getDiscountValueAttribute();
//         if ($discountValue) {
//             return 'Акция - ' . $discountValue . '%';
//         }
//         return 'Акция';
//     }

//     public function priceFromCountBYN($count)
//     {

//         if ($this->discounted_rub) {

//             $prices = $this->createListPricesBYN();
//             $finishPrice = $this->bel_price;
//             foreach ($prices as $p) {
//                 if ($count < $p['count']) {
//                     return $finishPrice;
//                 } else {
//                     $finishPrice = $p['price'];
//                 }
//             }
//             return $finishPrice;


//             //COM в цикле перебераем количество
//             // если меньше всех то используем:
//             //$this->price_bel_1;
//             // Иначе из массива

//             // Как узнать ) сравниваем в цикле больше или равно. Если меньше то используем придедушее ) если больше и следующего нет то используем последующее


//         }

//         return $this->bel_price;
//     }

//     // для каталога
//     public function viewPriceList()
//     {

// // dd($data_markup);
//         if ($this->discounted_rub) {
//             $prices = $this->createListPricesBYN();
//             //«от 10шт 176,4руб, от 20шт 156,8руб»
//             $pricesText = '';

//             foreach ($prices as $price) {
//                 $pricesText .= ' от ' . $price['count'] . ' шт ' . number_format((float)$price['price'], 2, '.', '') . ' руб, ';
//             }
//             if ($pricesText) {
//                 $pricesText = substr($pricesText, 0, -2);
//                 return $pricesText;
//             }
//         }
//         return false;
//     }

//     // для карточки
//     public function viewPriceList2()
//     {
//         if ($this->discounted_rub) {
//             $prices = $this->createListPricesBYN();
//             //«от 10шт 176,4руб, от 20шт 156,8руб»
//             $pricesText = '';

//             foreach ($prices as $price) {
//                 $pricesText .= 'от ' . $price['count'] . ' шт ' . number_format((float)$price['price'], 2, '.', '') . ' руб<br>';
//             }
//             if ($pricesText) {
//                 $pricesText = substr($pricesText, 0, -4);
//                 return $pricesText;
//             }
//         }
//         return false;
//     }


//     public function getMinCount()
//     {
//         if ($this->discounted_rub) {
//             $prices = $this->createListPricesBYN();
//             $discountCounts = [];

//             foreach ($prices as $price) {
//                 $discountCounts[] = $price['count'];
//             }

//             return min($discountCounts);
//         }

//         return false;
//     }

}
