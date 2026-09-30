@extends('layouts.site')

@section('title', __('site.nav.profile') . ' — CBA')

@section('content')
<div class="container">
    <div class="profile-page">
        @include('about._nav', ['current' => $current])

        <div class="profile-hero">
            @if($image)
                <img src="{{ asset('storage/' . $image) }}" alt="{{ $heading }}">
            @else
                <div class="profile-hero-placeholder"><span>Görsel</span></div>
            @endif
        </div>

        @if($body)
            <div class="profile-body">
                {!! $body !!}
            </div>
        @endif
    </div>
</div>
@endsection
