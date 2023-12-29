<section class="s-popup">
    <div class="dark-bg"></div>
    <div class="w-popup _write-to-us">
        <div class="pop-table">
            <div class="pop-cell">
                <form class="w-pop" id="form-send-email-from-reg" method="post" action="/registration/manager" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                    <input type="hidden" name="login" value="">
                    <a href="" class="close">&times;</a>
                    <div class="w-head">
                        <img src=" {{ asset('assets/img/logo.png') }}" alt="Logo">
                    </div>
                    <div class="w-name">Написать письмо</div>
                    <div class="w-body">
                        <div class="input">
                            <label>Ваше имя</label>
                            <input type="text" name="name">
                        </div>
                        <div class="input">
                            <label>Ваш E-mail <span>*</span></label>
                            <input type="text" name="email">
                        </div>
                        <div class="input">
                            <label>Ваш номер телефона <span></span></label>
                            <input type="text" class="_phone-mask" name="phone">
                        </div>
                        <div class="input">
                            <label>Сообщение <span>*</span></label>
                            <textarea name="comment"></textarea>
                        </div>
                        <div class="input">
                            <div id="grecaptcha"></div>
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
                            <input type="hidden" name="grecaptcha" data-conditional="captcha" />

                        </div>
                        <div class="input ">
                            <label class="for-checkbox"><input type="checkbox" name="is_confidential" value="1"> Я настаиваю на конфиденциальности</label>
                        </div>
                        <div class="input center">
                            <input type="submit" class="button _red" value="отправить">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
