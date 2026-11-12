@extends('layouts.public')

@section('title', $book->title.' — Addariyah Press')
@section('description', $book->author.' · '.$book->title)

@section('content')
    <section class="section book-section">
        <div class="container">
            <nav class="crumb" aria-label="Jejak">
                <a href="{{ route('home') }}">Beranda</a>
                <span>/</span>
                @if ($book->for_sale)
                    <a href="{{ route('catalog') }}">Katalog</a>
                @else
                    <a href="{{ route('publications') }}">Terbitan</a>
                @endif
                <span>/</span>
                <span>{{ $book->title }}</span>
            </nav>

            <div class="detail">
                <div class="book-stage">
                    @if ($book->cover_path)
                        <img src="{{ asset($book->cover_path) }}" alt="Sampul {{ $book->title }}">
                    @else
                        <span class="cover-fallback">{{ $book->title }}</span>
                    @endif
                </div>

                <div class="book-copy">
                    <div class="book-kicker">
                        <p class="eyebrow">{{ $book->category ?: 'Buku' }}</p>
                        @if ($book->upcoming)
                            <span class="pill">Segera terbit</span>
                        @endif
                        @if ($book->for_sale)
                            <span class="pill pill-soft">E-book</span>
                        @endif
                    </div>
                    <h1>{{ $book->title }}</h1>
                    <p class="book-by">{{ $book->author }} <span> · {{ $book->publisher }}</span></p>

                    @if ($book->for_sale && $book->price !== null)
                        <div class="book-buy">
                            <div>
                                <p class="price">@rupiah($book->price)</p>
                                <p class="help">File dikirim setelah pembayaran dikonfirmasi lewat WhatsApp.</p>
                            </div>
                            <form method="POST" action="{{ route('cart.store', $book) }}" class="qty-row">
                                @csrf
                                <label for="qty">Jumlah</label>
                                <input id="qty" type="number" name="qty" value="1" min="1" max="10">
                                <button class="btn btn-primary" type="submit">Masukkan keranjang</button>
                            </form>
                        </div>
                    @else
                        <div class="book-buy">
                            <div>
                                <p class="price">{{ $book->expected_label ?: 'Segera terbit' }}</p>
                                <p class="help">Buku ini belum dijual di katalog. Informasi terbit menyusul.</p>
                            </div>
                        </div>
                    @endif

                    <div class="prose">{!! \App\Support\Site::paragraphs($book->description) !!}</div>

                    @php
                        $specs = array_filter([
                            'Penulis' => $book->author,
                            'Penerbit' => $book->publisher,
                            'Kategori' => $book->category,
                            'Halaman' => $book->pages ? $book->pages.' halaman' : null,
                            'ISBN' => $book->isbn,
                            'QRCBN' => $book->qrcbn,
                            'Editor' => $book->editor,
                            'Layout' => $book->book_layout,
                            'Cover' => $book->cover_designer,
                            'Perkiraan terbit' => $book->expected_label,
                        ], fn ($value) => filled($value));
                    @endphp
                    @if ($specs)
                        <h2 class="spec-title">Detail buku</h2>
                        <dl class="spec-grid">
                            @foreach ($specs as $label => $value)
                                <div>
                                    <dt>{{ $label }}</dt>
                                    <dd>{{ $value }}</dd>
                                </div>
                            @endforeach
                        </dl>
                    @endif
                </div>
            </div>
        </div>

        @if ($related->isNotEmpty())
            <div class="container related">
                <div class="section-head">
                    <div>
                        <p class="eyebrow">Katalog lain</p>
                        <h2>Buku yang bisa ikut dipesan</h2>
                    </div>
                    <a class="btn btn-line dark" href="{{ route('catalog') }}">Semua katalog</a>
                </div>
                <div class="book-grid">
                    @foreach ($related as $item)
                        @include('partials.book-card', ['book' => $item])
                    @endforeach
                </div>
            </div>
        @endif
    </section>
@endsection
