<div class="open_popup js-popup-forgot-pass">
    
    <div class="open_popup_wrapper">
        
        <div class="open_popup_info-block">

            <div class="open_popup_close-button js-forgot-close">×</div>

            <div class="open_popup_logo">
                <img src="{{ asset('assets/img/logo.png') }}" alt="Alfastok logo">
            </div>
            
            <div class="open_popup_title">
                Напомнить пароль
            </div>

            <div class="open_popup_form-wrapper">

                <form class="open_popup_form" id="form-remember-password" method="post" action="/remember/restore" >

                    {{ csrf_field() }}

                    <div class="open_popup_field-title">E-mail <span>*</span></div> 
                    <input type="email" name="email" required>

                    <div class="open_popup_submit-block">
                        <button type="submit">Напомнить</button>
                        <a href="/registration">Регистрация</a>
                    </div>

                </form>

                <div class="open_popup_result-note-wrapper js-result-wrapper">

                    <div class="open_popup_result-note js-result-note"></div>
                    
                </div>

            </div>

        </div>
    </div>
</div>
