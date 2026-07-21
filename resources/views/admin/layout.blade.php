<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CBA — Yönetim Paneli</title>
    <link rel="stylesheet" href="/css/admin.css">
    @stack('styles')
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-logo">
        <h1>CBA</h1>
        <p>Admin Panel</p>
    </div>

    <nav class="sidebar-nav">
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>

        <div class="nav-section">İçerik</div>
        <a href="{{ route('admin.projects.index') }}" class="{{ request()->routeIs('admin.projects.*') ? 'active' : '' }}">Projeler</a>
        <a href="{{ route('admin.news.index') }}" class="{{ request()->routeIs('admin.news.*') ? 'active' : '' }}">Haberler</a>
        <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">Kategoriler</a>
        <a href="{{ route('admin.team.index') }}" class="{{ request()->routeIs('admin.team.*') ? 'active' : '' }}">Ekip</a>
        <a href="{{ route('admin.services.index') }}" class="{{ request()->routeIs('admin.services.*') ? 'active' : '' }}">Hizmetler</a>

        <div class="nav-section">Genel</div>
        <a href="{{ route('admin.media.index') }}" class="{{ request()->routeIs('admin.media.*') ? 'active' : '' }}">Medya Kütüphanesi</a>
        <a href="{{ route('admin.messages.index') }}" class="{{ request()->routeIs('admin.messages.*') ? 'active' : '' }}">Mesajlar</a>
        <a href="{{ route('admin.settings.index') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">Ayarlar</a>
    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Çıkış Yap</button>
        </form>
    </div>
</aside>

<div class="main">
    <div class="topbar">
        <span class="topbar-title">@yield('title', 'Dashboard')</span>
        <span class="topbar-user">{{ auth()->user()->name }}</span>
    </div>

    <div class="content">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif

        @yield('content')
    </div>
</div>

@stack('scripts')
</body>
</html>
