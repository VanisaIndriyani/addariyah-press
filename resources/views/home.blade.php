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

    <section class="band">
        <div class="container band-grid">
            <div>
                <strong>01</strong>
                <h2>Penerbitan</h2>
                <p>Naskah disunting, ditata, lalu disiapkan dengan ISBN dan QRCBN.</p>
            </div>
            <div>
                <strong>02</strong>
                <h2>Katalog digital</h2>
                <p>E-book yang sudah terbit bisa dibeli. Bayar dengan QRIS, konfirmasi lewat WhatsApp.</p>
            </div>
            <div>
                <strong>03</strong>
                <h2>Naskah masuk</h2>
                <p>Ada pedoman, template, dan formulir kirim naskah untuk penulis.</p>
            </div>
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
