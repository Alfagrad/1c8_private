@extends('layouts.app')

@section('content')

<div class="page">

    <div class="container">

        <div class="cabinet-page">

            @include('profile.menu')

            <div class="info-block">

                <div class="profile-info-block">
                    <div class="text-line">
                        <strong>Имя:</strong>
                        {{ $profile->name }}
                    </div>
                    <div class="text-line">
                        <strong>Email:</strong>
                        {{ $profile->email }}
                    </div>
                    <div class="text-line">
                        <strong>Компания:</strong>
                        {{ $profile->company_name }}
                    </div>
                    <div class="text-line">
                        <strong>УНП:</strong>
                        {{ $profile->unp }}
                    </div>
                    <div class="text-line">
                        <strong>Телефон(ы):</strong>
                        {{ $profile->phone }}@if($profile->phone_mob){{ ', '.$profile->phone_mob }}@endif
                    </div>

                    @if(isset($profile->partner) && $profile->partner->address)

                        <div class="text-line">
                            <strong>Адрес пункта продажи:</strong>
                            {{ $profile->partner->address }}
                        </div>

                    @endif

                    @if(isset($profile->partner) && $profile->partner->warehouse)

                        <div class="text-line">
                            <strong>Адрес склада:</strong>
                            {{ $profile->partner->warehouse }}
                        </div>

                    @endif

                    <div class="reset-password">

                        <div class="link">
                            <a href="#" class="js-show-password">Сменить пароль</a>
                        </div>

                        <div class="input-block js-add-pass-block" style="display: none;">
                            <input type="password" placeholder="минимум 6 символов" name="new_password">
                            <div class="button-block">
                                <div class="save-button js-password-save">Сохранить</div>
                                <div class="cancel-button js-cancel-password">Скрыть</div>
                            </div>
                        </div>

                        <div class="note-string js-note-string" style="display: none;"></div>

                    </div>

                </div>

                <div class="debts-block">
                    <div>
                        Ваш долг:
                        <span class="header_debt_my-debt">
                            {{ number_format($profile->debt()->sum('sum'), 2, '.', ' ') }} руб
                        </span>
                    </div>

                    @foreach($profile->debt as $debt)

                        @php
                            $now = Carbon\Carbon::now();
                            $pay_date = Carbon\Carbon::parse($debt->pay_date);
                            $days = $pay_date->diffInDays($now, false);

                        @endphp

                        <div class="debt-line">
                            Реализация от
                            {{ Carbon\Carbon::parse($debt->realization_date)->format('d.m.Y') }}
                            на сумму
                            {{ number_format($debt->realization_sum, 2, '.', ' ') }} руб.
                            Срок оплаты
                            {{ $pay_date->format('d.m.Y') }}.
                            @if($days > 0)<span>Просрочка {{$days}} дн.@endif
                            Долг
                            {{ number_format($debt->sum, 2, '.', ' ')}} руб
                            @if($days > 0)</span>@endif
                        </div>

                    @endforeach



                </div>

            </div>

        </div>

    </div>

</div>

@endsection

@section('scripts')
@parent

@vite(['resources/js/new_profile.js'])


@endsection
