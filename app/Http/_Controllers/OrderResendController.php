<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use SimpleXMLElement;
use App\Helpers\XMLHelper;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\RequestOptions;

use App\Order;
use App\OrderItem;
use App\Profile;
use App\Item;

class OrderResendController extends Controller
{
    public function resendFromDB()
    {
        // берем неотправленные ордера
        $orders = Order::where('is_send', 0)->pluck('id')->toArray();

        // если есть
        if (count($orders)) {
            // переотправляем
            foreach ($orders as $order_id) {
                $this->resesendOrder($order_id);
            }

            return "Отправлено заказов - ".count($orders);
        } else {
            return "Нет заказов для отправки";
        }


    }

    public function resesendOrder($order_id)
    {
        // берем ордер
        $order = Order::where('id', $order_id)->first([
            'id',
            'profile_id',
            'delivery_partner_uuid',
            'agreement_uuid',
            'price',
            'comment'
        ]);

        // берем товары ордера
        $order_items = OrderItem::where('order_id', $order_id)->get([
            'item_1c_id',
            'item_count',
            'item_sum_price'
        ]);

        // берем email из профайла
        $email = Profile::where('id', $order->profile_id)->first(['email'])->email;

        // формируем xml для отправки в 1с
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><data></data>');
        $xml->addAttribute('version', '1.0');
        $xml->addChild('type', 'request');
        $xml->addChild('id', $order->id);
        if ($order->delivery_partner_uuid) {
            $xml->addChild('partner', $order->delivery_partner_uuid);
        } else {
            $xml->addChild('partner');
        }

        $xml->addChild('email', $email);
        $xml->addChild('agreement', $order->agreement_uuid);
        $xml->addChild('sum', $order->price);
        $xml->addChild('comment', $order->comment);
        $xml->addChild('products');

        foreach($order_items as $value) {

            $product = $xml->products->addChild('product');

            $product->addChild('uuid', Item::where('id_1c', $value->item_1c_id)->first(['uuid'])->uuid);
            $product->addChild('amount', $value->item_count);
            $product->addChild('sum', $value->item_sum_price);
        }

        $xml = $xml->asXML();

        // отправка заказа в 1с8
        $client = new GuzzleClient();
        // $credentials = base64_encode($log.':'.$pass);

        try {

            $response = $client->post('http://93.125.106.243/UT_Site/hs/site.exchange/updInf', [
                'connect_timeout' => 10,
                'headers' => [
                    'objectType' => 'request',
                    // 'Authorization' => 'Basic '.$credentials,
                    'Content-Type' => 'application/xml',
                ],
                'body' => $xml,
            ]);

            // получаем код ответа
            $status_code = $response->getStatusCode();
            // $body = $response->getContent();

        } catch (GuzzleException $e) {

            // ничего не делаем, просто пропускаем...
            $status_code = '';
        
        }

        // если запрос отправлен успешно (код 200)
        if($status_code == 200) {
            // меняем статус в БД
            Order::where('id', $order->id)->update(['is_send' => 1]);
        }

dd($xml, $status_code);

    }
}
