<!-- Yandex.Metrika counter -->




<script type="text/javascript" >



    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {

            var params = {
                @if (session('state'))
                    state: '{{ session('state') }}',
                @else
                    @if(Request::user())
                        state: 'visit',
                    @endif
                @endif

                @if (session('userId'))
                    user_id: {{ session('userId') }},
                    email: '{{ Request::user()->email  }}'
                @else
                    @if(Request::user())
                        user_id: {{ Request::user()->id }},
                        email: '{{ Request::user()->email  }}'
                    @endif
                @endif
            }

            try {
                w.yaCounter46488507 = new Ya.Metrika({
                    id:46488507,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    webvisor:true,
                    params: params
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
                s = d.createElement("script"),
                f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/46488507" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-D7DH7R8L3M"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-D7DH7R8L3M');
</script>