<?php

namespace App\Objects;

use App\Models\Agreement;
use App\Models\Cart as CartModel;

class Cart
{

    private Agreement $agreementType;
    private CartModel $cart;


    public function __construct(CartModel $cart)
    {
        $this->cart = $cart;
    }



}
