@extends('layouts.admin')

@section('title', 'Buku')

@section('content')
    <div class="admin-top">
        <div>
            <p class="eyebrow">Katalog & terbitan</p>
            <h1>Buku</h1>
        </div>
        <a class="btn btn-primary" href="{{ route('admin.buku.create') }}">Tambah buku</a>
    </div>

    <section class="panel">
        <form class="crud-search" method="GET" action="{{ route('admin.buku.index') }}">
            <input type="search" name="q" value="{{ $search }}" placeholder="Cari judul, penulis, atau kategori" aria-label="Cari buku">
            <button class="btn btn-line dark btn-small" type="submit">Cari</button>
            @if ($search !== '')
                <a class="btn btn-line dark btn-small" href="{{ route('admin.buku.index') }}">Reset</a>
            @endif
        </form>

        @forelse ($books as $book)
            <article class="book-admin-row">
                <a href="{{ route('admin.buku.edit', $book) }}">
                    @if ($book->cover_path)
                        <img class="preview" src="{{ asset($book->cover_path) }}" alt="Sampul {{ $book->title }}">
                    @else
                        <span class="preview preview-empty">Tanpa sampul</span>
                    @endif
                </a>
                <div>
                    <strong><a href="{{ route('admin.buku.edit', $book) }}">{{ $book->title }}</a></strong>
                    <p class="muted">{{ $book->author }}@if ($book->category) · {{ $book->category }}@endif</p>
                    <div class="tags">
                        @if ($book->for_sale)<span class="tag">Katalog</span>@endif
                        @if ($book->upcoming)<span class="tag tag-soft">Terbitan</span>@endif
                        @if ($book->is_active)
                            <span class="tag tag-line">Tampil</span>
                        @else
                            <span class="tag tag-muted">Disembunyikan</span>
                        @endif
                    </div>
                    <div class="book-admin-foot">
                        <p class="book-admin-price">{{ $book->price !== null ? \App\Support\Site::rupiah($book->price) : 'Belum dijual' }}</p>
                        <div class="actions">
                            @if ($book->is_active)
                                <a class="btn btn-line dark btn-small" href="{{ route('books.show', $book) }}" target="_blank" rel="noopener">Lihat</a>
                            @endif
                            <a class="btn btn-line dark btn-small" href="{{ route('admin.buku.edit', $book) }}">Ubah</a>
                            <form method="POST" action="{{ route('admin.buku.destroy', $book) }}" onsubmit="return confirm('Hapus buku ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-small" type="submit">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </article>
        @empty
            <div class="empty">
                <p>{{ $search !== '' ? 'Tidak ada buku yang cocok.' : 'Belum ada buku.' }}</p>
                <a class="btn btn-primary" href="{{ route('admin.buku.create') }}">Tambah buku</a>
            </div>
        @endforelse
        {{ $books->links() }}
    </section>
@endsection
