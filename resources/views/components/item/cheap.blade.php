@props(['item'])

@if(isset($item->cheap_items) && $item->cheap_items->count() && profile()->isDealer())

    <div class="catalog-item-line_cheep-items-block">

        <div class="catalog-item-line_cheep-items-head">
            Уцененные товары (торг):
        </div>

        @php
            $z = 1; // вводим счетчик товаров
        @endphp

        @foreach($item->cheap_items as $cheap)

            @php
                // if($cheap->discounted_rub) {
                //     // определяем минимальную цену со скидкой
                //     // ликвидируем конечную точку с запятой
                //     $discounted_rub = substr($cheap->discounted_rub, 0, -1);
                //     // делим и берем первое значение
                //     $min_price_str = explode(';', $discounted_rub)[0];
                //     // выделяем цену
                //     $min_price = explode('-', $min_price_str)[1];
                // } else {
                    // или берем стандартную
//                                if ($cheap->adjustable == 1) { // если товар регулируемый
//                                    $min_price = $cheap->price_rub;
//                                } else {
//                                    // считаем по оптовому курсу
//                                    $min_price = number_format($cheap->price_usd * $usd_opt, 2, '.', '');
//                                }
                // }

                // добавляем аттрибут стиля и класс для ссылки на товар
                if($z <= 2) {
                    $disp = "block";
                    $cls = "";
                } else {
                    $disp = "none";
                    $cls = "js-chip-".$item->id_1c;
                }

            @endphp

            <a
                class="catalog-item-line_cheep-items {{ $cls }}"
                href="{{ asset('catalogue/item/'.$cheap->id_1c) }}"
                style="display: {{ $disp }};"
                target="_blank"
            >
                {{ $cheap->name }}
                -
                <span class="js-cheep-price" style="display: {{ $cheep_item_style }};">
                                    {{price($item->min_price)}} руб.
                                </span>
            </a>

            @php
                $z++;
            @endphp

        @endforeach

        @if($item->cheap_items->count() > 2)

            <div
                class="catalog-item-line_cheep-items-toggler js-down js-chip-down-{{ $item->id_1c }}"
                data-parent_id="{{ $item->id_1c }}"
                style="display: block;"
            >
                Показать еще {{ $item->cheap_items->count() - 2 }}
                <img src="{{ asset('assets/img/toggler_arrow.png') }}">
            </div>

            <div
                class="catalog-item-line_cheep-items-toggler js-up js-chip-up-{{ $item->id_1c }}"
                data-parent_id="{{ $item->id_1c }}"
                style="display: none;"
            >
                Скрыть
                <img src="{{ asset('assets/img/toggler_arrow.png') }}" style="transform: rotate(180deg);">
            </div>

        @endif

    </div>

@endif
