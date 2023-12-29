@extends('layouts.app')

@section('content')
    <body>
    <div class="b-wrapper p-catalogs-list">
        @include('general.header')
        @include('general.nav')
        @include('general.bcrumb')
        <section class="s-main-wrapper">
            <div class="container">
                <div class="wrapper">
                    <div class="w-main-table">
                        <div class="left">
                            <div class="wrapper white-bg-wrapper b-cabinet-navigation">
                                <div class="padding-20">
                                    <ul class="ul-brands-list js-brands-list">
                                        @foreach($brands as $brand)
                                            <li><label><input type="checkbox" value="{{$brand->id}}">{{$brand->name}}</label></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="right">
                            <div class="wrapper white-bg-wrapper">
                                <div class="padding-20">
                                    <div class="w-price-list-700">
                                        <div class="w-search-form js-brands-search-form">
                                            <input type="text" class="js-brands-text"  placeholder="Фильтр по слову">
                                            <input type="submit" class="button _red" value="Найти">
                                        </div>
                                        <table class="table-brands-download">
                                            <thead>
                                            <tr>
                                                <th>Название</th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                            </thead>
                                            <tbody>

                                                @foreach($brandGuides as $brandGuide)
                                                    <tr data-brand_id="{{$brandGuide->brand_id}}">
                                                        <td class="js-brand-name">{{$brandGuide->name}}</td>
                                                        <td class="td-downoad"><a href="{{'storage/' . $brandGuide->src}}" class="download__link" target="_blank">Скачать</a></td>
                                                        <td class="td-fileformat">{{$brandGuide->type}} @if($brandGuide->weight){{$brandGuide->weight}}kb @endif  </td>
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
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
    <script type="text/javascript" src="{{ asset('assets/js/brands.js') }}"> </script>
    </body>
@endsection
