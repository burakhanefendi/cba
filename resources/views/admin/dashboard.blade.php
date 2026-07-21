@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-number">{{ $stats['projects'] }}</div>
        <div class="stat-label">Proje</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ $stats['categories'] }}</div>
        <div class="stat-label">Kategori</div>
    </div>
    <div class="stat-card">
        <div class="stat-number">{{ $stats['team'] }}</div>
        <div class="stat-label">Ekip Üyesi</div>
    </div>
    <div class="stat-card {{ $stats['messages'] > 0 ? 'highlight' : '' }}">
        <div class="stat-number">{{ $stats['messages'] }}</div>
        <div class="stat-label">Okunmamış Mesaj</div>
    </div>
</div>

<p class="section-title">Hızlı Erişim</p>

<div class="quick-links">
    <a href="{{ route('admin.projects.create') }}" class="quick-link">
        <span>Yeni Proje Ekle</span>
        <span class="icon">+</span>
    </a>
    <a href="{{ route('admin.team.create') }}" class="quick-link">
        <span>Ekip Üyesi Ekle</span>
        <span class="icon">+</span>
    </a>
    <a href="{{ route('admin.messages.index') }}" class="quick-link">
        <span>Mesajları Gör</span>
        <span class="icon">→</span>
    </a>
</div>

@endsection
