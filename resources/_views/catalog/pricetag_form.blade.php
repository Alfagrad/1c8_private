@extends('layouts.app')

@section('content')
    <body>
    <div class="b-wrapper p-item">

        @include('general.header')
        @include('general.nav')

        <section class="s-product">
            <div class="container pricetag">

                <div class="pricetag-blocks">

                    <form method="post" action="{{ asset('/pricetag/download/'.$itemId) }}">
                       {{ csrf_field() }}
                    <div class="pricetag_block block-form">
                        <div class="pricetag_info bl-info-height">
                            <div class="pricetag_header">
                                <div class="pricetag_header_logo" id="js_brand_logo" style="display: none;">
                                </div>
                                <div class="pricetag_header_name name" id="js_name_bl" style="flex: 0 0 100%">
                                    <textarea name="name" id="js_name">{{ $item->name }}</textarea>
                                </div>
                            </div>

                            <div class="pricetag_choice-logo">
                                Выберите логотип:
                                <div><label><input type="radio" name="brand_logo" value="" id="js_nologo" checked> Без логотипа</label></div>
                                <div><label><input type="radio" name="brand_logo" value="brado" id="js_brado"> Brado</label></div>
                                <div><label><input type="radio" name="brand_logo" value="katana" id="js_katana"> Katana</label></div>
                                <div><label><input type="radio" name="brand_logo" value="welt" id="js_welt"> Welt</label></div>
                                <div><label><input type="radio" name="brand_logo" value="darc" id="js_darc"> Darc</label></div>
                                <div><label><input type="radio" name="brand_logo" value="sbk" id="js_sbk"> SBK</label></div>
                                <div><label><input type="radio" name="brand_logo" value="spec" id="js_spec"> Spec</label></div>
                                <div><label><input type="radio" name="brand_logo" value="skiper_new" id="js_skiper"> Skiper</label></div>
                            </div>

                            <div class="pricetag_price-bl price-bl-height">
                                <div class="pricetag_price-bl_img">
                                    {{-- @php dd($item->images) @endphp --}}
                                    @if(count($item->images))
                                    <img src="{{ asset('storage/'.$item->images[0]->path_image) }}">
                                    @endif
                                </div>
                                <div class="pricetag_price-bl_right pricetag_sale">
                                    <div>
                                        <strong>Тип ценника:</strong>
                                    </div>
                                    <div class="pricetag_price-bl_sales">
                                        <label><input type="radio" name="type" value="" id="js_no_sale" checked>Без Распродажа / Акция</label>
                                    </div>
                                    <div class="pricetag_price-bl_sales">
                                        <label><input type="radio" name="type" value="sale" id="js_sale"><span class="pricetag_price-bl_sale">распродажа</span></label>
                                    </div>
                                    <div class="pricetag_price-bl_sales">
                                        <label><input type="radio" name="type" value="action" id="js_action"><span class="pricetag_price-bl_sale">акция</span></label>
                                    </div>
                                    <input type="hidden" name="type_string" id="type_string" value="">

                                    <div>
                                        <strong>Установите цену:</strong>
                                    </div>
                                    <div class="pricetag_price-bl_prices prices-bl">
                                        <div class="pricetag_price-bl_bel" id="js_price_sale" style="display: none;">
                                            <input type="text" name="price_sale" value="{{ $item->price_mr_bel }}" class="mr_bel_sale"><span>руб</span>
                                            <input type="hidden" name="price_old" id="price_old" value="{{ $item->price_mr_bel }}">
                                        </div>
                                        <div class="pricetag_price-bl_bel" id="js_price">
                                            <input type="text" name="price" value="{{ $item->price_mr_bel }}" class="mr_bel"><span>руб</span>
                                            <input type="hidden" name="price_new" id="price_new" value="{{ $item->price_mr_bel }}">
                                        </div>
                                    </div>
                                    <div class="pricetag_price-bl_sales">
                                        <label><input type="radio" name="type" value="" id="js_no_price">Цену не указывать</label>
                                    </div>
                                    <input type="hidden" name="type_price" id="type_price" value="visible">



                                </div>
                            </div>

                            <div class="pricetag_advantages advantages-textarea">
                                <div>Преимущества:</div>
                                <div>
                                    <textarea name="advantages" id="advantages">{{ $item->content }}</textarea>
                                </div>
                            </div>

                            <div class="pricetag_caracteristics caracteristics-height">
                                <div class="pricetag_caracteristics_left">
                                    <div>Характеристики:</div>
                                    <div>
                                        <textarea name="characteristics" id="characteristics">{{ $characteristics }}</textarea>
                                    </div>
                                </div>
                                <div class="pricetag_caracteristics_right">
                                    <div>Комплектация:</div>
                                    <div>
                                        <textarea name="complect" id="complect">{{ $item->equipment }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="pricetag_producer">
                                <strong>Страна производитель:</strong>
                                <input type="text" name="country" id="brand_country" value="{{ $item->country }}">
                            </div>
                            <div class="pricetag_best-price-input">
                                <div><strong>Красная полоса:</strong></div>
                                <label>
                                    <input type="radio" name="line_checker" id="line_checker_off" value=""> не показывать
                                </label>
                                <label>
                                    <input type="radio" name="line_checker" id="line_checker_on" value="on" checked> показывать
                                </label>
                                С текстом:
                                <input type="text" name="red_string" id="red_string" value="Выгодная цена!">
                            </div>
                        </div>
                        <div class="pricetag_bottom" id="red_string_view_1">
                            Выгодная цена!
                        </div>
                    </div>

                    <div class="pricetag_format">

                        <div><strong>Формат ценника:</strong></div>

                        <div class="pricetag_type-list">
                            <label>
                                <input id="js-a4" type="radio" name="type_list" value="a4" checked> A4 (210мм х 297мм)
                            </label>
                            <div class="pricetag_type-list_separator"> | </div>
                            <label>
                                <input id="js-a5" type="radio" name="type_list" value="a5"> А5 (148мм х 210мм)
                            </label>
                            <div class="pricetag_type-list_separator"> | </div>
                            <label>
                                <input id="js-a6" type="radio" name="type_list" value="a6"> А6 (105мм х 148мм)
                            </label>
                            <div class="pricetag_type-list_separator"> | </div>
                            <label>
                                <input id="js-mini" type="radio" name="type_list" value="mini"> Мини
                            </label>
                        </div>

                        <div class="pricetag_submit">
                            <input type="submit" value="Скачать">
                        </div>
                        
                    </div>

                    </form>

                </div>

                <div id="js-result" style="display: block;">
                
                    <h1 class="pricetag-block_header">Результат:</h1>

                    <div class="pricetag_block">
                        <div class="pricetag_info">
                            <div class="pricetag_header">
                                <div class="pricetag_header_logo" id="js_brand_logo_view" style="display: none;">
                                </div>
                                <div class="pricetag_header_name" id="js_name_bl_view" style="flex: 0 0 100%">
                                    <h1 id="js_name_view">{{ $item->name }}</h1>
                                </div>
                            </div>

                            <div class="pricetag_price-bl">
                                <div class="pricetag_price-bl_img">
                                    @if(count($item->images))
                                    <img src="{{ asset('storage/'.$item->images[0]->path_image) }}">
                                    @endif
                                </div>
                                <div class="pricetag_price-bl_right" id="js_price_bl_view" style="visibility: visible;">
                                    <div class="pricetag_price-bl_sale" id="js_sale_table" style="display: none;">
                                    </div>
                                    <div class="pricetag_price-bl_sale" id="js_action_table" style="display: none;">
                                    </div>

                                    <div class="pricetag_price-bl_prices">
                                        <div class="pricetag_price-bl_old-bel" id="js_price_sale_view" style="display: none;">
                                            {{ $item->price_mr_bel }}<span>руб</span>
                                        </div>
                                        <div class="pricetag_price-bl_mr-bel" id="js_price_view">
                                            {{ $item->price_mr_bel }}<span>руб</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="pricetag_advantages">
                                <div>Преимущества:</div>
                                <div id="advantages_view">{{ $item->content }}</div>
                            </div>

                            <div class="pricetag_caracteristics">
                                <div class="pricetag_caracteristics_left">
                                    <div>Характеристики:</div>
                                    <div id="characteristics_view">{{ $characteristics }}</div>
                                </div>
                                <div class="pricetag_caracteristics_right">
                                    <div>Комплектация:</div>
                                    <div id="complect_view">{{ $item->equipment }}</div>
                                </div>
                            </div>
                            <div class="pricetag_producer">
                                <strong>Страна производитель:</strong> <span id="brand_country_view">{{ $item->country }}</span>
                            </div>
                        </div>
                        <div class="pricetag_bottom" id="red_string_view_2">
                            Выгодная цена!
                        </div>
                    </div>
                    
                </div>

            </div>
        </section>
        {{-- @include('general.popups') --}}
    </div>
    {{-- @include('general.scripts') --}}

<script type="text/javascript">
    console.log('Обработчик формы печати ценника');
    window.onload = function () {

        // вставляем/убираем логотип брэнда

        let logo_pic;
        let logo_tag;
        let logo_elem = document.getElementById('js_brand_logo');
        let logo_elem_view = document.getElementById('js_brand_logo_view');
        let name_bl = document.getElementById('js_name_bl');
        let name_bl_view = document.getElementById('js_name_bl_view');

        document.getElementById('js_brado').onclick = function() {
            logo_pic = "brado.jpg";
            logo_tag = "<img src='/storage/brand-logo/" + logo_pic + "'>";
            logo_elem.innerHTML = logo_tag;
            logo_elem.style.cssText = "display: flex; flex: 0 0 160px;";
            logo_elem_view.style.cssText = "display: flex; flex: 0 0 160px;";
            logo_elem_view.innerHTML = logo_tag;
            name_bl.style.cssText = "flex: 0 0 538px;";
            name_bl_view.style.cssText = "flex: 0 0 538px;";
        }

        document.getElementById('js_katana').onclick = function() {
            logo_pic = "katana.jpg";
            logo_tag = "<img src='/storage/brand-logo/" + logo_pic + "'>";
            logo_elem.innerHTML = logo_tag;
            logo_elem.style.cssText = "display: flex; flex: 0 0 160px;";
            logo_elem_view.style.cssText = "display: flex; flex: 0 0 160px;";
            logo_elem_view.innerHTML = logo_tag;
            name_bl.style.cssText = "flex: 0 0 538px;";
            name_bl_view.style.cssText = "flex: 0 0 538px;";
        }

        document.getElementById('js_welt').onclick = function() {
            logo_pic = "welt.jpg";
            logo_tag = "<img src='/storage/brand-logo/" + logo_pic + "'>";
            logo_elem.innerHTML = logo_tag;
            logo_elem.style.cssText = "display: flex; flex: 0 0 160px;";
            logo_elem_view.style.cssText = "display: flex; flex: 0 0 160px;";
            logo_elem_view.innerHTML = logo_tag;
            name_bl.style.cssText = "flex: 0 0 538px;";
            name_bl_view.style.cssText = "flex: 0 0 538px;";
        }

        document.getElementById('js_darc').onclick = function() {
            logo_pic = "darc.jpg";
            logo_tag = "<img src='/storage/brand-logo/" + logo_pic + "'>";
            logo_elem.innerHTML = logo_tag;
            logo_elem.style.cssText = "display: flex; flex: 0 0 160px;";
            logo_elem_view.style.cssText = "display: flex; flex: 0 0 160px;";
            logo_elem_view.innerHTML = logo_tag;
            name_bl.style.cssText = "flex: 0 0 538px;";
            name_bl_view.style.cssText = "flex: 0 0 538px;";
        }

        document.getElementById('js_sbk').onclick = function() {
            logo_pic = "sbk.jpg";
            logo_tag = "<img src='/storage/brand-logo/" + logo_pic + "'>";
            logo_elem.innerHTML = logo_tag;
            logo_elem.style.cssText = "display: flex; flex: 0 0 160px;";
            logo_elem_view.style.cssText = "display: flex; flex: 0 0 160px;";
            logo_elem_view.innerHTML = logo_tag;
            name_bl.style.cssText = "flex: 0 0 538px;";
            name_bl_view.style.cssText = "flex: 0 0 538px;";
        }

        document.getElementById('js_spec').onclick = function() {
            logo_pic = "spec.jpg";
            logo_tag = "<img src='/storage/brand-logo/" + logo_pic + "'>";
            logo_elem.innerHTML = logo_tag;
            logo_elem.style.cssText = "display: flex; flex: 0 0 160px;";
            logo_elem_view.style.cssText = "display: flex; flex: 0 0 160px;";
            logo_elem_view.innerHTML = logo_tag;
            name_bl.style.cssText = "flex: 0 0 538px;";
            name_bl_view.style.cssText = "flex: 0 0 538px;";
        }

        document.getElementById('js_skiper').onclick = function() {
            logo_pic = "skiper_new.jpg";
            logo_tag = "<img src='/storage/brand-logo/" + logo_pic + "'>";
            logo_elem.innerHTML = logo_tag;
            logo_elem.style.cssText = "display: flex; flex: 0 0 160px;";
            logo_elem_view.style.cssText = "display: flex; flex: 0 0 160px;";
            logo_elem_view.innerHTML = logo_tag;
            name_bl.style.cssText = "flex: 0 0 538px;";
            name_bl_view.style.cssText = "flex: 0 0 538px;";
        }

        document.getElementById('js_nologo').onclick = function() {
            logo_elem.innerHTML = '';
            logo_elem_view.innerHTML = '';
            logo_elem.style.cssText = "display: none;";
            logo_elem_view.style.cssText = "display: none;";
            name_bl.style.cssText = "flex: 0 0 100%;";
            name_bl_view.style.cssText = "flex: 0 0 100%;";
        }

        // меняем поле с наименованием

        document.getElementById('js_name').onblur = function() {
            let name = document.getElementById('js_name').value;
            document.getElementById('js_name_view').innerHTML = name;
        }
console.log('проверка');
        // обрабатываем блок с ценами и скидками

        let price_sale = document.getElementById('js_price_sale');
        let price_action = document.getElementById('js_price');
        let price_sale_view = document.getElementById('js_price_sale_view');
        let price_view = document.getElementById('js_price_view');
        let sale_table = document.getElementById('js_sale_table');
        let action_table = document.getElementById('js_action_table');
        let bel_price = document.getElementById('js_price').children[0];
        let bel_price_action = document.getElementById('js_price_sale').children[0];
        let action_button = document.getElementById('js_action');
        let discount_percent;
        let action_str;
        let type_str = document.getElementById('type_string');
        let type_price = document.getElementById('type_price');
        let price_old_inp = document.getElementById('price_old');
        let price_new_inp = document.getElementById('price_new');
        let view_price_block = document.getElementById('js_price_bl_view');
        

        document.getElementById('js_no_sale').onclick = function() {
            price_sale.style.cssText = "display: none;";
            price_sale_view.style.cssText = "display: none;";
            price_action.style.cssText = "display: block;";
            sale_table.style.cssText = "display: none;";
            action_table.style.cssText = "display: none;";
            view_price_block.style.cssText = "visibility: visible;";
            type_str.value = "";
            type_price.value = "visible";
        }

        document.getElementById('js_sale').onclick = function() {
            price_sale.style.cssText = "display: block;";
            price_sale_view.style.cssText = "display: block;";
            price_action.style.cssText = "display: block;";
            sale_table.style.cssText = "display: block;";
            action_table.style.cssText = "display: none;";
            view_price_block.style.cssText = "visibility: visible;";
            action_str = sale_table.innerHTML = 'распродажа';
            type_str.value = action_str;
            type_price.value = "visible";
        }

        action_button.onclick = function() {
            price_sale.style.cssText = "display: block;";
            price_sale_view.style.cssText = "display: block;";
            price_action.style.cssText = "display: block;";
            sale_table.style.cssText = "display: none;";
            action_table.style.cssText = "display: block;";
            view_price_block.style.cssText = "visibility: visible;";
            (isNaN(Number(bel_price.value)) || isNaN(Number(bel_price_action.value))) ? 0 :
                discount_percent = (1 - (Number(bel_price.value) / Number(bel_price_action.value))) * 100;
            action_str = action_table.innerHTML = 'акция - ' + discount_percent.toFixed(0) + ' %';
            type_str.value = action_str;
            type_price.value = "visible";
            console.log(type_str.value);
        }

        document.getElementById('js_no_price').onclick = function() {
            price_sale.style.cssText = "display: none;";
            price_action.style.cssText = "display: none;";
            price_sale_view.style.cssText = "display: none;";
            view_price_block.style.cssText = "visibility: hidden;";
            type_str.value = "";
            type_price.value = "hidden";
        }

        bel_price.onblur = function() {
            bel_pr = bel_price.value.replace(",",".");
            bel_pr = Number(bel_pr);
            (bel_pr) ? bel_pr : 0;
            if(isNaN(bel_pr)) bel_pr = 0;
            price_view.innerHTML = bel_pr.toFixed(2) + '<span>руб</span>';
            (isNaN(Number(bel_price_action.value)))
                ? discount_percent = 0 
                : discount_percent = (1 - (bel_pr / Number(bel_price_action.value))) * 100;

            if(document.getElementById('js_action').checked) {
                action_str = action_table.innerHTML = 'акция - ' + discount_percent.toFixed(0) + ' %';
            } else if(document.getElementById('js_sale').checked) {
                action_str = action_table.innerHTML = 'распродажа';
            } else {
                action_str = '';
            }

            type_str.value = action_str;
            price_old_inp.value = bel_pr.toFixed(2);
            console.log(action_str);
        }

        bel_price_action.onblur = function() {
            bel_pr_action = bel_price_action.value.replace(",",".");
            bel_pr_action = Number(bel_pr_action);
            (bel_pr_action) ? bel_pr_action : 0;
            if(isNaN(bel_pr_action)) bel_pr_action = 0;
            price_sale_view.innerHTML = bel_pr_action.toFixed(2) + '<span>руб</span>';
            (bel_pr_action != 0) ? discount_percent = (1 - (Number(bel_price.value) / bel_pr_action)) * 100 : discount_percent = 0;

            if(document.getElementById('js_action').checked) {
                action_str = action_table.innerHTML = 'акция - ' + discount_percent.toFixed(0) + ' %';
            } else if(document.getElementById('js_sale').checked) {
                action_str = action_table.innerHTML = 'распродажа';
            } else {
                action_str = '';
            }

            type_str.value = action_str;
            price_new_inp.value = bel_pr_action.toFixed(2);
            console.log(discount_percent);
        }

        // Обрабатываем поле Преимущества
        let advant = document.getElementById('advantages');
        advant.onblur = function() {
            document.getElementById('advantages_view').innerHTML = advant.value;
         }

        // Обрабатываем поле Характеристики
        let charact = document.getElementById('characteristics');
        charact.onblur = function() {
            document.getElementById('characteristics_view').innerHTML = charact.value;
         }

        // Обрабатываем поле Комплектация
        let compl = document.getElementById('complect');
        compl.onblur = function() {
            document.getElementById('complect_view').innerHTML = compl.value;
         }

        // Обрабатываем поле Страна
        let br_country = document.getElementById('brand_country');
        br_country.onblur = function() {
            document.getElementById('brand_country_view').innerHTML = br_country.value;
         }

        // Обрабатываем Красная полоса
        let r_string = document.getElementById('red_string');
        let r_string_view_1 = document.getElementById('red_string_view_1');
        let r_string_view_2 = document.getElementById('red_string_view_2');

        r_string.onblur = function() {
            r_string_view_1.innerHTML = r_string.value;
            r_string_view_2.innerHTML = r_string.value;
         }

        document.getElementById('line_checker_off').onclick = function() {
            r_string_view_1.style.cssText = "display: none;";
            r_string_view_2.style.cssText = "display: none;";
        }

        document.getElementById('line_checker_on').onclick = function() {
            r_string_view_1.style.cssText = "display: block;";
            r_string_view_2.style.cssText = "display: block;";
        }

        // скрываем отображение результата по нажатию на мини
        var mini = document.getElementById('js-mini');
        var a4 = document.getElementById('js-a4');
        var a5 = document.getElementById('js-a5');
        var a6 = document.getElementById('js-a6');
        var result = document.getElementById('js-result');
        mini.onclick = function() {
            result.style.cssText = "display: none;";
        }
        a4.onclick = function() {
            result.style.cssText = "display: block;";
        }
        a5.onclick = function() {
            result.style.cssText = "display: block;";
        }
        a6.onclick = function() {
            result.style.cssText = "display: block;";
        }

    }
</script>


    </body>
@endsection


