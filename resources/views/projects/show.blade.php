@extends('layouts.site')

@section('title', $project->trans('title') . ' — CBA')

@section('content')

<div class="container">
    <div class="project-detail">

        {{-- Başlık --}}
        <h1 class="news-page-title">{{ __('site.projects.title') }}</h1>

        {{-- Ana Görsel / Slider --}}
        @if($project->sliderImages->count())
            <div class="news-slider" id="newsSlider">
                <div class="news-slider-track" id="sliderTrack">
                    @foreach($project->sliderImages as $img)
                        <div class="news-slide">
                            <img src="{{ asset('storage/' . $img->image) }}" alt="{{ $project->trans('title') }}">
                        </div>
                    @endforeach
                </div>
                @if($project->sliderImages->count() > 1)
                    <button class="slider-btn slider-prev" id="sliderPrev">&#8592;</button>
                    <button class="slider-btn slider-next" id="sliderNext">&#8594;</button>
                    <div class="slider-dots" id="sliderDots">
                        @foreach($project->sliderImages as $i => $img)
                            <span class="slider-dot {{ $i === 0 ? 'active' : '' }}" data-index="{{ $i }}"></span>
                        @endforeach
                    </div>
                @endif
            </div>
        @elseif($project->cover_image)
            <div class="news-slider">
                <div class="news-slider-track">
                    <div class="news-slide">
                        <img src="{{ asset('storage/' . $project->cover_image) }}" alt="{{ $project->trans('title') }}">
                    </div>
                </div>
            </div>
        @endif

        {{-- 3 Kolonlu İçerik --}}
        <div class="project-detail-body">

            {{-- Kolon 1: Başlık + Meta Bilgiler --}}
            <div class="project-col-1">
                <div class="col-inner" id="col1Inner">
                    <h2 class="project-detail-title">{{ $project->trans('title') }}</h2>

                    <dl class="project-meta-list">
                        @if($project->location)
                            <dt>{{ app()->getLocale() === 'en' ? 'Location' : 'Lokasyon' }}</dt>
                            <dd>{{ $project->location }}</dd>
                        @endif
                        @if($project->year)
                            <dt>{{ app()->getLocale() === 'en' ? 'Year' : 'Yıl' }}</dt>
                            <dd>{{ $project->year }}</dd>
                        @endif
                        @if($project->client)
                            <dt>{{ app()->getLocale() === 'en' ? 'Client' : 'İşveren' }}</dt>
                            <dd>{{ $project->client }}</dd>
                        @endif
                        @if($project->land_area)
                            <dt>{{ app()->getLocale() === 'en' ? 'Land Area' : 'Toplam Arsa Alanı' }}</dt>
                            <dd>{{ $project->land_area }} m²</dd>
                        @endif
                        @if($project->construction_area)
                            <dt>{{ app()->getLocale() === 'en' ? 'Construction Area' : 'Toplam İnşaat Alanı' }}</dt>
                            <dd>{{ $project->construction_area }} m²</dd>
                        @endif
                    </dl>
                </div>
                <button class="project-expand-btn" id="expandBtn1" aria-label="Daha fazla göster">+</button>
            </div>

            {{-- Kolon 2: Alt Başlık + Mimari Tasarım + Proje Ekibi --}}
            <div class="project-col-2">
                <div class="col-inner" id="col2Inner">
                    @if($project->trans('subtitle'))
                        <p class="project-subtitle">{{ $project->trans('subtitle') }}</p>
                    @endif

                    @if($project->designers->count())
                        <div class="project-team-section">
                            <div class="project-team-label">{{ app()->getLocale() === 'en' ? 'Architectural Design' : 'Mimari Tasarım' }}</div>
                            @foreach($project->designers as $d)
                                <div class="project-team-name">{{ $d->name }}</div>
                            @endforeach
                        </div>
                    @endif

                    @if($project->team->count())
                        <div class="project-team-section">
                            <div class="project-team-label">{{ app()->getLocale() === 'en' ? 'Project Team' : 'Proje Ekibi' }}</div>
                            @foreach($project->team as $t)
                                <div class="project-team-name">{{ $t->name }}</div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <button class="project-expand-btn" id="expandBtn2" aria-label="Daha fazla göster">+</button>
            </div>

            {{-- Kolon 3: Açıklama + Video --}}
            <div class="project-col-3">
                <div class="col-inner" id="col3Inner">
                    {!! $project->trans('description') !!}

                    @if($project->video_url)
                        <div class="project-video">
                            @php
                                $videoUrl = $project->video_url;
                                if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\s]+)/', $videoUrl, $m)) {
                                    $videoUrl = 'https://www.youtube.com/embed/' . $m[1];
                                } elseif (preg_match('/vimeo\.com\/(\d+)/', $videoUrl, $m)) {
                                    $videoUrl = 'https://player.vimeo.com/video/' . $m[1];
                                }
                            @endphp
                            <iframe src="{{ $videoUrl }}" frameborder="0" allowfullscreen
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                        </div>
                    @endif
                </div>
                <button class="project-expand-btn" id="expandBtn3" aria-label="Daha fazla göster">+</button>
            </div>
        </div>

        {{-- Alt Galeri --}}
        @if($project->galleryImages->count())
            <div class="news-gallery">
                @foreach($project->galleryImages as $img)
                    <a href="{{ asset('storage/' . $img->image) }}"
                       class="news-gallery-item glightbox"
                       data-gallery="project-gallery"
                       data-description="{{ $project->trans('title') }}">
                        <img src="{{ asset('storage/' . $img->image) }}" alt="{{ $project->trans('title') }}">
                    </a>
                @endforeach
            </div>
        @endif

        {{-- Benzer Projeler --}}
        @if($similarProjects->count())
            <div class="similar-projects">
                <h3 class="similar-projects-title">{{ app()->getLocale() === 'en' ? 'SIMILAR PROJECTS' : 'BENZER PROJELER' }}</h3>
                <div class="projects-grid">
                    @foreach($similarProjects as $similar)
                        @php
                            $similarUrl = app()->getLocale() === 'en'
                                ? url('en/projects/' . $similar->slug)
                                : route('projects.show', $similar->slug);
                        @endphp
                        <a href="{{ $similarUrl }}" class="project-card">
                            <div class="project-card-image">
                                @if($similar->cover_image)
                                    <img src="{{ asset('storage/' . $similar->cover_image) }}" alt="{{ $similar->trans('title') }}">
                                @else
                                    <div class="placeholder"><span>Görsel</span></div>
                                @endif
                            </div>
                            <div class="project-card-title">{{ $similar->trans('title') }}</div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

    </div>
