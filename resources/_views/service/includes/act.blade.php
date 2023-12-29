<div style="width: 650px; padding-left: 50px; box-sizing: border-box; font-family: DejaVu Sans, sans-serif; font-size: 12px; line-height: 1;">

    <div style="text-align: center; font-weight: bold;">
        АКТ ВЫПОЛНЕННЫХ РАБОТ №{{ $act->id }} от {{ date('d.m.Y', strtotime($act->act_date)) }}
    </div>

    <div style="margin-top: 50px;">
        Мы, нижеподписавшиеся, Заказчик {{ $act->client_name }} и Исполнитель {{ $act->company_name }} составили настоящий Акт о нижеследующем: в соответствии с Договором Исполнитель сдал, а Заказчик принял следующие работы:
    </div>

    <div>
        <table cellspacing=0 style="width: 100%; margin-top: 20px; font-size: 12px;">
            <tr style="font-weight: bold; vertical-align: middle; text-align: center;">
                <th style="border: solid 1px #ccc; padding: 2px; width: 57px;">№ п/п</th>
                <th style="border: solid 1px #ccc; padding: 2px;">Наименование работы, услуги, материала, прочих затрат</th>
                <th style="border: solid 1px #ccc; padding: 2px; width: 57px">Ед. изм</th>
                <th style="border: solid 1px #ccc; padding: 2px; width: 53px">Кол-во</th>
            </tr>

            @php($i = 1)
            @foreach($order->items as $item)

                <tr style="vertical-align: middle;">
                    <td style="border: solid 1px #ccc; padding: 2px; text-align: center;">{{ $i }}</td>
                    <td style="border: solid 1px #ccc; padding: 2px;">{{ $item->item_name }}</td>
                    <td style="border: solid 1px #ccc; padding: 2px; text-align: center;">шт.</td>
                    <td style="border: solid 1px #ccc; padding: 2px; text-align: center;">{{ $item->item_count }}</td>
                </tr>

                @php ($i++)

            @endforeach

        </table>
    </div>

    <div style="margin-top: 20px">
        Работы выполнены в полном объеме. Качество работы соответствует указанным в Договоре требованиям. Недостатки в результате приема работ не выявлены. Заказчик к выполненной работе претензий не имеет.
    </div>

    <div>
        <table style="width: 100%; margin-top: 50px;">
            <tr style="font-weight: bold;">
                <th style="width: 50%; padding-bottom: 10px;">Заказчик:</th>
                <th style="padding-bottom: 10px;">Исполнитель:</th>
            </tr>
            <tr style=" vertical-align: top;">
                <td style="padding-bottom: 50px;">{!! str_replace(PHP_EOL, '<br>', $act->client_requisites) !!}</td>
                <td style="padding-bottom: 50px;">{!! str_replace(PHP_EOL, '<br>', $act->company_requisites) !!}</td>
            </tr>
            <tr>
                <td>________________ {{ $act->signature_client_name }}</td>
                <td>________________ {{ $act->signature_director_name }}</td>
            </tr>
        </table>

    </div>

</div>