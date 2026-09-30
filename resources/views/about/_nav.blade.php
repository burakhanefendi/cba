@php
    $isEn = app()->getLocale() === 'en';
    $current = $current ?? 'profile';
    $links = [
        [
            'key'   => 'profile',
            'label' => __('site.about.cafer'),
            'url'   => $isEn ? url('en/profile') : route('about.profile'),
        ],
        [
            'key'   => 'studio',
            'label' => __('site.about.studio'),
            'url'   => $isEn ? url('en/profile/studio') : route('about.studio'),
        ],
        [
            'key'   => 'team',
            'label' => __('site.about.team'),
            'url'   => $isEn ? url('en/profile/team') : route('about.team'),
        ],
        [
            'key'   => 'awards',
            'label' => __('site.about.awards'),
            'url'   => $isEn ? url('en/profile/awards') : route('about.awards'),
        ],
        [
            'key'   => 'publications',
            'label' => __('site.about.publications'),
            'url'   => $isEn ? url('en/profile/publications') : route('about.publications'),
        ],
    ];
@endphp

<div class="profile-header">
    <h1 class="news-page-title">{{ __('site.nav.profile') }}</h1>
    <nav class="profile-subnav">
        @foreach($links as $link)
            <a href="{{ $link['url'] }}" class="{{ $current === $link['key'] ? 'active' : '' }}">{{ $link['label'] }}</a>
        @endforeach
    </nav>
</div>
