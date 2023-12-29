@extends('layouts.app')

@section('content')
    <body>
    <div class="b-wrapper p-cabinet main-info">
        @include('general.header')
        @include('general.nav')
        @include('general.bcrumb')
        <section class="s-main-wrapper">
            <div class="container">
                <div class="wrapper">
                    <div class="w-main-table">
                       @include('profile.menu')
                        <div class="right">
                            <div class="wrapper white-bg-wrapper">
                                <form class="wrapper b-cabinet-content" id="form-profile" method="post" action="/profile/update" enctype="multipart/form-data">
                                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                    <div class="wrapper w-main-info">
                                        <div class="section-name">Основная информация</div>
                                        <div class="left">
                                            <div class="row">
                                                <div class="description">E-mail:</div>
                                                <div class="main row-profile-view-email">{{$profile->email or ''}}</div>
                                            </div>
                                            <div class="row">
                                                <div class="description">Имя пользователя:</div>
                                                <div class="main row-profile-view-name">{{$profile->name or ''}}</div>
                                            </div>
                                            <div class="row">
                                                <div class="description">Наименование юр. лица:</div>
                                                <div class="main row-profile-view-company-name">{{$profile->company_name or ''}}</div>
                                            </div>
                                            <div class="row">
                                                <div class="description">Юридический адрес:</div>
                                                <div class="main row-profile-view-company-name">{{$profile->company_address or ''}}</div>
                                            </div>
                                            <div class="row">
                                                <div class="description">УНП:</div>
                                                <div class="main row-profile-view-unp">{{$profile->unp or ''}}</div>
                                            </div>
                                            <div class="row">
                                                <div class="description">Расчетный счёт (IBAN):</div>
                                                <div class="main row-profile-view-bank-account">{{$profile->bank_account or ''}}</div>
                                            </div>
                                            <div class="row">
                                                <div class="description">БИК Банка:</div>
                                                <div class="main row-profile-view-bank-name">{{$profile->bank_name or ''}}</div>
                                            </div>
                                            <div class="row row-change-password">
                                                <div class="description"></div>
                                                <div class="main"><a href="#" class="js-show-password">Сменить пароль</a></div>
                                            </div>
                                            <div class="_change-password" style="display: none;">
                                                <div class="row">
                                                    <div class="description"><input type="password" placeholder="*******" name="new_password"></div>
                                                    <div class="main">
                                                        <div class="row">
                                                            <a href="#" class="button _red js-password-save">Сохранить</a>
                                                            <a href="#" class="button _gray js-password-save-cancel">Отменить</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <a href="" class="button _gray js-open-edit-profile">Редактировать профиль</a>
                                            </div>
                                            <div class="w-edit-profile" style="display: none;">
                                                <div class="row">
                                                    <div class="description">E-mail:</div>
                                                    <div class="main"><input type="text" placeholder="{{$profile->email or ''}}" name="email" value="{{$profile->email or ''}}"  disabled></div>
                                                </div>
                                                <div class="row">
                                                    <div class="description">Имя пользователя:</div>
                                                    <div class="main"><input type="text" placeholder="{{$profile->name or ''}}" name="profile_name" value="{{$profile->name or ''}}"></div>
                                                </div>
                                                <div class="row">
                                                    <div class="description">Наименование юр. лица:</div>
                                                    <div class="main"><input type="text" placeholder="ООО “Промснаб”" name="company_name" value="{{$profile->company_name or ''}}" disabled></div>
                                                </div>
                                                <div class="row">
                                                    <div class="description">Юридический адрес:</div>
                                                    <div class="main"><input type="text" placeholder="Минск, Независимости 1”" name="company_address" value="{{$profile->company_address or ''}}" disabled></div>
                                                </div>
                                                <div class="row">
                                                    <div class="description">УНП:</div>
                                                    <div class="main"><input type="text" placeholder="194528192" name="unp" value="{{$profile->unp or ''}}" disabled></div>
                                                </div>
                                                <div class="row">
                                                    <div class="description">Расчетный счёт (IBAN):</div>
                                                    <div class="main"><input type="text" placeholder="BY52PJCB30120328871000000933" name="bank_account" value="{{$profile->bank_account or ''}}"></div>
                                                </div>
                                                <div class="row">
                                                    <div class="description">БИК Банка:</div>
                                                    <div class="main"><input type="text" placeholder="PJCBBY2X" name="bank_name" value="{{$profile->bank_name or ''}}" ></div>
                                                </div>
                                                <div class="row">
                                                    <a href="" class="button _gray js-close-edit-profile">Отменить</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="right">
                                            <div class="row">
                                                <div class="description">Мой долг:</div>
                                                <div class="main">
                                                    <div class="debt">
                                                        <span>{{number_format($deptSum, 2, '.', ' ')}} руб.</span>
                                                        <div class="row">
                                                            <p>
                                                               <!-- <b>Здесь будем выводить информацию о долге</b><br>
                                                                Ваш долг 9 999 руб. Заказ №9999 от 21.01.2017<br> -->
                                                                @foreach($profile->depts as $d)
                                                                  <?
                                                                    $now = Carbon\Carbon::now();
                                                                    $days = $d->pay_date->diffInDays($now, false);
                                                                    ?>
                                                                  Реализация от {{$d->realization_date->format('d.m.Y')}} на сумму {{number_format($d->realization_sum, 2, '.', ' ')}} руб. Срок оплаты {{$d->pay_date->format('d.m.Y')}}. @if($days > 0) <span>Просрочка {{$days}} дней.@endif Долг {{number_format($d->dept, 2, '.', ' ')}} руб @if($days > 0)</span>@endif
                                                                    <br>
                                                                @endforeach
                                                                <!-- <b style="color: red">Можно красным цветом!!!</b> -->
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="description">Ремонты в СЦ:</div>
                                                <div class="main"><div class="repair"><a href="{{route('profileRepairs')}}"><span>{{$repairsCountInWork}} в работе</span><span class="ready">{{$repairsCountReady}} готово</span></a></div></div>
                                            </div>
                                            @if($profile->discount)
                                                <div class="row">
                                                    <div class="w-personal-sale">
                                                        <p>Персональная @if($profile->discount < 0) скидка @else надбавка @endif для партнера<i> {{$profile->discount_text}}</i></p><div class="icon _green">{{abs($profile->discount)}}%</div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="wrapper w-contacts-info">
                                        <div class="left">
                                            <div class="section-name secondary insert_contact">
                                                Контактные лица
                                            </div>
                                            <p style= "background-color: green; padding: 10px 15px; color: #fff">
                                                Что бы сохранить изменения нажмите на «Сохранить данные» в учетной записи внизу страницы
                                            </p>


                                            @foreach($profile->contact as $c)
                                                <div class="w-ready-contact-wrapp block_phones">
                                                    <a href="" class="edit js-edit-contact">Редактировать</a>
                                                    <a href="" class="close js-delete-contact">&times;</a>
                                                    <div class="tel">{{$c->phone}}</div>
                                                    <div class="description">{{$c->name}}</div>
                                                    <input type="hidden" name="contact[phone][]" value="{{$c->phone}}">
                                                    <input type="hidden" name="contact[name][]" value="{{$c->name}}">
                                                </div>
                                            @endforeach

                                            <!--
                                            <div class="w-ready-contact-wrapp">
                                                <a href="" class="edit">Редактировать</a>
                                                <a href="" class="close">&times;</a>
                                                <div class="tel">+375 29 333 22 44</div>
                                                <div class="description">Васили Иванович (директор)</div>
                                                <div class="wrapper w-add-wrapp-form">
                                                    <div class="section-name secondary">Редактировать контакт</div>
                                                    <div class="row">
                                                        <div class="input w_50">
                                                            <label>Телефон<span>*</span></label>
                                                            <input class="thin" type="text" placeholder="+375 29 333 22 44">
                                                        </div>
                                                        <div class="input w_50">
                                                            <label>Имя<span>*</span></label>
                                                            <input class="thin" type="text" placeholder="Васили Иванович (директор)">
                                                        </div>
                                                    </div>
                                                    <div class="input">
                                                        <input type="submit" class="button _red" value="Добавить">
                                                    </div>
                                                </div>
                                            </div>
                                            -->

                                            <a href="" class="add-wrapp js-open-add-contact">Добавить контакт</a>

                                            <div class="wrapper w-add-wrapp-form one-form b-add-contact" style="display: none">
                                                <div class="section-name secondary">Новый контакт</div>
                                                <div class="row">
                                                    <div class="input w_50">
                                                        <label>Телефон<span>*</span></label>
                                                        <input class="thin" type="text" name="phone">
                                                    </div>
                                                    <div class="input w_50">
                                                        <label>Имя<span></span></label>
                                                        <input class="thin" type="text" placeholder="Имя, должность" name="name">
                                                    </div>
                                                </div>
                                                <div class="input">
                                                    <a href="" class="button _red js-add-contact" >Добавить</a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="right">
                                            <div class="section-name secondary insert_address">
                                                Адреса для доставок
                                            </div>
                                            <p style= "background-color: green; padding: 10px 15px; color: #fff">
                                                Что бы сохранить изменения нажмите на «Сохранить данные» в учетной записи внизу страницы
                                            </p>
                                            @foreach($profile->address as $addr)
                                                <div class="w-ready-contact-wrapp block_addresses">
                                                    <a href="" class="edit js-edit-address">Редактировать</a>
                                                    <a href="" class="close js-delete-address">&times;</a>
                                                    <div class="address">{{$addr->address}}</div>
                                                    <div class="description">{{$addr->comment}}</div>
                                                    <input type="hidden" name="addresses[address][]" value="{{$addr->address}}">
                                                    <input type="hidden" name="addresses[comment][]" value="{{$addr->comment}}">
                                                </div>
                                            @endforeach

                                            <a href="" class="add-wrapp js-open-add-address">Добавить адрес</a>

                                            <div class="wrapper w-add-wrapp-form one-form b-add-address"  style="display: none">
                                                <div class="section-name secondary">Новый адрес</div>
                                                <div class="input">
                                                    <label>Адрес<span>*</span></label>
                                                    <input class="thin" type="text" placeholder="г. Минск, ул. Пушкина 17 офис 41" name="address">
                                                </div>
                                                <div class="input">
                                                    <label>Дополнительный комментарий<span></span></label>
                                                    <input class="thin" type="text" placeholder="Въезд со стороны ул.Ленина через шлагбаум" name="comment">
                                                </div>
                                                <div class="input">
                                                    <a href="" class="button _red js-add-address" >Добавить</a>
                                                </div>
                                                </div>
                                        </div>

                                    </div>
                                    <!--
                                    <div class="wrapper w-more-contacts-info">
                                        <div class="input w_33">
                                            <label>Торговые объекты. Адреса. Площади</label>
                                            <textarea placeholder="Ваши торговые объекты, адреса, площади. Например: Магазин электроинструмента, ул.Энгельса д3, 40м2" name="trade_object">{{$profile->trade_object or ''}}</textarea>
                                        </div>
                                        <div class="input w_33">
                                            <label>Интернет магазины</label>
                                            <textarea placeholder="Укажите ссылки на ваши интернет магазины" name="shops">{{$profile->shops or ''}}</textarea>
                                        </div>
                                        <div class="input w_33">
                                            <label>Регион покрытия</label>
                                            <textarea placeholder="Регион покрытия. Например: Минск и частично Минский район" name="coverage_area">{{$profile->coverage_area or ''}}</textarea>
                                        </div>
                                    </div>
                                    -->
                                    <div class="wrapper w-cabinet-save-form centered">
                                        <input class="button _red" type="submit" value="сохранить данные" @if($profile->is_block_info) disabled @endif>

                                        @if($profile->is_block_info)
                                            <p>{{setting('updated_data_text')}}</p>
                                        @else
                                            <p>{{setting('update_data_text')}}</p>
                                        @endif

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
        @include('general.footer')
        @include('general.popups')
    </div>
    @include('general.scripts')
    </body>
@endsection
