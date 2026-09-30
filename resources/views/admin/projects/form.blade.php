@extends('admin.layout')

@section('title', isset($project) ? 'Proje Düzenle' : 'Yeni Proje')

@section('content')

<div class="page-header">
    <h2>{{ isset($project) ? 'Proje Düzenle' : 'Yeni Proje' }}</h2>
    <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">Geri</a>
</div>

<form action="{{ isset($project) ? route('admin.projects.update', $project) : route('admin.projects.store') }}"
      method="POST" enctype="multipart/form-data" id="projectForm">
    @csrf
    @if(isset($project)) @method('PUT') @endif

    <div class="news-form-grid">

        {{-- Sol: İçerik --}}
        <div class="form-card">
            <div class="lang-tabs">
                <button type="button" class="lang-tab active" data-lang="tr">TR</button>
                <button type="button" class="lang-tab" data-lang="en">EN</button>
            </div>

            {{-- TR --}}
            <div class="lang-panel" id="panel-tr">
                <div class="form-group">
                    <label>Başlık *</label>
                    <input type="text" name="title" value="{{ old('title', $project->title ?? '') }}" required>
                    @error('title')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label>Alt Başlık <span class="label-hint">— kısa açıklayıcı metin</span></label>
                    <input type="text" name="subtitle" value="{{ old('subtitle', $project->subtitle ?? '') }}">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label>Açıklama</label>
                    <textarea name="description" id="content-tr" style="display:none;">{{ old('description', $project->description ?? '') }}</textarea>
                    <div id="editor-tr" class="quill-editor"></div>
                </div>
            </div>

            {{-- EN --}}
            <div class="lang-panel" id="panel-en" style="display:none;">
                <div class="form-group">
                    <label>Title (EN)</label>
                    <input type="text" name="title_en" value="{{ old('title_en', $project->title_en ?? '') }}">
                </div>
                <div class="form-group">
                    <label>Subtitle (EN)</label>
                    <input type="text" name="subtitle_en" value="{{ old('subtitle_en', $project->subtitle_en ?? '') }}">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label>Description (EN)</label>
                    <textarea name="description_en" id="content-en" style="display:none;">{{ old('description_en', $project->description_en ?? '') }}</textarea>
                    <div id="editor-en" class="quill-editor"></div>
                </div>
            </div>
        </div>

        {{-- Sağ: Görseller + Meta --}}
        <div class="news-sidebar">

            {{-- Thumbnail --}}
            <div class="form-card">
                <div class="form-group" style="margin-bottom:0;">
                    <label>Thumbnail <span class="label-hint">— listeleme sayfası görseli</span></label>
                    <div id="coverPreview" style="margin-bottom:8px;">
                        @if(isset($project) && $project->cover_image)
                            <div class="media-preview-item" data-path="{{ $project->cover_image }}" style="position:relative;display:inline-block;max-width:160px;">
                                <img src="{{ asset('storage/' . $project->cover_image) }}" style="width:100%;aspect-ratio:1;object-fit:cover;display:block;">
                                <button type="button" class="media-preview-remove" onclick="clearCover()">×</button>
                            </div>
                        @endif
                    </div>
                    <input type="hidden" name="cover_image" id="coverImageInput" value="{{ old('cover_image', $project->cover_image ?? '') }}">
                    <div style="display:flex;gap:6px;align-items:center;">
                        <button type="button" class="btn btn-secondary btn-sm"
                            onclick="MediaPicker.open({ multiple: false, onSelect: items => setCover(items[0]) })">
                            Kütüphaneden Seç
                        </button>
                        @if(isset($project) && $project->cover_image)
                            <button type="button" class="btn btn-secondary btn-sm" onclick="clearCover()">Kaldır</button>
                        @endif
                    </div>
                    <p style="font-size:11px;color:#aaa;margin:6px 0 0;">Seçilmezse otomatik atanır (slider 1. görsel)</p>
                </div>
            </div>

            {{-- Ana Görseller --}}
            <div class="form-card">
                <div class="form-group" style="margin-bottom:0;">
                    <label>Ana Görsel <span class="label-hint">— birden fazla → slider</span></label>
                    <div class="media-preview-list" id="sliderPreview">
                        @if(isset($project))
                            @foreach($project->sliderImages as $img)
                                <div class="media-preview-item" data-path="{{ $img->image }}">
                                    <img src="{{ asset('storage/' . $img->image) }}">
                                    <button type="button" class="media-preview-remove">×</button>
                                    <input type="hidden" name="slider_paths[]" value="{{ $img->image }}">
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <button type="button" class="btn btn-secondary btn-sm" style="margin-top:8px;"
                        onclick="MediaPicker.open({ multiple: true, onSelect: items => addToPreview('sliderPreview', items) })">
                        Kütüphaneden Seç
                    </button>
                </div>
            </div>

            {{-- Galeri --}}
            <div class="form-card">
                <div class="form-group" style="margin-bottom:0;">
                    <label>Galeri <span class="label-hint">— detay sayfası altında</span></label>
                    <div class="media-preview-list" id="galleryPreview">
                        @if(isset($project))
                            @foreach($project->galleryImages as $img)
                                <div class="media-preview-item" data-path="{{ $img->image }}">
                                    <img src="{{ asset('storage/' . $img->image) }}">
                                    <button type="button" class="media-preview-remove">×</button>
                                    <input type="hidden" name="gallery_paths[]" value="{{ $img->image }}">
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <button type="button" class="btn btn-secondary btn-sm" style="margin-top:8px;"
                        onclick="MediaPicker.open({ multiple: true, onSelect: items => addToPreview('galleryPreview', items) })">
                        Kütüphaneden Seç
                    </button>
                </div>
            </div>

            {{-- Proje Detayları --}}
            <div class="form-card">
                <div class="form-group">
                    <label>Kategori <span class="label-hint">— birden fazla seçilebilir</span></label>
                    @php
                        $selectedCategoryIds = old('category_ids',
                            isset($project) ? $project->categories->pluck('id')->toArray() : []
                        );
                    @endphp
                    <div class="category-checklist">
                        @foreach($categories as $cat)
                            <label class="check-item">
                                <input type="checkbox" name="category_ids[]" value="{{ $cat->id }}"
                                    {{ in_array($cat->id, $selectedCategoryIds) ? 'checked' : '' }}>
                                <span>{{ $cat->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="form-group">
                    <label>Video URL <span class="label-hint">— YouTube/Vimeo linki</span></label>
                    <input type="url" name="video_url" value="{{ old('video_url', $project->video_url ?? '') }}" placeholder="https://...">
                </div>
                <div class="form-group">
                    <label>Yıl <span class="label-hint">— sıralama için</span></label>
                    <input type="number" name="year" value="{{ old('year', $project->year ?? '') }}" placeholder="2024" min="1900" max="2100">
                </div>
            </div>

            {{-- Proje Bilgileri (Serbest Metin) --}}
            <div class="form-card">
                <div class="lang-tabs" id="meta-lang-tabs">
                    <button type="button" class="lang-tab active" data-lang="meta-tr">TR</button>
                    <button type="button" class="lang-tab" data-lang="meta-en">EN</button>
                </div>
                <div class="lang-panel" id="panel-meta-tr">
                    <div class="form-group" style="margin-bottom:0;">
                        <label>Proje Bilgileri <span class="label-hint">— lokasyon, yıl, işveren vb. serbest metin</span></label>
                        <textarea name="meta_text" id="content-meta-tr" style="display:none;">{{ old('meta_text', $project->meta_text ?? '') }}</textarea>
                        <div id="editor-meta-tr" class="quill-editor"></div>
                    </div>
                </div>
                <div class="lang-panel" id="panel-meta-en" style="display:none;">
                    <div class="form-group" style="margin-bottom:0;">
                        <label>Project Info (EN)</label>
                        <textarea name="meta_text_en" id="content-meta-en" style="display:none;">{{ old('meta_text_en', $project->meta_text_en ?? '') }}</textarea>
                        <div id="editor-meta-en" class="quill-editor"></div>
                    </div>
                </div>
            </div>

            {{-- Meta --}}
            <div class="form-card">
                <div class="form-group" style="margin-bottom:0;">
                    <div class="form-check">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                            {{ old('is_active', $project->is_active ?? true) ? 'checked' : '' }}>
                        <label for="is_active">Aktif</label>
                    </div>
                    <div class="form-check" style="margin-top:8px;">
                        <input type="checkbox" name="is_featured" id="is_featured" value="1"
                            {{ old('is_featured', $project->is_featured ?? false) ? 'checked' : '' }}>
                        <label for="is_featured">Anasayfada yayınla</label>
                    </div>
                </div>
            </div>

            {{-- Kaydet --}}
            <div class="form-actions" style="margin:0;">
                <button type="submit" class="btn btn-primary">Kaydet</button>
                <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">İptal</a>
            </div>

        </div>
    </div>

</form>

@endsection

@push('styles')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<style>
.news-form-grid {
    display: grid;
    grid-template-columns: minmax(0, 720px) 300px;
    justify-content: start;
    gap: 16px;
    align-items: start;
}
.news-sidebar {
    display: flex;
    flex-direction: column;
    gap: 16px;
}
.news-sidebar .form-card { margin-bottom: 0; max-width: none; }
.sidebar-row-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
}
.sidebar-row-header label { margin: 0; }
.lang-tabs {
    display: flex;
    border-bottom: 1px solid #eee;
    margin-bottom: 20px;
}
.lang-tab {
    padding: 8px 20px;
    border: none;
    background: none;
    font-family: inherit;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 1px;
    color: #aaa;
    cursor: pointer;
    border-bottom: 2px solid transparent;
    margin-bottom: -1px;
    transition: color 0.15s, border-color 0.15s;
}
.lang-tab.active { color: #111; border-bottom-color: #111; }
.label-hint {
    font-weight: 300;
    font-size: 11px;
    text-transform: none;
    letter-spacing: 0;
    color: #bbb;
}
.quill-editor { font-family: inherit; }
.quill-editor .ql-toolbar.ql-snow {
    border: 1px solid #ccc;
    border-bottom: 1px solid #e0e0e0;
    background: #f7f7f7;
    font-family: inherit;
}
.quill-editor .ql-container.ql-snow {
    border: 1px solid #ccc;
    border-top: none;
    font-family: inherit;
    font-size: 14px;
    min-height: 280px;
}
.quill-editor .ql-editor { min-height: 280px; line-height: 1.6; }
.quill-editor:focus-within .ql-toolbar.ql-snow,
.quill-editor:focus-within .ql-container.ql-snow { border-color: #111; }
.tag-row {
    display: flex;
    gap: 6px;
    margin-bottom: 6px;
    align-items: center;
}
.tag-row input {
    flex: 1;
    min-width: 0;
    padding: 6px 8px;
    border: 1px solid #ddd;
    font-size: 13px;
    font-family: inherit;
    background: #fafafa;
    outline: none;
}
.tag-row input:focus { border-color: #111; }
.media-preview-list { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 4px; }
.media-preview-item { position: relative; width: 64px; }
.media-preview-item img { width: 64px; height: 48px; object-fit: cover; display: block; }
.media-preview-remove {
    position: absolute;
    top: 2px; right: 2px;
    width: 18px; height: 18px;
    background: rgba(0,0,0,0.55);
    color: #fff;
    border: none;
    font-size: 13px;
    line-height: 1;
    cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    padding: 0;
}
.category-checklist {
    display: flex;
    flex-direction: column;
    gap: 6px;
    margin-top: 6px;
}
.check-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    font-weight: 400;
    color: #333;
    cursor: pointer;
    user-select: none;
}
.check-item input[type="checkbox"] {
    width: 14px;
    height: 14px;
    accent-color: #111;
    cursor: pointer;
    flex-shrink: 0;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
const toolbarOptions = [
    ['bold', 'italic', 'underline'],
    [{ header: [2, 3, false] }],
    [{ list: 'ordered' }, { list: 'bullet' }],
    ['link'],
    ['clean'],
];

const quillTr = new Quill('#editor-tr', { theme: 'snow', modules: { toolbar: toolbarOptions } });
const contentTr = document.getElementById('content-tr');
if (contentTr.value) quillTr.root.innerHTML = contentTr.value;

const quillEn = new Quill('#editor-en', { theme: 'snow', modules: { toolbar: toolbarOptions } });
const contentEn = document.getElementById('content-en');
if (contentEn.value) quillEn.root.innerHTML = contentEn.value;

const quillMetaTr = new Quill('#editor-meta-tr', { theme: 'snow', modules: { toolbar: toolbarOptions } });
const contentMetaTr = document.getElementById('content-meta-tr');
if (contentMetaTr.value) quillMetaTr.root.innerHTML = contentMetaTr.value;

const quillMetaEn = new Quill('#editor-meta-en', { theme: 'snow', modules: { toolbar: toolbarOptions } });
const contentMetaEn = document.getElementById('content-meta-en');
if (contentMetaEn.value) quillMetaEn.root.innerHTML = contentMetaEn.value;

document.getElementById('projectForm').addEventListener('submit', function () {
    contentTr.value = quillTr.root.innerHTML;
    contentEn.value = quillEn.root.innerHTML;
    contentMetaTr.value = quillMetaTr.root.innerHTML;
    contentMetaEn.value = quillMetaEn.root.innerHTML;
});

document.querySelectorAll('.lang-tab').forEach(tab => {
    tab.addEventListener('click', function () {
        const tabGroup = this.closest('.form-card').querySelectorAll('.lang-tab');
        const panelGroup = this.closest('.form-card').querySelectorAll('.lang-panel');
        tabGroup.forEach(t => t.classList.remove('active'));
        panelGroup.forEach(p => p.style.display = 'none');
        this.classList.add('active');
        document.getElementById('panel-' + this.dataset.lang).style.display = '';
    });
});

function addTagRow(containerId, fieldName) {
    const row = document.createElement('div');
    row.className = 'tag-row';
    row.innerHTML = `<input type="text" name="${fieldName}[]" placeholder="Ad Soyad">
        <button type="button" class="btn btn-danger btn-sm remove-tag">×</button>`;
    document.getElementById(containerId).appendChild(row);
    row.querySelector('input').focus();
}

document.addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-tag')) e.target.closest('.tag-row').remove();
});
</script>
@endpush

@include('admin.partials.media-picker')

@push('scripts')
<script>
function setCover(item) {
    const path = item.url.split('/storage/')[1];
    document.getElementById('coverImageInput').value = path;
    const preview = document.getElementById('coverPreview');
    preview.innerHTML = `<div class="media-preview-item" data-path="${path}" style="position:relative;display:inline-block;max-width:160px;">
        <img src="${item.url}" style="width:100%;aspect-ratio:1;object-fit:cover;display:block;">
        <button type="button" class="media-preview-remove" onclick="clearCover()">×</button>
    </div>`;
}
function clearCover() {
    document.getElementById('coverImageInput').value = '';
    document.getElementById('coverPreview').innerHTML = '';
}

function addToPreview(containerId, items) {
    const container = document.getElementById(containerId);
    items.forEach(item => {
        const path = item.url.split('/storage/')[1];
        if (container.querySelector(`[data-path="${path}"]`)) return;
        const fieldName = containerId === 'sliderPreview' ? 'slider_paths[]' : 'gallery_paths[]';
        const div = document.createElement('div');
        div.className = 'media-preview-item';
        div.dataset.path = path;
        div.innerHTML = `
            <img src="${item.url}" alt="${item.name}">
            <button type="button" class="media-preview-remove">×</button>
            <input type="hidden" name="${fieldName}" value="${path}">`;
        div.querySelector('.media-preview-remove').addEventListener('click', () => div.remove());
        container.appendChild(div);
    });
}

document.querySelectorAll('.media-preview-remove').forEach(btn => {
    btn.addEventListener('click', () => btn.closest('.media-preview-item').remove());
});
</script>
@endpush
