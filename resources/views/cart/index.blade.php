@extends('layouts.public')

@section('title', 'Keranjang — Addariyah Press')
@section('robots', 'noindex, nofollow')

@section('content')
    <section class="section book-section">
        <div class="container">
            <nav class="crumb" aria-label="Jejak">
                <a href="{{ route('home') }}">Beranda</a>
                <span>/</span>
                <a href="{{ route('catalog') }}">Katalog</a>
                <span>/</span>
                <span>Keranjang</span>
            </nav>
            <div class="section-head">
                <div>
                    <p class="eyebrow">Pembelian</p>
                    <h1>Keranjang</h1>
                </div>
            </div>

            @if ($lines->isEmpty())
                <div class="cart-empty">
                    <p class="eyebrow">Belum ada buku</p>
                    <h2>Keranjang masih kosong.</h2>
                    <p class="muted">Pilih e-book dari katalog, lalu lanjutkan pembayaran lewat QRIS.</p>
                    <a class="btn btn-primary" href="{{ route('catalog') }}">Pilih buku</a>
                </div>
            @else
                <div class="cart-layout">
                    <div>
                        @foreach ($lines as $line)
                            <article class="cart-card">
                                <a class="cart-cover" href="{{ route('books.show', $line['book']) }}">
                                    @if ($line['book']->cover_path)
                                        <img src="{{ asset($line['book']->cover_path) }}" alt="Sampul {{ $line['book']->title }}">
                                    @else
                                        <span class="cover-fallback">{{ $line['book']->title }}</span>
                                    @endif
                                </a>
                                <div>
                                    <p class="meta">{{ $line['book']->category ?: 'E-book' }}</p>
                                    <h2><a href="{{ route('books.show', $line['book']) }}">{{ $line['book']->title }}</a></h2>
                                    <p class="muted">{{ $line['book']->author }} · @rupiah($line['book']->price)</p>
                                    <div class="cart-actions">
                                        <form method="POST" action="{{ route('cart.update', $line['book']) }}" class="qty-row">
                                            @csrf
                                            @method('PATCH')
                                            <label for="qty-{{ $line['book']->id }}">Jumlah</label>
                                            <input id="qty-{{ $line['book']->id }}" type="number" name="qty" value="{{ $line['qty'] }}" min="1" max="10">
                                            <button class="btn btn-line dark btn-small" type="submit">Ubah</button>
                                        </form>
                                        <form method="POST" action="{{ route('cart.destroy', $line['book']) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn-text" type="submit">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                                <p class="cart-sub">@rupiah($line['subtotal'])</p>
                            </article>
                        @endforeach
                    </div>
                    <aside class="cart-summary">
                        <h2>Ringkasan</h2>
                        <p class="sum-row"><span>Jumlah buku</span><strong>{{ $lines->sum('qty') }}</strong></p>
                        <p class="sum-row sum-total"><span>Total</span><strong>@rupiah($total)</strong></p>
                        <a class="btn btn-primary" href="{{ route('checkout') }}">Lanjut pembayaran</a>
                        <p class="help">Setelah ini kode QRIS ditampilkan. Konfirmasi bukti pembayaran lewat WhatsApp.</p>
                        <a class="cart-more" href="{{ route('catalog') }}">Tambah buku lain</a>
                    </aside>
                </div>
            @endif
        </div>
    </section>
@endsection
