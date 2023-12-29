<?php

namespace App\Livewire;

use App\Models\{Agreement, Cart as CartModel, DeliveryType, Order, OrderItem};
use App\Actions\Cart\Relocate;
use App\Actions\Cart\Swap;
use App\Actions\IsItemCanDiscount;
use App\Actions\ItemPriceCalculator;
use App\Actions\RequestToUT;
use App\Helpers\XMLHelper;
use App\Livewire\Forms\ServiceOrderForm;
use App\Repositories\AgreementTypeRepository;
use App\Repositories\CartOrderRepository;
use App\Repositories\CartRepository;
use App\Repositories\PartnerRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;
use Livewire\Attributes\{Computed, Locked, On, Validate};
use Livewire\Component;

class CartsService extends Carts
{

    use WithFileUploads;

    public ServiceOrderForm $form;

    #[Locked]
    public ?int $cartOrderId = null;

    public function setItem(int $id1c, string $name): void
    {
        $this->form->item1cId = $id1c;
        $this->form->itemName = $name;
    }

    #[Computed(persist: true, seconds: 300)]
    public function deliveries(): Collection
    {
        return DeliveryType::all();
    }

    public function render()
    {
        $orderItems = $this->getOrderItems();
        return view('livewire.carts-service', compact('orderItems'));
    }

    public function store()
    {
        $order = new Order();
        $this->authorize('create', $order);
        $order = $this->form->store($order, $this->getOrderItems());
        $this->isStored($order);
    }

}
