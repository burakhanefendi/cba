<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CBA — Cafer Bozkurt Architecture Istanbul')</title>
    <meta name="description" content="@yield('description', 'Cafer Bozkurt Architecture Istanbul')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/site.css">
    @stack('styles')
</head>
<body>

@php
    $locale = app()->getLocale();
    $isEn   = $locale === 'en';

    // Rota adına göre karşılıklı dil URL'leri
    $routeName   = Route::currentRouteName();
    $routeParams = request()->route()?->parameters() ?? [];
    $slug        = $routeParams['slug'] ?? null;

    // TR ↔ EN rota haritası
    $routeMap = [
        'en.home'       => ['tr' => route('home'),           'en' => url('en')],
        'home'          => ['tr' => route('home'),           'en' => url('en')],
        'en.news.index' => ['tr' => route('news.index'),     'en' => url('en/news')],
        'news.index'    => ['tr' => route('news.index'),     'en' => url('en/news')],
        'en.news.show'  => ['tr' => $slug ? route('news.show', $slug) : route('news.index'), 'en' => url('en/news/' . ($slug ?? ''))],
        'news.show'     => ['tr' => $slug ? route('news.show', $slug) : route('news.index'), 'en' => $slug ? url('en/news/' . $slug) : url('en/news')],
    ];

    $trUrl = $routeMap[$routeName]['tr'] ?? route('home');
    $enUrl = $routeMap[$routeName]['en'] ?? url('en');
@endphp

<header class="site-header">
    <div class="container">
        <div class="header-inner">
            <a href="{{ $isEn ? url('en') : route('home') }}" class="header-logo">
                @if(\App\Models\Setting::get('logo'))
                    <img src="{{ asset('storage/' . \App\Models\Setting::get('logo')) }}" alt="CBA Logo" class="logo-img">
                @else
                    <span class="logo-cba">CBA</span>
                    <span class="logo-sub">CAFER<br>BOZKURT<br>ARCHITECTURE<br>ISTANBUL</span>
                @endif
            </a>

            <div class="header-right-wrapper">
                <div class="header-right">
                    <ul class="header-nav-col">
                        <li><a href="#">{{ __('site.nav.profile') }}</a></li>
                        <li><a href="#">{{ __('site.nav.projects') }}</a></li>
                        <li><a href="{{ $isEn ? url('en/news') : route('news.index') }}" class="{{ request()->routeIs('news.*') || request()->routeIs('en.news.*') ? 'active' : '' }}">{{ __('site.nav.news') }}</a></li>
                        <li><a href="#">{{ __('site.nav.contact') }}</a></li>
                    </ul>

                    <ul class="header-nav-col">
                        <li><a href="{{ $isEn ? url('en') : route('home') }}" class="{{ request()->is('/') || request()->is('en') || request()->is('en/') ? 'active' : '' }}">{{ __('site.nav.home') }}</a></li>
                        <li class="lang-item">
                            <a href="{{ $trUrl }}" class="{{ !$isEn ? 'active' : 'inactive' }}">TR</a>
                            <span class="sep">|</span>
                            <a href="{{ $enUrl }}" class="{{ $isEn ? 'active' : 'inactive' }}">EN</a>
                        </li>
                        <li></li>
                        <li class="search-item" id="searchToggle">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <circle cx="6.5" cy="6.5" r="5" stroke="#333" stroke-width="1.2"/>
                                <line x1="10.5" y1="10.5" x2="15" y2="15" stroke="#333" stroke-width="1.2"/>
                            </svg>
                            {{ __('site.nav.search') }}
                        </li>
                    </ul>
                </div>

                <div class="header-search-bar" id="searchBar">
                    <form action="#" method="GET">
                        <input type="text" name="q" placeholder="{{ __('site.nav.search') }}" autocomplete="off" id="searchInput">
                        <button type="submit">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <circle cx="6.5" cy="6.5" r="5" stroke="#333" stroke-width="1.2"/>
                                <line x1="10.5" y1="10.5" x2="15" y2="15" stroke="#333" stroke-width="1.2"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <button class="hamburger" id="hamburgerBtn" aria-label="Menü">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>

        <div class="header-divider"></div>
    </div>
</header>

<nav class="mobile-nav" id="mobileNav">
    <a href="#">{{ __('site.nav.profile') }}</a>
    <a href="#">{{ __('site.nav.projects') }}</a>
    <a href="{{ $isEn ? url('en/news') : route('news.index') }}" class="{{ request()->routeIs('news.*') || request()->routeIs('en.news.*') ? 'active' : '' }}">{{ __('site.nav.news') }}</a>
    <a href="#">{{ __('site.nav.contact') }}</a>
</nav>

<div class="page-body">
    @yield('content')
</div>

<footer class="site-footer">
    <div class="container">
        <div class="footer-logo">CAFER BOZKURT ARCHITECTURE ISTANBUL</div>
        <div class="footer-copy">© {{ date('Y') }}</div>
        <a href="https://burakhan.dev" target="_blank" rel="noopener" class="footer-credit">{{ __('site.footer.developed') }} burakhan.dev</a>
    </div>
</footer>

<script>
    const header = document.querySelector('.site-header');
    const pageBody = document.querySelector('.page-body');
    function setOffset() {
        pageBody.style.paddingTop = header.offsetHeight + 'px';
    }
    setOffset();
    window.addEventListener('resize', setOffset);

    // Hamburger
    const btn = document.getElementById('hamburgerBtn');
    const nav = document.getElementById('mobileNav');
    btn.addEventListener('click', () => nav.classList.toggle('open'));

    // Arama
    const searchToggle = document.getElementById('searchToggle');
    const searchBar = document.getElementById('searchBar');
    const searchInput = document.getElementById('searchInput');
    searchToggle.addEventListener('click', () => {
        searchBar.classList.toggle('open');
        if (searchBar.classList.contains('open')) {
            searchInput.focus();
            setOffset();
        } else {
            setOffset();
        }
    });
</script>

@stack('scripts')
</body>
</html>
