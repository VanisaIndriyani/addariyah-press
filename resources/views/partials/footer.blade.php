@php
    $name = \App\Support\Site::get('site_name', 'Addariyah Press');
    $instagram = \App\Support\Site::instagramUrl();
@endphp
<footer class="site-footer">
    <div class="container footer-grid">
        <div>
            <div class="footer-brand {{ \App\Support\Site::hasCustomLogo() ? 'brand-mark' : '' }}">
                <img src="{{ \App\Support\Site::logo(true) }}" alt="{{ $name }}">
                @unless (\App\Support\Site::hasCustomLogo())
                    <strong>{{ $name }}</strong>
                @endunless
            </div>
            <p>{{ \App\Support\Site::get('tagline', 'Menerbitkan naskah menjadi buku yang rapi, terbaca, dan siap beredar.') }}</p>
        </div>
        <div>
            <h3>Jelajahi</h3>
            <ul>
                <li><a href="{{ route('catalog') }}">Katalog Buku</a></li>
                <li><a href="{{ route('publications') }}">Terbitan</a></li>
                <li><a href="{{ route('news') }}">Berita</a></li>
                <li><a href="{{ route('profile') }}">Profil</a></li>
                <li><a href="{{ route('contact') }}">Kontak</a></li>
            </ul>
        </div>
        <div>
            <h3>Naskah</h3>
            <ul>
                <li><a href="{{ route('manuscripts.guide') }}">Pedoman penulisan</a></li>
                <li><a href="{{ route('manuscripts.templates') }}">Template</a></li>
                <li><a href="{{ route('manuscripts.create') }}">Kirim naskah</a></li>
                <li><a href="{{ route('ecatalog') }}">E-Catalog</a></li>
            </ul>
        </div>
        <div>
            <h3>Kontak</h3>
            <ul>
                @if(\App\Support\Site::waNumber())
                    <li><a href="https://wa.me/{{ \App\Support\Site::waNumber() }}" target="_blank" rel="noopener">WhatsApp</a></li>
                @endif
                @if(\App\Support\Site::get('email'))
                    <li><a href="mailto:{{ \App\Support\Site::get('email') }}">{{ \App\Support\Site::get('email') }}</a></li>
                @endif
                @if($instagram)
                    <li><a href="{{ $instagram }}" target="_blank" rel="noopener">Instagram</a></li>
                @endif
            </ul>
        </div>
        @if(\App\Support\Site::get('address'))
            <div>
                <h3>Alamat</h3>
                <p class="footer-address">{!! nl2br(e(\App\Support\Site::get('address'))) !!}</p>
            </div>
        @endif
    </div>
    <div class="container legal">{{ $name }} · Pembayaran e-book lewat QRIS, konfirmasi bukti ke WhatsApp.</div>
</footer>
