@extends('layouts.site')

@section('title', __('site.about.' . $current) . ' — CBA')

@section('content')
<div class="container">
    <div class="profile-page">
        @include('about._nav', ['current' => $current])

        <div class="entry-grid">
            @foreach($entries as $entry)
                @php $isEn = app()->getLocale() === 'en'; @endphp
                <article class="entry-card">
                    <div class="entry-card-image">
                        @if($entry->image)
                            <img src="{{ asset('storage/' . $entry->image) }}" alt="{{ $entry->trans('title') }}">
                        @else
                            <div class="entry-card-placeholder"></div>
                        @endif
                    </div>
                    <div class="entry-card-body">
                        <h2 class="entry-card-title">{{ $entry->trans('title') }}</h2>
                        @if($entry->trans('subtitle'))
                            <p class="entry-card-meta">{{ $entry->trans('subtitle') }}</p>
                        @endif

                        <ul class="entry-card-links">
                            @foreach($entry->projects as $project)
                                @php
                                    $projectUrl = $isEn
                                        ? url('en/projects/' . $project->slug)
                                        : route('projects.show', $project->slug);
                                @endphp
                                <li>
                                    <a href="{{ $projectUrl }}">
                                        <span class="link-icon link-icon-project">P</span>
                                        {{ $project->trans('title') }}
                                    </a>
                                </li>
                            @endforeach

                            @if($entry->pdf)
                                <li>
                                    <a href="{{ asset('storage/' . $entry->pdf) }}" target="_blank" rel="noopener">
                                        <span class="link-icon link-icon-pdf">
                                            <svg width="12" height="14" viewBox="0 0 12 14" fill="none">
                                                <path d="M1 1.5h6.5L11 5v7.5H1V1.5Z" stroke="currentColor" stroke-width="1.1"/>
                                                <path d="M7.5 1.5V5H11" stroke="currentColor" stroke-width="1.1"/>
                                            </svg>
                                        </span>
                                        {{ __('site.about.pdf') }}
                                    </a>
                                </li>
                            @endif

                            @if($entry->external_url)
                                <li>
                                    <a href="{{ $entry->external_url }}" target="_blank" rel="noopener">
                                        <span class="link-icon link-icon-link">L</span>
                                        {{ __('site.about.go_link') }}
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</div>
@endsection
