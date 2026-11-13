@extends('layouts.admin')

@section('title', 'Profil dan kontak')

@section('content')
    @php($openModal = old('modal'))

    <div class="admin-top">
        <div>
            <p class="eyebrow">Website</p>
            <h1>Profil dan kontak</h1>
        </div>
        <button class="btn btn-primary" type="button" data-open="contact-create">Tambah kontak person</button>
    </div>

    <section class="panel">
        <h2>Profil penerbit</h2>
        <form method="POST" action="{{ route('admin.profile.update') }}">
            @csrf
            @method('PUT')
            <div class="field">
                <label for="about">Profil</label>
                <textarea id="about" name="about" rows="6">{{ old('about', $about) }}</textarea>
            </div>
            <div class="field">
                <label for="visi">Visi</label>
                <textarea id="visi" name="visi" rows="3" required>{{ old('visi', $visi) }}</textarea>
            </div>
            <div class="field">
                <label for="misi">Misi</label>
                <textarea id="misi" name="misi" rows="5" required>{{ old('misi', $misi) }}</textarea>
                <p class="help">Tiap baris baru tetap tampil sebagai baris. Baris kosong memisahkan paragraf.</p>
            </div>
            <h2>Halaman kontak</h2>
            <div class="field">
                <label for="map_embed">Peta</label>
                <textarea id="map_embed" name="map_embed" rows="3" placeholder="https://www.google.com/maps/embed?pb=...">{{ old('map_embed', $mapEmbed) }}</textarea>
                <p class="help">Tempel tautan semat Google Maps atau OpenStreetMap. Boleh juga menempel kode iframe. Kosongkan jika belum ada peta.</p>
            </div>
            <div class="field">
                <label for="office_hours">Jam layanan</label>
                <textarea id="office_hours" name="office_hours" rows="3" placeholder="Senin–Jumat, pukul 08.00–16.00">{{ old('office_hours', $hours) }}</textarea>
            </div>
            <button class="btn btn-primary" type="submit">Simpan profil dan kontak</button>
        </form>
    </section>

    <section class="panel">
        <h2>Kontak person</h2>
        @if ($people->isEmpty())
            <div class="empty">
                <p>Belum ada kontak person.</p>
                <button class="btn btn-primary" type="button" data-open="contact-create">Tambah kontak person</button>
            </div>
        @else
            @foreach ($people as $person)
                <article class="file-row">
                    <div>
                        <strong>{{ $person->name }}</strong>
                        <p class="muted">{{ $person->role ?: 'Tanpa bagian' }}@if ($person->phone) · {{ $person->phone }}@endif @if ($person->email) · {{ $person->email }}@endif</p>
                    </div>
                    <div class="actions">
                        <button class="btn btn-line dark btn-small" type="button" data-open="contact-edit-{{ $person->id }}">Ubah</button>
                        <button class="btn btn-danger btn-small" type="button" data-open="contact-delete-{{ $person->id }}">Hapus</button>
                    </div>
                </article>
            @endforeach
        @endif
    </section>

    <section class="panel">
        <h2>Pesan masuk</h2>
        @if ($messages->isEmpty())
            <p class="muted">Belum ada pesan dari formulir kontak.</p>
        @else
            @foreach ($messages as $message)
                <article class="file-row">
                    <div>
                        <strong>{{ $message->name }}</strong>
                        <p class="muted">{{ $message->email }}@if ($message->subject) · {{ $message->subject }}@endif · {{ $message->created_at->timezone('Asia/Jakarta')->translatedFormat('d M Y H:i') }}</p>
                        <p>{{ $message->message }}</p>
                    </div>
                    <div class="actions">
                        <button class="btn btn-danger btn-small" type="button" data-open="message-delete-{{ $message->id }}">Hapus</button>
                    </div>
                </article>
            @endforeach
        @endif
    </section>

    <dialog class="modal" id="contact-create">
        <div class="modal-card">
            <div class="modal-head">
                <h2>Tambah kontak person</h2>
                <button class="modal-close" type="button" data-close aria-label="Tutup">×</button>
            </div>
            <form method="POST" action="{{ route('admin.contacts.store') }}">
                @csrf
                <input type="hidden" name="modal" value="contact-create">
                <div class="field">
                    <label for="contact-name">Nama</label>
                    <input id="contact-name" name="name" value="{{ $openModal === 'contact-create' ? old('name') : '' }}" required>
                </div>
                <div class="field">
                    <label for="contact-role">Bagian</label>
                    <input id="contact-role" name="role" value="{{ $openModal === 'contact-create' ? old('role') : '' }}" placeholder="Contoh: Penerbitan">
                </div>
                <div class="field">
                    <label for="contact-phone">Telepon atau WhatsApp</label>
                    <input id="contact-phone" name="phone" value="{{ $openModal === 'contact-create' ? old('phone') : '' }}" placeholder="08xxxxxxxxxx">
                </div>
                <div class="field">
                    <label for="contact-email">Email</label>
                    <input id="contact-email" type="email" name="email" value="{{ $openModal === 'contact-create' ? old('email') : '' }}">
                </div>
                <div class="form-bar">
                    <button class="btn btn-line dark" type="button" data-close>Batal</button>
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </dialog>

    @foreach ($people as $person)
        <dialog class="modal" id="contact-edit-{{ $person->id }}">
            <div class="modal-card">
                <div class="modal-head">
                    <h2>Ubah kontak person</h2>
                    <button class="modal-close" type="button" data-close aria-label="Tutup">×</button>
                </div>
                <form method="POST" action="{{ route('admin.contacts.update', $person) }}">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="modal" value="contact-edit-{{ $person->id }}">
                    <div class="field">
                        <label for="name-{{ $person->id }}">Nama</label>
                        <input id="name-{{ $person->id }}" name="name" value="{{ $openModal === 'contact-edit-'.$person->id ? old('name', $person->name) : $person->name }}" required>
                    </div>
                    <div class="field">
                        <label for="role-{{ $person->id }}">Bagian</label>
                        <input id="role-{{ $person->id }}" name="role" value="{{ $openModal === 'contact-edit-'.$person->id ? old('role', $person->role) : $person->role }}">
                    </div>
                    <div class="field">
                        <label for="phone-{{ $person->id }}">Telepon atau WhatsApp</label>
                        <input id="phone-{{ $person->id }}" name="phone" value="{{ $openModal === 'contact-edit-'.$person->id ? old('phone', $person->phone) : $person->phone }}">
                    </div>
                    <div class="field">
                        <label for="email-{{ $person->id }}">Email</label>
                        <input id="email-{{ $person->id }}" type="email" name="email" value="{{ $openModal === 'contact-edit-'.$person->id ? old('email', $person->email) : $person->email }}">
                    </div>
                    <div class="field">
                        <label for="order-{{ $person->id }}">Urutan</label>
                        <input id="order-{{ $person->id }}" type="number" name="sort_order" min="0" max="9999" value="{{ $openModal === 'contact-edit-'.$person->id ? old('sort_order', $person->sort_order) : $person->sort_order }}">
                    </div>
                    <div class="form-bar">
                        <button class="btn btn-line dark" type="button" data-close>Batal</button>
                        <button class="btn btn-primary" type="submit">Simpan</button>
                    </div>
                </form>
            </div>
        </dialog>
        <dialog class="modal" id="contact-delete-{{ $person->id }}">
            <div class="modal-card">
                <div class="modal-head">
                    <h2>Hapus kontak person</h2>
                    <button class="modal-close" type="button" data-close aria-label="Tutup">×</button>
                </div>
                <p>Hapus {{ $person->name }} dari halaman kontak?</p>
                <form method="POST" action="{{ route('admin.contacts.destroy', $person) }}">
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

    @foreach ($messages as $message)
        <dialog class="modal" id="message-delete-{{ $message->id }}">
            <div class="modal-card">
                <div class="modal-head">
                    <h2>Hapus pesan</h2>
                    <button class="modal-close" type="button" data-close aria-label="Tutup">×</button>
                </div>
                <p>Hapus pesan dari {{ $message->name }}?</p>
                <form method="POST" action="{{ route('admin.messages.destroy', $message) }}">
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
