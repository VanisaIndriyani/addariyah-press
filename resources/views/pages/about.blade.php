@extends('layouts.public')

@section('title', 'Tentang — Addariyah Press')

@section('content')
    <section class="section">
        <div class="container narrow prose">
            <p class="eyebrow">Tentang</p>
            <h1>{{ \App\Support\Site::get('site_name', 'Addariyah Press') }}</h1>
            @if (filled($about))
                {!! \App\Support\Site::paragraphs($about) !!}
            @else
                <p>Addariyah Press menerbitkan buku dan menjual e-book melalui katalog online.</p>
            @endif
        </div>
    </section>
@endsection
