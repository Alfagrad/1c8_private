@props(['item'])

@if(Auth::user()->role_id != '2')
    @php
        // формируем строку количеств для менеджеров
        $amount_str = "";
        if ($item->amount > 0) {
        $amount_str .= "в продаже - {$item->amount} шт., ";
        }
        if ($item->reserve > 0) {
        $amount_str .= "в резерве - {$item->reserve} шт., ";
        }
        if ($item->locked > 0) {
        $amount_str .= "заблокировано - {$item->locked} шт., ";
        }
        if ($item->expected > 0) {
        $current_date = time();
        $expected_date = strtotime($item->expected_date);
        if ($expected_date < $current_date) {
        $real_date = date('d.m.Y', $current_date);
        } else {
        $real_date = date('d.m.Y', $expected_date);
        }

        $amount_str .= "прибудет {$real_date} - {$item->expected} шт., ";
        }
        // если строка не пустая
        if ($amount_str) {
        // удаляем 2 последних символа
        $amount_str = mb_substr($amount_str, 0, -2);
        // делаем заглавным 1 символ
        $first = mb_strtoupper(mb_substr($amount_str, 0, 1));
        $amount_str = $first.mb_substr($amount_str, 1);
        }

    @endphp

    <div class="catalog-item-line_amount-string">
        {{ $amount_str }}
    </div>

@endif
