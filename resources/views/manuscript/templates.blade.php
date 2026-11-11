@extends('layouts.public')

@section('title', 'Template Naskah — Addariyah Press')

@section('content')
    <section class="section">
        <div class="container narrow">
            <p class="eyebrow">Naskah</p>
            <h1>Template</h1>
            <p class="muted">Gunakan berkas ini sebagai titik awal, lalu sesuaikan dengan pedoman penulisan.</p>
            @include('partials.search', ['action' => route('manuscripts.templates'), 'placeholder' => 'Cari template'])
            @forelse ($files as $file)
                <article class="panel" style="padding: 1rem; margin-bottom: .8rem;">
                    <h3>{{ $file->title }}</h3>
                    @if ($file->description)<p class="muted">{{ $file->description }}</p>@endif
                    <a class="btn btn-primary btn-small" href="{{ asset($file->file_path) }}" download>Unduh</a>
                </article>
            @empty
                <div class="empty">Template belum diunggah.</div>
            @endforelse
        </div>
    </section>
@endsection
