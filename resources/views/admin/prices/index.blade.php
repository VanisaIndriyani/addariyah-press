@extends('layouts.admin')

@section('title', 'Harga paket')

@section('content')
    @php($openModal = old('modal'))

    <div class="admin-top">
        <div>
            <p class="eyebrow">Layanan</p>
            <h1>Harga paket penerbitan</h1>
        </div>
        <button class="btn btn-primary" type="button" data-open="price-create">Tambah paket</button>
    </div>

    <section class="panel">
        <h2>Keterangan</h2>
        <form method="POST" action="{{ route('admin.prices.notes') }}">
            @csrf
            @method('PUT')
            <div class="field">
                <label for="price_note">Catatan pembaruan</label>
                <input id="price_note" name="price_note" value="{{ old('price_note', $note) }}" placeholder="Update harga: 14 November 2026">
            </div>
            <div class="form-grid">
                <div class="field">
                    <label for="price_included">Harga sudah termasuk</label>
                    <textarea id="price_included" name="price_included" rows="6" placeholder="Satu baris satu item">{{ old('price_included', $included) }}</textarea>
                </div>
                <div class="field">
                    <label for="price_specs">Spesifikasi umum buku</label>
                    <textarea id="price_specs" name="price_specs" rows="6" placeholder="Satu baris satu item">{{ old('price_specs', $specs) }}</textarea>
                </div>
            </div>
            <button class="btn btn-primary" type="submit">Simpan keterangan</button>
        </form>
    </section>

    <section class="panel">
        <h2>Paket</h2>
        @if ($packages->isEmpty())
            <div class="empty">
                <p>Belum ada paket. Contoh judul: Cetak hitam putih, Cetak full colour.</p>
                <button class="btn btn-primary" type="button" data-open="price-create">Tambah paket</button>
            </div>
        @else
            @foreach ($packages as $package)
                <article class="file-row">
                    <div>
                        <strong>{{ $package->title }}</strong>
                        <p class="muted">{{ count($package->tables ?? []) }} tabel · urutan {{ $package->sort_order }}</p>
                    </div>
                    <div class="actions">
                        <a class="btn btn-line dark btn-small" href="{{ route('admin.prices.edit', $package) }}">Isi harga</a>
                        <button class="btn btn-danger btn-small" type="button" data-open="price-delete-{{ $package->id }}">Hapus</button>
                    </div>
                </article>
            @endforeach
        @endif
    </section>

    <dialog class="modal" id="price-create">
        <div class="modal-card">
            <div class="modal-head">
                <h2>Tambah paket</h2>
                <button class="modal-close" type="button" data-close aria-label="Tutup">×</button>
            </div>
            <form method="POST" action="{{ route('admin.prices.store') }}">
                @csrf
                <input type="hidden" name="modal" value="price-create">
                <div class="field">
                    <label for="title">Nama paket</label>
                    <input id="title" name="title" value="{{ $openModal === 'price-create' ? old('title') : '' }}" placeholder="Cetak hitam putih" required>
                </div>
                <div class="form-bar">
                    <button class="btn btn-line dark" type="button" data-close>Batal</button>
                    <button class="btn btn-primary" type="submit">Lanjut isi tabel</button>
                </div>
            </form>
        </div>
    </dialog>

    @foreach ($packages as $package)
        <dialog class="modal" id="price-delete-{{ $package->id }}">
            <div class="modal-card">
                <div class="modal-head">
                    <h2>Hapus paket</h2>
                    <button class="modal-close" type="button" data-close aria-label="Tutup">×</button>
                </div>
                <p>Hapus paket <strong>{{ $package->title }}</strong> beserta tabel harganya?</p>
                <form method="POST" action="{{ route('admin.prices.destroy', $package) }}">
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
