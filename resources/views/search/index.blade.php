@extends('layouts.site')

@section('title', __('site.search.title') . ($q ? ' — ' . $q : '') . ' — CBA')

@section('content')
<div class="container">
    <div class="news-page">
        <h1 class="news-page-title">{{ __('site.search.title') }}</h1>

        @if(mb_strlen($q) < 2)
            <p class="empty-state">{{ __('site.search.hint') }}</p>
        @elseif($total === 0)
            <p class="empty-state">{{ __('site.search.empty', ['q' => $q]) }}</p>
        @else
            <p class="search-count">{{ __('site.search.count', ['n' => $total, 'q' => $q]) }}</p>

            @if($projects->count())
                <section class="search-section">
                    <h2 class="search-section-title">{{ __('site.projects.title') }}</h2>
                    <div class="projects-grid">
                        @foreach($projects as $project)
                            @php
                                $projectUrl = app()->getLocale() === 'en'
                                    ? url('en/projects/' . $project->slug)
                                    : route('projects.show', $project->slug);
                            @endphp
                            <a href="{{ $projectUrl }}" class="project-card">
                                <div class="project-card-image">
                                    @if($project->cover_image)
                                        <img src="{{ asset('storage/' . $project->cover_image) }}" alt="{{ $project->trans('title') }}">
                                    @else
                                        <div class="placeholder"><span>Görsel</span></div>
                                    @endif
                                </div>
                                <div class="project-card-title">{{ $project->trans('title') }}</div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif

            @if($news->count())
                <section class="search-section">
                    <h2 class="search-section-title">{{ __('site.news.title') }}</h2>
                    <div class="news-grid">
                        @foreach($news as $item)
                            @php
                                $newsUrl = app()->getLocale() === 'en'
                                    ? url('en/news/' . $item->slug)
                                    : route('news.show', $item->slug);
                            @endphp
                            <a href="{{ $newsUrl }}" class="news-item">
                                <div class="news-item-image">
                                    @if($item->cover_image)
                                        <img src="{{ asset('storage/' . $item->cover_image) }}" alt="{{ $item->trans('title') }}">
                                    @endif
                                </div>
                                <div class="news-item-body">
                                    <div class="news-item-meta">
                                        <span class="news-item-title">{{ $item->trans('title') }}</span>
                                        <div class="news-item-date">{{ $item->formatted_date }}</div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif

            @if($awards->count())
                <section class="search-section">
                    <h2 class="search-section-title">{{ __('site.about.awards') }}</h2>
                    <div class="projects-grid">
                        @foreach($awards as $entry)
                            <a href="{{ app()->getLocale() === 'en' ? url('en/profile/awards') : route('about.awards') }}" class="project-card">
                                <div class="project-card-image">
                                    @if($entry->image)
                                        <img src="{{ asset('storage/' . $entry->image) }}" alt="{{ $entry->trans('title') }}">
                                    @else
                                        <div class="placeholder"><span>Görsel</span></div>
                                    @endif
                                </div>
                                <div class="project-card-title">{{ $entry->trans('title') }}</div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif

            @if($publications->count())
                <section class="search-section">
                    <h2 class="search-section-title">{{ __('site.about.publications') }}</h2>
                    <div class="projects-grid">
                        @foreach($publications as $entry)
                            <a href="{{ app()->getLocale() === 'en' ? url('en/profile/publications') : route('about.publications') }}" class="project-card">
                                <div class="project-card-image">
                                    @if($entry->image)
                                        <img src="{{ asset('storage/' . $entry->image) }}" alt="{{ $entry->trans('title') }}">
                                    @else
                                        <div class="placeholder"><span>Görsel</span></div>
                                    @endif
                                </div>
                                <div class="project-card-title">{{ $entry->trans('title') }}</div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif

            @if($team->count())
                <section class="search-section">
                    <h2 class="search-section-title">{{ __('site.about.team') }}</h2>
                    <div class="projects-grid">
                        @foreach($team as $member)
                            <a href="{{ app()->getLocale() === 'en' ? url('en/profile/team') : route('about.team') }}" class="project-card">
                                <div class="project-card-image">
                                    @if($member->photo)
                                        <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->name }}">
                                    @else
                                        <div class="placeholder"><span>Görsel</span></div>
                                    @endif
                                </div>
                                <div class="project-card-title">{{ $member->name }}</div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        @endif
    </div>
</div>
@endsection
