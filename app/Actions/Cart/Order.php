<?php

namespace App\Actions\Cart;

use App\Events\OrderCreated;
use App\Models\Agreement;
use App\Models\Order as OrderModel;
use App\Models\OrderItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Order
{

    public function __invoke(OrderModel $order, array $validated, array $orderItems, Agreement $agreementType): OrderModel
    {
        $orderItems = collect($orderItems);
        $sum = $orderItems->sum('item_sum_price');
        if ($sum < config('settings.min_price_to_order') || $orderItems->min('item_sum_price') < config('settings.min_cart_to_order')) {
            return $order;
        }

        DB::beginTransaction();

        $order->fill($this->getOrderData($orderItems, $validated, $agreementType, $sum));
        $order->save();
        $order->items()->saveMany($this->getOrderItemsData($orderItems));

        DB::commit();

        if ($order->exists) {
            event(new OrderCreated($order));
        }
        return $order;
    }

    private function getOrderData(Collection $orderItems, array $validated, Agreement $agreementType, float $sum): array
    {

        $data = [
            'profile_id' => profile()->id,
            'agreement_uuid' => $agreementType?->uuid,
            'calculation' => $agreementType?->name,
            'delivery' => 'Самовывоз - доработать',
            'delivery_partner_uuid' => 0,
            'address' => $validated['address'],
            'savings' => $orderItems->sum('cart.count'),
            'weight' => $orderItems->sum('cart.sum_weight'),
            'price' => price($sum),
            'comment' => $validated['comment'],
            'client_name' => $validated['name'] ?? null,
            'client_phone' => $validated['phone'] ?? null,
            'item_name' => $validated['itemName'] ?? null,
            'item_sn' => $validated['serial'] ?? null,
            'item_sale_date' => $validated['buyDate'] ?? null,
            'item_defect' => $validated['fault'] ?? null,
            'item_diagnostic' => $validated['diagnostic'] ?? null,
        ];
        return $data;
    }

    private function getOrderItemsData(Collection $orderItems): Collection
    {
        return $orderItems->map(fn(OrderItem $orderItem): OrderItem => new OrderItem($orderItem->only($orderItem->getFillable())));
    }

}
