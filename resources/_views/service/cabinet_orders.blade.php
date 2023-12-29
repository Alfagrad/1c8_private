@extends('layouts.service')

@section('content')

<div class="b-wrapper p-cabinet orders-history">

    @include('service.includes.header')
    @include('service.includes.nav')

    <section class="s-main-wrapper" style="margin-bottom: 5px;">
        <div class="container">
            <div class="wrapper">
                <div class="w-main-table">

                    @include('service.includes.cabinet_menu')

                    <div class="right" style="border-left: none;">
                        <div class="wrapper white-bg-wrapper">
                            <div class="wrapper b-cabinet-content">
                                <div class="wrapper w-orders-history">
                                    <div class="section-name">История заказов</div>

                                    @if(session('res'))

                                    <div style="color: red; font-size: 14px;">
                                        {!! session('res') !!}
                                    </div>

                                    @endif

                                    <div class="wrapper items-table orders-table">
                                        @foreach($orders as $order)
                                            <div class="w-table order-history-block @if(isset($order_mark) && $order_mark == $order->id){{ '_toggled' }}@endif">
                                            <div class="table-head">
                                                <table>
                                                    <thead>
                                                    <tr>
                                                        <td class="td-toggler"><div class="toggler-button"></div></td>
                                                        <td class="td-article"><div class="mobile-helper">Номер:</div>{{$order->id}}</td>
                                                        <td class="td-order-status">Оформлен {{$order->created_at->format('d.m.Y')}}</td>
                                                        @if($order->delivery == 'Доставка')
                                                            <td class="td-delivery-type">{{$order->delivery}}, {{$order->address}}</td>
                                                            @else
                                                            <td class="td-delivery-type">{{$order->delivery}}</td>
                                                        @endif
                                                        <td class="td-pay-status"><span style="visibility: hidden;">{{$order->calculation}}</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="td-toggler"></td>
                                                        <td colspan="4" class="td-order-weight" style="text-align: left;">Изделие: <b>{{$order->item_name}}</b></td>
                                                    </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                            <div class="table-body " style="display: @if(isset($order_mark) && $order_mark == $order->id){{ 'block' }}@else{{ 'none' }}@endif;">
                                                <div class="order-info-block">

                                                    <div>

                                                        <p>Серийный номер: {{ $order->item_sn }}</p>
                                                        <p>Дата продажи: {{ $order->item_sale_date }}</p>
                                                        <p>Неисправность: {{ $order->item_defect }}</p>
                                                        <p>Диагностика: {{ $order->item_diagnostic }}</p>
                                                        <p>Клиент: {{ $order->client_name }}, {{ $order->client_phone }}</p>

                                                    </div>

                                                    <div class="docs-block">

                                                        <div class="title">Заключение</div>

                                                        @if(!$order->conclusion)

                                                        <form method="post" action="{{ asset('/service/make-conclusion') }}">
                                                            {{ csrf_field() }}
                                                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                            <button type="submit">Создать</button>
                                                        </form>

                                                        @else

                                                        <form method="post" action="{{ asset('/service/edit-conclusion') }}">
                                                            {{ csrf_field() }}
                                                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                            <button type="submit">Редактировать</button>
                                                        </form>

                                                        <form method="post" action="{{ asset('/service/download-conclusion') }}">
                                                            {{ csrf_field() }}
                                                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                            <button type="submit">Скачать</button>
                                                        </form>

                                                        @endif

                                                    </div>

                                                    <div class="docs-block">

                                                        <div class="title">Акт</div>

                                                        @if(!$order->act)

                                                        <form method="post" action="{{ asset('/service/make-act') }}">
                                                            {{ csrf_field() }}
                                                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                            <button type="submit">Создать</button>
                                                        </form>

                                                        @else

                                                        <form method="post" action="{{ asset('/service/edit-act') }}">
                                                            {{ csrf_field() }}
                                                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                            <button type="submit">Редактировать</button>
                                                        </form>

                                                        <form method="post" action="{{ asset('/service/download-act') }}">
                                                            {{ csrf_field() }}
                                                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                            <button type="submit">Скачать</button>
                                                        </form>

                                                        @endif

                                                    </div>

                                                </div>
                                                <div>

                                                    @if(is_dir(public_path()."/storage/service-images/".$order->id))

                                                    <div><strong>Изображения:</strong></div>

                                                    <div class="image-block">

                                                        @php
                                                            $dir_big_pic = asset("/storage/service-images/".$order->id)."/";
                                                            $dir_small_pic = asset("/storage/service-images-trumbs/".$order->id)."/";

                                                            for($i=1; $i < 5; $i++) {
                                                                echo "

                                                        <div class='image-block-element'>
                                                            <div class='image-block-element_image-block'>
                                                                ";

                                                                $file_big = public_path().'/storage/service-images/'.$order->id.'/'.$order->id.'_'.$i.'.jpg';
                                                                $file_sm = public_path().'/storage/service-images-trumbs/'.$order->id.'/'.$order->id.'_'.$i.'.jpg';

                                                                if(file_exists($file_big) && file_exists($file_sm)) {
                                                                    echo "
                                                                <a href='".$dir_big_pic.$order->id."_".$i.".jpg' target='_blank' title='Жми, чтобы увеличить!'>
                                                                    <img src='".$dir_small_pic.$order->id."_".$i.".jpg'>
                                                                </a>
                                                                    ";

                                                                    if($i != 1) {

                                                                    echo "
                                                                <form method='post' action='/order/del-pic' class='del'>
                                                                    ".csrf_field()."
                                                                    <input type='hidden' name='order' value='".$order->id."'>
                                                                    <input type='hidden' name='index' value='".$i."'>
                                                                    <button type='submit'>Удалить</button>
                                                                </form>
                                                                ";

                                                                    }
                                                                } else {
                                                                    echo "
                                                                Нет изображения
                                                                    ";
                                                                }

                                                                echo "
                                                            </div>
                                                            <div style='font-size:12px; line-height: 1.1;'>
                                                                Загрузите изображение (заменит текущее):<br>
                                                                <span style='font-size: 0.8em;'>
                                                                    (максимальный размер файла 10МБ, тип &laquo;.jpg&raquo;)
                                                                </span>
                                                            </div>
                                                            <form method='post' enctype='multipart/form-data' action='/order/edit-pic'>
                                                                ".csrf_field()."
                                                                <input type='hidden' name='order' value='".$order->id."'>
                                                                <input type='hidden' name='index' value='".$i."'>
                                                                <div>
                                                                    <input type='file' name='image' class='js-service-pic' required>
                                                                </div>
                                                                <div>
                                                                    <button type='submit'>Загрузить</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                                ";
                                                            }
                                                        @endphp

                                                    </div>

                                                    @endif

                                                </div>
                                                <table>
                                                    <tbody>
                                                    <tr class="orders-history-head">
                                                        <td class="td-article"></td>
                                                        <td class="td-name">Наименование</td>
                                                        <td class="td-price"><span style="visibility: hidden;">Цена</span></td>
                                                        <td class="td-pcs">Кол-во</td>
                                                        <td class="td-price"><span style="visibility: hidden;">Итого</span></td>
                                                    </tr>
                                                    @foreach($order->items as $item)
                                                        <tr class="orders-history-item">
                                                            <td class="td-article"><div class="mobile-helper">Код: </div>{{$item->id}}</td>
                                                            <td class="td-name"><a href="" class="name">{{$item->item_name}}</a></td>
                                                            <td class="td-price"><span style="visibility: hidden;">{{$item->item_price}} руб.</span></td>
                                                            <td class="td-pcs"><div class="mobile-helper">Кол-во: </div>{{$item->item_count}}</td>
                                                            <td class="td-price"><span style="visibility: hidden;">{{$item->item_sum_price}} руб.</span></td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    <div class="wrapper pagination">
                                        @include('pagination.default', ['paginator' => $orders])
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>

{{-- Валидация размера и типа изображения --}}
<script type="text/javascript" src="{{ asset('assets/js/pic_validator.js') }}"></script>

@endsection