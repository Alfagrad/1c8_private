<tr>
    <td>Номер</td>
    <td>Наименование изделия</td>
    <td>Оформлен</td>
    <td>Статус</td>
    <td>Комментарий</td>
</tr>

<tr>
    <td>
        {{ $repair->{'1c_id'} }}
    </td>

    <td>
        {{ $repair->name }}
    </td>

    <td>
        {{ $repair->date->format('d.m.Y') }}
    </td>

    @if($repair->state == config('constants.repair.inWork'))

        <td style="color: red;">
            В работе
        </td>

    @elseif($repair->state == config('constants.repair.ready'))

        <td style="color: green;">
            Готово
        </td>

    @elseif($repair->state == config('constants.repair.issued'))

        @php
            if($repair->shipment_date != "0000-00-00") {
                $shipment_date = " - ".date('d.m.Y', strtotime($repair->shipment_date));
            } else {
                $shipment_date = "";
            }
        @endphp

        <td style="color: green;">
            Выдан {{ $shipment_date }}
        </td>

    @elseif($repair->state == config('constants.repair.return'))

        <td style="color: red;">
            Возврат
        </td>

    @elseif($repair->state == config('constants.repair.change'))

        <td style="color: red;">
            Замена
        </td>

    @endif

    <td>
        {{ $repair->comment }}
    </td>

</tr>
