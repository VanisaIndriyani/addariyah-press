@extends('layouts.admin')

@section('title', 'Pedoman dan template')

@section('content')
    @php($openModal = old('modal'))

    <div class="admin-top">
        <div>
            <p class="eyebrow">Naskah</p>
            <h1>Pedoman dan template</h1>
        </div>
        <div class="actions">
            <button class="btn btn-line dark" type="button" data-open="resource-guide">Ubah pedoman</button>
            <button class="btn btn-primary" type="button" data-open="resource-create">Tambah berkas</button>
        </div>
    </div>

    <section class="panel">
        @include('partials.search', ['action' => route('admin.resources.index'), 'placeholder' => 'Cari judul berkas', 'searchClass' => 'crud-search'])

        <h2>Pedoman</h2>
        @include('admin.resources.list', ['files' => $guides, 'empty' => $search !== '' ? 'Tidak ada pedoman yang cocok.' : 'Belum ada berkas pedoman.'])

        <h2>Template</h2>
        @include('admin.resources.list', ['files' => $templates, 'empty' => $search !== '' ? 'Tidak ada template yang cocok.' : 'Belum ada template.'])
    </section>

    <dialog class="modal" id="resource-guide">
        <div class="modal-card">
            <div class="modal-head">
                <h2>Ubah pedoman</h2>
                <button class="modal-close" type="button" data-close aria-label="Tutup">×</button>
            </div>
            <p class="muted">Teks ini tampil di halaman pedoman penulisan.</p>
            <form method="POST" action="{{ route('admin.resources.guide') }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="modal" value="resource-guide">
                <div class="field">
                    <label for="pedoman">Teks pedoman</label>
                    <textarea id="pedoman" name="pedoman" rows="12">{{ $openModal === 'resource-guide' ? old('pedoman') : $pedoman }}</textarea>
                </div>
                <div class="form-bar">
                    <button class="btn btn-line dark" type="button" data-close>Batal</button>
                    <button class="btn btn-primary" type="submit">Simpan pedoman</button>
                </div>
            </form>
        </div>
    </dialog>

    <dialog class="modal" id="resource-create">
        <div class="modal-card">
            <div class="modal-head">
                <h2>Tambah berkas</h2>
                <button class="modal-close" type="button" data-close aria-label="Tutup">×</button>
            </div>
            <form method="POST" action="{{ route('admin.resources.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="modal" value="resource-create">
                <div class="field">
                    <label for="type">Jenis</label>
                    <select id="type" name="type">
                        <option value="pedoman" @selected($openModal === 'resource-create' && old('type') === 'pedoman')>Pedoman</option>
                        <option value="template" @selected($openModal === 'resource-create' && old('type') === 'template')>Template</option>
                    </select>
                </div>
                <div class="field">
                    <label for="title">Judul berkas</label>
                    <input id="title" name="title" value="{{ $openModal === 'resource-create' ? old('title') : '' }}" required>
                </div>
                <div class="field">
                    <label for="description">Keterangan</label>
                    <input id="description" name="description" value="{{ $openModal === 'resource-create' ? old('description') : '' }}">
                </div>
                <div class="field">
                    <label for="file">Berkas</label>
                    <input id="file" type="file" name="file" accept=".pdf,.doc,.docx,.rtf,.txt" required>
                    <p class="help">PDF, DOC, DOCX, RTF, atau TXT. Maksimal 10 MB.</p>
                </div>
                <div class="form-bar">
                    <button class="btn btn-line dark" type="button" data-close>Batal</button>
                    <button class="btn btn-primary" type="submit">Unggah</button>
                </div>
            </form>
        </div>
    </dialog>

    @foreach ($guides->concat($templates) as $file)
        <dialog class="modal" id="resource-edit-{{ $file->id }}">
            <div class="modal-card">
                <div class="modal-head">
                    <h2>Ubah berkas</h2>
                    <button class="modal-close" type="button" data-close aria-label="Tutup">×</button>
                </div>
                <form method="POST" action="{{ route('admin.resources.update', $file) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="modal" value="resource-edit-{{ $file->id }}">
                    <div class="field">
                        <label for="type-{{ $file->id }}">Jenis</label>
                        <select id="type-{{ $file->id }}" name="type">
                            @php($type = $openModal === 'resource-edit-'.$file->id ? old('type') : $file->type)
                            <option value="pedoman" @selected($type === 'pedoman')>Pedoman</option>
                            <option value="template" @selected($type === 'template')>Template</option>
                        </select>
                    </div>
                    <div class="field">
                        <label for="title-{{ $file->id }}">Judul berkas</label>
                        <input id="title-{{ $file->id }}" name="title" value="{{ $openModal === 'resource-edit-'.$file->id ? old('title') : $file->title }}" required>
                    </div>
                    <div class="field">
                        <label for="description-{{ $file->id }}">Keterangan</label>
                        <input id="description-{{ $file->id }}" name="description" value="{{ $openModal === 'resource-edit-'.$file->id ? old('description') : $file->description }}">
                    </div>
                    <div class="field">
                        <label for="file-{{ $file->id }}">Ganti berkas</label>
                        <input id="file-{{ $file->id }}" type="file" name="file" accept=".pdf,.doc,.docx,.rtf,.txt">
                        <p class="help">Kosongkan jika berkas saat ini tetap dipakai.</p>
                    </div>
                    <div class="form-bar">
                        <button class="btn btn-line dark" type="button" data-close>Batal</button>
                        <button class="btn btn-primary" type="submit">Simpan</button>
                    </div>
                </form>
            </div>
        </dialog>

        <dialog class="modal" id="resource-delete-{{ $file->id }}">
            <div class="modal-card">
                <div class="modal-head">
                    <h2>Hapus berkas</h2>
                    <button class="modal-close" type="button" data-close aria-label="Tutup">×</button>
                </div>
                <p>Berkas <strong>{{ $file->title }}</strong> akan dihapus.</p>
                <form method="POST" action="{{ route('admin.resources.destroy', $file) }}">
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
