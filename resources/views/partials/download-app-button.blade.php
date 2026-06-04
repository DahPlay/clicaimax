{{--
    Botão "Baixar App" com detecção de plataforma.

    Detecção Apple (Bloco 2): UA contém Macintosh, iPhone, iPad ou iPod → App Store.
    NÃO use navigator.vendor (falha em Chrome/Firefox do Mac).

    Configure os links nos custom config:
      - custom.app_store_url   (link iOS / App Store)
      - custom.play_store_url  (link Android / Google Play)
--}}

@php
    $iosUrl     = config('custom.app_store_url', '#');
    $androidUrl = config('custom.play_store_url', '#');
@endphp

<a href="#" id="downloadAppBtn"
   data-ios-url="{{ $iosUrl }}"
   data-android-url="{{ $androidUrl }}"
   class="btn-download-app">
    <i class="fa fa-download mr-2"></i> Baixar App
</a>

<script>
    (function () {
        const btn = document.getElementById('downloadAppBtn');
        if (!btn) return;

        const ua = navigator.userAgent || '';

        // Detecção Apple por UA: Macintosh, iPhone, iPad ou iPod.
        // Cobre Mac (Safari/Chrome/Firefox), iPad em modo desktop e iPhone.
        const isApple = /Macintosh|iPhone|iPad|iPod/i.test(ua);
        const isAndroid = /Android/i.test(ua);

        let target = btn.dataset.iosUrl; // default fallback: App Store
        if (isApple) {
            target = btn.dataset.iosUrl;
        } else if (isAndroid) {
            target = btn.dataset.androidUrl;
        }

        btn.setAttribute('href', target);
        btn.setAttribute('target', '_blank');
        btn.setAttribute('rel', 'noopener');
    })();
</script>
