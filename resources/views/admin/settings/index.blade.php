@extends('admin.layout')

@section('title', 'Ayarlar')

@section('content')

<div class="page-header">
    <h2>Ayarlar</h2>
</div>

<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="form-card" style="max-width: 100%;">

        {{-- Logo --}}
        <div class="settings-section">
            <div class="settings-section-title">Logo</div>

            <div class="form-group">
                <label>Mevcut Logo</label>
                @if($settings->get('logo'))
                    <div class="logo-preview">
                        <img src="{{ asset('storage/' . $settings->get('logo')) }}" alt="Logo">
                    </div>
                @else
                    <div class="logo-empty">Logo yüklenmemiş</div>
                @endif
            </div>

            <div class="form-group">
                <label>Logo Güncelle <span style="font-weight:300;text-transform:none;letter-spacing:0;color:#bbb;">(PNG, JPG, SVG — maks. 2MB)</span></label>
                <input type="file" name="logo" accept="image/*">
                @error('logo') <div class="form-error">{{ $message }}</div> @enderror
            </div>
        </div>

        {{-- Genel Bilgiler --}}
        <div class="settings-section">
            <div class="settings-section-title">Genel Bilgiler</div>

            <div class="form-group">
                <label>Site Başlığı</label>
                <input type="text" name="site_title" value="{{ $settings->get('site_title', 'CBA — Cafer Bozkurt Architecture Istanbul') }}">
            </div>

            <div class="form-group">
                <label>Site Açıklaması</label>
                <textarea name="site_description" rows="3">{{ $settings->get('site_description') }}</textarea>
            </div>
        </div>

        {{-- İletişim --}}
        <div class="settings-section">
            <div class="settings-section-title">İletişim Bilgileri</div>

            <div class="form-group">
                <label>E-posta</label>
                <input type="text" name="contact_email" value="{{ $settings->get('contact_email') }}">
            </div>

            <div class="form-group">
                <label>Telefon</label>
                <input type="text" name="contact_phone" value="{{ $settings->get('contact_phone') }}">
            </div>

            <div class="form-group">
                <label>Adres</label>
                <textarea name="contact_address" rows="3">{{ $settings->get('contact_address') }}</textarea>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Kaydet</button>
        </div>

    </div>
</form>

@endsection

@push('styles')
<style>
.settings-section {
    margin-bottom: 36px;
    padding-bottom: 36px;
    border-bottom: 1px solid #f0f0f0;
}
.settings-section:last-of-type {
    border-bottom: none;
    margin-bottom: 24px;
}
.settings-section-title {
    font-size: 10px;
    letter-spacing: 3px;
    text-transform: uppercase;
    color: #999;
    margin-bottom: 20px;
}
.logo-preview {
    margin-top: 8px;
    margin-bottom: 16px;
    padding: 16px;
    border: 1px solid #e5e5e5;
    display: inline-block;
    background: #fafafa;
}
.logo-preview img {
    max-height: 80px;
    max-width: 300px;
    object-fit: contain;
}
.logo-empty {
    font-size: 12px;
    color: #bbb;
    padding: 12px 0;
}
</style>
@endpush
