@php
    $name = \App\Support\Site::get('site_name', 'Addariyah Press');
@endphp
<header class="site-header">
    <div class="container header-bar">
        <a class="brand {{ \App\Support\Site::hasCustomLogo() ? 'brand-mark' : '' }}" href="{{ route('home') }}">
            <img src="{{ \App\Support\Site::logo() }}" alt="{{ $name }}">
            @unless (\App\Support\Site::hasCustomLogo())
                <span>
                    <strong>{{ $name }}</strong>
                    <small>Penerbit</small>
                </span>
            @endunless
        </a>
        <button class="nav-toggle" type="button" data-nav-toggle aria-expanded="false" aria-label="Buka menu">
            <span></span><span></span><span></span>
        </button>
        <form class="header-search" method="GET" action="{{ route('catalog') }}">
            <input type="search" name="q" value="{{ request()->routeIs('catalog') ? request('q') : '' }}" placeholder="Cari buku" aria-label="Cari buku">
            <button class="btn btn-primary btn-small" type="submit">Cari</button>
        </form>
    </div>
    <div class="nav-row">
        <nav class="nav" data-nav>
            <a href="{{ route('home') }}" @class(['is-active' => request()->routeIs('home')])>Beranda</a>
            <a href="{{ route('profile') }}" @class(['is-active' => request()->routeIs('profile', 'about')])>Profil</a>
            <a href="{{ route('catalog') }}" @class(['is-active' => request()->routeIs('catalog', 'books.show')])>Katalog Buku</a>
            <a href="{{ route('publications') }}" @class(['is-active' => request()->routeIs('publications')])>Terbitan</a>
            <a href="{{ route('news') }}" @class(['is-active' => request()->routeIs('news', 'news.show')])>Berita</a>
            <a href="{{ route('manuscripts') }}" @class(['is-active' => request()->routeIs('manuscripts*')])>Naskah</a>
            <a href="{{ route('ecatalog') }}" @class(['is-active' => request()->routeIs('ecatalog')])>E-Catalog</a>
            <a href="{{ route('prices') }}" @class(['is-active' => request()->routeIs('prices')])>Harga</a>
            <a href="{{ route('contact') }}" @class(['is-active' => request()->routeIs('contact')])>Kontak</a>
            <a href="{{ route('cart') }}" @class(['nav-cart', 'is-active' => request()->routeIs('cart')])>
                <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6.5 8h11l-.8 9.2a1.5 1.5 0 0 1-1.5 1.3H8.8a1.5 1.5 0 0 1-1.5-1.3L6.5 8Z"/><path d="M9 8V6.8A3 3 0 0 1 12 3.8 3 3 0 0 1 15 6.8V8"/></svg>
                Keranjang
                @if(($cartCount ?? 0) > 0)<span>{{ $cartCount }}</span>@endif
            </a>
            @auth
                <a href="{{ route('admin.dashboard') }}">Admin</a>
            @endauth
            <a href="https://addariyah.repositoryiaiddisidrap.com/index.php/ebooks/index" target="_blank" rel="noopener">OMP Addariyah</a>
        </nav>
    </div>
</header>
