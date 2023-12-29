@extends('layouts.new_app')

@section('content')

<div class="main-page">
	<div class="container">

        <nav class="main-page_catlogue-link">
            <a href="{{ asset('catalogue') }}">
                Каталог
            </a>
        </nav>

		<section class="main-page_news-block">
            <div class="main-page_promo-links-block hidden">
{{--                @include('general.promo-links')--}}
            </div>


{{--            <div class="main-page_promo-links-block">--}}
{{--                <a href="{{ asset('catalogue/new-items') }}" title="Все новинки" class="main-page_new-items-link">--}}
{{--                    <div class="main-page_promo-links_new-sign">NEW</div>--}}
{{--                    <div>Все новинки ({{ $new_items_count }})</div>--}}
{{--                </a>--}}
{{--                <a href="{{ asset('catalogue/all-actions') }}"  title="Все акции и скидки" class="main-page_action-items-link">--}}
{{--                    <div class="main-page_promo-links_action-sign">АКЦИЯ</div>--}}
{{--                    <div>Все акции и скидки</div>--}}
{{--                </a>--}}

{{--            </div>--}}

            <div class="main-page_news-block_header js-news-toggler">
            	<a href="/news">
            		Новости
                    <span class="">&#9660;</span>
             	</a>
            </div>

            <div class="main-page_news-block_news js-news-block">

                @foreach($news as $n)

                <div class="main-page_news-block_news-element">
                    <a href="{{ $n->getRouteName() }}">
                        <div class="main-page_news-block_news-image">
{{--                            <img src="{{ asset('/storage/'.$n->path_image) }}" alt="{{ $n->title }}">--}}
                            <img src="{{ Storage::disk('local2')->url($n->path_image) }}" alt="{{ $n->title }}">
                        </div>
                        <div class="main-page_news-block_news-description">
                            <div class="main-page_news-block_news-date">
                            	{{ $n->created_at->format('d.m.Y') }}
                            </div>
                            <div class="main-page_news-block_news-text">
                            	{{ $n->title }}
                            </div>
                        </div>
                    </a>
                </div>

                @endforeach

            </div>

		</section>

        <section class="main-page_actions-block">

            <div class="main-page_actions-block-wrapper js-action-block">

                @if($blockNews->count())

                <div class="main-page_actions-block_news">

                    @foreach($blockNews as $n)

                        <div class="main-page_actions-block_news-element">
                            <a href="{{ $n->getRouteName() }}" title="Смотреть подробнее">
                                <img src="{{ Storage::disk('local2')->url($n->path_image) }}">
{{--                                <img src="{{ asset('/storage/'.$n->path_image) }}">--}}

                                <div class="main-page_actions-block_news-content-wrapper">
                                    <div class="main-page_actions-block_news-content">
                                        <div class="main-page_actions-block_news-date">
                                            {{ $n->created_at->format('d.m.Y') }}
                                        </div>
                                        <div class="main-page_actions-block_news-text">
                                            {{ $n->title }}
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>

                    @endforeach

                </div>

                @endif

                @if($blockActions->count())

                <div class="main-page_actions-block_actions">

                    @foreach($blockActions as $action)

                    <div class="main-page_middle-block_action-element @if(($loop->count % 2) and ($loop->iteration == $loop->count)) wide @else short @endif">

                        <div class="main-page_middle-block_action-img-wrapper">

                            <div class="main-page_middle-block_action-img">
                                <img src="{{ asset($action->image) }}" alt="{{ $action->title }}">
                            </div>

                        </div>


                        <div class="main-page_middle-block_action-sticker">Акционное предложение</div>

                        <a href="{{ $action->link }}" class="main-page_middle-block_action-title">
                            <div>
                                {{ $action->title }}
                            </div>
                        </a>

                    </div>

                    @endforeach

                </div>

                @endif

            </div>

        </section>


        <section class="main-page_right-block">

            <div class="main-page_right-block_arrivals-block">

                <div class="main-page_right-block_arrivals-title js-arrivals-toggler">
                    <div>
                        Последние поступления
                        <span class="">&#9660;</span>
                    </div>
                </div>

                <div class="main-page_right-block_arrivals js-arrivals-block">

                    @foreach($arrivalItems as $item)

                    <div class="main-page_right-block_arrivals-element">
                        <a href="{{ $item->link }}">
                            <div class="main-page_right-block_arrivals-date">
                                {{ $item->created_at->format('d.m.Y') }}
                            </div>
                            <div class="main-page_right-block_arrivals-text">
                                {{ $item->title }}
                            </div>
                        </a>
                    </div>

                    @endforeach

                </div>

            </div>

            <div class="main-page_right-block_reviews-block">

                <div class="main-page_right-block_reviews-title js-reviews-toggler">
                    <a href="{{ asset('/reviews') }}">
                        Обзоры
                        <span class="">&#9660;</span>
                    </a>
                </div>

                <div class="main-page_right-block_reviews js-reviews-block">

                    @foreach($reviewItems as $item)

                    <div class="main-page_right-block_review-element">
                        <a href="/reviews/{{ $item->alias }}">
                            <div class="main-page_right-block_review-img">
                                <img src="{{ Storage::disk('local2')->url($item->path_image) }}" alt="{{ $item->title }}">
{{--                                <img src="{{ asset('/storage/'.$item->path_image) }}" alt="{{ $item->title }}">--}}
                            </div>
                            <div class="main-page_right-block_review-text">
                                {{ $item->title }}
                            </div>
                        </a>
                    </div>

                    @endforeach

                </div>

            </div>

        </section>

	</div>
</div>

@endsection

@section('scripts')
@parent

@vite(['resources/js/home_page_resizer.js', 'resources/js/home_page_right_block_handler.js'])

{{--<script type="text/javascript" src="{{ asset('assets/js/home_page_resizer.js').'?v='.config('constants.version') }}"> </script>--}}
{{--<script type="text/javascript" src="{{ asset('assets/js/home_page_right_block_handler.js').'?v='.config('constants.version') }}"> </script>--}}

<script type="text/javascript">
    $(document).ready(function() {
        console.log('приподымаем футер на 5px');
        $('.footer').css('marginTop', '-5px');
    });
</script>
<script type="text/javascript">
    // $.removeCookie('mr_state');
    // $.removeCookie('opt_state');
    // $.removeCookie('opt_state, ');
    // $.removeCookie('purcent_state');
// console.log($.cookie());
</script>

@endsection
