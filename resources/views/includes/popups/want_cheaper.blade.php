<div class="popup js-popup-want-cheaper">

    <div class="popup_wrapper">

        <div class="popup_info-block">

            <div class="popup_close-button js-popup-close">×</div>

            <div class="popup_logo">
                <img src="{{ asset('assets/img/logo.png') }}" alt="Alfastok logo">
            </div>

            <div class="popup_title">
                Хочу дешевле
                <br>
                <span class="js-item-name"></span>
            </div>

            <div class="popup_form-wrapper">

                <form
                    class="popup_form"
                    method="post"
                    enctype="multipart/form-data"
                    action="{{ route('email.feedback') }}"
                >

                    {{ csrf_field() }}

                    <div class="popup_field-title">Сообщение <span>*</span></div>

                    <textarea
                        placeholder="Укажите аргументы: с каким товаром сравниваете, где дешевле, и прочие аргументы. Прикрепите ссылку или файл."
                        name="comment"
                        required
                    ></textarea>

                    <label class="popup_attach-file">
                        <input type="file" name="attach">
                        <img src="{{ asset('assets/img/clip.png') }}" alt="clip">
                        <span>Прикрепить файл</span>
                    </label>

                    <label class="popup_checkbox">
                        <input type="checkbox" name="is_confidential" value="1"/>
                        Я настаиваю на конфиденциальности
                    </label>

                    <input type="hidden" name="feedback_type" value="{{ config('constants.emailTo.discount') }}">
                    <input type="hidden" name="id_1c" value="">

                    <div class="popup_button">
                        <button type="submit">Отправить</button>
                    </div>

                </form>

            </div>

        </div>

    </div>

</div>
