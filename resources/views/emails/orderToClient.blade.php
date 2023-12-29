<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
</head>
<body>

    <h2>Заявка {{$order->id}}</h2>

    <table>

        <tr style="text-align: left;">
            <td style="width: 300px; border: solid 1px #ccc; margin: 0; padding: 5px;">Наименование</td>
            <td style="width: 100px; border: solid 1px #ccc; margin: 0; padding: 5px;">Цена рачетная</td>
            <td style="width: 50px; border: solid 1px #ccc; margin: 0; padding: 5px;">Количество</td>
            <td style="width: 100px; border: solid 1px #ccc; margin: 0; padding: 5px;">Итого</td>
        </tr>

        @foreach($order->items as $item)

        <tr>
            <td style="border: solid 1px #ccc; margin: 0; padding: 5px;">{{ $item->item_name }}</td>
            <td style="border: solid 1px #ccc; margin: 0; padding: 5px;">{{ $item->item_price }}</td>
            <td style="border: solid 1px #ccc; margin: 0; padding: 5px;">{{ $item->item_count }}</td>
            <td style="border: solid 1px #ccc; margin: 0; padding: 5px;">{{ $item->item_sum_price }}</td>
        </tr>

        @endforeach

    </table>

<p>
    <b>Общая цена: </b> {{ number_format($order->price, 2) }} BYN.
    <br>
    <b>Общий вес: </b>{{ number_format($order->weight, 2) }} кг.
</p>

<p>
    <b>Тип цен:</b> {{ ($profile->type_price == 2) ? "Розница" : "Дилер" }}
    <br>
    <b>Оплата:</b> {{ $order->calculation }}
    <br>
    <b>Доставка:</b> {{ $order->delivery }}

    @if($order->delivery != "Самовывоз")

    <br>
    {{ $order->address }}

    @endif

</p>

<p>
    <b>Комментарий:</b>
    <br>
    {{ $order->comment }}
</p>



</body>
</html>