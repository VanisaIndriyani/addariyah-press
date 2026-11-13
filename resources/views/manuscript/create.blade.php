@extends('layouts.public')

@section('title', 'Kirim Naskah — Addariyah Press')

@section('content')
    <section class="section">
        <div class="container narrow">
            <p class="eyebrow">Naskah</p>
            <h1>Kirim naskah</h1>
            <p class="muted">Isi data penulis, lalu unggah surat, cover, dummy, dan file buku. Tiap berkas maksimal 10 MB, file buku maksimal 20 MB.</p>
            <form class="panel" style="padding: 1.2rem;" method="POST" action="{{ route('manuscripts.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="hp" aria-hidden="true">
                    <label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                </div>
                <div class="field">
                    <label for="name">Penulis</label>
                    <input id="name" name="name" value="{{ old('name') }}" required>
                </div>
                <div class="form-grid">
                    <div class="field">
                        <label for="email">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                    </div>
                    <div class="field">
                        <label for="phone">WhatsApp</label>
                        <input id="phone" name="phone" value="{{ old('phone') }}" required placeholder="08xxxxxxxxxx">
                    </div>
                </div>
                <div class="field">
                    <label for="title">Judul</label>
                    <input id="title" name="title" value="{{ old('title') }}" required>
                </div>
                <div class="field">
                    <label for="category">Kategori</label>
                    <input id="category" name="category" value="{{ old('category') }}">
                </div>
                <div class="field">
                    <label for="message">Pesan</label>
                    <textarea id="message" name="message" rows="5">{{ old('message') }}</textarea>
                </div>
                <div class="field">
                    <div class="sample-row">
                        <label for="statement">Surat pernyataan</label>
                        @if ($sampleStatement)
                            <a class="btn btn-line dark btn-small" href="{{ asset($sampleStatement) }}" download>Unduh contoh</a>
                        @endif
                    </div>
                    <input id="statement" type="file" name="statement" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                </div>
                <div class="field">
                    <div class="sample-row">
                        <label for="originality">Suket keaslian karya</label>
                        @if ($sampleOriginality)
                            <a class="btn btn-line dark btn-small" href="{{ asset($sampleOriginality) }}" download>Unduh contoh</a>
                        @endif
                    </div>
                    <input id="originality" type="file" name="originality" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                </div>
                <div class="field">
                    <label for="cover">Cover</label>
                    <input id="cover" type="file" name="cover" accept=".jpg,.jpeg,.png,.webp" required>
                </div>
                <div class="field">
                    <label for="dummy">Dummy</label>
                    <input id="dummy" type="file" name="dummy" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
                </div>
                <div class="field">
                    <label for="book">File buku</label>
                    <input id="book" type="file" name="book" accept=".pdf,.doc,.docx" required>
                    <p class="help">PDF, DOC, atau DOCX. Maksimal 20 MB.</p>
                </div>
                <button class="btn btn-primary" type="submit">Kirim naskah</button>
            </form>
        </div>
    </section>
@endsection
