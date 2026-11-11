@extends('layouts.admin')

@section('title', 'E-Catalog')

@section('content')
    @php($openModal = old('modal'))

    <div class="admin-top">
        <div>
            <p class="eyebrow">Biaya penerbitan</p>
            <h1>E-Catalog</h1>
        </div>
        <button class="btn btn-primary" type="button" data-open="ecatalog-create">Tambah gambar</button>
    </div>

    <section class="panel">
        @include('partials.search', ['action' => route('admin.ecatalog.index'), 'placeholder' => 'Cari judul atau keterangan', 'searchClass' => 'crud-search'])

        @if ($items->isEmpty())
            <div class="empty">
                <p>{{ $search !== '' ? 'Tidak ada gambar yang cocok.' : 'Belum ada gambar.' }}</p>
                @if ($search === '')
                    <button class="btn btn-primary" type="button" data-open="ecatalog-create">Tambah gambar</button>
                @endif
            </div>
        @else
            <div class="ecatalog-admin">
                @foreach ($items as $item)
                    <article class="ecatalog-card">
                        <img src="{{ asset($item->image_path) }}" alt="{{ $item->title }}">
                        <div>
                            <strong>{{ $item->title }}</strong>
                            @if ($item->caption)
                                <p class="muted">{{ $item->caption }}</p>
                            @endif
                            <p class="meta">Urutan {{ $item->sort_order }}</p>
                            <div class="actions">
                                <button class="btn btn-line dark btn-small" type="button" data-open="ecatalog-edit-{{ $item->id }}">Ubah</button>
                                <button class="btn btn-danger btn-small" type="button" data-open="ecatalog-delete-{{ $item->id }}">Hapus</button>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    <dialog class="modal" id="ecatalog-create">
        <div class="modal-card">
            <div class="modal-head">
                <h2>Tambah gambar</h2>
                <button class="modal-close" type="button" data-close aria-label="Tutup">×</button>
            </div>
            <p class="muted">Satu judul bisa dipakai untuk beberapa gambar sekaligus.</p>
            <form method="POST" action="{{ route('admin.ecatalog.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="modal" value="ecatalog-create">
                <div class="field">
                    <label for="title">Judul</label>
                    <input id="title" name="title" value="{{ $openModal === 'ecatalog-create' ? old('title') : '' }}" placeholder="Biaya penerbitan">
                </div>
                <div class="field">
                    <label for="caption">Keterangan</label>
                    <input id="caption" name="caption" value="{{ $openModal === 'ecatalog-create' ? old('caption') : '' }}" placeholder="Misalnya paket terbitan 2026">
                </div>
                <div class="field">
                    <label for="images">Gambar</label>
                    <input id="images" type="file" name="images[]" accept="image/*" multiple required>
                    <p class="help">JPG, PNG, atau WEBP. Bisa memilih beberapa gambar. Maksimal 4 MB tiap gambar.</p>
                </div>
                <div class="form-bar">
                    <button class="btn btn-line dark" type="button" data-close>Batal</button>
                    <button class="btn btn-primary" type="submit">Unggah</button>
                </div>
            </form>
        </div>
    </dialog>

    @foreach ($items as $item)
        <dialog class="modal" id="ecatalog-edit-{{ $item->id }}">
            <div class="modal-card">
                <div class="modal-head">
                    <h2>Ubah gambar</h2>
                    <button class="modal-close" type="button" data-close aria-label="Tutup">×</button>
                </div>
                <form method="POST" action="{{ route('admin.ecatalog.update', $item) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="modal" value="ecatalog-edit-{{ $item->id }}">
                    <img class="modal-preview" src="{{ asset($item->image_path) }}" alt="">
                    <div class="field">
                        <label for="title-{{ $item->id }}">Judul</label>
                        <input id="title-{{ $item->id }}" name="title" value="{{ $openModal === 'ecatalog-edit-'.$item->id ? old('title') : $item->title }}">
                    </div>
                    <div class="field">
                        <label for="caption-{{ $item->id }}">Keterangan</label>
                        <input id="caption-{{ $item->id }}" name="caption" value="{{ $openModal === 'ecatalog-edit-'.$item->id ? old('caption') : $item->caption }}">
                    </div>
                    <div class="field">
                        <label for="order-{{ $item->id }}">Urutan</label>
                        <input id="order-{{ $item->id }}" type="number" min="0" name="sort_order" value="{{ $openModal === 'ecatalog-edit-'.$item->id ? old('sort_order') : $item->sort_order }}">
                        <p class="help">Angka kecil tampil lebih dulu.</p>
                    </div>
                    <div class="field">
                        <label for="image-{{ $item->id }}">Ganti gambar</label>
                        <input id="image-{{ $item->id }}" type="file" name="image" accept="image/*">
                        <p class="help">Kosongkan jika gambar saat ini tetap dipakai.</p>
                    </div>
                    <div class="form-bar">
                        <button class="btn btn-line dark" type="button" data-close>Batal</button>
                        <button class="btn btn-primary" type="submit">Simpan</button>
                    </div>
                </form>
            </div>
        </dialog>

        <dialog class="modal" id="ecatalog-delete-{{ $item->id }}">
            <div class="modal-card">
                <div class="modal-head">
                    <h2>Hapus gambar</h2>
                    <button class="modal-close" type="button" data-close aria-label="Tutup">×</button>
                </div>
                <p>Gambar <strong>{{ $item->title }}</strong> akan dihapus dari e-catalog.</p>
                <form method="POST" action="{{ route('admin.ecatalog.destroy', $item) }}">
                    @csrf
                    @method('DELETE')
                    <div class="form-bar">
                        <button class="btn btn-line dark" type="button" data-close>Batal</button>
                        <button class="btn btn-danger" type="submit">Hapus</button>
                    </div>
                </form>
            </div>
        </dialog>
    @endforeach

    <script>
        document.querySelectorAll('[data-open]').forEach(function (button) {
            button.addEventListener('click', function () {
                var dialog = document.getElementById(button.getAttribute('data-open'));
                if (dialog) dialog.showModal();
            });
        });
        document.querySelectorAll('dialog.modal').forEach(function (dialog) {
            dialog.querySelectorAll('[data-close]').forEach(function (button) {
                button.addEventListener('click', function () { dialog.close(); });
            });
            dialog.addEventListener('click', function (event) {
                if (event.target === dialog) dialog.close();
            });
        });
        @if ($openModal)
            var reopen = document.getElementById(@json($openModal));
            if (reopen) reopen.showModal();
        @endif
    </script>
@endsection
