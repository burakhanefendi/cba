@extends('layouts.site')

@section('title', __('site.nav.profile') . ' — CBA')

@section('content')
<div class="container">
    <div class="profile-page">
        @include('about._nav', ['current' => 'profile'])

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

@push('styles')
<style>
.profile-body {
    -webkit-columns: 2;
    -moz-columns: 2;
    columns: 2;
    -webkit-column-gap: 40px;
    column-gap: 40px;
    -webkit-column-fill: balance;
    column-fill: balance;
}
.profile-body p,
.profile-body div {
    break-inside: auto;
    -webkit-column-break-inside: auto;
}
@media (max-width: 768px) {
    .profile-body { -webkit-columns: 1; columns: 1; }
}
</style>
@endpush
