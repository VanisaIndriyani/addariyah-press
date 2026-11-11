@extends('layouts.admin')

@section('title', $post->exists ? 'Ubah berita' : 'Tulis berita')

@section('content')
    <div class="admin-top">
        <h1>{{ $post->exists ? 'Ubah berita' : 'Tulis berita' }}</h1>
        <a href="{{ route('admin.berita.index') }}">Kembali</a>
    </div>
    <form class="panel" method="POST" enctype="multipart/form-data" action="{{ $post->exists ? route('admin.berita.update', $post) : route('admin.berita.store') }}">
        @csrf
        @if ($post->exists) @method('PUT') @endif
        <div class="field">
            <label for="title">Judul</label>
            <input id="title" name="title" value="{{ old('title', $post->title) }}" required>
        </div>
        <div class="field">
            <label for="excerpt">Ringkasan</label>
            <input id="excerpt" name="excerpt" value="{{ old('excerpt', $post->excerpt) }}">
        </div>
        <div class="field">
            <label for="body">Isi</label>
            <textarea id="body" name="body" rows="10" required>{{ old('body', $post->body) }}</textarea>
        </div>
        <div class="field">
            <label for="published_at">Waktu terbit</label>
            <input id="published_at" type="datetime-local" name="published_at" value="{{ old('published_at', optional($post->published_at)->format('Y-m-d\TH:i')) }}">
        </div>
        <div class="field">
            <label for="image">Gambar</label>
            <input id="image" type="file" name="image" accept="image/*">
            @if ($post->image_path)
                <img src="{{ asset($post->image_path) }}" alt="" style="width: 180px; margin-top: .6rem; border-radius: 12px;">
            @endif
        </div>
        <input type="hidden" name="is_published" value="0">
        <label class="check"><input type="checkbox" name="is_published" value="1" @checked((string) old('is_published', ($post->is_published ?? true) ? '1' : '0') === '1')> Terbitkan</label>
        <p style="margin-top: 1rem;"><button class="btn btn-primary" type="submit">Simpan berita</button></p>
    </form>
@endsection
