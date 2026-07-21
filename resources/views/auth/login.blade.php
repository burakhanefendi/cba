<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CBA — Yönetim Paneli</title>
    <link rel="stylesheet" href="/css/admin-login.css">
</head>
<body>

<div class="login-wrapper">

    <div class="login-logo">
        <h1>CBA</h1>
        <p>Cafer Bozkurt Architecture</p>
    </div>

    <div class="login-card">

        @if (session('status'))
            <div class="alert-error">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">E-posta</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                @error('email')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Şifre</label>
                <input id="password" type="password" name="password" required autocomplete="current-password">
                @error('password')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-remember">
                <input id="remember_me" type="checkbox" name="remember">
                <label for="remember_me">Beni hatırla</label>
            </div>

            <button type="submit" class="btn-login">Giriş Yap</button>
        </form>

    </div>

</div>

</body>
</html>
