<!--POPUP-->
<section class="s-popup">
    <div class="dark-bg"></div>
    <div class="w-popup _write-to-us">
        <div class="pop-table">
            <div class="pop-cell">
                <form class="w-pop" id="form-send-email-to-manager" method="post" action="/email/manager"
                      enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                    <input type="hidden" name="feedback_type" value="{{config('constants.emailTo.manager')}}">
                    <a href="" class="close">&times;</a>
                    <div class="w-head">
                        <img src=" {{ asset('assets/img/logo.png') }}" alt="Logo">
                    </div>
                    <div class="w-name">Написать письмо</div>
                    <div class="w-body">
                        <div class="input">
                            <label>Сообщение <span>*</span></label>
                            <textarea placeholder="Ваше сообщение, ссылка..." name="comment"></textarea>
                        </div>
                        <div class="input">
                            <label class="attach_file">
                                <input type="file" name="attach" style="display: none">
                                <span class="file_path">Прикрепить файл</span>
                            </label>
                        </div>
                        <div class="input">
                            <label class="for-checkbox">
                                <input type="checkbox" name="is_confidential" value="1"/>
                                Я настаиваю на конфиденциальности
                            </label>
                        </div>
                        <div class="input center">
                            <input type="submit" class="button _red" value="отправить">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="w-popup _demping">
        <div class="pop-table">
            <div class="pop-cell">
                <form class="w-pop" id="form-send-email-to-claim" method="post" action="/email/manager"
                      enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                    <input type="hidden" name="feedback_type" value="{{config('constants.emailTo.claim')}}">
                    @if(isset($item1cId) and $item1cId)
                        <input type="hidden" name="id_1c" value="{{$item1cId}}">
                    @endif

                    <input type="hidden" name="feedback_type" value="{{config('constants.emailTo.claim')}}">

                    <a href="" class="close">&times;</a>
                    <div class="w-head">
                        <img src=" {{ asset('assets/img/logo.png') }}" alt="Logo">
                    </div>
                    <div class="w-name">Пожаловаться на демпинг
                        @if(isset($itemNameToPopUp) and $itemNameToPopUp)
                            <span class="demping-item-name">{{$itemNameToPopUp}}</span>
                        @endif
                    </div>
                    <div class="w-body">
                        <div class="input">
                            <label>Сообщение <span>*</span></label>
                            <textarea placeholder="Ваше сообщение, ссылка..." name="comment"></textarea>
                        </div>
                        <div class="input">
                            <label class="attach_file">
                                <input type="file" name="attach" style="display: none">
                                <span class="file_path">Прикрепить файл</span>
                            </label>
                        </div>
                        <div class="input">
                            <label class="for-checkbox">
                                <input type="checkbox" name="is_confidential" value="1"/>
                                Я настаиваю на конфиденциальности
                            </label>
                        </div>
                        <div class="input center">
                            <input type="submit" class="button _red" value="отправить">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="w-popup _to-director">
        <div class="pop-table">
            <div class="pop-cell">
                <form class="w-pop" id="form-send-email-to-head" method="post" action="/email/manager"
                      enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                    <input type="hidden" name="feedback_type" value="{{config('constants.emailTo.head')}}">
                    <a href="" class="close">&times;</a>
                    <div class="w-head">
                        <img src=" {{ asset('assets/img/logo.png') }}" alt="Logo">
                    </div>
                    <div class="w-name">Пожаловаться директору</div>
                    <div class="w-body">
                        <div class="input">
                            <label>Сообщение <span>*</span></label>
                            <textarea placeholder="Ваше сообщение, ссылка..." name="comment"></textarea>
                        </div>
                        <div class="input">
                            <label class="attach_file">
                                <input type="file" name="attach" style="display: none">
                                <span class="file_path">Прикрепить файл</span>
                            </label>
                        </div>
                        <div class="input">
                            <label class="for-checkbox">
                                <input type="checkbox" name="is_confidential" value="1"/>
                                Я настаиваю на конфиденциальности
                            </label>
                        </div>
                        <div class="input center">
                            <input type="submit" class="button _red" value="отправить">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <div class="w-popup _add_cart">
        <div class="pop-table">
            <div class="pop-cell">
                <form class="w-pop" id="form-send-email-to-head" method="post" action="/cart/addCart"
                      enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                    <input type="hidden" name="feedback_type" value="{{config('constants.emailTo.discount')}}">
                    <input type="hidden" name="id_1c" value="">
                    <a href="" class="close">&times;</a>
                    <div class="w-head">
                        <img src=" {{ asset('assets/img/logo.png') }}" alt="Logo">
                    </div>

                    <div class="w-name">Создать новую корзину
                    </div>

                    <div class="w-body">
                        <input type="text" placeholder="Введите имя  новой корзины" name="cart" required="required"  aria-required="true" class="successMessages" aria-invalid="false">

                        <div class="input center" style="margin: 10px">
                            <input type="submit" class="button _red" value="отправить">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <div class="w-popup _p-discount">
        <div class="pop-table">
            <div class="pop-cell">
                <form class="w-pop" id="form-send-email-to-claim" method="post" action="/email/manager"
                      enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                    <input type="hidden" name="feedback_type" value="{{config('constants.emailTo.discount')}}">
                    <input type="hidden" name="id_1c" value="">
                    <a href="" class="close">&times;</a>
                    <div class="w-head">
                        <img src=" {{ asset('assets/img/logo.png') }}" alt="Logo">
                    </div>
                    <div class="w-name">Хочу дешевле
                        <span class="demping-item-name"></span>
                    </div>
                    <div class="w-body">
                        <div class="input">
                            <label>Сообщение <span>*</span></label>
                            <textarea
                                    placeholder="Укажите аргументы: с каким товаром сравниваете, где дешевле, и прочие аргументы. Прикрепите ссылку или файл"
                                    name="comment"></textarea>
                        </div>
                        <div class="input">
                            <label class="attach_file">
                                <input type="file" name="attach" style="display: none">
                                <span class="file_path">Прикрепить файл</span>
                            </label>
                        </div>
                        <div class="input">
                            <label class="for-checkbox">
                                <input type="checkbox" name="is_confidential" value="1"/>
                                Я настаиваю на конфиденциальности
                            </label>
                        </div>
                        <div class="input center">
                            <input type="submit" class="button _red" value="отправить">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="w-popup w-popup-full _p-spares">
        <div class="pop-table">
            <div class="pop-cell w-pop"><a href="" class="close">&times;</a>
                <div class="w-body">

                </div>
            </div>
        </div>
    </div>
</section>
<!--POPUP-->
