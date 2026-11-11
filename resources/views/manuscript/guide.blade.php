@extends('layouts.public')

@section('title', 'Pedoman Penulisan — Addariyah Press')

@section('content')
    <section class="section">
        <div class="container narrow">
            <p class="eyebrow">Naskah</p>
            <h1>Pedoman penulisan</h1>
            @include('partials.search', ['action' => route('manuscripts.guide'), 'placeholder' => 'Cari berkas pedoman'])
            @if (filled(\App\Support\Site::get('pedoman')))
                <div class="prose">{!! \App\Support\Site::paragraphs(\App\Support\Site::get('pedoman')) !!}</div>
            @else
                <div class="empty">Pedoman belum diisi. Admin dapat menuliskannya di menu Naskah.</div>
            @endif
            @if ($files->isNotEmpty())
                <h2>Unduhan</h2>
                @foreach ($files as $file)
                    <article class="panel" style="padding: 1rem; margin-bottom: .8rem;">
                        <h3>{{ $file->title }}</h3>
                        @if ($file->description)<p class="muted">{{ $file->description }}</p>@endif
                        <a class="btn btn-primary btn-small" href="{{ asset($file->file_path) }}" download>Unduh</a>
                    </article>
                @endforeach
            @endif
        </div>
    </section>
@endsection
