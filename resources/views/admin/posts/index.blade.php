@extends('layouts.admin')

@section('title', 'Berita')

@section('content')
    <div class="admin-top">
        <div>
            <p class="eyebrow">Kegiatan</p>
            <h1>Berita</h1>
        </div>
        <a class="btn btn-primary" href="{{ route('admin.berita.create') }}">Tulis berita</a>
    </div>
    <section class="panel">
        @include('partials.search', ['action' => route('admin.berita.index'), 'placeholder' => 'Cari judul berita', 'searchClass' => 'crud-search'])
        <div class="table-wrap">
            <table>
                <thead><tr><th>Judul</th><th>Terbit</th><th></th></tr></thead>
                <tbody>
                    @forelse ($posts as $post)
                        <tr>
                            <td>{{ $post->title }} @if(! $post->is_published)<span class="muted">Draf</span>@endif</td>
                            <td>{{ $post->published_at?->translatedFormat('d M Y') }}</td>
                            <td class="actions">
                                <a class="btn btn-line dark btn-small" href="{{ route('admin.berita.edit', $post) }}">Ubah</a>
                                <form method="POST" action="{{ route('admin.berita.destroy', $post) }}" onsubmit="return confirm('Hapus berita ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-small" type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3">{{ $search !== '' ? 'Tidak ada berita yang cocok.' : 'Belum ada berita.' }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $posts->links() }}
    </section>
@endsection
