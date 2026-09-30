@extends('layouts.site')

@section('title', __('site.contact.title') . ' — CBA')

@section('content')
<div class="container">
    <div class="contact-page">
        <h1 class="news-page-title">{{ __('site.contact.title') }}</h1>

        <div class="contact-layout">
            <div class="contact-info">
                <div class="contact-block">
                    <div class="contact-label">{{ __('site.contact.address') }}</div>
                    <a href="{{ $mapUrl }}" target="_blank" rel="noopener" class="contact-value">{!! nl2br(e($address)) !!}</a>
                </div>

                <div class="contact-block">
                    <div class="contact-label">{{ __('site.contact.phone') }}</div>
                    <a href="{{ $phoneTel }}" class="contact-value">{{ $phoneDisplay }}</a>
                </div>

                <div class="contact-block">
                    <div class="contact-label">{{ __('site.contact.email') }}</div>
                    <a href="mailto:{{ $email }}" class="contact-value">{{ $email }}</a>
                </div>

                <a href="{{ $mapUrl }}" target="_blank" rel="noopener" class="contact-map-link">{{ __('site.contact.map') }}</a>
            </div>

            <div class="contact-map" id="contactMap" role="link" tabindex="0" data-map-url="{{ $mapUrl }}" aria-label="{{ __('site.contact.map') }}"></div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
@endpush

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    (function () {
        const el = document.getElementById('contactMap');
        if (!el || typeof L === 'undefined') return;

        const map = L.map(el, {
            scrollWheelZoom: false,
            attributionControl: true,
            zoomControl: false,
            dragging: false,
            doubleClickZoom: false,
            boxZoom: false,
            keyboard: false,
            tap: false,
        }).setView([41.069054, 29.0435878], 17);

        if (map.attributionControl) {
            map.attributionControl.setPrefix(false);
        }

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap',
        }).addTo(map);

        L.circleMarker([41.069054, 29.0435878], {
            radius: 7,
            color: '#111',
            weight: 0,
            fillColor: '#111',
            fillOpacity: 1,
        }).addTo(map);

        const openMap = function () {
            const url = el.getAttribute('data-map-url');
            if (url) window.open(url, '_blank', 'noopener');
        };
        el.addEventListener('click', openMap);
        el.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                openMap();
            }
        });

        window.addEventListener('resize', function () { map.invalidateSize(); });
    })();
</script>
@endpush
