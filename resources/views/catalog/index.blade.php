@extends('layouts.public')

@section('title', 'Katalog Buku — Addariyah Press')
@section('description', 'E-book Addariyah Press yang bisa dibeli online.')

@section('content')
    <section class="section">
        <div class="container">
            <p class="eyebrow">Katalog buku</p>
            <h1>Buku yang bisa dibeli</h1>
            <p class="muted">Pilih e-book, masukkan ke keranjang, bayar lewat QRIS, lalu konfirmasi ke WhatsApp.</p>

            <form class="filters" method="GET" action="{{ route('catalog') }}">
                <input type="search" name="q" value="{{ $search }}" placeholder="Cari judul atau penulis" aria-label="Cari buku">
                <select name="kategori" aria-label="Kategori">
                    <option value="">Semua kategori</option>
                    @foreach ($categories as $item)
                        <option value="{{ $item }}" @selected($category === $item)>{{ $item }}</option>
                    @endforeach
                </select>
                <button class="btn btn-primary btn-small" type="submit">Cari</button>
            </form>

            @if ($books->isEmpty())
                <div class="empty">Tidak ada buku yang cocok dengan pencarian.</div>
            @else
                <div class="book-grid">
                    @foreach ($books as $book)
                        @include('partials.book-card', ['book' => $book])
                    @endforeach
                </div>
                {{ $books->links() }}
            @endif
        </div>
    </section>
@endsection
