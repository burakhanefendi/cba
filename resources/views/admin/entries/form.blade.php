@extends('admin.layout')

@section('title', isset($entry) ? $labels['singular'] . ' Düzenle' : 'Yeni ' . $labels['singular'])

@section('content')

<div class="page-header">
    <h2>{{ isset($entry) ? $labels['singular'] . ' Düzenle' : 'Yeni ' . $labels['singular'] }}</h2>
    <a href="{{ route($routes . '.index') }}" class="btn btn-secondary">Geri</a>
</div>

<form action="{{ isset($entry) ? route($routes . '.update', $entry) : route($routes . '.store') }}"
      method="POST" enctype="multipart/form-data">
    @csrf
    @if(isset($entry)) @method('PUT') @endif

    <div class="news-form-grid">
        <div class="form-card">
            <div class="lang-tabs">
                <button type="button" class="lang-tab active" data-lang="tr">TR</button>
                <button type="button" class="lang-tab" data-lang="en">EN</button>
            </div>

            <div class="lang-panel" id="panel-tr">
                <div class="form-group">
                    <label>Başlık *</label>
                    <input type="text" name="title" value="{{ old('title', $entry->title ?? '') }}" required>
                    @error('title')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label>Açıklama <span class="label-hint">— dergi, tarih, sayfa vb.</span></label>
                    <textarea name="subtitle" rows="4">{{ old('subtitle', $entry->subtitle ?? '') }}</textarea>
                </div>
            </div>

            <div class="lang-panel" id="panel-en" style="display:none;">
                <div class="form-group">
                    <label>Title (EN)</label>
                    <input type="text" name="title_en" value="{{ old('title_en', $entry->title_en ?? '') }}">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label>Description (EN)</label>
                    <textarea name="subtitle_en" rows="4">{{ old('subtitle_en', $entry->subtitle_en ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <div class="news-sidebar">
            <div class="form-card">
                <div class="form-group" style="margin-bottom:0;">
                    <label>Görsel</label>
                    <div id="coverPreview" style="margin-bottom:8px;">
                        @if(isset($entry) && $entry->image)
                            <div class="media-preview-item" style="position:relative;display:inline-block;max-width:100%;">
                                <img src="{{ asset('storage/' . $entry->image) }}" style="width:100%;aspect-ratio:4/3;object-fit:cover;display:block;">
                                <button type="button" class="media-preview-remove" onclick="clearCover()">×</button>
                            </div>
                        @endif
                    </div>
                    <input type="hidden" name="image" id="coverImageInput" value="{{ old('image', $entry->image ?? '') }}">
                    <div style="display:flex;gap:6px;">
                        <button type="button" class="btn btn-secondary btn-sm"
                            onclick="MediaPicker.open({ multiple: false, onSelect: items => setCover(items[0]) })">
                            Kütüphaneden Seç
                        </button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="clearCover()">Kaldır</button>
                    </div>
                </div>
            </div>

            <div class="form-card">
                <div class="form-group">
                    <label>İlgili Projeler</label>
                    <div class="category-checklist" style="max-height:220px;overflow:auto;">
                        @forelse($projects as $project)
                            <label class="check-item">
                                <input type="checkbox" name="project_ids[]" value="{{ $project->id }}"
                                    {{ in_array($project->id, $selected) ? 'checked' : '' }}>
                                <span>{{ $project->title }}</span>
                            </label>
                        @empty
                            <span style="font-size:12px;color:#aaa;">Henüz proje yok.</span>
                        @endforelse
                    </div>
                </div>
                <div class="form-group">
                    <label>PDF</label>
                    @if(isset($entry) && $entry->pdf)
                        <div style="font-size:12px;margin-bottom:8px;">
                            <a href="{{ asset('storage/' . $entry->pdf) }}" target="_blank">Mevcut PDF</a>
                            <label style="display:flex;align-items:center;gap:6px;margin-top:6px;font-size:12px;">
                                <input type="checkbox" name="remove_pdf" value="1"> Kaldır
                            </label>
                        </div>
                    @endif
                    <input type="file" name="pdf" accept="application/pdf">
                </div>
                <div class="form-group">
                    <label>Dış Link <span class="label-hint">— LINKE GIT</span></label>
                    <input type="url" name="external_url" value="{{ old('external_url', $entry->external_url ?? '') }}" placeholder="https://...">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <div class="form-check">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                            {{ old('is_active', $entry->is_active ?? true) ? 'checked' : '' }}>
                        <label for="is_active">Aktif</label>
                    </div>
                </div>
            </div>

            <div class="form-actions" style="margin:0;">
                <button type="submit" class="btn btn-primary">Kaydet</button>
                <a href="{{ route($routes . '.index') }}" class="btn btn-secondary">İptal</a>
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
.label-hint { font-weight: 300; font-size: 11px; text-transform: none; letter-spacing: 0; color: #bbb; }
.media-preview-item { position: relative; }
.media-preview-remove {
    position: absolute; top: 2px; right: 2px;
    width: 18px; height: 18px;
    background: rgba(0,0,0,0.55); color: #fff;
    border: none; font-size: 13px; line-height: 1;
    cursor: pointer; display: flex; align-items: center; justify-content: center; padding: 0;
}
.category-checklist { display: flex; flex-direction: column; gap: 6px; margin-top: 6px; }
.check-item {
    display: flex; align-items: center; gap: 8px;
    font-size: 13px; font-weight: 400; color: #333; cursor: pointer;
}
.check-item input { width: auto; accent-color: #111; }
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
function setCover(item) {
    const path = item.url.split('/storage/')[1];
    document.getElementById('coverImageInput').value = path;
    document.getElementById('coverPreview').innerHTML = `<div class="media-preview-item" style="position:relative;display:inline-block;max-width:100%;">
        <img src="${item.url}" style="width:100%;aspect-ratio:4/3;object-fit:cover;display:block;">
        <button type="button" class="media-preview-remove" onclick="clearCover()">×</button>
    </div>`;
}
function clearCover() {
    document.getElementById('coverImageInput').value = '';
    document.getElementById('coverPreview').innerHTML = '';
}
</script>
@endpush
