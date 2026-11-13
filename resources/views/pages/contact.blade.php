@extends('layouts.public')

@section('title', 'Kontak — Addariyah Press')

@section('content')
    <section class="section">
        <div class="container">
            <p class="eyebrow">Kontak</p>
            <h1>Kontak person</h1>
            <p class="muted">Hubungi Addariyah Press lewat kontak di bawah, atau kirim pesan melalui formulir.</p>

            @if ($map)
                <figure class="contact-map">
                    <iframe src="{{ $map }}" title="Peta lokasi Addariyah Press" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    @if (\App\Support\Site::get('address'))
                        <figcaption>{!! nl2br(e(\App\Support\Site::get('address'))) !!}</figcaption>
                    @endif
                </figure>
            @endif

            <div class="contact-layout">
                <div>
                    <h2>Kontak person</h2>
                    @if (\App\Support\Site::waNumber() || \App\Support\Site::get('email') || $hours)
                        <article class="person">
                            <p class="eyebrow">Penerbit</p>
                            <strong>{{ \App\Support\Site::get('site_name', 'Addariyah Press') }}</strong>
                            @if (\App\Support\Site::get('address') && ! $map)
                                <p>{!! nl2br(e(\App\Support\Site::get('address'))) !!}</p>
                            @endif
                            @if (\App\Support\Site::waNumber())
                                <p><a href="https://wa.me/{{ \App\Support\Site::waNumber() }}" target="_blank" rel="noopener">WhatsApp {{ \App\Support\Site::get('whatsapp') }}</a></p>
                            @endif
                            @if (\App\Support\Site::get('email'))
                                <p><a href="mailto:{{ \App\Support\Site::get('email') }}">{{ \App\Support\Site::get('email') }}</a></p>
                            @endif
                            @if ($hours)
                                <p class="muted">{!! nl2br(e($hours)) !!}</p>
                            @endif
                        </article>
                    @endif

                    @forelse ($people as $person)
                        <article class="person">
                            @if ($person->role)
                                <p class="eyebrow">{{ $person->role }}</p>
                            @endif
                            <strong>{{ $person->name }}</strong>
                            @if ($person->phone)
                                @php($digits = preg_replace('/\D+/', '', $person->phone))
                                <p><a href="{{ str_starts_with($digits, '0') || str_starts_with($digits, '62') ? 'https://wa.me/'.(str_starts_with($digits, '0') ? '62'.substr($digits, 1) : $digits) : 'tel:'.$person->phone }}" @if(str_starts_with($digits, '0') || str_starts_with($digits, '62')) target="_blank" rel="noopener" @endif>{{ $person->phone }}</a></p>
                            @endif
                            @if ($person->email)
                                <p><a href="mailto:{{ $person->email }}">{{ $person->email }}</a></p>
                            @endif
                        </article>
                    @empty
                        @if (! \App\Support\Site::waNumber() && ! \App\Support\Site::get('email'))
                            <p class="muted">Kontak person belum diisi.</p>
                        @endif
                    @endforelse
                </div>

                <form class="panel" method="POST" action="{{ route('contact.store') }}">
                    @csrf
                    <h2>Kirim pesan</h2>
                    <div class="hp" aria-hidden="true">
                        <label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                    </div>
                    <div class="form-grid">
                        <div class="field">
                            <label for="name">Nama</label>
                            <input id="name" name="name" value="{{ old('name') }}" required>
                        </div>
                        <div class="field">
                            <label for="email">Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                        </div>
                    </div>
                    <div class="field">
                        <label for="subject">Subjek</label>
                        <input id="subject" name="subject" value="{{ old('subject') }}">
                    </div>
                    <div class="field">
                        <label for="message">Pesan</label>
                        <textarea id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
                    </div>
                    <button class="btn btn-primary" type="submit">Kirim pesan</button>
                </form>
            </div>
        </div>
    </section>
@endsection
