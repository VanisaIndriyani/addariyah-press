@extends('layouts.public')

@section('title', 'Pembayaran '.$order->code.' — Addariyah Press')
@section('robots', 'noindex, nofollow')

@section('content')
    <section class="section">
        <div class="container pay-grid">
            <div class="panel" style="padding: 1.3rem;">
                <p class="eyebrow">Kode pesanan</p>
                <p class="code">{{ $order->code }}</p>
                <p>{{ $order->name }} · {{ $order->phone }}</p>
                @foreach ($order->items as $item)
                    <p>{{ $item->book_title }} × {{ $item->qty }}<br><strong>@rupiah($item->subtotal)</strong></p>
                @endforeach
                <div class="total"><span>Total bayar</span><span>@rupiah($order->total)</span></div>
                <ol class="steps">
                    <li>Pindai kode QRIS di samping memakai aplikasi pembayaran.</li>
                    <li>Bayar sesuai total pesanan.</li>
                    <li>Tekan tombol WhatsApp, kirim pesan, lalu lampirkan bukti pembayaran.</li>
                </ol>
                <p class="help">Konfirmasi pembayaran cukup lewat WhatsApp. Tim penerbit membalas setelah bukti diterima, kemudian file e-book dikirim.</p>
            </div>
            <div class="qris-frame">
                <h2>QRIS</h2>
                @if (\App\Support\Site::qrisReady())
                    <img src="{{ asset(\App\Support\Site::get('qris_image')) }}" alt="QRIS Addariyah Press">
                @else
                    <div class="empty">Gambar QRIS belum diunggah. Admin menambahkannya di menu Pengaturan.</div>
                @endif
                @if ($waUrl)
                    <a class="btn btn-primary" href="{{ $waUrl }}" target="_blank" rel="noopener">Konfirmasi pembayaran via WhatsApp</a>
                @else
                    <p>Nomor WhatsApp penerbit belum diatur.</p>
                @endif
            </div>
        </div>
    </section>
@endsection
