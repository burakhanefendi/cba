@extends('layouts.site')

@section('title', __('site.news.title') . ' — CBA')

@section('content')

<div class="container">
    <div class="news-page">

        <h1 class="news-page-title">{{ __('site.news.title') }}</h1>

        @if($news->count())
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
                            @if($item->trans('excerpt'))
                                <p class="news-item-excerpt">{{ $item->trans('excerpt') }}</p>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="pagination-wrap">
                {{ $news->links() }}
            </div>
        @else
            <p class="empty-state">{{ app()->getLocale() === 'en' ? 'No news yet.' : 'Henüz haber eklenmemiş.' }}</p>
        @endif

    </div>
</div>

@endsection
