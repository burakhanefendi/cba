@extends('admin.layout')

@section('title', isset($member) ? 'Ekip Üyesi Düzenle' : 'Yeni Ekip Üyesi')

@section('content')

<div class="page-header">
    <h2>{{ isset($member) ? 'Ekip Üyesi Düzenle' : 'Yeni Ekip Üyesi' }}</h2>
    <a href="{{ route('admin.team.index') }}" class="btn btn-secondary">Geri</a>
</div>

<form action="{{ isset($member) ? route('admin.team.update', $member) : route('admin.team.store') }}" method="POST" id="teamForm">
    @csrf
    @if(isset($member)) @method('PUT') @endif

    <div class="news-form-grid">
        <div class="form-card">
            <div class="form-group">
                <label>Ad Soyad *</label>
                <input type="text" name="name" value="{{ old('name', $member->name ?? '') }}" required>
                @error('name')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="lang-tabs">
                <button type="button" class="lang-tab active" data-lang="tr">TR</button>
                <button type="button" class="lang-tab" data-lang="en">EN</button>
            </div>

            <div class="lang-panel" id="panel-tr">
                <div class="form-group">
                    <label>Ünvan</label>
                    <input type="text" name="title" value="{{ old('title', $member->title ?? '') }}" placeholder="Mimar / Arkeolog">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label>Biyografi</label>
                    <textarea name="bio" rows="8">{{ old('bio', $member->bio ?? '') }}</textarea>
                </div>
            </div>

            <div class="lang-panel" id="panel-en" style="display:none;">
                <div class="form-group">
                    <label>Title (EN)</label>
                    <input type="text" name="title_en" value="{{ old('title_en', $member->title_en ?? '') }}">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label>Biography (EN)</label>
                    <textarea name="bio_en" rows="8">{{ old('bio_en', $member->bio_en ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <div class="news-sidebar">
            <div class="form-card">
                <div class="form-group" style="margin-bottom:0;">
                    <label>Fotoğraf</label>
                    <div id="photoPreview" style="margin-bottom:8px;">
                        @if(isset($member) && $member->photo)
                            <div class="media-preview-item" style="position:relative;display:inline-block;max-width:160px;">
                                <img src="{{ asset('storage/' . $member->photo) }}" style="width:100%;aspect-ratio:1;object-fit:cover;display:block;">
                                <button type="button" class="media-preview-remove" onclick="clearPhoto()">×</button>
                            </div>
                        @endif
                    </div>
                    <input type="hidden" name="photo" id="photoInput" value="{{ old('photo', $member->photo ?? '') }}">
                    <div style="display:flex;gap:6px;">
                        <button type="button" class="btn btn-secondary btn-sm"
                            onclick="MediaPicker.open({ multiple: false, onSelect: items => setPhoto(items[0]) })">
                            Kütüphaneden Seç
                        </button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="clearPhoto()">Kaldır</button>
                    </div>
                </div>
            </div>

            <div class="form-card">
                <div class="form-group">
                    <label>Sıra</label>
                    <input type="number" name="order" value="{{ old('order', $member->order ?? 0) }}" style="width:90px;">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <div class="form-check">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                            {{ old('is_active', $member->is_active ?? true) ? 'checked' : '' }}>
                        <label for="is_active">Aktif</label>
                    </div>
                </div>
            </div>

            <div class="form-actions" style="margin:0;">
                <button type="submit" class="btn btn-primary">Kaydet</button>
                <a href="{{ route('admin.team.index') }}" class="btn btn-secondary">İptal</a>
            </div>
        </div>
    </div>
</form>

@endsection

@push('styles')
<style>
.news-form-grid {
    display: grid;
    grid-template-columns: minmax(0, 720px) 300px;
    justify-content: start;
    gap: 16px;
    align-items: start;
}
.news-sidebar { display: flex; flex-direction: column; gap: 16px; }
.news-sidebar .form-card { margin-bottom: 0; max-width: none; }
.lang-tabs { display: flex; border-bottom: 1px solid #eee; margin-bottom: 20px; }
.lang-tab {
    padding: 8px 20px; border: none; background: none;
    font-family: inherit; font-size: 12px; font-weight: 600;
    letter-spacing: 1px; color: #aaa; cursor: pointer;
    border-bottom: 2px solid transparent; margin-bottom: -1px;
}
.lang-tab.active { color: #111; border-bottom-color: #111; }
.media-preview-item { position: relative; }
.media-preview-remove {
    position: absolute; top: 2px; right: 2px;
    width: 18px; height: 18px;
    background: rgba(0,0,0,0.55); color: #fff;
    border: none; font-size: 13px; line-height: 1;
    cursor: pointer; display: flex; align-items: center; justify-content: center; padding: 0;
}
</style>
@endpush

@include('admin.partials.media-picker')

@push('scripts')
<script>
document.querySelectorAll('.lang-tab').forEach(tab => {
    tab.addEventListener('click', function () {
        document.querySelectorAll('.lang-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.lang-panel').forEach(p => p.style.display = 'none');
        this.classList.add('active');
        document.getElementById('panel-' + this.dataset.lang).style.display = '';
    });
});
function setPhoto(item) {
    const path = item.url.split('/storage/')[1];
    document.getElementById('photoInput').value = path;
    document.getElementById('photoPreview').innerHTML = `<div class="media-preview-item" style="position:relative;display:inline-block;max-width:160px;">
        <img src="${item.url}" style="width:100%;aspect-ratio:1;object-fit:cover;display:block;">
        <button type="button" class="media-preview-remove" onclick="clearPhoto()">×</button>
    </div>`;
}
function clearPhoto() {
    document.getElementById('photoInput').value = '';
    document.getElementById('photoPreview').innerHTML = '';
}
</script>
@endpush
