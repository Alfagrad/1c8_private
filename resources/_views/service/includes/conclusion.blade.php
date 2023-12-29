<div style="width: 650px; padding-left: 50px; box-sizing: border-box; font-family: DejaVu Sans, sans-serif; line-height: 1;">

    <table style="width: 100%">
        <tr>
            <td style="width: 360px;"> </td>
            <td style="font-size: 12px;">{!! str_replace(PHP_EOL, '<br>', $conclusion->requisites) !!}</td>
        </tr>
    </table>

    <div style="text-align: center; font-weight: 600; margin-top: 50px;">Заключение №{{ $conclusion->order_id }}</div>

    <div style="margin-top: 50px;">

        <table style="width: 100%; font-size: 13px;">
            <tr>
                <td style="width: 300px; padding: 2px 5px; vertical-align: middle;">От:</td>
                <td style="padding: 2px 5px; border: solid 1px #ccc; vertical-align: middle;">
                    {{ date('d.m.Y', strtotime($conclusion->document_date)) }}
                </td>
            </tr>
            <tr>
                <td style="padding: 2px 5px; vertical-align: middle;">Изделие:</td>
                <td style="padding: 2px 5px; border: solid 1px #ccc; vertical-align: middle;">{{ $conclusion->item_name }}</td>
            </tr>
            <tr>
                <td style="padding: 2px 5px; vertical-align: middle;">Серийный номер:</td>
                <td style="padding: 2px 5px; border: solid 1px #ccc; vertical-align: middle;">{{ $conclusion->item_sn }}</td>
            </tr>
            <tr>
                <td style="padding: 2px 5px; vertical-align: middle;">Дата продажи:</td>
                <td style="padding: 2px 5px; border: solid 1px #ccc; vertical-align: middle;">
                    {{ date('d.m.Y', strtotime($conclusion->item_sale_date)) }}
                </td>
            </tr>
            <tr>
                <td style="padding: 2px 5px; vertical-align: middle;">Дата поступления изделия в ремонт:</td>
                <td style="padding: 2px 5px; border: solid 1px #ccc; vertical-align: middle;">
                    {{ date('d.m.Y', strtotime($conclusion->item_receiving_date)) }}
                </td>
            </tr>
            <tr>
                <td style="padding: 2px 5px; vertical-align: middle;">Фамилия и инициалы потребителя:</td>
                <td style="padding: 2px 5px; border: solid 1px #ccc; vertical-align: middle;">{{ $conclusion->client_name }}</td>
            </tr>
            <tr>
                <td style="padding: 2px 5px; vertical-align: middle;">Телефон:</td>
                <td style="padding: 2px 5px; border: solid 1px #ccc; vertical-align: middle;">{{ $conclusion->client_phone }}</td>
            </tr>
            <tr>
                <td style="padding: 2px 5px; vertical-align: middle;">Наличие гарантийного талона:</td>
                <td style="padding: 2px 5px; border: solid 1px #ccc; vertical-align: middle;">
                    @if($conclusion->guarantee_availability){{ 'Есть' }}@else{{ 'Нет' }}@endif
                </td>
            </tr>
            <tr>
                <td style="padding: 2px 5px; vertical-align: middle;">Отметка продавца в гарантийном талоне:</td>
                <td style="padding: 2px 5px; border: solid 1px #ccc; vertical-align: middle;">
                    @if($conclusion->seller_record){{ 'Есть' }}@else{{ 'Нет' }}@endif
                </td>
            </tr>
            <tr>
                <td style="padding: 2px 5px; vertical-align: middle;">Наличие внешних мех.повреждений:</td>
                <td style="padding: 2px 5px; border: solid 1px #ccc; vertical-align: middle;">{{ $conclusion->external_damage }}</td>
            </tr>
        </table>

    </div>

    <div style="margin-top: 30px; font-size: 13px;">

        <div>
            <div style="font-weight: bold;">Выявленные потребителем повреждения, неисправности:</div>
            <div style="padding: 2px 5px; border: solid 1px #ccc;">{{ $conclusion->client_damage_detected }}</div>
        </div>

        <div style="margin-top: 10px;">
            <div style="font-weight: bold;">Результат осмотра:</div>
            <div style="padding: 2px 5px; border: solid 1px #ccc;">{{ $conclusion->diagnostic }}</div>
        </div>

        <div style="margin-top: 10px;">
            <div style="font-weight: bold;">Предполагаемая причина повреждения, неисправности:</div>
            <div style="padding: 2px 5px; border: solid 1px #ccc;">{{ $conclusion->damage_reason }}</div>
        </div>

        <div style="margin-top: 10px;">
            <div style="font-weight: bold;">Предложения по восстановлению работоспособности, внешнего вида или замене изделия:</div>
            <div style="padding: 2px 5px; border: solid 1px #ccc;">{{ $conclusion->recovery_recommendation }}</div>
        </div>

    </div>

    <div style="font-size: 13px; text-align: right; margin-top: 50px">
        Директор ________________ {{ $conclusion->signature_name }}
    </div>



</div>