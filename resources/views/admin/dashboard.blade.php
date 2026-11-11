@extends('layouts.admin')

@section('title', 'Dasbor')

@section('content')
    <div class="admin-top">
        <div>
            <p class="eyebrow">Admin</p>
            <h1>Dasbor</h1>
        </div>
    </div>

    @if ($needsQris || $needsWhatsapp)
        <div class="notice">
            Sebelum pembeli memakai katalog, unggah gambar QRIS dan ganti nomor WhatsApp di menu Pengaturan.
            Pembayaran dikonfirmasi pembeli lewat WhatsApp, tanpa daftar status bayar di admin.
        </div>
    @endif

    <div class="stat-grid">
        <article class="stat"><span>Buku</span><b>{{ $bookCount }}</b></article>
        <article class="stat"><span>Katalog dijual</span><b>{{ $catalogCount }}</b></article>
        <article class="stat"><span>Terbitan</span><b>{{ $upcomingCount }}</b></article>
        <article class="stat"><span>Naskah masuk</span><b>{{ $manuscriptCount }}</b></article>
    </div>

    <section class="panel">
        <h2>Naskah terbaru</h2>
        @if ($latestManuscripts->isEmpty())
            <p class="muted">Belum ada naskah masuk.</p>
        @else
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Judul</th><th>Pengirim</th><th>Masuk</th></tr></thead>
                    <tbody>
                        @foreach ($latestManuscripts as $item)
                            <tr>
                                <td><a href="{{ route('admin.manuscripts.show', $item) }}">{{ $item->title }}</a></td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->created_at->translatedFormat('d M Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endsection
