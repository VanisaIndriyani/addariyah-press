@extends('layouts.public')

@section('title', \App\Support\Site::get('site_name', 'Addariyah Press'))
@section('description', \App\Support\Site::get('tagline', 'Penerbit buku Addariyah Press.'))

@section('content')
    <section class="hero">
        <div class="container hero-grid">
            <div>
                <p class="eyebrow light">Addariyah Press</p>
                <h1>{{ \App\Support\Site::get('hero_title', 'Penerbit yang menemani naskah sampai menjadi buku.') }}</h1>
                <p class="lead">{{ \App\Support\Site::get('hero_text', 'Katalog e-book bisa dibeli online. Pembayaran lewat QRIS, lalu konfirmasi bukti ke WhatsApp.') }}</p>
                <div class="hero-actions">
                    <a class="btn btn-light" href="{{ route('catalog') }}">Lihat katalog buku</a>
                    <a class="btn btn-line" href="{{ route('manuscripts.create') }}">Kirim naskah</a>
                </div>
            </div>
            <div class="hero-covers">
                @forelse ($heroBooks as $book)
                    <a href="{{ route('books.show', $book) }}">
                        @if ($book->cover_path)
                            <img src="{{ asset($book->cover_path) }}" alt="Sampul {{ $book->title }}">
                        @else
                            <span class="fake"><strong>{{ $book->title }}</strong></span>
                        @endif
                    </a>
                @empty
                    <div class="fake"><strong>Katalog</strong></div>
                    <div class="fake"><strong>Terbitan</strong></div>
                    <div class="fake"><strong>Naskah</strong></div>
                @endforelse
            </div>
        </div>
    </section>

    @if ($slides->isNotEmpty())
        <section class="cert-slider" data-slider aria-roledescription="carousel" aria-label="Sertifikat dan piagam">
            <div class="cert-viewport">
                <div class="cert-track">
                    @foreach ($slides as $slide)
                        <figure class="cert-slide">
                            <img src="{{ asset($slide->image_path) }}" alt="{{ $slide->title }}">
                            <figcaption>
                                <strong>{{ $slide->title }}</strong>
                                @if ($slide->caption)
                                    <span>{{ $slide->caption }}</span>
                                @endif
                            </figcaption>
                        </figure>
                    @endforeach
                </div>
            </div>
            @if ($slides->count() > 1)
                <button class="cert-arrow cert-prev" type="button" data-slider-prev aria-label="Gambar sebelumnya">‹</button>
                <button class="cert-arrow cert-next" type="button" data-slider-next aria-label="Gambar berikutnya">›</button>
                <div class="cert-dots">
                    @foreach ($slides as $slide)
                        <button type="button" data-slider-dot="{{ $loop->index }}" aria-label="Gambar {{ $loop->iteration }}" @if ($loop->first) aria-current="true" @endif></button>
                    @endforeach
                </div>
            @endif
        </section>
    @endif

    @if ($slides->count() > 1)
        <script>
            (function () {
                var root = document.querySelector('[data-slider]');
                if (!root) return;
                var track = root.querySelector('.cert-track');
                var slides = root.querySelectorAll('.cert-slide');
                var dots = root.querySelectorAll('[data-slider-dot]');
                var index = 0;
                var timer = null;
                var phone = window.matchMedia('(max-width: 760px)');

                var viewport = root.querySelector('.cert-viewport');

                function show(next) {
                    index = (next + slides.length) % slides.length;
                    if (phone.matches) {
                        viewport.style.height = '';
                        track.style.transform = 'translateX(' + (-index * 100) + '%)';
                    } else {
                        var offset = 0;
                        for (var i = 0; i < index; i++) offset += slides[i].offsetHeight;
                        viewport.style.height = slides[index].offsetHeight + 'px';
                        track.style.transform = 'translateY(' + (-offset) + 'px)';
                    }
                    dots.forEach(function (dot, i) {
                        if (i === index) dot.setAttribute('aria-current', 'true');
                        else dot.removeAttribute('aria-current');
                    });
                }

                function start() {
                    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                        stop();
                        return;
                    }
                    stop();
                    timer = setInterval(function () { show(index + 1); }, 4500);
                }

                function stop() {
                    if (timer) clearInterval(timer);
                }

                root.querySelector('[data-slider-prev]').addEventListener('click', function () {
                    show(index - 1);
                    start();
                });
                root.querySelector('[data-slider-next]').addEventListener('click', function () {
                    show(index + 1);
                    start();
                });
                dots.forEach(function (dot) {
                    dot.addEventListener('click', function () {
                        show(Number(dot.getAttribute('data-slider-dot')));
                        start();
                    });
                });
                root.addEventListener('mouseenter', stop);
                root.addEventListener('mouseleave', start);
                phone.addEventListener('change', function () {
                    show(0);
                    start();
                });
                window.addEventListener('resize', function () { show(index); });
                slides.forEach(function (slide) {
                    var image = slide.querySelector('img');
                    if (image && !image.complete) image.addEventListener('load', function () { show(index); });
                });
                start();
                show(0);
            })();
        </script>
    @endif

    <section class="band">
        <div class="container band-grid">
            @foreach ([
                ['01', 'band_1_title', 'band_1_text', 'Penerbitan', 'Naskah disunting, ditata, lalu disiapkan dengan ISBN dan QRCBN.'],
                ['02', 'band_2_title', 'band_2_text', 'Katalog digital', 'E-book yang sudah terbit bisa dibeli. Bayar dengan QRIS, konfirmasi lewat WhatsApp.'],
                ['03', 'band_3_title', 'band_3_text', 'Naskah masuk', 'Ada pedoman, template, dan formulir kirim naskah untuk penulis.'],
            ] as [$number, $titleKey, $textKey, $titleFallback, $textFallback])
                <div>
                    <strong>{{ $number }}</strong>
                    <h2>{{ \App\Support\Site::get($titleKey, $titleFallback) }}</h2>
                    <p>{{ \App\Support\Site::get($textKey, $textFallback) }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section-head">
                <div>
                    <p class="eyebrow">Katalog buku</p>
                    <h2>E-book yang bisa dibeli</h2>
                </div>
                <a class="btn btn-line dark" href="{{ route('catalog') }}">Semua katalog</a>
            </div>
            @if ($catalog->isEmpty())
                <div class="empty">Katalog belum berisi buku. Admin dapat menambahkannya dari panel.</div>
            @else
                <div class="book-grid">
                    @foreach ($catalog as $book)
                        @include('partials.book-card', ['book' => $book])
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <section class="section" style="padding-top: 0;">
        <div class="container">
            <div class="section-head">
                <div>
                    <p class="eyebrow">Terbitan</p>
                    <h2>Buku yang segera terbit</h2>
                </div>
                <a class="btn btn-line dark" href="{{ route('publications') }}">Lihat terbitan</a>
            </div>
            @if ($upcoming->isEmpty())
                <div class="empty">Belum ada jadwal terbitan.</div>
            @else
                <div class="book-grid">
                    @foreach ($upcoming as $book)
                        @include('partials.book-card', ['book' => $book])
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <section class="section" style="padding-top: 0;">
        <div class="container">
            <div class="section-head">
                <div>
                    <p class="eyebrow">Berita</p>
                    <h2>Kegiatan penerbit</h2>
                </div>
                <a class="btn btn-line dark" href="{{ route('news') }}">Semua berita</a>
            </div>
            @if ($posts->isEmpty())
                <div class="empty">Belum ada berita.</div>
            @else
                <div class="news-grid">
                    @foreach ($posts as $post)
                        <article class="news-card">
                            <a href="{{ route('news.show', $post) }}">
                                @if ($post->image_path)
                                    <img src="{{ asset($post->image_path) }}" alt="">
                                @else
                                    <span class="news-fallback">Berita</span>
                                @endif
                            </a>
                            <div class="news-card-body">
                                <p class="meta">{{ $post->published_at?->translatedFormat('d F Y') }}</p>
                                <h3><a href="{{ route('news.show', $post) }}">{{ $post->title }}</a></h3>
                                <p class="muted">{{ $post->excerpt }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif

            <div class="cta" style="margin-top: 2.5rem;">
                <div>
                    <p class="eyebrow light">Untuk penulis</p>
                    <h2>Siapkan naskah, lalu kirim dari halaman ini.</h2>
                </div>
                <a class="btn btn-light" href="{{ route('manuscripts') }}">Buka halaman naskah</a>
            </div>
        </div>
    </section>
@endsection