</div>

@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css">
<style>
.project-detail { padding: 24px 0; }

.project-detail-header {
    display: flex;
    align-items: baseline;
    justify-content: space-between;
    margin-bottom: 24px;
    gap: 16px;
}

.project-nav {
    display: flex;
    gap: 20px;
    flex-shrink: 0;
}

.project-nav-link {
    font-size: 11px;
    letter-spacing: 1px;
    text-transform: uppercase;
    color: #aaa;
    text-decoration: none;
    transition: color 0.15s;
}
.project-nav-link:hover { color: #111; }

.project-detail-body {
    display: grid;
    grid-template-columns: 24% 24% 49%;
    gap: 12px;
    margin-bottom: 40px;
}

.project-detail-title {
    font-size: 18px;
    font-weight: 400;
    line-height: 1.35;
    color: #111;
    margin-bottom: 20px;
}

.project-subtitle {
    font-size: 12px;
    font-weight: 300;
    color: #555;
    margin: 0 0 20px;
    line-height: 1.6;
}

.project-col-1 { align-self: start; position: relative; }
.project-col-2 { align-self: start; position: relative; }
.project-col-3 { align-self: start; position: relative; }

.col-inner { overflow: hidden; }

.project-expand-btn {
    display: none;
    width: 14px;
    height: 14px;
    background: #fff;
    color: #111;
    border: 1px solid #6d6868;
    border-radius: 50%;
    font-size: 10px;
    line-height: 1;
    cursor: pointer;
    align-items: center;
    justify-content: center;
    margin-top: 6px;
    margin-left: auto;
    transition: background 0.15s, color 0.15s;
}
.project-expand-btn:hover { background: #111; color: #fff; border-color: #111; }
.project-expand-btn.visible { display: flex; }

.project-meta-list {
    display: block;
    margin: 0 0 24px;
    padding: 0;
}
.project-meta-list dt {
    font-size: 10px;
    font-weight: 500;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    color: #aaa;
    margin-top: 10px;
}
.project-meta-list dt:first-child { margin-top: 0; }
.project-meta-list dd {
    font-size: 12px;
    font-weight: 300;
    color: #333;
    margin: 3px 0 0;
    line-height: 1.4;
}

.project-team-section { margin-bottom: 20px; }
.project-team-label {
    font-size: 10px;
    font-weight: 500;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    color: #aaa;
    margin-bottom: 6px;
}
.project-team-name {
    font-size: 12px;
    font-weight: 300;
    color: #333;
    line-height: 1.7;
}

.project-col-3 {
    font-size: 13px;
    line-height: 1.85;
    color: #333;
    font-weight: 300;
}

.project-video {
    margin-top: 32px;
    position: relative;
    padding-bottom: 56.25%;
    height: 0;
    overflow: hidden;
}
.project-video iframe {
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
}

.news-gallery-item { display: block; overflow: hidden; cursor: zoom-in; }
.news-gallery-item img { transition: transform 0.3s ease; }
.news-gallery-item:hover img { transform: scale(1.03); }

.similar-projects {
    margin-top: 60px;
    padding-top: 32px;
    border-top: 1px solid #e8e8e8;
}
.similar-projects-title {
    font-size: 11px;
    font-weight: 500;
    letter-spacing: 2px;
    text-transform: uppercase;
    color: #999;
    margin-bottom: 24px;
}
.projects-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
}
@media (max-width: 1024px) {
    .projects-grid { grid-template-columns: repeat(3, 1fr); }
}
@media (max-width: 600px) {
    .projects-grid { grid-template-columns: repeat(2, 1fr); gap: 8px; }
}

@media (max-width: 768px) {
    .project-detail-header { flex-direction: column; gap: 8px; }
    .project-detail-body { grid-template-columns: 1fr; gap: 0; }
    .news-gallery { grid-template-columns: repeat(2, 1fr); }
    .project-meta-list { margin-bottom: 0; }
    .project-col-2 { margin-top: 20px; }
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

// Kolon hizalama + kırpma + toggle (tüm kolonlar)
window.addEventListener('load', function () {
    requestAnimationFrame(function () {
        if (window.innerWidth <= 768) return;

        var cols = [
            { wrap: document.querySelector('.project-col-1'), inner: document.getElementById('col1Inner'), btn: document.getElementById('expandBtn1') },
            { wrap: document.querySelector('.project-col-2'), inner: document.getElementById('col2Inner'), btn: document.getElementById('expandBtn2') },
            { wrap: document.querySelector('.project-col-3'), inner: document.getElementById('col3Inner'), btn: document.getElementById('expandBtn3') },
        ];

        if (cols.some(function(c){ return !c.wrap || !c.inner || !c.btn; })) return;

        // Doğal içerik yükseklikleri (align-self:start sayesinde gerçek değer)
        var heights = cols.map(function(c){ return c.inner.scrollHeight; });

        // Referans yüksekliği = col1 ve col2'nin max'ı (meta veriler belirler)
        var refHeight = Math.max(heights[0], heights[1]);

        cols.forEach(function(c, i) {
            var naturalH = heights[i];

            // Alt hizalama: kısa kolonlara padding-top ekle
            var pt = Math.max(0, refHeight - naturalH);
            c.wrap.style.paddingTop = pt + 'px';

            // Overflow yoksa buton gösterme
            if (naturalH <= refHeight) return;

            // Kırp ve buton göster
            c.inner.style.overflow  = 'hidden';
            c.inner.style.maxHeight = refHeight + 'px';
            c.inner.style.transition = 'max-height 0.4s ease';
            c.btn.classList.add('visible');

            var open = false;
            c.btn.addEventListener('click', (function(cw, ci, cn, cb, cpt) {
                return function() {
                    open = !open;
                    ci.style.maxHeight = open ? cn + 'px' : refHeight + 'px';
                    cb.textContent = open ? '−' : '+';
                    cw.style.paddingTop = open ? '0' : cpt + 'px';
                };
            })(c.wrap, c.inner, naturalH, c.btn, pt));
        });
    });
});
</script>
@endpush
