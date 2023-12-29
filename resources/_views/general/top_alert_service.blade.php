    @if($top_alert_service->is_active) {{-- Если категории, не выводим.. --}}

    <div class="top-alert">
        <div class="container">
            <div class="top-alert_content">
                {!! $top_alert_service->content !!}
            </div>
            <div class="top-alert_close-button" title="Закрыть">
                <svg viewBox="0 0 50 50">
                    <path d="M48.77 1.21c-1.64,-1.62 -4.3,-1.62 -5.94,0l-17.92 17.93 -17.66 -17.68c-1.63,-1.62 -4.28,-1.62 -5.9,0 -1.63,1.63 -1.63,4.3 0,5.92l17.67 17.64 -17.79 17.8c-1.64,1.63 -1.64,4.3 0,5.97 1.64,1.62 4.3,1.62 5.94,0l17.79 -17.81 17.66 17.68c1.63,1.63 4.28,1.63 5.9,0 1.63,-1.62 1.63,-4.29 0,-5.92l-17.66 -17.64 17.91 -17.93c1.64,-1.66 1.64,-4.29 0,-5.96zm0 0l0 0z"/>
                </svg>
            </div>
        </div>
    </div>

    @endif
