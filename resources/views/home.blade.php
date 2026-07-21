@extends('layouts.site')

@section('title', 'CBA — Cafer Bozkurt Architecture Istanbul')

@section('content')

{{-- Hero --}}
<div class="container">
    <section class="hero">
        <div class="hero-placeholder">
            <span>Ana Görsel</span>
        </div>
    </section>
</div>

{{-- Intro --}}
<div class="container">
    <div class="intro">
        <div class="intro-inner">
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent blandit diam ullamcorper mi vulputate consequat. Morbi scelerisque arcu ut diam varius suscipit. Sed accumsan turpis eget tincidunt ligula. Duis porta lorem at nisl facilisis aliquet. Aenean sollicitudin ex tempus elit pellentesque. Fusce ut sapien eget sapien accumsan dictum. Etiam tincidunt ligula eget risus suscipit sed accumsan turpis varius. Duis porta lorem at nisl facilisis cursus. Sed accumsan turpis eget tincidunt ligula consequat facilisis lorem.</p>
        </div>
    </div>
</div>

{{-- Projeler --}}
<div class="container">
    <section class="section">
        <h2 class="section-title">PROJELER</h2>

        <div class="projects-grid">
            @forelse($featuredProjects as $project)
                <div class="project-card">
                    <div class="project-card-image">
                        @if($project->cover_image)
                            <img src="{{ asset('storage/' . $project->cover_image) }}" alt="{{ $project->title }}">
                        @else
                            <div class="placeholder"><span>Görsel</span></div>
                        @endif
                    </div>
                    <div class="project-card-title">{{ $project->title }}</div>
                    @if($project->location)
                        <div class="project-card-meta">{{ $project->location }}</div>
                    @endif
                </div>
            @empty
                @for($i = 0; $i < 10; $i++)
                    <div class="project-card">
                        <div class="project-card-image">
                            <div class="placeholder"><span>Görsel</span></div>
                        </div>
                        <div class="project-card-title">Proje Adı</div>
                    </div>
                @endfor
            @endforelse
        </div>
    </section>
</div>

{{-- Haberler --}}
<div class="container">
    <section class="section">
        <h2 class="section-title">{{ __('site.home.latest_news') }}</h2>

        @if($featuredNews->count())
            <div class="news-grid">
                @foreach($featuredNews as $item)
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
                            @if($item->trans('excerpt'))
                                <p class="news-item-excerpt">{{ $item->trans('excerpt') }}</p>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @endif

    </section>
</div>

@endsection
