@extends('layouts.admin')

@section('title', 'Pengaturan')

@section('content')
    <div class="admin-top">
        <div>
            <p class="eyebrow">Website</p>
            <h1>Pengaturan</h1>
        </div>
    </div>
    <form class="panel" method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-grid">
            <div class="field">
                <label for="site_name">Nama website</label>
                <input id="site_name" name="site_name" value="{{ old('site_name', $settings['site_name']) }}" required>
            </div>
            <div class="field">
                <label for="tagline">Tagline</label>
                <input id="tagline" name="tagline" value="{{ old('tagline', $settings['tagline']) }}">
            </div>
            <div class="field span-2">
                <label for="hero_title">Judul utama beranda</label>
                <input id="hero_title" name="hero_title" value="{{ old('hero_title', $settings['hero_title']) }}" required>
            </div>
            <div class="field span-2">
                <label for="hero_text">Teks pembuka</label>
                <textarea id="hero_text" name="hero_text" rows="3" required>{{ old('hero_text', $settings['hero_text']) }}</textarea>
            </div>
            <div class="field span-2">
                <label for="about">Tentang penerbit</label>
                <textarea id="about" name="about" rows="6">{{ old('about', $settings['about']) }}</textarea>
            </div>
            <div class="field">
                <label for="whatsapp">Nomor WhatsApp</label>
                <input id="whatsapp" name="whatsapp" value="{{ old('whatsapp', $settings['whatsapp']) }}" required placeholder="628xxxxxxxxxx">
                <p class="help">Dipakai untuk konfirmasi pembayaran dan tombol WhatsApp.</p>
            </div>
            <div class="field">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email', $settings['email']) }}">
            </div>
            <div class="field span-2">
                <label for="address">Alamat</label>
                <textarea id="address" name="address" rows="3" placeholder="Jl. Tugu Tani, Majjelling Wattang, Maritengngae, Sidenreng Rappang, Sulawesi Selatan.">{{ old('address', $settings['address']) }}</textarea>
                <p class="help">Tampil di kolom Alamat pada footer. Pisahkan baris dengan Enter.</p>
            </div>
            <div class="field">
                <label for="instagram">Instagram</label>
                <input id="instagram" name="instagram" value="{{ old('instagram', $settings['instagram']) }}" placeholder="@addariyahpress">
            </div>
            <div class="field span-2">
                <label for="qris">Gambar QRIS</label>
                <input id="qris" type="file" name="qris" accept="image/*">
                @if (\App\Support\Site::qrisReady())
                    <img src="{{ asset($settings['qris_image']) }}" alt="QRIS" style="width: 180px; margin-top: .7rem; border-radius: 12px;">
                @else
                    <p class="help">Belum ada QRIS. Unggah potongan kode pembayaran di sini.</p>
                @endif
            </div>
            <div class="field">
                <label for="password">Kata sandi baru</label>
                <input id="password" type="password" name="password" autocomplete="new-password">
            </div>
            <div class="field">
                <label for="password_confirmation">Ulangi kata sandi</label>
                <input id="password_confirmation" type="password" name="password_confirmation" autocomplete="new-password">
            </div>
        </div>
      
        <button class="btn btn-primary" type="submit">Simpan pengaturan</button>
    </form>
@endsection
