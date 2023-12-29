<?php

namespace App\Livewire\Forms;

use App\Models\Order;
use App\Repositories\AgreementTypeRepository;
use Livewire\Attributes\Validate;

class DealerOrderForm extends OrderForm
{

    #[Validate('required|string')]
    public string $delivery = '';

    #[Validate('required_if:delivery,Доставка|nullable|string')]
    public string $address = '';

    #[Validate('required|string|exists:agreement_types,uuid')]
    public ?string $agreementTypeUuid = null;

    public array $cartsSelected = [];

    public function delete(?int $cartId = null): bool
    {
        $carts = profile()->carts();
        if($cartId) {
            $carts->where('id', $cartId);
        } else {
            $carts->whereIn('id', $this->cartsSelected);
        }
        return $carts->delete();
    }

    public function store(Order $order, array $orderItems): Order
    {
        $validated = $this->validate();
        $agreementType = app(AgreementTypeRepository::class)->find($this->agreementTypeUuid);

        $order = app(\App\Actions\Cart\Order::class)($order, $validated, $orderItems, $agreementType);

        return $order;

    }

}
