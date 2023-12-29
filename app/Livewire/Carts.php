<?php

namespace App\Livewire;

use App\Models\{Agreement, Cart as CartModel, Order, OrderItem};
use App\Actions\Cart\Relocate;
use App\Actions\Cart\Swap;
use App\Actions\IsItemCanDiscount;
use App\Actions\ItemPriceCalculator;
use App\Actions\RequestToUT;
use App\Helpers\XMLHelper;
use App\Repositories\AgreementTypeRepository;
use App\Repositories\CartOrderRepository;
use App\Repositories\CartRepository;
use App\Repositories\PartnerRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\{Computed, On, Validate};
use Livewire\Component;

class Carts extends Component
{

//    public Collection $addresses;
//    public Collection $carts;
//    public array $cartsSelected = [];
//    public Collection $cartOrders;
//    public ?AgreementType $currentAgreement = null;

//    public ?int $cartOrderId = null;
//    public string $cartOrderName = 'Основная';

    #[Validate('nullable|string')]
    public string $comment = '';
//    public string $currentAddress = '';

//    public array $orderItems = [];

    public function getOrderItems(): array
    {
        $calculator = app(ItemPriceCalculator::class);

        $agreement = null;
        if($this->form->agreementTypeUuid){
            $agreement = app(AgreementTypeRepository::class)->find($this->form->agreementTypeUuid);
            $calculator->agreement($agreement);
        }

        $orderItems = [];
        foreach ($this->carts as $cart) {
            $orderItem = new OrderItem();

            $orderItem->item_1c_id = $cart->item->id_1c;
            $orderItem->item_name = $cart->item->name;
            $orderItem->item_price = $calculator->calculate($cart);
            $orderItem->discount = $agreement?->formula ?? 0;
            $orderItem->item_count = $cart->count;
            $orderItem->item_sum_price = $orderItem->item_price * $cart->count;
//
            $orderItem->setRelation('item', $cart->item);
            $orderItem->setRelation('cart', $cart);
            $orderItems[] = $orderItem;
        }
        return $orderItems;
    }

    #[Computed]
    public function carts(): Collection
    {
        return app(CartRepository::class)->byCartOrder($this->cartOrderId);
    }

    protected function isStored(Order $order): void
    {
        if(!$order->exists){
            $this->dispatch('error', 'Произошла ошибка при создании заказа');
            return;
        }
        $this->dispatch('success', 'Заказ успешно создан. В ближайшее время с вами свяжется менеджер');
    }

//    public function mount(AgreementTypeRepository $agreementTypeRepository)
//    {
//        $this->cartOrders = app(CartOrderRepository::class)->all();
////        list($partnersUuids, $addresses) = $partnerRepository->get();
////        $this->addresses = $addresses;
//        $this->commonAgreements = $agreementTypeRepository->common();
////        $this->personalAgreements = $agreementTypeRepository->personal($partnersUuids);
//        $this->personalAgreements = collect();
//        $this->resetFields();
//        $this->setCarts();
//    }

//    public function render()
//    {
//        $this->prices();
//        return view('livewire.carts');
//    }



//    #[On('cart-updated')]
//    public function prices(): void
//    {
//        /**
//         * @var $calculator ItemPriceCalculator
//         */
//        $this->resetFields();
//        $calculator = app(ItemPriceCalculator::class)->agreement($this->currentAgreement);
//        foreach ($this->carts as $cart){
//            $discount = 0;
//            $price = $cart->item->price_rub;
//            if(app(IsItemCanDiscount::class)->__invoke($cart->item)){
//                $discount =  $this->currentAgreement?->formula ?? 0;
//                $price = $calculator->calculate($cart);
//            }
//            $this->discounts->put($cart->id, $discount);
//            $this->prices->put($cart->id, $price);
//            $this->sums->put($cart->id, $price * $cart->count);
//        }
//    }

    #[Computed]
    public function addresses()
    {
        return app(PartnerRepository::class)->addresses($this->partners);
    }



    public function setAgreement(string $uuid)
    {
        $this->currentAgreement = app(AgreementTypeRepository::class)->find($uuid);
        $this->prices();
//        $this->dispatch('agreement-updated', $this->currentAgreement);
    }

//    public function delete(?int $cartId = null)
//    {
//        $carts = profile()->carts();
//        if($cartId) {
//            $carts->where('id', $cartId);
//        } else {
//            $carts->whereIn('id', $this->cartsSelected);
//        }
//        $carts->delete();
//        $this->setCarts();
//        $this->dispatch('cart-updated');
//        $this->dispatch('success', 'Товар успешно удален');
//    }
//
//    public function store()
//    {
//        $validated = $this->validate();
//
//        $order = new Order();
//        $this->authorize('create', $order);
//
//        $stored = app(\App\Actions\Cart\Order::class)($order, $validated, $this->carts, $this->currentAgreement, $this->prices, $this->sums);
//
//        if(!$stored){
//            $this->dispatch('error', 'Произошла ошибка при создании заказа');
//            return;
//        }
//
//        $this->dispatch('success', 'Заказ успешно создан. В ближайшее время с вами свяжется менеджер');
//
//    }
//
//    public function copyTo(?int $cartOrderId): void
//    {
//        $copiedCount = app(Relocate::class)->copy($this->cartsSelected, $this->cartOrderId, $cartOrderId);
//
//        if($copiedCount <= 0){
//            $this->dispatch('error', 'Произошла ошибка при копировании товаров');
//            return;
//        }
//
//        $this->dispatch('success', 'Товары успешно скопированы');
//        $this->cartsSelected = [];
//        $this->dispatch('cart-updated');
//
//    }
//
//    public function moveTo(?int $cartOrderId): void
//    {
//        $movedCount = app(Relocate::class)->move($this->cartsSelected, $this->cartOrderId, $cartOrderId);
//
//        if($movedCount <= 0){
//            $this->dispatch('error', 'Произошла ошибка при перемещении товаров');
//            return;
//        }
//
//        $this->dispatch('success', 'Товары успешно перенесены');
//        $this->cartsSelected = [];
//        $this->dispatch('cart-updated');
//
//    }
//
//    public function swap()
//    {
//        $swapedCount = app(Swap::class)($this->cartOrderId);
//        $swapedCount > 0
//            ? $this->dispatch('success', 'Товары успешно обменяны местами')
//            : $this->dispatch('error', 'Произошла ошибка при обмене товаров местами');
//
//        $this->setCarts();
//        $this->dispatch('cart-updated');
//    }





//    private function setCarts(): void
//    {
//        /**
//         * @var $repository CartRepository
//         */
//        $repository = app(CartRepository::class);
//        $this->carts = $repository->byCartOrder($this->cartOrderId);
//    }

//    private function resetFields(): void
//    {
//        $this->discounts = collect();
//        $this->prices = collect();
//        $this->sums = collect();
//    }

}
