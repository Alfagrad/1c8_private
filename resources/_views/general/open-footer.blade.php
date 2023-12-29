<footer class="footer">
    <div class="container">

        <div class="footer_info">

            <div class="footer_address-block">

                <div class="footer_legal-address element">
                    <div class="footer_svg">
                        @include('svg.mail_ico')
                    </div>
                    <div>
                        {!! setting('legal_address') !!}
                    </div>
                </div>

                <div class="footer_actual_address">
                    <div class="footer_svg">
                        @include('svg.address_ico')
                    </div>
                    <div>
                        {!! setting('actual_address') !!}
                    </div>
                </div>

            </div>

            <div class="footer_phones-block">

                <div class="footer_phones">
                    <a href="tel:<?php echo '+'. preg_replace("/\D/", "", setting('footer_city_phone') );?>">
                        <div class="footer_svg">
                            @include('svg.phone_footer_ico')
                        </div>
                        <div>
                            {!! setting('footer_city_phone') !!}
                        </div>
                    </a>

                    <a href="tel:<?php echo '+'. preg_replace("/\D/", "", setting('footer_fax_phone') );?>">
                        <div class="footer_svg">
                            @include('svg.fax_footer_ico')
                        </div>
                        <div>
                            {!! setting('footer_fax_phone') !!}
                        </div>
                    </a>
                </div>

                <div class="footer_phones">
{{--
                    <a href="tel:{!! '+'. preg_replace("/\D/", "", setting('footer_mts_phone') ) !!}">
                        <img src="{{ asset('assets/img/mts_ico.jpg') }}">
                        <div>
                            {{ setting('footer_mts_phone') }}
                        </div>
                    </a>
 --}}
                    <a href="tel:<?php echo '+'. preg_replace("/\D/", "", setting('open_footer_velcom_phone') );?>">
                        <img src="{{ asset('assets/img/a1_ico.jpg') }}">
                        <div>
                            {!! setting('open_footer_velcom_phone') !!}
                        </div>
                    </a>
                </div>

            </div>

        </div>

        <div class="footer_social-links-wrapper">
            <div class="footer_social-links-block">
                <a href="https://www.youtube.com/channel/UCfknEny_iCcCkTVyNqhvDXA/videos" target="_blank" title="Мы в Youtube">
                    <img src="{{ asset('storage/youtube_square_ico.png') }}" alt="youtube icon">
                    Youtube
                </a>
                <a href="https://www.instagram.com/katana.by/" target="_blank" title="Мы в Instagram">
                    <img src="{{ asset('storage/instagram_ico.png') }}" alt="instagram icon">
                    Instagram
                </a>
            </div>
        </div>

        <div class="footer_copyright">
            &copy; ООО «Альфасад» 2003 - {{ date('Y', time()) }}
        </div>

    </div>
</footer>
