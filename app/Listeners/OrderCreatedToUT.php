<?php

namespace App\Listeners;

use App\Actions\RequestToUT;
use App\Events\OrderCreated;
use App\Helpers\XMLHelper;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class OrderCreatedToUT
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderCreated $event): void
    {
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><data></data>');

        $xml->addAttribute('version', '1.0');
        $xml->addChild('type', 'request');
        $xml->addChild('id', $event->order->id);
        //            if ($delivery_partner_uuid) {
        //                $xml->addChild('partner', $delivery_partner_uuid);
        //            } else {
        //                $xml->addChild('partner');
        //            }
        $xml->addChild('email', profile()->email);
        $xml->addChild('agreement', $event->order->agreement_uuid);
        $xml->addChild('sum', $event->order->price);
        $xml->addChild('comment', $event->order->comment);
        $xml->addChild('products');

        foreach ($event->order->items as $orderItem) {
            $product = $xml->products->addChild('product');
            $product->addChild('uuid', $orderItem->item->uuid);
            $product->addChild('amount', $orderItem->item_count);
            $product->addChild('sum', $orderItem->item_sum_price);
        }

        $XMLHelper = new XMLHelper();
        $xml = $XMLHelper->beauty_xml($xml);
        $status_code = (new RequestToUT())->__invoke($xml);
        if ($status_code == 200) {
            $event->order->update(['is_send' => 1]);
        }
    }
}
