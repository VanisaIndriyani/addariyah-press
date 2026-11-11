@extends('layouts.public')

@section('title', 'Berita — Addariyah Press')

@section('content')
    <section class="section">
        <div class="container">
            <p class="eyebrow">Berita</p>
            <h1>Informasi kegiatan penerbit</h1>
            @include('partials.search', ['action' => route('news'), 'placeholder' => 'Cari judul berita'])
            @if ($posts->isEmpty())
                <div class="empty">{{ $search !== '' ? 'Tidak ada berita yang cocok.' : 'Belum ada berita.' }}</div>
            @else
                <div class="news-list">
                    @foreach ($posts as $post)
                        <article @class(['news-card', 'news-feature' => $loop->first && $posts->onFirstPage() && $search === ''])>
                            <a class="news-media" href="{{ route('news.show', $post) }}">
                                @if ($post->image_path)
                                    <img src="{{ asset($post->image_path) }}" alt="">
                                @else
                                    <span class="news-fallback">{{ $post->title }}</span>
                                @endif
                            </a>
                            <div class="news-card-body">
                                <p class="meta">{{ $post->published_at?->translatedFormat('d F Y') }}</p>
                                <h3><a href="{{ route('news.show', $post) }}">{{ $post->title }}</a></h3>
                                <p class="muted">{{ $post->excerpt }}</p>
                                <a class="news-more" href="{{ route('news.show', $post) }}">Baca selengkapnya</a>
                            </div>
                        </article>
                    @endforeach
                </div>
                {{ $posts->links() }}
            @endif
        </div>
    </section>
@endsection
