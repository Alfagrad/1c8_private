@extends('layouts.app')

@section('content')
<body>
<div class="b-wrapper p-cabinet rss">

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
                            <div class="wrapper b-cabinet-content">
                                <div class="wrapper w-cabinet-rss">
                                    <div class="section-name">Подписки на рассылки</div>
                                    <form class="w-rss-subscribes" method="post" action="/profile/subscribes/save" enctype="multipart/form-data">
                                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                        <div class="row">
                                            <label>
                                                <input type="checkbox" name="xls_weekly" value="1"  @if($subscribe->xls_weekly) checked @endif />
                                                Получать прайс лист XLS еженедельно
                                            </label>
                                        </div>
                                        <div class="row">
                                            <label>
                                                <input type="checkbox" name="news" value="1"  @if($subscribe->news) checked @endif />
                                                Рассылка новостей и акций
                                            </label>
                                        </div>
                                        <div class="row">
                                            <label>
                                                <input type="checkbox" name="new_items" value="1"  @if($subscribe->new_items) checked @endif />
                                                Получать уведомления о поступлении товаров
                                            </label>
                                        </div>
                                        <div class="row">
                                            <label>
                                                <input type="checkbox" name="copy_order" value="1"  @if($subscribe->copy_order) checked @endif />
                                                Получать копию заказов на E-mail
                                            </label>
                                        </div>
                                       
                                        <div class="row">
                                            <input type="submit" class="button _red" value="Сохранить">
                                        </div>
                                    </form>
                                    <script>
                                        $('input[type=checkbox] , select').styler();
                                    </script>
                                </div>
                            </div>
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