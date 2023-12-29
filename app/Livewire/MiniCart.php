<?php

namespace App\Livewire;


use App\Repositories\CartRepository;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class MiniCart extends Component
{


    public int $items = 0;
    public int $positions = 0;
    public float $sum = 0;

    public function mount(?Collection $carts = null)
    {
        $this->getCart($carts);
    }

    #[On('cart-updated')]
    public function getCart(?Collection $carts = null): void
    {
        $carts = app(CartRepository::class)->all();
        $this->items = $carts->sum('count');
        $this->positions = $carts->count();
        $this->sum = $carts->sum('sum');
    }

    public function render()
    {
        return view('livewire.mini-cart');
    }

}
