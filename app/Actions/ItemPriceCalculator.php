<?php

namespace App\Actions;

use App\Models\Agreement;
use App\Models\Cart;
use App\Models\DiscountValue;
use App\Models\Item;
use Illuminate\Support\Collection;

class ItemPriceCalculator
{

    private ?Agreement $agreementType = null;
    private ?DiscountValue $discountValue = null;

    private float $discount = 0;

    public function agreement(?Agreement $agreementType = null): self
    {
        $this->agreementType = $agreementType;
        return $this;
    }

    public function discount(?DiscountValue $discountValue = null): self
    {
        $this->discountValue = $discountValue;
        return $this;
    }

    public function calculate(Cart $cart): float
    {
        $price = $cart->item->price_rub;
        if(!app(IsItemCanDiscount::class)->__invoke($cart->item)){
            return $price;
        }
        $this->discount = $this->discountValue ? $this->discountValue->value : $this->calculateDiscount($cart);
//        $this->discount = 0;
        $price = $this->discount < 0 ? percent($price, $this->discount) : $price;
//        $this->discount
        $price = percent($price, $this->agreementType?->formula ?? 0);
        return $price;
    }

    public function getDiscount(): float
    {
        return $this->discount;
    }

    private function calculateDiscount(Cart $cart): float
    {
        $discountValues = $cart->item->discount_values;
        if($discountValues->count() <= 0){
            return 0;
        }
        $value = $discountValues
            ->filter(fn(DiscountValue $discountValue) => $discountValue->condition <= $cart->count)
            ->min('value');
        return $value;
    }

}
