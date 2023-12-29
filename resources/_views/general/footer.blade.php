<!--FOOTER-->
<footer class="s-footer">
    <div class="container">
        <div class="wrapper white-bg-wrapper">
            <div class="f-top">
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
                <div class="f-cell f-phones">
                    <div>
                        <div class="tel _phone">
                            <a href="tel:<?php echo '+'. preg_replace("/\D/", "", setting('footer_city_phone') );?>">{{ setting('footer_city_phone') }} </a>
                        </div>
                        <div class="tel _phone">
                            <a href="tel:<?php echo '+'. preg_replace("/\D/", "", setting('footer_fax_phone') );?>">{{ setting('footer_fax_phone') }}</a>
                        </div>
                    </div>
                    <div>
                        <div class="tel _mts">
                            <a href="tel:<?php echo '+'. preg_replace("/\D/", "", setting('footer_mts_phone') );?>">{{ setting('footer_mts_phone')}}</a>
                        </div>
                        <div class="tel _vel">
                            <a href="tel:<?php echo '+'. preg_replace("/\D/", "", setting('footer_velcom_phone') );?>">{{ setting('footer_velcom_phone')}}</a>
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
