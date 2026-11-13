@extends('layouts.public')

@section('title', 'Profil — Addariyah Press')

@section('content')
    <section class="section">
        <div class="container narrow prose">
            <p class="eyebrow">Profil</p>
            <h1>{{ \App\Support\Site::get('site_name', 'Addariyah Press') }}</h1>
            @if (filled($about))
                {!! \App\Support\Site::paragraphs($about) !!}
            @else
                <p>Addariyah Press menerbitkan buku dan menjual e-book melalui katalog online.</p>
            @endif

            <div class="profile-block">
                <h2>Visi</h2>
                {!! \App\Support\Site::paragraphs($visi) !!}
            </div>
            <div class="profile-block">
                <h2>Misi</h2>
                {!! \App\Support\Site::paragraphs($misi) !!}
            </div>
        </div>
    </section>
@endsection
