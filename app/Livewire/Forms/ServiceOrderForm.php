<?php

namespace App\Livewire\Forms;

use App\Models\Order;
use App\Repositories\AgreementTypeRepository;
use Illuminate\Support\Collection;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\WithFileUploads;

class ServiceOrderForm extends OrderForm
{

    use WithFileUploads;

    #[Locked]
    #[Validate('nullable|string')]
    public string $address = '';
    #[Locked]
    public string $agreementTypeUuid = '5547878c-1f58-11ec-9891-005056a0b35f';
    #[Validate('required|string')]
    public string $name = '';
    #[Validate('required|string')]
    public string $phone = '';
    #[Validate('required|string')]
    public string $itemName = '';
    #[Validate('required|integer|min:0')]
    public int $item1cId = 0;
    #[Validate('required|string')]
    public string $serial = '';
    #[Validate('required|date')]
    public string $buyDate = '';
    #[Validate('required|string')]
    public string $fault = '';
    #[Validate('required|string')]
    public string $diagnostic = '';
    #[Validate('required|array')]
    #[Validate(['photos.*' => 'image|mimes:jpeg|max:10500'])]
    public array $photos = [];

    public function store(Order $order, array $orderItems): Order
    {
        $validated = $this->validate();
        $agreementType = app(AgreementTypeRepository::class)->find($this->agreementTypeUuid);
        $order = app(\App\Actions\Cart\Order::class)($order, $validated, $orderItems, $agreementType);

        foreach ($this->photos as $photo) {
            $photo->store('service-images/'.$order->id);
        }

        return $order;
    }

}
