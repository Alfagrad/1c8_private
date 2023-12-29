@php
    if ($first_scheme_id == $spare->scheme_id) {
        $scheme_item_line_class = "scheme_item_line active";
    } else {
        $scheme_item_line_class = "scheme_item_line";
    }
@endphp

<div 
    class="catalog-item-line_wrapper js-item-element js-filtred-item {{ $scheme_item_line_class }}"
    data-is_service="{{ $is_service }}"
>

    <div class="catalog-item-line spare-empty">

        <div class="catalog-item-line_image-wrapper">

            <div class="catalog-item-line_image">

            </div>

        </div>
        
        <div class="catalog-item-line_name-block-wrapper">

            <div class="catalog-item-line_name-block">

                <div class="catalog-item-line_item-name">

                    <span class="catalog-item-line_item-code">
                    </span>

                    <span class="catalog-item-line_spare-name spare js-scheme-num" data-scheme_id={{ $spare->scheme_id }}>
                        {{ $spare->spare_name }}
                    </span>

                    <span class="catalog-item-line_item-code spare">
                        {{ $spare->position }}
                    </span>

                    <span class="catalog-item-line_item-code spare">
                        {{ $spare->amount }}
                    </span>

                    <span class="catalog-item-line_name">

                        <span class="name"></span>

                    </span>

                </div>

            </div>
            
        </div>





{{--         <div class="catalog-item-line_links-block">

        </div>

        <div class="catalog-item-line_opt-price-block js-opt">

        </div>

        <div class="catalog-item-line_percent-block js-purcent">

            <div>

                <div class="catalog-item-line_mobile-price-title">
                </div>

            </div>

        </div>

        <div class="catalog-item-line_mr-block js-mr">

        </div> --}}

        @if($is_service)

            <div class="catalog-item-line_input-block-wrapper">

                <div class="catalog-item-line_input-block">

                    <div class="catalog-item-line_input-control minus js-item-remove-from-cart">
                        -
                    </div>

                    <input 
                        type="number"
                        name="item_count"
                        class="js-count-input"
                        value="0"
                        data-step="" 
                        data-id_1c=""
                        data-rout_name="{{ \Request::route()->getName() }}"
                        onfocus="this.removeAttribute('readonly');"
                        readonly
                        autocomplete="off"
                    >

                    <div class="catalog-item-line_input-control plus js-item-add-to-cart">
                        +
                    </div>
                    
                </div>

            </div>

            <div class="catalog-item-line_availability-block">

            </div>

        @endif

    </div>

</div>