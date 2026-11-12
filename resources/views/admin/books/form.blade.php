@extends('layouts.admin')

@section('title', $book->exists ? 'Ubah buku' : 'Tambah buku')

@section('content')
    <div class="admin-top">
        <div>
            <p class="eyebrow">Buku</p>
            <h1>{{ $book->exists ? 'Ubah buku' : 'Tambah buku' }}</h1>
        </div>
        <a class="btn btn-line dark" href="{{ route('admin.buku.index') }}">Kembali</a>
    </div>

    <form method="POST" enctype="multipart/form-data" action="{{ $book->exists ? route('admin.buku.update', $book) : route('admin.buku.store') }}">
        @csrf
        @if ($book->exists) @method('PUT') @endif

        <div class="form-sections">
            <section class="panel">
                <h2>Identitas</h2>
                <div class="form-grid">
                    <div class="field span-2">
                        <label for="title">Judul</label>
                        <input id="title" name="title" value="{{ old('title', $book->title) }}" required>
                    </div>
                    <div class="field">
                        <label for="author">Penulis</label>
                        <input id="author" name="author" value="{{ old('author', $book->author) }}" required>
                    </div>
                    <div class="field">
                        <label for="publisher">Penerbit</label>
                        <input id="publisher" name="publisher" value="{{ old('publisher', $book->publisher ?: 'Addariyah Press') }}">
                    </div>
                    <div class="field">
                        <label for="category">Kategori</label>
                        <input id="category" name="category" list="categories" value="{{ old('category', $book->category) }}">
                        <datalist id="categories">
                            @foreach ($categories as $category)
                                <option value="{{ $category }}"></option>
                            @endforeach
                        </datalist>
                    </div>
                    <div class="field">
                        <label for="pages">Halaman</label>
                        <input id="pages" type="number" name="pages" min="1" value="{{ old('pages', $book->pages) }}">
                    </div>
                </div>
            </section>

            <section class="panel">
                <h2>Penerbitan dan harga</h2>
                <div class="form-grid">
                    <div class="field">
                        <label for="isbn">ISBN</label>
                        <input id="isbn" name="isbn" value="{{ old('isbn', $book->isbn) }}">
                    </div>
                    <div class="field">
                        <label for="qrcbn">QRCBN</label>
                        <input id="qrcbn" name="qrcbn" value="{{ old('qrcbn', $book->qrcbn) }}">
                    </div>
                    <div class="field">
                        <label for="editor">Editor</label>
                        <input id="editor" name="editor" value="{{ old('editor', $book->editor) }}">
                    </div>
                    <div class="field">
                        <label for="book_layout">Layout</label>
                        <input id="book_layout" name="book_layout" value="{{ old('book_layout', $book->book_layout) }}">
                    </div>
                    <div class="field">
                        <label for="cover_designer">Cover</label>
                        <input id="cover_designer" name="cover_designer" value="{{ old('cover_designer', $book->cover_designer) }}" placeholder="Nama desainer sampul">
                    </div>
                    <div class="field">
                        <label for="price">Harga (Rp)</label>
                        <input id="price" type="number" name="price" min="0" step="1" value="{{ old('price', $book->price !== null ? (int) $book->price : '') }}">
                        <p class="help">Wajib diisi jika buku dijual di katalog.</p>
                    </div>
                    <div class="field">
                        <label for="expected_label">Perkiraan terbit</label>
                        <input id="expected_label" name="expected_label" value="{{ old('expected_label', $book->expected_label) }}" placeholder="Maret 2027">
                    </div>
                </div>
            </section>

            <section class="panel">
                <h2>Deskripsi</h2>
                <div class="field">
                    <label for="description">Isi deskripsi</label>
                    <textarea id="description" name="description" rows="7">{{ old('description', $book->description) }}</textarea>
                </div>
            </section>

            <section class="panel">
                <h2>Berkas</h2>
                <div class="file-grid">
                    <div class="field">
                        <label for="cover">Sampul</label>
                        <input id="cover" type="file" name="cover" accept="image/*" @required(! $book->exists)>
                        <p class="help">JPG, PNG, atau WEBP. Maksimal 4 MB.</p>
                        @if ($book->cover_path)
                            <img class="preview preview-lg" src="{{ asset($book->cover_path) }}" alt="Sampul saat ini">
                        @endif
                    </div>
                    <div class="field">
                        <label for="ebook">File e-book</label>
                        <input id="ebook" type="file" name="ebook" accept=".pdf,.epub">
                        <p class="help">PDF atau EPUB. Pengunjung tidak bisa mengunduhnya. File dikirim setelah konfirmasi WhatsApp.</p>
                        @if ($book->ebook_path)
                            <p><a class="btn btn-line dark btn-small" href="{{ route('admin.buku.ebook', $book) }}">Unduh file saat ini</a></p>
                            <label class="check"><input type="checkbox" name="remove_ebook" value="1"> Hapus file e-book</label>
                        @endif
                    </div>
                </div>
            </section>

            <section class="panel">
                <h2>Tampilan di website</h2>
                <div class="choice-grid">
                    <label class="choice">
                        <input type="hidden" name="for_sale" value="0">
                        <input type="checkbox" name="for_sale" value="1" @checked((string) old('for_sale', $book->for_sale ? '1' : '0') === '1')>
                        <span><strong>Jual di katalog</strong><small>Buku muncul di katalog dan bisa dimasukkan ke keranjang.</small></span>
                    </label>
                    <label class="choice">
                        <input type="hidden" name="upcoming" value="0">
                        <input type="checkbox" name="upcoming" value="1" @checked((string) old('upcoming', $book->upcoming ? '1' : '0') === '1')>
                        <span><strong>Tampilkan di terbitan</strong><small>Dipakai untuk buku yang akan segera terbit.</small></span>
                    </label>
                    <label class="choice">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" @checked((string) old('is_active', ($book->is_active ?? true) ? '1' : '0') === '1')>
                        <span><strong>Tampilkan di website</strong><small>Matikan jika buku ingin disimpan tanpa ditampilkan.</small></span>
                    </label>
                </div>
            </section>
        </div>

        <div class="form-bar">
            <a class="btn btn-line dark" href="{{ route('admin.buku.index') }}">Batal</a>
            <button class="btn btn-primary" type="submit">Simpan buku</button>
        </div>
    </form>
@endsection
