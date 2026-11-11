@extends('layouts.public')

@section('title', 'E-Catalog — Addariyah Press')

@section('content')
    <section class="section">
        <div class="container">
            <p class="eyebrow">E-Catalog</p>
            <h1>Biaya dan informasi penerbitan</h1>
            <p class="muted">Gambar di bawah memuat informasi biaya penerbitan. Ketuk gambar untuk memperbesar.</p>
            @include('partials.search', ['action' => route('ecatalog'), 'placeholder' => 'Cari judul atau keterangan'])
            @if ($items->isEmpty())
                <div class="empty">E-catalog belum diunggah.</div>
            @else
                <div class="ecatalog-grid">
                    @foreach ($items as $item)
                        <a href="{{ asset($item->image_path) }}" data-lightbox data-alt="{{ $item->title }}">
                            <img src="{{ asset($item->image_path) }}" alt="{{ $item->title }}">
                            <figcaption>
                                <strong>{{ $item->title }}</strong>
                                @if ($item->caption)<p class="muted">{{ $item->caption }}</p>@endif
                            </figcaption>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
