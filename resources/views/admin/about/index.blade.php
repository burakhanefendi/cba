@extends('admin.layout')

@section('title', 'Profil')

@section('content')

<div class="page-header">
    <h2>Profil — Cafer Bozkurt</h2>
</div>

<form action="{{ route('admin.about.update') }}" method="POST" id="aboutForm">
    @csrf

    <div class="news-form-grid">
        <div class="form-card">
            <div class="lang-tabs" data-group="profile">
                <button type="button" class="lang-tab active" data-group="profile" data-lang="tr">TR</button>
                <button type="button" class="lang-tab" data-group="profile" data-lang="en">EN</button>
            </div>

            <div class="lang-panel" id="panel-tr" data-group="profile">
                <div class="form-group">
                    <label>Başlık</label>
                    <input type="text" name="profile_heading" value="{{ old('profile_heading', $settings->get('profile_heading', 'CAFER BOZKURT')) }}">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label>Metin</label>
                    <textarea name="profile_body" id="content-tr" style="display:none;">{{ old('profile_body', $settings->get('profile_body')) }}</textarea>
                    <div id="editor-tr" class="quill-editor"></div>
                </div>
            </div>

            <div class="lang-panel" id="panel-en" data-group="profile" style="display:none;">
                <div class="form-group">
                    <label>Heading (EN)</label>
                    <input type="text" name="profile_heading_en" value="{{ old('profile_heading_en', $settings->get('profile_heading_en', 'CAFER BOZKURT')) }}">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label>Text (EN)</label>
                    <textarea name="profile_body_en" id="content-en" style="display:none;">{{ old('profile_body_en', $settings->get('profile_body_en')) }}</textarea>
                    <div id="editor-en" class="quill-editor"></div>
                </div>
            </div>
        </div>

        <div class="news-sidebar">
            <div class="form-card">
                <div class="form-group" style="margin-bottom:0;">
                    <label>Görsel</label>
                    <div id="coverPreview" style="margin-bottom:8px;">
                        @if($settings->get('profile_image'))
                            <div class="media-preview-item" data-path="{{ $settings->get('profile_image') }}" style="position:relative;display:inline-block;max-width:100%;">
                                <img src="{{ asset('storage/' . $settings->get('profile_image')) }}" style="width:100%;aspect-ratio:16/9;object-fit:cover;display:block;">
                                <button type="button" class="media-preview-remove" onclick="clearCover()">×</button>
                            </div>
                        @endif
                    </div>
                    <input type="hidden" name="profile_image" id="coverImageInput" value="{{ old('profile_image', $settings->get('profile_image')) }}">
                    <div style="display:flex;gap:6px;align-items:center;">
                        <button type="button" class="btn btn-secondary btn-sm"
                            onclick="MediaPicker.open({ multiple: false, onSelect: items => setCover(items[0]) })">
                            Kütüphaneden Seç
                        </button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="clearCover()">Kaldır</button>
                    </div>
                </div>
            </div>

            <div class="form-actions" style="margin:0;">
                <button type="submit" class="btn btn-primary">Kaydet</button>
            </div>
        </div>
    </div>
</form>

<div class="page-header" style="margin-top:40px;">
    <h2>Stüdyo</h2>
</div>

