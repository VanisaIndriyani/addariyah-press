<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk admin — Addariyah Press</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" href="{{ \App\Support\Site::logo() }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,560&family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="login-wrap">
        <form class="login-card" method="POST" action="{{ route('admin.login.store') }}">
            @csrf
            <img src="{{ \App\Support\Site::logo() }}" alt="" width="56" height="56">
            <h1>Masuk admin</h1>
            <p class="muted">Kelola buku, berita, naskah, dan QRIS Addariyah Press.</p>
            @if ($errors->any())
                <div class="alert alert-error">{{ $errors->first() }}</div>
            @endif
            <div class="field">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="field">
                <label for="password">Kata sandi</label>
                <input id="password" type="password" name="password" required>
            </div>
            <label class="check"><input type="checkbox" name="remember" value="1"> Ingat saya</label>
            <button class="btn btn-primary" type="submit" style="width:100%; margin-top: .8rem;">Masuk</button>
        </form>
    </div>
</body>
</html>
