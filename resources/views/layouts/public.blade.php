<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Addariyah Press')</title>
    <meta name="description" content="@yield('description', 'Addariyah Press menerbitkan buku, menjual e-book, dan menerima naskah.')">
    <meta name="robots" content="@yield('robots', 'index, follow')">
    <meta name="theme-color" content="#0B3D2E">
    <link rel="icon" href="{{ \App\Support\Site::logo() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,620&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <a class="skip" href="#isi">Loncat ke isi</a>
    @include('partials.header')
    <main id="isi">
        @if (session('success') || session('error') || $errors->any())
            <div class="container">@include('partials.flash')</div>
        @endif
        @yield('content')
    </main>
    @include('partials.footer')
    <div class="lightbox" data-lightbox-view hidden>
        <img alt="Gambar diperbesar">
    </div>
    <script>
        (function () {
            var toggle = document.querySelector('[data-nav-toggle]');
            var nav = document.querySelector('[data-nav]');
            if (toggle && nav) {
                toggle.addEventListener('click', function () {
                    var open = nav.classList.toggle('is-open');
                    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
                });
            }
            var box = document.querySelector('[data-lightbox-view]');
            if (!box) return;
            document.querySelectorAll('[data-lightbox]').forEach(function (link) {
                link.addEventListener('click', function (event) {
                    event.preventDefault();
                    box.querySelector('img').src = link.getAttribute('href');
                    box.querySelector('img').alt = link.getAttribute('data-alt') || 'Gambar e-catalog';
                    box.hidden = false;
                });
            });
            box.addEventListener('click', function () { box.hidden = true; });
            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') box.hidden = true;
            });
        })();
    </script>
</body>
</html>
