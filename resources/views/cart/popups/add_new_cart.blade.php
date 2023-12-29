<div class="popup js-add-cart-popup">

    <div class="popup_wrapper">

        <div class="popup_info-block">

            <div class="popup_close-button js-popup-close">×</div>

            <div class="popup_logo">
                <img src="{{ asset('assets/img/logo.png') }}" alt="Alfastok logo">
            </div>

            <div class="popup_title"></div>

            <div class="popup_form-wrapper">

                <form
                    class="popup_form"
                    method="post"
                    action="{{ route('cart.store') }}"
                >

                    {{ csrf_field() }}
                    <input type="hidden" name="profile_id" value="{{ profile()->id }}">

                    <div class="popup_input">
                        <input type="text" name="cart_name" placeholder="Введите имя корзины" required>
                    </div>

                    <div class="popup_button">
                        <button type="submit">Сохранить</button>
                    </div>

                </form>

            </div>

        </div>

    </div>

</div>
