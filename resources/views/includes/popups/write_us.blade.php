<div x-data="{show:false}">

    <div class="header_phone-element" title="Напишите нам!">
        <div class="header_phone-line">
            <div class="header_phone-ico">
                @include('svg.wr_letter_ico')
            </div>
            <div @click="show=true" class="header_write-to-us js-write-us">Написать письмо</div>
        </div>
    </div>


    <div x-show="show" x-cloak x-transition class="open_popup _js-popup">

        <div class="open_popup_wrapper">

            <div @click.away="show=false" class="open_popup_info-block">

                <div @click="show=false" class="open_popup_close-button _js-popup-close">×</div>

                <div class="open_popup_logo">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="Alfastok logo">
                </div>

                <div class="open_popup_title">
                    Написать письмо
                </div>

                <div class="open_popup_form-wrapper">

                    <form class="open_popup_form" id="captcha-validate" method="post"
                          action="{{ asset('/mail-to-us') }}">

                        {{ csrf_field() }}

                        <div class="open_popup_field-title">Ваше имя <span>*</span></div>
                        <input type="text" name="name" required>

                        <div class="open_popup_field-title">Ваш E-mail <span>*</span></div>
                        <input type="email" name="email" required>

                        <div class="open_popup_field-title">Ваш номер телефона</div>
                        <input type="text" name="phone">

                        <div class="open_popup_field-title">Сообщение <span>*</span></div>
                        <textarea name="comment" required></textarea>

                        <div class="open_popup_capcha" id="grecaptcha"></div>
                        <script type="text/javascript">
                            var onloadCallback = function () {
                                grecaptcha.render('grecaptcha', {
                                    'sitekey': '6LfCS48UAAAAAKfGiuG8iLv71DFoXRDhe-iZ6Rvw',
                                    'callback': verifyCallback
                                });
                            };
                            var verifyCallback = function (response) {
                                if (response.length > 0 && response != false) {
                                    $('input[name=grecaptcha]').val(1);
                                }
                            };
                        </script>
                        <input type="hidden" name="grecaptcha" data-conditional="captcha">

                        <div class="open_popup_button">
                            <button type="submit">Отправить</button>
                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>
