@extends('layouts.site')

@section('title', $item->trans('title') . ' — CBA')

@section('content')

<div class="container">
    <div class="news-detail">

        <h1 class="news-page-title">{{ __('site.news.title') }}</h1>

        {{-- Ana Görsel / Slider --}}
        @php
            $sliderImages = collect();
            if ($item->cover_image) {
                $sliderImages->push((object)['src' => asset('storage/' . $item->cover_image)]);
            }
            foreach ($item->sliderImages as $img) {
                $sliderImages->push((object)['src' => asset('storage/' . $img->image)]);
            }
        @endphp

        @if($sliderImages->count())
            <div class="news-slider" id="newsSlider">
                <div class="news-slider-track" id="sliderTrack">
                    @foreach($sliderImages as $slide)
                        <div class="news-slide">
                            <img src="{{ $slide->src }}" alt="{{ $item->trans('title') }}">
                        </div>
                    @endforeach
                </div>

                @if($sliderImages->count() > 1)
                    <button class="slider-btn slider-prev" id="sliderPrev">&#8592;</button>
                    <button class="slider-btn slider-next" id="sliderNext">&#8594;</button>
                    <div class="slider-dots" id="sliderDots">
                        @foreach($sliderImages as $i => $slide)
                            <span class="slider-dot {{ $i === 0 ? 'active' : '' }}" data-index="{{ $i }}"></span>
                        @endforeach
                    </div>
                @endif
            </div>
        @endif

        {{-- İçerik --}}
        <div class="news-detail-body">

            {{-- Sol: başlık, tarih, linkler --}}
            <div class="news-detail-left">
                <div class="news-detail-meta">
                    <h2 class="news-detail-title">{{ $item->trans('title') }}</h2>
                    <div class="news-detail-date">{{ $item->formatted_date }}</div>
                </div>

                @if($item->links->count())
                    @php $sortedLinks = $item->links->sortByDesc('type'); @endphp
                    <div class="news-detail-links-wrap">
                        <ul class="news-detail-links">
                            @foreach($sortedLinks as $link)
                                <li>
                                    <a href="{{ $link->url }}" target="_blank" rel="noopener">
                                        <span class="link-icon {{ $link->type === 'project' ? 'link-icon-project' : 'link-icon-link' }}">{{ $link->type === 'project' ? 'P' : 'L' }}</span>
                                        {{ $link->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            {{-- Sağ: içerik metni --}}
            <div class="news-detail-right">
                {!! $item->trans('content') !!}
            </div>
        </div>

        {{-- Alt Galeri --}}
        @if($item->galleryImages->count())
            <div class="news-gallery">
                @foreach($item->galleryImages as $img)
                    <a href="{{ asset('storage/' . $img->image) }}"
                       class="news-gallery-item glightbox"
                       data-gallery="news-gallery"
                       data-description="{{ $item->trans('title') }}">
                        <img src="{{ asset('storage/' . $img->image) }}" alt="{{ $item->trans('title') }}">
                    </a>
                @endforeach
            </div>
        @endif

    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css">
<style>
.news-gallery-item {
    display: block;
    overflow: hidden;
    cursor: zoom-in;
}
.news-gallery-item img {
    transition: transform 0.3s ease;
}
.news-gallery-item:hover img {
    transform: scale(1.03);
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
<script>
(function() {
    const track = document.getElementById('sliderTrack');
    if (!track) return;

    const slides = track.querySelectorAll('.news-slide');
    if (slides.length <= 1) return;

    const dots = document.querySelectorAll('.slider-dot');
    let current = 0;

    function goTo(n) {
        current = (n + slides.length) % slides.length;
        track.style.transform = `translateX(-${current * 100}%)`;
        dots.forEach((d, i) => d.classList.toggle('active', i === current));
    }

    document.getElementById('sliderPrev')?.addEventListener('click', () => goTo(current - 1));
    document.getElementById('sliderNext')?.addEventListener('click', () => goTo(current + 1));
    dots.forEach(d => d.addEventListener('click', () => goTo(+d.dataset.index)));
})();

GLightbox({ selector: '.glightbox', touchNavigation: true, loop: true });
</script>
@endpush
