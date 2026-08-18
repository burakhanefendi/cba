@extends('layouts.site')

@section('title', __('site.projects.title') . ' — CBA')

@section('content')

<div class="container">
    <div class="news-page">

        <h1 class="news-page-title">{{ __('site.projects.title') }}</h1>

        {{-- Kategori Filtresi --}}
        @if($categories->count())
            <div class="project-filter">
                @php $activeCategory = request('category'); @endphp
                <a href="{{ app()->getLocale() === 'en' ? url('en/projects') : route('projects.index') }}"
                   class="filter-tag {{ !$activeCategory ? 'active' : '' }}">
                    {{ app()->getLocale() === 'en' ? 'All' : 'Tümü' }}
                </a>
                @foreach($categories as $cat)
                    @php
                        $filterUrl = (app()->getLocale() === 'en' ? url('en/projects') : route('projects.index'))
                            . '?category=' . $cat->slug;
                    @endphp
                    <a href="{{ $filterUrl }}"
                       class="filter-tag {{ $activeCategory === $cat->slug ? 'active' : '' }}">
                        {{ $cat->trans('name') }}
                    </a>
                @endforeach
            </div>
        @endif

        @if($projects->count())
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

            <div class="pagination-wrap">
                {{ $projects->links() }}
            </div>
        @else
            <p class="empty-state">{{ app()->getLocale() === 'en' ? 'No projects yet.' : 'Henüz proje eklenmemiş.' }}</p>
        @endif

    </div>
</div>

@endsection

@push('styles')
<style>
.project-filter {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 32px;
}
.filter-tag {
    display: inline-block;
    padding: 6px 16px;
    border: 1px solid #ddd;
    font-size: 12px;
    font-weight: 400;
    letter-spacing: 0.5px;
    color: #666;
    text-decoration: none;
    transition: all 0.15s;
}
.filter-tag:hover,
.filter-tag.active {
    background: #111;
    border-color: #111;
    color: #fff;
}
.projects-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 12px;
    margin-bottom: 40px;
}
@media (max-width: 1024px) {
    .projects-grid { grid-template-columns: repeat(3, 1fr); }
}
@media (max-width: 600px) {
    .projects-grid { grid-template-columns: repeat(2, 1fr); gap: 8px; }
}
</style>
@endpush
