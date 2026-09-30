@extends('layouts.site')

@section('title', __('site.about.team') . ' — CBA')

@section('content')
<div class="container">
    <div class="profile-page">
        @include('about._nav', ['current' => 'team'])

        <div class="team-list">
            @forelse($members as $member)
                <div class="team-row">
                    <div class="team-photo">
                        @if($member->photo)
                            <img src="{{ asset('storage/' . $member->photo) }}" alt="{{ $member->name }}">
                        @else
                            <div class="team-photo-placeholder"></div>
                        @endif
                    </div>
                    <div class="team-meta">
                        <div class="team-name">{{ $member->name }}</div>
                        @if($member->trans('title'))
                            <div class="team-title">{{ $member->trans('title') }}</div>
                        @endif
                    </div>
                    <div class="team-bio">{!! nl2br(e($member->trans('bio'))) !!}</div>
                </div>
            @empty
            @endforelse
        </div>
    </div>
</div>
@endsection
