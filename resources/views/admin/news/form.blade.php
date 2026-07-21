@extends('admin.layout')

@section('title', isset($news) ? 'Haber Düzenle' : 'Yeni Haber')

@section('content')

<div class="page-header">
    <h2>{{ isset($news) ? 'Haber Düzenle' : 'Yeni Haber' }}</h2>
    <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">Geri</a>
</div>

<form action="{{ isset($news) ? route('admin.news.update', $news) : route('admin.news.store') }}"
      method="POST" enctype="multipart/form-data" id="newsForm">
    @csrf
    @if(isset($news)) @method('PUT') @endif

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
                    <input type="text" name="title" value="{{ old('title', $news->title ?? '') }}" required>
                    @error('title')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label>Özet <span class="label-hint">— listede görselin yanında görünür</span></label>
                    <textarea name="excerpt" rows="3" placeholder="Kısa açıklama (isteğe bağlı)">{{ old('excerpt', $news->excerpt ?? '') }}</textarea>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label>İçerik</label>
                    <textarea name="content" id="content-tr" style="display:none;">{{ old('content', $news->content ?? '') }}</textarea>
                    <div id="editor-tr" class="quill-editor"></div>
                </div>
            </div>

            {{-- EN --}}
            <div class="lang-panel" id="panel-en" style="display:none;">
                <div class="form-group">
                    <label>Title (EN)</label>
                    <input type="text" name="title_en" value="{{ old('title_en', $news->title_en ?? '') }}">
                </div>
                <div class="form-group">
                    <label>Excerpt (EN) <span class="label-hint">— appears beside image in list</span></label>
                    <textarea name="excerpt_en" rows="3" placeholder="Short description (optional)">{{ old('excerpt_en', $news->excerpt_en ?? '') }}</textarea>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label>Content (EN)</label>
                    <textarea name="content_en" id="content-en" style="display:none;">{{ old('content_en', $news->content_en ?? '') }}</textarea>
                    <div id="editor-en" class="quill-editor"></div>
                </div>
            </div>
        </div>

        {{-- Sağ: Görseller + Meta + Linkler --}}
        <div class="news-sidebar">

            {{-- Ana Görsel --}}
            <div class="form-card">
                <div class="form-group" style="margin-bottom:0;">
                    <label>Ana Görsel <span class="label-hint">— birden fazla → slider</span></label>

                    <div class="media-preview-list" id="sliderPreview">
                        @if(isset($news) && $news->cover_image)
                            <div class="media-preview-item" data-path="{{ $news->cover_image }}">
                                <img src="{{ asset('storage/' . $news->cover_image) }}">
                                <button type="button" class="media-preview-remove">×</button>
                                <input type="hidden" name="slider_paths[]" value="{{ $news->cover_image }}">
                            </div>
                        @endif
                        @if(isset($news))
                            @foreach($news->sliderImages as $img)
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
                    <label>Galeri <span class="label-hint">— detay sayfası altında 4'lü grid</span></label>

                    <div class="media-preview-list" id="galleryPreview">
                        @if(isset($news))
                            @foreach($news->galleryImages as $img)
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

            {{-- Meta --}}
            <div class="form-card">
                <div class="form-group">
                    <label>Yayın Tarihi</label>
                    <input type="date" name="published_at" value="{{ old('published_at', isset($news) ? $news->published_at?->format('Y-m-d') : date('Y-m-d')) }}">
                </div>
                <div class="form-group">
                    <label>Sıra</label>
                    <input type="number" name="order" value="{{ old('order', $news->order ?? 0) }}" style="width:90px;">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <div class="form-check">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                            {{ old('is_active', $news->is_active ?? true) ? 'checked' : '' }}>
                        <label for="is_active">Aktif</label>
                    </div>
                    <div class="form-check" style="margin-top:8px;">
                        <input type="checkbox" name="is_featured" id="is_featured" value="1"
                            {{ old('is_featured', $news->is_featured ?? false) ? 'checked' : '' }}>
                        <label for="is_featured">Anasayfada yayınla</label>
                    </div>
                </div>
            </div>

            {{-- Linkler --}}
            <div class="form-card">
                <div class="sidebar-row-header">
                    <label>Linkler</label>
                    <button type="button" class="btn btn-secondary btn-sm" id="addLink">+ Ekle</button>
                </div>
                <div id="linksContainer">
                    @if(isset($news))
                        @foreach($news->links as $link)
                            <div class="link-row">
                                <input type="text" name="link_titles[]" value="{{ $link->title }}" placeholder="Başlık">
                                <input type="text" name="link_urls[]" value="{{ $link->url }}" placeholder="URL">
                                <select name="link_types[]">
                                    <option value="link" {{ $link->type === 'link' ? 'selected' : '' }}>L</option>
                                    <option value="project" {{ $link->type === 'project' ? 'selected' : '' }}>P</option>
                                </select>
                                <button type="button" class="btn btn-danger btn-sm remove-link">×</button>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            {{-- Kaydet / İptal --}}
            <div class="form-actions" style="margin:0;">
                <button type="submit" class="btn btn-primary">Kaydet</button>
                <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">İptal</a>
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
.link-row {
    display: grid;
    grid-template-columns: 1fr 1fr 36px 28px;
    gap: 6px;
    margin-bottom: 6px;
    align-items: center;
}
.link-row input, .link-row select {
    min-width: 0;
    padding: 6px 8px;
    border: 1px solid #ddd;
    font-size: 12px;
    font-family: inherit;
    background: #fafafa;
    outline: none;
}
.link-row input:focus, .link-row select:focus { border-color: #111; }
.gallery-thumbs { display: flex; flex-wrap: wrap; gap: 8px; }
.gallery-thumb { width: 64px; }
.gallery-thumb img { width: 64px; height: 48px; object-fit: cover; display: block; }
.gallery-delete {
    display: flex;
    align-items: center;
    gap: 3px;
    font-size: 10px;
    color: #c0392b;
    cursor: pointer;
    margin-top: 3px;
}
.gallery-delete input { width: auto; }
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

document.getElementById('newsForm').addEventListener('submit', function () {
    contentTr.value = quillTr.root.innerHTML;
    contentEn.value = quillEn.root.innerHTML;
});

document.querySelectorAll('.lang-tab').forEach(tab => {
    tab.addEventListener('click', function () {
        document.querySelectorAll('.lang-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.lang-panel').forEach(p => p.style.display = 'none');
        this.classList.add('active');
        document.getElementById('panel-' + this.dataset.lang).style.display = '';
    });
});

document.getElementById('addLink').addEventListener('click', function () {
    const row = document.createElement('div');
    row.className = 'link-row';
    row.innerHTML = `
        <input type="text" name="link_titles[]" placeholder="Başlık">
        <input type="text" name="link_urls[]" placeholder="URL">
        <select name="link_types[]">
            <option value="link">L</option>
            <option value="project">P</option>
        </select>
        <button type="button" class="btn btn-danger btn-sm remove-link">×</button>
    `;
    document.getElementById('linksContainer').appendChild(row);
});

document.getElementById('linksContainer').addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-link')) e.target.closest('.link-row').remove();
});
</script>
@endpush

@include('admin.partials.media-picker')

@push('scripts')
<script>
// Media preview helper
function addToPreview(containerId, items) {
    const container = document.getElementById(containerId);
    items.forEach(item => {
        if (container.querySelector(`[data-path="${item.url.split('/storage/')[1]}"]`)) return;
        const path = item.url.split('/storage/')[1];
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

// Bind existing remove buttons
document.querySelectorAll('.media-preview-remove').forEach(btn => {
    btn.addEventListener('click', () => btn.closest('.media-preview-item').remove());
});
</script>
@endpush
