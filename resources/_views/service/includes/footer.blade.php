<!--FOOTER-->
<footer class="s-footer">
    <div class="container">
        <div class="wrapper white-bg-wrapper">
            <div class="f-top service">
                <div class="f-cell">
                    <div class="tel _mail">
                        <p>{{setting('legal_address')}}</p>
                    </div>
                </div>
                <div class="f-cell">
                    <div class="tel _location">
                        <p>{!! setting('actual_address') !!}</p>
                    </div>
                </div>
                <div class="f-cell f-phones service">
                    <div>
                        <div class="tel ">
                            <a href="tel:<?php echo '+'. preg_replace("/\D/", "", setting('service_phone_1') );?>">{{ setting('service_phone_1')}}</a>
                        </div>
                        <div class="tel ">
                            <a href="tel:<?php echo '+'. preg_replace("/\D/", "", setting('service_phone_2') );?>">{{ setting('service_phone_2')}}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="f-bottom">
                <div class="wrapper">
                    <div class="left">
                        <div class="copy">
                            <p>&copy;  {{setting('footer_copyright')}}</p>
                        </div>
                    </div>
                    <div class="right">

                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!--FOOTER END-->
