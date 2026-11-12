<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin') — Addariyah Press</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" href="{{ \App\Support\Site::logo() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,560&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="admin" data-admin>
        <aside class="side" data-side>
            <div class="side-head">
                <a class="brand {{ \App\Support\Site::hasCustomLogo() ? 'brand-mark' : '' }}" href="{{ route('admin.dashboard') }}">
                    <img src="{{ \App\Support\Site::logo(true) }}" alt="Addariyah Press">
                    @unless (\App\Support\Site::hasCustomLogo())
                        <span><strong>Addariyah</strong><small>Panel admin</small></span>
                    @endunless
                </a>
                <button class="side-close" type="button" data-side-close aria-label="Tutup menu">×</button>
            </div>
            <p class="side-label">Menu</p>
            <nav>
                <a href="{{ route('admin.dashboard') }}" @class(['is-active' => request()->routeIs('admin.dashboard')])>
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 10.5 12 4l8 6.5V20a1 1 0 0 1-1 1h-5v-6H10v6H5a1 1 0 0 1-1-1v-9.5z"/></svg>
                    Dasbor
                </a>
                <a href="{{ route('admin.buku.index') }}" @class(['is-active' => request()->routeIs('admin.buku.*')])>
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 4.5h9.5A3.5 3.5 0 0 1 18 8v11.5H8.5A3.5 3.5 0 0 0 5 16V4.5z"/><path d="M5 16a3.5 3.5 0 0 1 3.5-3.5H18"/></svg>
                    Buku
                </a>
                <a href="{{ route('admin.berita.index') }}" @class(['is-active' => request()->routeIs('admin.berita.*')])>
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 5.5h14v13H5z"/><path d="M8 9h8M8 12.5h8M8 16h5"/></svg>
                    Berita
                </a>
                <a href="{{ route('admin.resources.index') }}" @class(['is-active' => request()->routeIs('admin.resources.*')])>
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 3.5h7l5 5V20.5H7z"/><path d="M14 3.5v5h5M9.5 13h6M9.5 16.5h4"/></svg>
                    Pedoman & template
                </a>
                <a href="{{ route('admin.manuscripts.index') }}" @class(['is-active' => request()->routeIs('admin.manuscripts.*')])>
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4.5 7.5h15v11h-15z"/><path d="m4.5 8 7.5 6 7.5-6"/></svg>
                    Naskah masuk
                </a>
                <a href="{{ route('admin.slides.index') }}" @class(['is-active' => request()->routeIs('admin.slides.*')])>
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4.5 6.5h15v11h-15z"/><path d="m9 15 2-2 1.5 1.5L15 11.5l2 3.5"/><circle cx="9" cy="10" r="1"/></svg>
                    Slide beranda
                </a>
                <a href="{{ route('admin.ecatalog.index') }}" @class(['is-active' => request()->routeIs('admin.ecatalog.*')])>
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4.5 6.5h15v12h-15z"/><path d="m8 14.5 2.2-2.2 1.6 1.6 2.4-3 2.3 3.6"/><circle cx="9" cy="9.5" r="1"/></svg>
                    E-Catalog
                </a>
                <a href="{{ route('admin.settings.edit') }}" @class(['is-active' => request()->routeIs('admin.settings.*')])>
                    <svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="3"/><path d="M12 3.5v2.2M12 18.3v2.2M3.5 12h2.2M18.3 12h2.2M6 6l1.6 1.6M16.4 16.4 18 18M18 6l-1.6 1.6M7.6 16.4 6 18"/></svg>
                    Pengaturan
                </a>
                <a href="{{ route('home') }}">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M14 5.5h5.5V11"/><path d="M19.5 5.5 12 13"/><path d="M17 13.5V19a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V8a1 1 0 0 1 1-1h5.5"/></svg>
                    Lihat website
                </a>
            </nav>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button class="btn btn-light" type="submit">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10 6.5H6.5v11H10"/><path d="M10 12h8"/><path d="m15 8.5 3.5 3.5L15 15.5"/></svg>
                    Keluar
                </button>
            </form>
        </aside>
        <button class="side-backdrop" type="button" data-side-close aria-label="Tutup menu"></button>
        <div class="admin-main">
            <header class="admin-bar">
                <button class="menu-btn" type="button" data-side-toggle aria-label="Buka atau tutup menu" aria-expanded="true">
                    <span></span><span></span><span></span>
                </button>
                <strong>Panel admin</strong>
                <form class="admin-search" method="GET" action="{{ route('admin.buku.index') }}">
                    <input type="search" name="q" value="{{ request()->routeIs('admin.buku.index') ? request('q') : '' }}" placeholder="Cari buku" aria-label="Cari buku">
                    <button class="btn btn-line dark btn-small" type="submit">Cari</button>
                </form>
            </header>
            @include('partials.flash')
            @yield('content')
        </div>
    </div>
    <script>
        (function () {
            var root = document.querySelector('[data-admin]');
            var toggle = document.querySelector('[data-side-toggle]');
            var key = 'addariyah-admin-side';
            var mobileQuery = window.matchMedia('(max-width: 980px)');

            function mobile() {
                return mobileQuery.matches;
            }

            function setExpanded() {
                var open = mobile() ? root.classList.contains('is-open') : !root.classList.contains('is-collapsed');
                toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            }

            if (!mobile() && localStorage.getItem(key) === 'closed') {
                root.classList.add('is-collapsed');
            }
            setExpanded();

            toggle.addEventListener('click', function () {
                if (mobile()) {
                    root.classList.toggle('is-open');
                } else {
                    root.classList.toggle('is-collapsed');
                    localStorage.setItem(key, root.classList.contains('is-collapsed') ? 'closed' : 'open');
                }
                setExpanded();
            });

            document.querySelectorAll('[data-side-close], .side nav a').forEach(function (item) {
                item.addEventListener('click', function () {
                    if (mobile()) {
                        root.classList.remove('is-open');
                        setExpanded();
                    }
                });
            });
        })();
    </script>
</body>
</html>
