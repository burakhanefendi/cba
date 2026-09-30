@extends('layouts.site')

@section('title', 'CBA — Cafer Bozkurt Architecture Istanbul')

@section('content')

{{-- Hero Slider --}}
<div class="container">
    <section class="hero">
        @if($heroSlides->count())
            <div class="hero-slider" id="heroSlider">
                <div class="hero-slider-track" id="heroTrack">
                    @foreach($heroSlides as $slide)
                        <div class="hero-slide">
                            @if($slide->link)
                                <a href="{{ $slide->link }}" target="_blank" rel="noopener">
                                    <img src="{{ asset('storage/' . $slide->image) }}" alt="">
                                </a>
                            @else
                                <img src="{{ asset('storage/' . $slide->image) }}" alt="">
                            @endif
                        </div>
                    @endforeach
                </div>

                @if($heroSlides->count() > 1)
                    <button class="hero-slider-btn hero-slider-prev" id="heroPrev">
                        <svg width="10" height="18" viewBox="0 0 10 18" fill="none"><polyline points="9,1 1,9 9,17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                    <button class="hero-slider-btn hero-slider-next" id="heroNext">
                        <svg width="10" height="18" viewBox="0 0 10 18" fill="none"><polyline points="1,1 9,9 1,17" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                    <div class="hero-slider-dots">
                        @foreach($heroSlides as $i => $slide)
                            <span class="hero-dot {{ $i === 0 ? 'active' : '' }}" data-index="{{ $i }}"></span>
                        @endforeach
                    </div>
                @endif
            </div>
        @else
            <div class="hero-placeholder">
                <span>Ana Görsel</span>
            </div>
        @endif
    </section>
</div>

{{-- Intro --}}
<div class="container">
    <div class="intro">
        <div class="intro-inner">
            @php $introText = app()->getLocale() === 'en' ? \App\Models\Setting::get('intro_text_en') : \App\Models\Setting::get('intro_text'); @endphp
            @if($introText)
                <p>{{ $introText }}</p>
            @else
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent blandit diam ullamcorper mi vulputate consequat. Morbi scelerisque arcu ut diam varius suscipit. Sed accumsan turpis eget tincidunt ligula. Duis porta lorem at nisl facilisis aliquet. Aenean sollicitudin ex tempus elit pellentesque. Fusce ut sapien eget sapien accumsan dictum. Etiam tincidunt ligula eget risus suscipit sed accumsan turpis varius. Duis porta lorem at nisl facilisis cursus. Sed accumsan turpis eget tincidunt ligula consequat facilisis lorem.</p>
            @endif
        </div>
    </div>
</div>

{{-- Projeler --}}
<div class="container">
    <section class="section">
        <h2 class="section-title">{{ app()->getLocale() === 'en' ? 'SELECTED PROJECTS' : 'SEÇİLMİŞ PROJELER' }}</h2>

        <div class="projects-grid">
            @forelse($featuredProjects as $project)
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
        <div class="home-section-more">
            <a href="{{ app()->getLocale() === 'en' ? url('en/projects') : route('projects.index') }}">{{ app()->getLocale() === 'en' ? 'All projects' : 'Tüm projeler' }}</a>
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

        <div class="home-section-more">
            <a href="{{ app()->getLocale() === 'en' ? url('en/news') : route('news.index') }}">{{ app()->getLocale() === 'en' ? 'All news' : 'Tüm haberler' }}</a>
        </div>
    </section>
</div>

@endsection

@if($heroSlides->count() > 1)
@push('scripts')
<script>
(function () {
    const track = document.getElementById('heroTrack');
    if (!track) return;
    const slides = track.querySelectorAll('.hero-slide');
    const dots   = document.querySelectorAll('.hero-dot');
    let current  = 0;
    let timer;

    function goTo(n) {
        current = (n + slides.length) % slides.length;
        track.style.transform = `translateX(-${current * 100}%)`;
        dots.forEach((d, i) => d.classList.toggle('active', i === current));
    }

    function autoPlay() { timer = setInterval(() => goTo(current + 1), 5000); }

    document.getElementById('heroPrev')?.addEventListener('click', () => { clearInterval(timer); goTo(current - 1); autoPlay(); });
    document.getElementById('heroNext')?.addEventListener('click', () => { clearInterval(timer); goTo(current + 1); autoPlay(); });
    dots.forEach(d => d.addEventListener('click', () => { clearInterval(timer); goTo(+d.dataset.index); autoPlay(); }));

    autoPlay();
})();
</script>
@endpush
@endif
