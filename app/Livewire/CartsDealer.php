<?php

namespace App\Livewire;

use App\Models\{Agreement, Cart as CartModel, Order, OrderItem};
use App\Actions\Cart\Relocate;
use App\Actions\Cart\Swap;
use App\Actions\IsItemCanDiscount;
use App\Actions\ItemPriceCalculator;
use App\Actions\RequestToUT;
use App\Helpers\XMLHelper;
use App\Livewire\Forms\DealerOrderForm;
use App\Repositories\AgreementTypeRepository;
use App\Repositories\CartOrderRepository;
use App\Repositories\CartRepository;
use App\Repositories\PartnerRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\{Computed, On, Validate};
use Livewire\Component;

class CartsDealer extends Carts
{

    public DealerOrderForm $form;

    public ?int $cartOrderId = null;
    public string $cartOrderName = 'Основная';

    #[Computed]
    public function agreementsCommon(): Collection
    {
        return app(AgreementTypeRepository::class)->common();
    }

    #[Computed]
    public function agreementsPersonal(): Collection
    {
        return app(AgreementTypeRepository::class)->personal($this->partners->pluck('uuid'));
    }

    #[Computed]
    public function addresses(): Collection
    {
        return app(PartnerRepository::class)->addresses($this->partners);
    }

    #[Computed]
    public function cartOrders(): Collection
    {
        return app(CartOrderRepository::class)->all();
    }

    #[Computed]
    public function partners(): Collection
    {
        return app(PartnerRepository::class)->partners();
    }

    #[On('cart-updated')]
    public function render()
    {
        $orderItems = $this->getOrderItems();
        return view('livewire.carts-dealer', compact('orderItems'));
    }

    #[On('cart-order-updated')]
    public function changeCartOrder(?int $cartOrderId = null)
    {
        $cartOrderId = $cartOrderId > 0 ? $cartOrderId : null;
        $this->cartOrderId = $cartOrderId;
        $this->cartOrderName = $cartOrderId ? app(CartOrderRepository::class)->findOrFail($this->cartOrderId)->name : 'Основная';
        $this->form->cartsSelected = [];
    }

    public function delete(?int $cartId = null): void
    {
        $deleted = $this->form->delete($cartId);
        if(!$deleted){
            $this->dispatch('error', 'Произошла ошибка во время удаления товара');
            return;
        }
        $this->dispatch('cart-updated');
        $this->dispatch('success', 'Товар успешно удален');
    }

    public function store(): void
    {
        $order = new Order();
        $this->authorize('create', $order);

        $order = $this->form->store($order, $this->getOrderItems());

        $this->isStored($order);
    }

    public function copyTo(?int $cartOrderId): void
    {
        $copiedCount = app(Relocate::class)->copy($this->cartsSelected, $this->cartOrderId, $cartOrderId);

        if($copiedCount <= 0){
            $this->dispatch('error', 'Произошла ошибка при копировании товаров');
            return;
        }

        $this->dispatch('success', 'Товары успешно скопированы');
        $this->cartsSelected = [];
        $this->dispatch('cart-updated');

    }

    public function moveTo(?int $cartOrderId): void
    {
        $movedCount = app(Relocate::class)->move($this->cartsSelected, $this->cartOrderId, $cartOrderId);

        if($movedCount <= 0){
            $this->dispatch('error', 'Произошла ошибка при перемещении товаров');
            return;
        }

        $this->dispatch('success', 'Товары успешно перенесены');
        $this->cartsSelected = [];
        $this->dispatch('cart-updated');

    }

    public function swap()
    {
        $swapedCount = app(Swap::class)($this->cartOrderId);
        $swapedCount > 0
            ? $this->dispatch('success', 'Товары успешно обменяны местами')
            : $this->dispatch('error', 'Произошла ошибка при обмене товаров местами');

        $this->setCarts();
        $this->dispatch('cart-updated');
    }

}
