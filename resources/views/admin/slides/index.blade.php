@extends('layouts.admin')

@section('title', 'Slide beranda')

@section('content')
    @php($openModal = old('modal'))

    <div class="admin-top">
        <div>
            <p class="eyebrow">Beranda</p>
            <h1>Slide sertifikat</h1>
        </div>
        <button class="btn btn-primary" type="button" data-open="slide-create">Tambah gambar</button>
    </div>

    <section class="panel">
        <p class="muted">Gambar ini bergeser di beranda, tepat di bawah bagian hijau. Cocok untuk sertifikat, piagam, atau spanduk.</p>
        @include('partials.search', ['action' => route('admin.slides.index'), 'placeholder' => 'Cari judul sertifikat', 'searchClass' => 'crud-search'])

        @if ($items->isEmpty())
            <div class="empty">
                <p>{{ $search !== '' ? 'Tidak ada gambar yang cocok.' : 'Belum ada gambar slide.' }}</p>
                @if ($search === '')
                    <button class="btn btn-primary" type="button" data-open="slide-create">Tambah gambar</button>
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
                                <button class="btn btn-line dark btn-small" type="button" data-open="slide-edit-{{ $item->id }}">Ubah</button>
                                <button class="btn btn-danger btn-small" type="button" data-open="slide-delete-{{ $item->id }}">Hapus</button>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif
    </section>

    <dialog class="modal" id="slide-create">
        <div class="modal-card">
            <div class="modal-head">
                <h2>Tambah gambar</h2>
                <button class="modal-close" type="button" data-close aria-label="Tutup">×</button>
            </div>
            <p class="muted">Isi kolom yang dipakai. Kolom kosong dilewati. Bisa ditambah lagi nanti.</p>
            @if ($errors->has('images'))
                <div class="alert alert-error">{{ $errors->first('images') }}</div>
            @endif
            <form method="POST" action="{{ route('admin.slides.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="modal" value="slide-create">
                <div data-slide-rows>
                    @for ($i = 0; $i < 5; $i++)
                        <div class="slide-upload">
                            <strong>Gambar {{ $i + 1 }}</strong>
                            <div class="field">
                                <label for="title-{{ $i }}">Judul</label>
                                <input id="title-{{ $i }}" name="titles[]" value="{{ $openModal === 'slide-create' ? old('titles.'.$i) : '' }}" placeholder="Contoh: Sertifikat IKAPI">
                            </div>
                            <div class="field">
                                <label for="caption-{{ $i }}">Keterangan</label>
                                <input id="caption-{{ $i }}" name="captions[]" value="{{ $openModal === 'slide-create' ? old('captions.'.$i) : '' }}" placeholder="Opsional">
                            </div>
                            <div class="field">
                                <label for="image-{{ $i }}">Berkas gambar</label>
                                <input id="image-{{ $i }}" type="file" name="images[]" accept=".jpg,.jpeg,.png,.webp">
                            </div>
                        </div>
                    @endfor
                </div>
                <button class="btn btn-line dark" type="button" data-add-slide>Tambah kolom</button>
                <p class="help">JPG, PNG, atau WEBP. Maksimal 5 MB tiap gambar.</p>
                <div class="form-bar">
                    <button class="btn btn-line dark" type="button" data-close>Batal</button>
                    <button class="btn btn-primary" type="submit">Unggah</button>
                </div>
            </form>
        </div>
    </dialog>

    @foreach ($items as $item)
        <dialog class="modal" id="slide-edit-{{ $item->id }}">
            <div class="modal-card">
                <div class="modal-head">
                    <h2>Ubah gambar</h2>
                    <button class="modal-close" type="button" data-close aria-label="Tutup">×</button>
                </div>
                <img class="modal-preview" src="{{ asset($item->image_path) }}" alt="">
                <form method="POST" action="{{ route('admin.slides.update', $item) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="modal" value="slide-edit-{{ $item->id }}">
                    <div class="field">
                        <label for="edit-title-{{ $item->id }}">Judul</label>
                        <input id="edit-title-{{ $item->id }}" name="title" value="{{ $openModal === 'slide-edit-'.$item->id ? old('title') : $item->title }}" required>
                    </div>
                    <div class="field">
                        <label for="edit-caption-{{ $item->id }}">Keterangan</label>
                        <input id="edit-caption-{{ $item->id }}" name="caption" value="{{ $openModal === 'slide-edit-'.$item->id ? old('caption') : $item->caption }}">
                    </div>
                    <div class="field">
                        <label for="edit-order-{{ $item->id }}">Urutan</label>
                        <input id="edit-order-{{ $item->id }}" type="number" name="sort_order" min="0" max="9999" value="{{ $openModal === 'slide-edit-'.$item->id ? old('sort_order') : $item->sort_order }}">
                        <p class="help">Angka kecil tampil lebih dulu.</p>
                    </div>
                    <div class="field">
                        <label for="edit-image-{{ $item->id }}">Ganti gambar</label>
                        <input id="edit-image-{{ $item->id }}" type="file" name="image" accept=".jpg,.jpeg,.png,.webp">
                        <p class="help">Kosongkan jika gambar saat ini tetap dipakai.</p>
                    </div>
                    <div class="form-bar">
                        <button class="btn btn-line dark" type="button" data-close>Batal</button>
                        <button class="btn btn-primary" type="submit">Simpan</button>
                    </div>
                </form>
            </div>
        </dialog>

        <dialog class="modal" id="slide-delete-{{ $item->id }}">
            <div class="modal-card">
                <div class="modal-head">
                    <h2>Hapus gambar</h2>
                    <button class="modal-close" type="button" data-close aria-label="Tutup">×</button>
                </div>
                <p>Gambar <strong>{{ $item->title }}</strong> akan dihapus dari slide beranda.</p>
                <form method="POST" action="{{ route('admin.slides.destroy', $item) }}">
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
        var addRow = document.querySelector('[data-add-slide]');
        if (addRow) {
            addRow.addEventListener('click', function () {
                var rows = document.querySelector('[data-slide-rows]');
                var last = rows.lastElementChild;
                var next = last.cloneNode(true);
                var number = rows.children.length + 1;
                next.querySelector('strong').textContent = 'Gambar ' + number;
                next.querySelectorAll('input').forEach(function (input) {
                    input.value = '';
                    var id = input.id.replace(/-\d+$/, '-' + number);
                    var label = next.querySelector('label[for="' + input.id + '"]');
                    input.id = id;
                    if (label) label.setAttribute('for', id);
                });
                rows.appendChild(next);
            });
        }
        @if ($openModal)
            var reopen = document.getElementById(@json($openModal));
            if (reopen) reopen.showModal();
        @endif
    </script>
@endsection
