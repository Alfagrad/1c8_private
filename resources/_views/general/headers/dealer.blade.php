@extends('general.headers.header')

@section('download-price')
    <div class="header_info-lines header_price">
        <div class="header_svg js-mobile-ico">
            @include('svg.excel_ico')
        </div>
        <a href="{{ route('price.index') }}" title="Сформировать и Скачать прайсы XLS и YML ">Скачать прайс</a>
    </div>
@endsection

@section('order-history')
    <li><a href="{{ route('profile.orders') }}" title="В кабинет - История заказов">История заказов</a></li>
@endsection

@section('debt')
    <div class="header_info-lines header_debt">
        <div class="header_svg js-mobile-ico">
            @include('svg.debt_ico')
        </div>
        <a href="{{ route('profileIndex') }}" title="В кабинет">
            <span>Ваш долг:</span>
            <span class="header_debt_my-debt" title="Ваш долг - {{ $dept_sum ?? 0 }} руб">
                                {{ $dept_sum ?? 0}} руб
                            </span>
        </a>
    </div>
@endsection

@section('repairs')
    <div class="header_info-lines header_repare flex">
        <div class="header_svg js-mobile-ico">
            @include('svg.repare_ico')
        </div>
        <a href="{{ route('profileRepairs') }}" title="В кабинет - Мои ремонты" id="js-repare-block">
            <span>Ремонты в СЦ:</span>
            <span class="header_repare_in-work" title="{{ $repairsCountInWork ?? '' }} изделий в работе">
                                {{ $repairsCountInWork ?? '' }} в работе
                            </span>
            <span class="header_repare_ready" title="{{$repairsCountReady ?? ''}} изделий готово">
                                {{$repairsCountReady ?? '' }} готово
                            </span>
        </a>
    </div>
@endsection
