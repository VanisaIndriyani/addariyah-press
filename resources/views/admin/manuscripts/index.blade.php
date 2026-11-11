@extends('layouts.admin')

@section('title', 'Naskah masuk')

@section('content')
    <div class="admin-top">
        <div>
            <p class="eyebrow">Kiriman penulis</p>
            <h1>Naskah masuk</h1>
        </div>
    </div>
    <section class="panel">
        @include('partials.search', ['action' => route('admin.manuscripts.index'), 'placeholder' => 'Cari judul, nama, atau WhatsApp', 'searchClass' => 'crud-search'])
        <div class="table-wrap">
            <table>
                <thead><tr><th>Judul</th><th>Pengirim</th><th>WhatsApp</th><th>Masuk</th></tr></thead>
                <tbody>
                    @forelse ($manuscripts as $item)
                        <tr>
                            <td><a href="{{ route('admin.manuscripts.show', $item) }}">{{ $item->title }}</a></td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->phone }}</td>
                            <td>{{ $item->created_at->translatedFormat('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4">{{ $search !== '' ? 'Tidak ada naskah yang cocok.' : 'Belum ada naskah.' }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $manuscripts->links() }}
    </section>
@endsection
