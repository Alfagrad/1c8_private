<footer>
    <div class="tel _phone">
        <a href="tel:<?php echo '+'. preg_replace("/\D/", "", setting('footer_city_phone') );?>">{{ setting('footer_city_phone') }} </a>
    </div>
    <div class="tel _phone">
        <a href="tel:<?php echo '+'. preg_replace("/\D/", "", setting('footer_fax_phone') );?>">{{ setting('footer_fax_phone') }}</a>
    </div>
    <div class="tel _mts">
        <a href="tel:<?php echo '+'. preg_replace("/\D/", "", setting('footer_mts_phone') );?>">{{ setting('footer_mts_phone')}}</a>
    </div>
    <div class="tel _vel">
        <a href="tel:<?php echo '+'. preg_replace("/\D/", "", setting('footer_velcom_phone') );?>">{{ setting('footer_velcom_phone')}}</a>
    </div>

    <a href="" class="button _gray js-b-write-to-us">Написать письмо</a>
</footer>
