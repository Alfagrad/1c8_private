<div class="open_service-repare-status_wrapper">
    <div class="info-line">
        <div class="title">Квитанция:</div>
        <div class="info">№ {{ $repair->{'1c_id'} }} от {{ date('d.m.Y', strtotime($repair->receipt_date)) }}</div>
    </div>

    <div class="info-line">
        <div class="title">Изделие:</div>
        <div class="info">{{ $repair->name }}</div>
    </div>

    @if($repair->serial)

        <div class="info-line">
            <div class="title">Серийный номер:</div>
            <div class="info">{{ $repair->serial }}</div>
        </div>

    @endif

    @php
        if($repair->repair_type == 0) {
            $repair_type = "Платный.";
        } elseif($repair->repair_type == 1) {
            $repair_type = "Бесплатный.";
        } else {
            $repair_type = "";
        }
    @endphp

    @if($repair_type && $repair->state != 1)

        <div class="info-line">
            <div class="title">Вид ремонта:</div>
            <div class="info">{{ $repair_type }}</div>
        </div>

    @endif

    @if($repair->repair_sum != 0)

        @php
            if($repair->paid == 1) $paid = "оплачено";
                else $paid = "к оплате";
        @endphp

        <div class="info-line">
            <div class="title">Стоимость ремонта:</div>
            <div class="info">{{ number_format($repair->repair_sum, 2, '.', '') }} руб. ({{ $paid }})</div>
        </div>

    @endif

    @php
        $st = $repair->state;
        $ret = $repair->return_item;
        if($st == 1 && $ret == 0) {
            $state = "В работе.";
        } elseif($st == 2 && $ret == 0) {
            if($repair->shipment_date != '0000-00-00') {
                // прибавляем 1 день к дате
                // задача - https://alfastok.bitrix24.by/company/personal/user/270/tasks/task/view/71064/
                $shipment_date = date('d.m.Y', strtotime($repair->shipment_date.'+ 1 days'));
                $state = "Отремонтирован. Ожидаемая дата доставки в пункт выдачи {$shipment_date}.";
            } else {
                $state = "Отремонтирован и готов к выдаче.";
            }
        } elseif($st == 3 && $ret == 0) {
            // если shipment_date - не нулевой
            if($repair->shipment_date != '0000-00-00') {
                // прибавляем 1 день к дате
                $shipment_date = date('d.m.Y', strtotime($repair->shipment_date.'+ 1 days'));
                $state = "Отремонтирован. Ожидаемая дата доставки в пункт выдачи {$shipment_date}.";
            } else {
                $state = "Выдан";
                if($repair->check_date != '0000-00-00') {
                    $check_date = date('d.m.Y', strtotime($repair->check_date));
                    $state .= " {$check_date}";
                }
            }
            $state .= ".";
        } elseif($ret == 1) {
            if(\Auth::user()) {
                $state = "Возврат денежных средств.";
            } else {
                $state = "Возврат товара. Обратитесь к продавцу.";
            }
        } elseif($ret == 2) {
            if(\Auth::user()) {
                $state = "Возврат товара: замена на новое изделие.";
            } else {
                $state = "Возврат товара. Обратитесь к продавцу.";
            }
        } else {
            $state = "Не определен.";
        }
    @endphp

    <div class="info-line">
        <div class="title">Статус ремонта:</div>
        <div class="info">{{ $state }}</div>
    </div>

    @if($repair->comment)

        <div class="info-line">
            <div class="title">Коментарий:</div>
            <div class="info">{{ $repair->comment }}</div>
        </div>

    @endif

    @if($repair->works)

        <div class="info-line">
            <div class="title">Материалы и выполненные работы:</div>
            <div class="info"><pre>{{ $repair->works }}</pre></div>
        </div>

    @endif

</div>
