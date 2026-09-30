<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CBA — Cafer Bozkurt Architecture Istanbul')</title>
    <meta name="description" content="@yield('description', 'Cafer Bozkurt Architecture Istanbul')">
    @php $favicon = \App\Models\Setting::favicon(); @endphp
    <link rel="icon" type="{{ $favicon['type'] }}" href="{{ $favicon['url'] }}">
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
        'en.home'              => ['tr' => route('home'),                    'en' => url('en')],
        'home'                 => ['tr' => route('home'),                    'en' => url('en')],
        'en.news.index'        => ['tr' => route('news.index'),              'en' => url('en/news')],
        'news.index'           => ['tr' => route('news.index'),              'en' => url('en/news')],
        'en.news.show'         => ['tr' => $slug ? route('news.show', $slug) : route('news.index'), 'en' => url('en/news/' . ($slug ?? ''))],
        'news.show'            => ['tr' => $slug ? route('news.show', $slug) : route('news.index'), 'en' => $slug ? url('en/news/' . $slug) : url('en/news')],
        'en.projects.index'    => ['tr' => route('projects.index'),          'en' => url('en/projects')],
        'projects.index'       => ['tr' => route('projects.index'),          'en' => url('en/projects')],
        'en.projects.show'     => ['tr' => $slug ? route('projects.show', $slug) : route('projects.index'), 'en' => url('en/projects/' . ($slug ?? ''))],
        'projects.show'        => ['tr' => $slug ? route('projects.show', $slug) : route('projects.index'), 'en' => $slug ? url('en/projects/' . $slug) : url('en/projects')],
        'en.about.profile'     => ['tr' => route('about.profile'),        'en' => url('en/profile')],
        'about.profile'        => ['tr' => route('about.profile'),        'en' => url('en/profile')],
        'en.about.studio'      => ['tr' => route('about.studio'),         'en' => url('en/profile/studio')],
        'about.studio'         => ['tr' => route('about.studio'),         'en' => url('en/profile/studio')],
        'en.about.team'        => ['tr' => route('about.team'),           'en' => url('en/profile/team')],
        'about.team'           => ['tr' => route('about.team'),           'en' => url('en/profile/team')],
        'en.about.awards'      => ['tr' => route('about.awards'),         'en' => url('en/profile/awards')],
        'about.awards'         => ['tr' => route('about.awards'),         'en' => url('en/profile/awards')],
        'en.about.publications'=> ['tr' => route('about.publications'),   'en' => url('en/profile/publications')],
        'about.publications'   => ['tr' => route('about.publications'),   'en' => url('en/profile/publications')],
        'en.search'            => ['tr' => route('search', request()->only('q')), 'en' => url('en/search') . (request('q') ? '?q=' . urlencode(request('q')) : '')],
        'search'               => ['tr' => route('search', request()->only('q')), 'en' => url('en/search') . (request('q') ? '?q=' . urlencode(request('q')) : '')],
        'en.contact'           => ['tr' => route('contact'),                   'en' => url('en/contact')],
        'contact'              => ['tr' => route('contact'),                   'en' => url('en/contact')],
    ];

    $trUrl = $routeMap[$routeName]['tr'] ?? route('home');
    $enUrl = $routeMap[$routeName]['en'] ?? url('en');
    $trSwitch = route('locale.switch', ['locale' => 'tr', 'to' => $trUrl]);
    $enSwitch = route('locale.switch', ['locale' => 'en', 'to' => $enUrl]);
@endphp

<header class="site-header">
    <div class="container">
        <div class="header-inner">
            <button class="hamburger" id="hamburgerBtn" aria-label="Menü">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <a href="{{ $isEn ? url('en') : route('home') }}" class="header-logo">
                <span class="logo-cba">CBA</span>
                <span class="logo-sub">CAFER<br>BOZKURT<br>ARCHITECTURE<br>ISTANBUL</span>
            </a>

            <div class="header-right-wrapper">
                <div class="header-right">
                    <ul class="header-nav-col">
                        <li><a href="{{ $isEn ? url('en/profile') : route('about.profile') }}" class="{{ request()->routeIs('about.*') || request()->routeIs('en.about.*') ? 'active' : '' }}">{{ __('site.nav.profile') }}</a></li>
                        <li><a href="{{ $isEn ? url('en/projects') : route('projects.index') }}" class="{{ request()->routeIs('projects.*') || request()->routeIs('en.projects.*') ? 'active' : '' }}">{{ __('site.nav.projects') }}</a></li>
                        <li><a href="{{ $isEn ? url('en/news') : route('news.index') }}" class="{{ request()->routeIs('news.*') || request()->routeIs('en.news.*') ? 'active' : '' }}">{{ __('site.nav.news') }}</a></li>
                        <li><a href="{{ $isEn ? url('en/contact') : route('contact') }}" class="{{ request()->routeIs('contact') || request()->routeIs('en.contact') ? 'active' : '' }}">{{ __('site.nav.contact') }}</a></li>
                    </ul>

                    <ul class="header-nav-col">
                        <li><a href="{{ $isEn ? url('en') : route('home') }}" class="{{ request()->is('/') || request()->is('en') || request()->is('en/') ? 'active' : '' }}">{{ __('site.nav.home') }}</a></li>
                        <li class="lang-item">
                            <a href="{{ $trSwitch }}" class="{{ !$isEn ? 'active' : 'inactive' }}">TR</a>
                            <span class="sep">|</span>
                            <a href="{{ $enSwitch }}" class="{{ $isEn ? 'active' : 'inactive' }}">EN</a>
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

                <div class="header-search-bar {{ request()->routeIs('search') || request()->routeIs('en.search') ? 'open' : '' }}" id="searchBar">
                    <form action="{{ $isEn ? url('en/search') : route('search') }}" method="GET">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('site.nav.search') }}" autocomplete="off" id="searchInput">
                        <button type="submit">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <circle cx="6.5" cy="6.5" r="5" stroke="#333" stroke-width="1.2"/>
                                <line x1="10.5" y1="10.5" x2="15" y2="15" stroke="#333" stroke-width="1.2"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="header-divider"></div>
    </div>
</header>

<nav class="mobile-nav" id="mobileNav">
    <form class="mobile-search" action="{{ $isEn ? url('en/search') : route('search') }}" method="GET">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ __('site.nav.search') }}" autocomplete="off">
        <button type="submit">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                <circle cx="6.5" cy="6.5" r="5" stroke="#333" stroke-width="1.2"/>
                <line x1="10.5" y1="10.5" x2="15" y2="15" stroke="#333" stroke-width="1.2"/>
            </svg>
        </button>
    </form>
    <a href="{{ $isEn ? url('en/profile') : route('about.profile') }}" class="{{ request()->routeIs('about.*') || request()->routeIs('en.about.*') ? 'active' : '' }}">{{ __('site.nav.profile') }}</a>
    <a href="{{ $isEn ? url('en/projects') : route('projects.index') }}" class="{{ request()->routeIs('projects.*') || request()->routeIs('en.projects.*') ? 'active' : '' }}">{{ __('site.nav.projects') }}</a>
    <a href="{{ $isEn ? url('en/news') : route('news.index') }}" class="{{ request()->routeIs('news.*') || request()->routeIs('en.news.*') ? 'active' : '' }}">{{ __('site.nav.news') }}</a>
    <a href="{{ $isEn ? url('en/contact') : route('contact') }}" class="{{ request()->routeIs('contact') || request()->routeIs('en.contact') ? 'active' : '' }}">{{ __('site.nav.contact') }}</a>
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
