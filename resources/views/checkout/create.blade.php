@extends('layouts.public')

@section('title', 'Checkout — Addariyah Press')
@section('robots', 'noindex, nofollow')

@section('content')
    <section class="section">
        <div class="container checkout-grid">
            <div>
                <p class="eyebrow">Pembayaran</p>
                <h1>Data pemesan</h1>
                <form class="panel" style="padding: 1.2rem;" method="POST" action="{{ route('checkout.store') }}">
                    @csrf
                    <div class="field">
                        <label for="name">Nama</label>
                        <input id="name" name="name" value="{{ old('name') }}" required>
                    </div>
                    <div class="field">
                        <label for="phone">WhatsApp</label>
                        <input id="phone" name="phone" value="{{ old('phone') }}" required placeholder="08xxxxxxxxxx">
                    </div>
                    <div class="field">
                        <label for="email">Email <span class="muted">(opsional)</span></label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}">
                    </div>
                    <div class="field">
                        <label for="note">Catatan</label>
                        <textarea id="note" name="note" rows="4">{{ old('note') }}</textarea>
                    </div>
                    <button class="btn btn-primary" type="submit">Buat pesanan</button>
                </form>
            </div>
            <aside class="panel" style="padding: 1.2rem;">
                <h2>Ringkasan</h2>
                @foreach ($lines as $line)
                    <p>{{ $line['book']->title }} × {{ $line['qty'] }}<br><strong>@rupiah($line['subtotal'])</strong></p>
                @endforeach
                <div class="total"><span>Total</span><span>@rupiah($total)</span></div>
                <p class="help">Setelah pesanan dibuat, kode QRIS ditampilkan. Konfirmasi pembayaran dikirim ke WhatsApp penerbit.</p>
            </aside>
        </div>
    </section>
@endsection
