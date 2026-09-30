@extends('layouts.site')

@section('title', __('site.projects.title') . ' — CBA')

@section('content')

<div class="container">
    <div class="news-page">

        <div class="projects-page-header">
            <h1 class="news-page-title" style="margin:0;">{{ __('site.projects.title') }}</h1>
            {{-- Kategori Filtresi --}}
            @if($categories->count())
                @php $activeCategory = request('category'); @endphp
                <select class="project-filter-select" onchange="window.location.href=this.value">
                    <option value="{{ app()->getLocale() === 'en' ? url('en/projects') : route('projects.index') }}"
                        {{ !$activeCategory ? 'selected' : '' }}>
                        {{ app()->getLocale() === 'en' ? 'All' : 'Tümü' }}
                    </option>
                    @foreach($categories as $cat)
                        @php
                            $filterUrl = (app()->getLocale() === 'en' ? url('en/projects') : route('projects.index'))
                                . '?category=' . $cat->slug;
                        @endphp
                        <option value="{{ $filterUrl }}" {{ $activeCategory === $cat->slug ? 'selected' : '' }}>
                            {{ $cat->trans('name') }}
                        </option>
                    @endforeach
                </select>
            @endif
        </div>

        <div class="projects-grid" id="projectsGrid">
            @forelse($projects as $project)
                @php
                    $projectUrl = app()->getLocale() === 'en'
                        ? url('en/projects/' . $project->slug)
                        : route('projects.show', $project->slug);
                @endphp
                <a href="{{ $projectUrl }}" class="project-card">
                    <div class="project-card-image">
                        @if($project->cover_image)
                            <img src="{{ asset('storage/' . $project->cover_image) }}" alt="{{ $project->trans('title') }}" loading="lazy">
                        @else
                            <div class="placeholder"><span>Görsel</span></div>
                        @endif
                    </div>
                    <div class="project-card-title">{{ $project->trans('title') }}</div>
                </a>
            @empty
                <p class="empty-state" style="grid-column:1/-1;">{{ app()->getLocale() === 'en' ? 'No projects yet.' : 'Henüz proje eklenmemiş.' }}</p>
            @endforelse
        </div>

        {{-- Infinite scroll sentinel --}}
        <div id="scrollSentinel" style="height:1px;"></div>
        <div id="loadingSpinner" style="display:none;text-align:center;padding:24px;">
            <span style="font-size:11px;letter-spacing:2px;color:#aaa;text-transform:uppercase;">Yükleniyor...</span>
        </div>

    </div>
</div>

@endsection

@push('styles')
<style>
.projects-page-header {
    display: flex;
    align-items: baseline;
    justify-content: space-between;
    gap: 16px;
    margin-bottom: 32px;
}
.project-filter-select {
    padding: 7px 32px 7px 12px;
    border: 1px solid #ddd;
    font-size: 12px;
    font-family: inherit;
    font-weight: 400;
    color: #333;
    background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%23999' stroke-width='1.2' fill='none' stroke-linecap='round'/%3E%3C/svg%3E") no-repeat right 10px center;
    appearance: none;
    cursor: pointer;
    outline: none;
    transition: border-color 0.15s;
    min-width: 200px;
}
.project-filter-select:focus { border-color: #111; }
.projects-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    margin-bottom: 20px;
}
@media (max-width: 1024px) {
    .projects-grid { grid-template-columns: repeat(3, 1fr); }
}
@media (max-width: 600px) {
    .projects-grid { grid-template-columns: repeat(2, 1fr); gap: 8px; }
}
</style>
@endpush

@push('scripts')
<script>
(function () {
    var nextUrl  = @json($projects->nextPageUrl());
    var loading  = false;
    var grid     = document.getElementById('projectsGrid');
    var sentinel = document.getElementById('scrollSentinel');
    var spinner  = document.getElementById('loadingSpinner');

    if (!nextUrl) return; // tek sayfa, infinite scroll gerek yok

    function loadMore() {
        if (loading || !nextUrl) return;
        loading = true;
        spinner.style.display = 'block';

        fetch(nextUrl, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(function (data) {
            data.data.forEach(function (p) {
                var a = document.createElement('a');
                a.href = p.url;
                a.className = 'project-card';
                a.innerHTML = '<div class="project-card-image">'
                    + (p.cover_image
                        ? '<img src="' + p.cover_image + '" alt="' + p.title + '" loading="lazy">'
                        : '<div class="placeholder"><span>Görsel</span></div>')
                    + '</div>'
                    + '<div class="project-card-title">' + p.title + '</div>';
                grid.appendChild(a);
            });

            nextUrl = data.next_page_url || null;
            loading = false;
            spinner.style.display = 'none';
        })
        .catch(function () { loading = false; spinner.style.display = 'none'; });
    }

    var observer = new IntersectionObserver(function (entries) {
        if (entries[0].isIntersecting) loadMore();
    }, { rootMargin: '200px' });

    observer.observe(sentinel);
})();
</script>
@endpush
