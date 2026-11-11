@extends('layouts.public')

@section('title', 'Terbitan — Addariyah Press')

@section('content')
    <section class="section">
        <div class="container">
            <p class="eyebrow">Terbitan</p>
            <h1>Buku yang akan segera terbit</h1>
            <p class="muted">Daftar ini memuat informasi buku yang sedang disiapkan Addariyah Press.</p>
            @include('partials.search', ['action' => route('publications'), 'placeholder' => 'Cari judul, penulis, atau kategori'])
            @if ($books->isEmpty())
                <div class="empty">Belum ada buku dalam daftar terbitan.</div>
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
