@extends('layouts.public')

@section('title', 'Naskah — Addariyah Press')

@section('content')
    <section class="section">
        <div class="container">
            <p class="eyebrow">Naskah</p>
            <h1>Kirim naskah ke Addariyah Press</h1>
            <p class="muted">Baca pedoman, unduh template, lalu kirim berkas naskah.</p>
            <div class="path-grid">
                <a href="{{ route('manuscripts.guide') }}">
                    <strong>Pedoman penulisan</strong>
                    <span class="muted">Ketentuan naskah yang diterima penerbit.</span>
                </a>
                <a href="{{ route('manuscripts.templates') }}">
                    <strong>Template</strong>
                    <span class="muted">Berkas siap pakai untuk menyusun naskah.</span>
                </a>
                <a href="{{ route('manuscripts.create') }}">
                    <strong>Kirim naskah</strong>
                    <span class="muted">Unggah naskah beserta data penulis.</span>
                </a>
            </div>
        </div>
    </section>
@endsection
