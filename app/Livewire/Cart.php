<?php

namespace App\Livewire;


use App\Models\{Agreement, Cart as CartModel, Item};
use App\Actions\IsItemCanDiscount;
use App\Actions\ItemPriceCalculator;
use Livewire\Attributes\On;
use Livewire\Component;

class Cart extends Component
{

    public CartModel $cart;
    private ?Agreement $agreementType = null;
    public Item $item;
    public float $calculatedPrice;
    public float $discount = 0;
    public float $sum;

    public function mount()
    {
        $this->item = $this->cart->item;
        $this->calculatedPrice = $this->item->price_rub;
        $this->sum();
    }

    #[On('agreement-updated')]
    public function setAgreement(Agreement $agreementType)
    {
        $this->agreementType = $agreementType;
        if(app(IsItemCanDiscount::class)->__invoke($this->item)){
            $this->discount = $agreementType->formula;
            $this->calculatedPrice = app(ItemPriceCalculator::class)->agreement($agreementType)->calculate($this->cart);
        }
//        $this->dispatch('cart-updated', $this->cart->count);
    }

    public function render()
    {
        $this->sum();
        return view('livewire.cart');
    }

    public function hydrate(): void
    {
//        dd(1);
        $this->dispatch('cart-updated', collect([
            'count' => $this->cart->count,
            'sum' => $this->sum
        ]));
    }

    private function sum(): void
    {
        $this->sum = $this->calculatedPrice * $this->cart->count;
    }

}
