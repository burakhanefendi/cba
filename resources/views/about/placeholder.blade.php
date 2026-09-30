@extends('layouts.site')

@section('title', __('site.about.' . $page) . ' — CBA')

@section('content')
<div class="container">
    <div class="profile-page">
        @include('about._nav', ['current' => $page])
    </div>
</div>
@endsection
