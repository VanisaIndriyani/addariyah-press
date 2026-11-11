@extends('layouts.public')

@section('title', 'Kirim Naskah — Addariyah Press')

@section('content')
    <section class="section">
        <div class="container narrow">
            <p class="eyebrow">Naskah</p>
            <h1>Kirim naskah</h1>
            <p class="muted">Lampirkan naskah dalam format PDF, DOC, DOCX, atau RTF. Ukuran maksimal 10 MB.</p>
            <form class="panel" style="padding: 1.2rem;" method="POST" action="{{ route('manuscripts.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="hp" aria-hidden="true">
                    <label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                </div>
                <div class="field">
                    <label for="name">Nama</label>
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
                    <label for="title">Judul naskah</label>
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
                    <label for="file">Berkas naskah</label>
                    <input id="file" type="file" name="file" accept=".pdf,.doc,.docx,.rtf" required>
                </div>
                <button class="btn btn-primary" type="submit">Kirim naskah</button>
            </form>
        </div>
    </section>
@endsection