<form action="{{ route('admin.about.update') }}" method="POST" id="studioForm">
    @csrf

    <div class="news-form-grid">
        <div class="form-card">
            <div class="lang-tabs" data-group="studio">
                <button type="button" class="lang-tab active" data-group="studio" data-lang="studio-tr">TR</button>
                <button type="button" class="lang-tab" data-group="studio" data-lang="studio-en">EN</button>
            </div>

            <div class="lang-panel" id="panel-studio-tr" data-group="studio">
                <div class="form-group" style="margin-bottom:0;">
                    <label>Metin</label>
                    <textarea name="studio_body" id="content-studio-tr" style="display:none;">{{ old('studio_body', $settings->get('studio_body')) }}</textarea>
                    <div id="editor-studio-tr" class="quill-editor"></div>
                </div>
            </div>

            <div class="lang-panel" id="panel-studio-en" data-group="studio" style="display:none;">
                <div class="form-group" style="margin-bottom:0;">
                    <label>Text (EN)</label>
                    <textarea name="studio_body_en" id="content-studio-en" style="display:none;">{{ old('studio_body_en', $settings->get('studio_body_en')) }}</textarea>
                    <div id="editor-studio-en" class="quill-editor"></div>
                </div>
            </div>
        </div>

        <div class="news-sidebar">
            <div class="form-card">
                <div class="form-group" style="margin-bottom:0;">
                    <label>Görsel</label>
                    <div id="studioPreview" style="margin-bottom:8px;">
                        @if($settings->get('studio_image'))
                            <div class="media-preview-item" data-path="{{ $settings->get('studio_image') }}" style="position:relative;display:inline-block;max-width:100%;">
                                <img src="{{ asset('storage/' . $settings->get('studio_image')) }}" style="width:100%;aspect-ratio:16/9;object-fit:cover;display:block;">
                                <button type="button" class="media-preview-remove" onclick="clearStudio()">×</button>
                            </div>
                        @endif
                    </div>
                    <input type="hidden" name="studio_image" id="studioImageInput" value="{{ old('studio_image', $settings->get('studio_image')) }}">
                    <div style="display:flex;gap:6px;align-items:center;">
                        <button type="button" class="btn btn-secondary btn-sm"
                            onclick="MediaPicker.open({ multiple: false, onSelect: items => setStudio(items[0]) })">
                            Kütüphaneden Seç
                        </button>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="clearStudio()">Kaldır</button>
                    </div>
                </div>
            </div>

            <div class="form-actions" style="margin:0;">
                <button type="submit" class="btn btn-primary">Kaydet</button>
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
.quill-editor { font-family: inherit; }
.quill-editor .ql-toolbar.ql-snow { border: 1px solid #ccc; border-bottom: 1px solid #e0e0e0; background: #f7f7f7; font-family: inherit; }
.quill-editor .ql-container.ql-snow { border: 1px solid #ccc; border-top: none; font-family: inherit; font-size: 14px; min-height: 360px; }
.quill-editor .ql-editor { min-height: 360px; line-height: 1.6; }
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

const quillStudioTr = new Quill('#editor-studio-tr', { theme: 'snow', modules: { toolbar: toolbarOptions } });
const contentStudioTr = document.getElementById('content-studio-tr');
if (contentStudioTr.value) quillStudioTr.root.innerHTML = contentStudioTr.value;

const quillStudioEn = new Quill('#editor-studio-en', { theme: 'snow', modules: { toolbar: toolbarOptions } });
const contentStudioEn = document.getElementById('content-studio-en');
if (contentStudioEn.value) quillStudioEn.root.innerHTML = contentStudioEn.value;

document.getElementById('aboutForm').addEventListener('submit', function () {
    contentTr.value = quillTr.root.innerHTML;
    contentEn.value = quillEn.root.innerHTML;
});

document.getElementById('studioForm').addEventListener('submit', function () {
    contentStudioTr.value = quillStudioTr.root.innerHTML;
    contentStudioEn.value = quillStudioEn.root.innerHTML;
});

document.querySelectorAll('.lang-tab').forEach(tab => {
    tab.addEventListener('click', function () {
        const group = this.dataset.group;
        document.querySelectorAll('.lang-tab[data-group="' + group + '"]').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.lang-panel[data-group="' + group + '"]').forEach(p => p.style.display = 'none');
        this.classList.add('active');
        document.getElementById('panel-' + this.dataset.lang).style.display = '';
    });
});

function setCover(item) {
    const path = item.url.split('/storage/')[1];
    document.getElementById('coverImageInput').value = path;
    document.getElementById('coverPreview').innerHTML = `<div class="media-preview-item" data-path="${path}" style="position:relative;display:inline-block;max-width:100%;">
        <img src="${item.url}" style="width:100%;aspect-ratio:16/9;object-fit:cover;display:block;">
        <button type="button" class="media-preview-remove" onclick="clearCover()">×</button>
    </div>`;
}
function clearCover() {
    document.getElementById('coverImageInput').value = '';
    document.getElementById('coverPreview').innerHTML = '';
}
function setStudio(item) {
    const path = item.url.split('/storage/')[1];
    document.getElementById('studioImageInput').value = path;
    document.getElementById('studioPreview').innerHTML = `<div class="media-preview-item" data-path="${path}" style="position:relative;display:inline-block;max-width:100%;">
        <img src="${item.url}" style="width:100%;aspect-ratio:16/9;object-fit:cover;display:block;">
        <button type="button" class="media-preview-remove" onclick="clearStudio()">×</button>
    </div>`;
}
function clearStudio() {
    document.getElementById('studioImageInput').value = '';
    document.getElementById('studioPreview').innerHTML = '';
}
</script>
@endpush
