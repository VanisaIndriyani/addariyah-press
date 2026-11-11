@extends('layouts.public')

@section('title', $post->title.' — Addariyah Press')

@section('content')
    <section class="section article-section">
        <div class="container narrow">
            <nav class="crumb" aria-label="Jejak">
                <a href="{{ route('news') }}">Berita</a>
                <span>/</span>
                <span>{{ $post->title }}</span>
            </nav>
            <p class="meta">{{ $post->published_at?->translatedFormat('d F Y') }}</p>
            <h1>{{ $post->title }}</h1>
            @if ($post->image_path)
                <figure class="article-cover">
                    <img src="{{ asset($post->image_path) }}" alt="">
                </figure>
            @endif
            <div class="prose article-body">{!! \App\Support\Site::paragraphs($post->body) !!}</div>
            <a class="btn btn-line dark" href="{{ route('news') }}">Kembali ke berita</a>
        </div>

        @if ($others->isNotEmpty())
            <div class="container related">
                <div class="section-head">
                    <div>
                        <p class="eyebrow">Berita lain</p>
                        <h2>Kegiatan lainnya</h2>
                    </div>
                </div>
                <div class="news-list">
                    @foreach ($others as $item)
                        <article class="news-card">
                            <a class="news-media" href="{{ route('news.show', $item) }}">
                                @if ($item->image_path)
                                    <img src="{{ asset($item->image_path) }}" alt="">
                                @else
                                    <span class="news-fallback">{{ $item->title }}</span>
                                @endif
                            </a>
                            <div class="news-card-body">
                                <p class="meta">{{ $item->published_at?->translatedFormat('d F Y') }}</p>
                                <h3><a href="{{ route('news.show', $item) }}">{{ $item->title }}</a></h3>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        @endif
    </section>
@endsection
