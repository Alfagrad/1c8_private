<?php

namespace App\Livewire;


use App\Models\Cart as CartModel;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Attributes\Validate;

class Count extends Component
{

    #[Locked]
    public ?CartModel $cart = null;

    #[Validate('required|integer|min:1')]
    public string $count;

    #[Locked]
    public int $itemId1c;

    public function mount()
    {
        $this->count = $this->cart?->count ?? 0;
    }

    public function minus(): void
    {
        $this->count = $this->count > 0 ? $this->count - ($this->cart->item->packaging ?? 1) : 0;
        $this->updatedCount();
    }

    public function plus(): void
    {
        $this->count = $this->count + ($this->cart->item->packaging ?? 1);
        $this->updatedCount();
    }

    public function updatingCount(string $count): void
    {
        if(is_int($count)){
            $this->count = $count;
        }
    }

    public function updatedCount(): void
    {
        if($this->cart) {
            $this->count <= 0 ? $this->delete() : $this->update();
        } else {
            $this->store();
        }
        $this->dispatch('cart-updated');
    }

    private function delete(): bool
    {
        $this->authorize('delete', $this->cart);
        $deleted = $this->cart->delete();
        $this->cart = null;
        return $deleted;
    }

    private function store(): void
    {
        $this->validate();
        $this->cart = new CartModel();
        $this->authorize('create', $this->cart);
        $this->cart->fill(['item_1c_id' => $this->itemId1c, 'count' => $this->count, 'cart_order_id' => null]);
        auth()->user()->profile->carts()->save($this->cart);
    }

    private function update(): void
    {
        $this->validate();
        $this->authorize('update', $this->cart);
        $this->cart->update(['count' => $this->count]);
    }

    public function render()
    {
        return view('livewire.count');
    }

}
