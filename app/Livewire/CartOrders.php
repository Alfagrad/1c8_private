<?php

namespace App\Livewire;

use App\Models\CartOrder;
use App\Repositories\CartOrderRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CartOrders extends Component
{

    public ?int $cartOrderId = null;
    public string $cartOrderName = '';
    public int $cartsCount = 0;
    public Collection $cartOrders;

    public function setCartOrders(): void
    {
        $this->cartOrders = app(CartOrderRepository::class)->all();
    }

    public function setCartOrderId($cartOrderId = 0)
    {
        $this->cartOrderId = $cartOrderId == 0 ? null : $cartOrderId;
        if($this->cartOrderId){
            $cartOrder = $this->cartOrders->where('id', $this->cartOrderId)->first();
            $this->cartOrderName = $cartOrder->name;
            $this->cartsCount = $cartOrder->carts->count();
        }
        $this->dispatch('cart-order-updated', $this->cartOrderId);
    }

    public function delete(): void
    {
        if(!$this->cartOrderId){
            return;
        }
        $cartOrder = app(CartOrderRepository::class)->findOrFail($this->cartOrderId);
        $this->authorize('delete', $cartOrder);
        $deleted = DB::transaction(function() use ($cartOrder) {
            return $cartOrder->delete();
        });
        $this->setCartOrders();
        $this->cartOrderId = null;
        $this->dispatch('cart-order-updated', $this->cartOrderId);
    }

    public function deleteAll(): void
    {
        // TODO сделать
//        dd()
    }

    public function render()
    {
        return view('livewire.cart-orders');
    }

}
