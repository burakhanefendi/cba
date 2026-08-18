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

        {{-- Projeler dropdown --}}
        @php $projectsOpen = request()->routeIs('admin.projects.*') || request()->routeIs('admin.categories.*'); @endphp
        <div class="nav-dropdown {{ $projectsOpen ? 'open' : '' }}" id="navDropdownProjeler">
            <button type="button" class="nav-dropdown-toggle {{ $projectsOpen ? 'active' : '' }}"
                    onclick="toggleDropdown('navDropdownProjeler')">
                Projeler
                <svg class="nav-dropdown-arrow" width="10" height="10" viewBox="0 0 10 10" fill="none">
                    <path d="M2 3.5L5 6.5L8 3.5" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                </svg>
            </button>
            <div class="nav-dropdown-menu">
                <a href="{{ route('admin.projects.index') }}" class="{{ request()->routeIs('admin.projects.*') ? 'active' : '' }}">Proje Listesi</a>
                <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">Kategoriler</a>
            </div>
        </div>

        <a href="{{ route('admin.news.index') }}" class="{{ request()->routeIs('admin.news.*') ? 'active' : '' }}">Haberler</a>
        {{-- <a href="{{ route('admin.team.index') }}" class="{{ request()->routeIs('admin.team.*') ? 'active' : '' }}">Ekip</a> --}}
        <a href="{{ route('admin.services.index') }}" class="{{ request()->routeIs('admin.services.*') ? 'active' : '' }}">Hizmetler</a>

        <div class="nav-section">Genel</div>
        <a href="{{ route('admin.homepage.index') }}" class="{{ request()->routeIs('admin.homepage.*') ? 'active' : '' }}">Anasayfa</a>
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
        <div style="display:flex;align-items:center;gap:16px;">
            <a href="{{ route('home') }}" target="_blank" class="btn btn-secondary btn-sm">Siteyi Görüntüle ↗</a>
            <span class="topbar-user">{{ auth()->user()->name }}</span>
        </div>
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
<script>
function toggleDropdown(id) {
    document.getElementById(id).classList.toggle('open');
}
</script>
</body>
</html>
