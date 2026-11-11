@extends('layouts.public')

@section('title', 'Halaman tidak ditemukan')
@section('robots', 'noindex, nofollow')

@section('content')
    <section class="section">
        <div class="container narrow" style="text-align:center;">
            <p class="eyebrow">404</p>
            <h1>Halaman ini tidak ada.</h1>
            <p>Tautan mungkin sudah dipindah, atau alamatnya keliru.</p>
            <a class="btn btn-primary" href="{{ route('home') }}">Kembali ke beranda</a>
        </div>
    </section>
@endsection
