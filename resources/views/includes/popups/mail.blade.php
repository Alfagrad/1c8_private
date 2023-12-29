<div x-data="{show:false}">

    <div @click="show=true" class="header_phone-element">
        <div class="header_phone-line">
            <div class="header_phone-ico">
                @include('svg.wr_letter_ico')
            </div>
            <div class="header_write-to-us js-write-to-us">Написать письмо менеджеру</div>
        </div>
    </div>

    <div @click="show=true" class="header_phone-element">
        <div class="header_phone-line">
            <div class="header_phone-ico">
                @include('svg.wr_director_ico')
            </div>
            <div class="header_write-to-director js-to-director">Пожаловаться директору
            </div>
        </div>
    </div>

    <div @click="show=true" class="header_phone-element">
        <div class="header_phone-line">
            <div class="header_phone-ico">
                @include('svg.wr_demping_ico')
            </div>
            <div class="header_write-demping js-demping">Пожаловаться на демпинг</div>
        </div>
    </div>

    <div x-show="show" x-cloak x-transition>
        <div class="popup js-mail-popup">

            <div class="popup_wrapper">

                <div @click.outside="show=false" class="popup_info-block">

                    <div @click="show=false" class="popup_close-button js-popup-close">×</div>

                    <div class="popup_logo">
                        <img src="{{ asset('assets/img/logo.png') }}" alt="Alfastok logo">
                    </div>

                    <div class="popup_title"></div>

                    <div class="popup_form-wrapper">

                        <form
                            class="popup_form"
                            method="post"
                            enctype="multipart/form-data"
                            action="{{ route('email.feedback') }}"
                        >

                            {{ csrf_field() }}

                            <div class="popup_field-title">Сообщение <span>*</span></div>

                            <textarea placeholder="Ваше сообщение, ссылка..." name="comment" required></textarea>

                            <label class="popup_attach-file">
                                <input type="file" name="attach">
                                <img src="{{ asset('assets/img/clip.png') }}" alt="clip">
                                <span>Прикрепить файл</span>
                            </label>

                            <label class="popup_checkbox">
                                <input type="checkbox" name="is_confidential" value="1"/>
                                Я настаиваю на конфиденциальности
                            </label>

                            <input type="hidden" name="feedback_type" value="">

                            @if(isset($item1cId) and $item1cId)

                                <input type="hidden" name="id_1c" value="{{ $item1cId }}">

                            @endif

                            <div class="popup_button">
                                <button type="submit">Отправить</button>
                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>
    </div>

</div>


{{--@section('scripts')--}}
{{--@parent--}}

{{--@vite(['resources/js/popups_email_to.js'])--}}
{{--<script type="text/javascript">--}}
{{--$(document).ready(function(){--}}

{{--    $('.js-write-to-us, .js-to-director, .js-demping, .js-discount').click(function(e) {--}}

{{--        // запускаем всплывающее окно--}}
{{--        $('.js-mail-popup').fadeIn('slow' , 'linear');--}}

{{--        // вписываем заголовок--}}
{{--        $('.popup_title').html($(this).html());--}}

{{--        // метка для типа сообщения--}}
{{--        if($(this).hasClass('js-write-to-us')) {--}}
{{--            $('.js-mail-popup input[name=feedback_type]').attr('value', {{ config('constants.emailTo.manager') }});--}}
{{--        }--}}
{{--        if($(this).hasClass('js-to-director')) {--}}
{{--            $('.js-mail-popup input[name=feedback_type]').attr('value', {{ config('constants.emailTo.head') }});--}}
{{--        }--}}
{{--        if($(this).hasClass('js-demping')) {--}}
{{--            $('.js-mail-popup input[name=feedback_type]').attr('value', {{ config('constants.emailTo.claim') }});--}}
{{--        }--}}
{{--    });--}}

{{--    // закрываем всплывающее окно--}}
{{--    $('.js-popup-close').click(function() {--}}
{{--        $('.js-mail-popup').fadeOut('slow' , 'linear');--}}
{{--    });--}}

{{--    // меняем "Прикрепить файл" на название файла--}}
{{--    $(".js-mail-popup input[type=file]").change(function(){--}}

{{--        var filename = $(this).val().replace(/.*\\/, "");--}}

{{--        // если слишком большой файл, выдаем предупреждение--}}
{{--        if(this.files[0].size > 20*1024*1024 ){--}}
{{--            $(this).val('');--}}
{{--            alert('Ошибка! Размер файла должен быть менее 20 мб');--}}

{{--        } else {--}}
{{--            $('.popup_attach-file span').text(filename);--}}
{{--        }--}}

{{--    });--}}


{{--    console.log('всплывашка письмо');--}}

{{--});--}}
{{--</script>--}}

{{--@endsection--}}
