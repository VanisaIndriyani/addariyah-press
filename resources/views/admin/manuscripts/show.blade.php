@extends('layouts.admin')

@section('title', $manuscript->title)

@section('content')
    @php
        $digits = preg_replace('/\D+/', '', (string) $manuscript->phone) ?? '';
        if (str_starts_with($digits, '0')) {
            $digits = '62'.substr($digits, 1);
        }
        $initial = mb_strtoupper(mb_substr(trim($manuscript->name), 0, 1));
        $extension = strtoupper(pathinfo((string) $manuscript->file_path, PATHINFO_EXTENSION) ?: 'FILE');
    @endphp

    <div class="admin-top ms-top">
        <div>
            <p class="eyebrow">Naskah masuk</p>
            <h1>{{ $manuscript->title }}</h1>
            <div class="tags">
                @if ($manuscript->category)
                    <span class="tag">{{ $manuscript->category }}</span>
                @endif
                <span class="tag tag-soft">{{ $manuscript->created_at->translatedFormat('d F Y, H:i') }}</span>
            </div>
        </div>
        <div class="actions">
            <a class="btn btn-line dark" href="{{ route('admin.manuscripts.index') }}">Kembali</a>
            <button class="btn btn-danger" type="button" data-open="manuscript-delete">Hapus</button>
        </div>
    </div>

    <div class="ms-grid">
        <aside class="panel">
            <div class="ms-person">
                <span class="ms-avatar" aria-hidden="true">{{ $initial !== '' ? $initial : '?' }}</span>
                <div>
                    <strong>{{ $manuscript->name }}</strong>
                    <p class="muted">Pengirim</p>
                </div>
            </div>
            <dl class="ms-meta">
                <div>
                    <dt>Email</dt>
                    <dd><a href="mailto:{{ $manuscript->email }}">{{ $manuscript->email }}</a></dd>
                </div>
                <div>
                    <dt>WhatsApp</dt>
                    <dd>
                        @if ($digits !== '')
                            <a href="https://wa.me/{{ $digits }}" target="_blank" rel="noopener">{{ $manuscript->phone }}</a>
                        @else
                            {{ $manuscript->phone ?: '—' }}
                        @endif
                    </dd>
                </div>
                <div>
                    <dt>Kategori</dt>
                    <dd>{{ $manuscript->category ?: '—' }}</dd>
                </div>
            </dl>
        </aside>

        <section class="panel">
            <h2>Pesan penulis</h2>
            @if (filled($manuscript->message))
                <div class="prose">{!! \App\Support\Site::paragraphs($manuscript->message) !!}</div>
            @else
                <p class="muted">Penulis tidak menyertakan pesan.</p>
            @endif
            <div class="ms-file">
                <div class="ms-file-body">
                    <span class="ms-file-mark">{{ $extension }}</span>
                    <div>
                        <strong>Berkas naskah</strong>
                        <p class="muted">Siap diunduh</p>
                    </div>
                </div>
                <a class="btn btn-primary" href="{{ route('admin.manuscripts.file', $manuscript) }}">Unduh naskah</a>
            </div>
        </section>
    </div>

    <dialog class="modal" id="manuscript-delete">
        <div class="modal-card">
            <div class="modal-head">
                <h2>Hapus naskah</h2>
                <button class="modal-close" type="button" data-close aria-label="Tutup">×</button>
            </div>
            <p>Naskah <strong>{{ $manuscript->title }}</strong> dari {{ $manuscript->name }} akan dihapus beserta berkasnya.</p>
            <form method="POST" action="{{ route('admin.manuscripts.destroy', $manuscript) }}">
                @csrf
                @method('DELETE')
                <div class="form-bar">
                    <button class="btn btn-line dark" type="button" data-close>Batal</button>
                    <button class="btn btn-danger" type="submit">Hapus</button>
                </div>
            </form>
        </div>
    </dialog>

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
    </script>
@endsection
