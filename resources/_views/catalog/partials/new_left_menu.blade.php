<div class="catalog-page_catalog-links-section js-catalog">
    <div class="catalog-page_catalog-links-block">

        @if (!$is_service)
            <div class="catalog-page_promo-links">
                <a href="{{ asset('catalogue/new-items') }}" title="Все новинки">
                    <span class="catalog-page_promo-links_new-sign">NEW</span>
                    Все новинки ({{ $new_items_count }})
                </a>
                <a href="{{ asset('catalogue/all-actions') }}" title="Все акции">
                    <span class="catalog-page_promo-links_action-sign">АКЦИЯ</span>
                    Все акции и скидки
                </a>
            </div>
        @endif

        <ul class="catalog-page_catalog-links">

            @foreach ($categories as $c)
                @if ($c->subCategory->count())
                    <li
                        class="catalog-page_catalog-main-link js-main-category @if ($c->id_1c == $category_id) {{ 'active' }} @endif">
                        <a {{-- href="{{ asset('catalogue/'.$c->id_1c) }}" --}} title="{{ $c->name }}" onclick='event.preventDefault()'>
                            {{ $c->name }}
                            <span class="catalog-page_catalog-main-link_gradient"></span>
                        </a>

                        <div class="catalog-page_link-arrow js-link-arrow">
                            @include('svg.phones_arrow_ico')
                        </div>

                    </li>
                @endif

                <ul class="catalog-page_inset js-category-inset">

                    @if ($c->subCategory->count())
                        <li class="catalog-page_sub-sub-cat all-items-link">

                            <a href="{{ asset('catalogue/' . $c->id_1c) }}">
                                Все товары категории
                            </a>

                        </li>

                        @foreach ($c->subCategory()->with('subCategory')->get() as $cs)
                            <div class="catalog-page_sub-cat-block">

                                <li
                                    class="catalog-page_sub-cat @if ($cs->id_1c == $category_id) {{ 'active' }} @endif">

                                    <a href="{{ asset('catalogue/' . $cs->id_1c) }}">

                                        @if ($cs->image_sm && file_exists(public_path('storage/ut_1c8/category-images/' . $cs->image_sm)))
                                            <img src="{{ asset('storage/ut_1c8/category-images/' . $cs->image_sm) }}">
                                        @endif

                                        {{ $cs->name }}

                                    </a>

                                </li>

                                @if ($cs->subCategory->count())
                                    @foreach ($cs->subCategory as $css)
                                        <li
                                            class="catalog-page_sub-sub-cat @if ($css->id_1c == $category_id) {{ 'active' }} @endif">

                                            <a href="{{ asset('catalogue/' . $css->id_1c) }}">

                                                {{ $css->name }}

                                            </a>

                                        </li>
                                    @endforeach
                                @endif

                            </div>
                        @endforeach
                    @endif

                </ul>
            @endforeach

        </ul>

    </div>
</div>
