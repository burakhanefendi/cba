@extends('admin.layout')

@section('title', 'Anasayfa Yönetimi')

@section('content')

<div class="page-header">
    <h2>Anasayfa Yönetimi</h2>
</div>

{{-- ─── Slider ─────────────────────────────────────────── --}}
<div class="form-card" style="margin-bottom:16px;">
    <div class="settings-section-title">Hero Slider <span style="font-weight:300;text-transform:none;letter-spacing:0;color:#bbb;font-size:11px;">— önerilen görsel boyutu: 1440 × 600 px</span></div>

    @if($slides->count())
        <div class="slide-list" id="slideList">
            @foreach($slides as $slide)
                <div class="slide-row" data-id="{{ $slide->id }}">
                    <div class="slide-drag">⠿</div>
                    <img src="{{ asset('storage/' . $slide->image) }}" class="slide-thumb" alt="">
                    <input type="text" class="slide-link-input" value="{{ $slide->link }}"
                           placeholder="Link (opsiyonel)" data-id="{{ $slide->id }}">
                    <form action="{{ route('admin.homepage.slides.destroy', $slide) }}" method="POST"
                          onsubmit="return confirm('Silinsin mi?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Sil</button>
                    </form>
                </div>
            @endforeach
        </div>
    @else
        <p style="color:#bbb;font-size:13px;margin-bottom:16px;">Henüz slide eklenmemiş.</p>
    @endif

    {{-- Yeni Slide Ekle --}}
    <form action="{{ route('admin.homepage.slides.store') }}" method="POST" id="slideAddForm">
        @csrf
        <input type="hidden" name="image_path" id="slideImagePath">
        <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;margin-top:8px;">
            <div class="media-preview-item" id="slideAddPreview" style="display:none;width:80px;">
                <img id="slideAddThumb" src="" alt="" style="width:80px;height:60px;object-fit:cover;display:block;">
                <button type="button" class="media-preview-remove" onclick="clearSlideAdd()">×</button>
            </div>
            <button type="button" class="btn btn-secondary btn-sm"
                onclick="MediaPicker.open({ multiple: false, onSelect: items => setSlideAdd(items[0]) })">
                Kütüphaneden Seç
            </button>
            <input type="text" name="link" placeholder="Link (opsiyonel)" style="width:240px;padding:6px 10px;border:1px solid #ddd;font-size:13px;font-family:inherit;">
            <button type="submit" class="btn btn-primary btn-sm" id="slideAddBtn" disabled>Slide Ekle</button>
        </div>
    </form>

    {{-- Slide sırası + link kaydet --}}
    <form action="{{ route('admin.homepage.update') }}" method="POST" id="slideOrderForm" style="margin-top:16px;">
        @csrf
        <div id="slideOrderInputs"></div>
        <button type="submit" class="btn btn-primary btn-sm">Sırayı & Linkleri Kaydet</button>
    </form>
</div>

{{-- ─── Intro Metin ─────────────────────────────────────── --}}
<form action="{{ route('admin.homepage.update') }}" method="POST">
    @csrf

    <div class="form-card">
        <div class="settings-section-title" style="margin-bottom:20px;">Tanıtım Metni</div>

        <div class="lang-tabs" style="margin-bottom:20px;">
            <button type="button" class="lang-tab active" data-lang="tr">TR</button>
            <button type="button" class="lang-tab" data-lang="en">EN</button>
        </div>

        <div class="lang-panel" id="panel-tr">
            <div class="form-group" style="margin-bottom:0;">
                <label>Metin (TR)</label>
                <textarea name="intro_text" rows="5" style="resize:vertical;">{{ $settings->get('intro_text') }}</textarea>
            </div>
        </div>

        <div class="lang-panel" id="panel-en" style="display:none;">
            <div class="form-group" style="margin-bottom:0;">
                <label>Text (EN)</label>
                <textarea name="intro_text_en" rows="5" style="resize:vertical;">{{ $settings->get('intro_text_en') }}</textarea>
            </div>
        </div>

        <div class="form-actions" style="margin-top:20px;margin-bottom:0;">
            <button type="submit" class="btn btn-primary">Kaydet</button>
        </div>
    </div>
</form>

@endsection

@push('styles')
<style>
.settings-section-title {
    font-size: 10px;
    letter-spacing: 3px;
    text-transform: uppercase;
    color: #999;
    margin-bottom: 16px;
}
.slide-list { display: flex; flex-direction: column; gap: 8px; margin-bottom: 16px; }
.slide-row {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px;
    background: #fafafa;
    border: 1px solid #eee;
}
.slide-drag { cursor: grab; color: #bbb; font-size: 18px; line-height: 1; user-select: none; padding: 4px; }
.slide-drag:active { cursor: grabbing; }
.slide-thumb { width: 80px; height: 52px; object-fit: cover; flex-shrink: 0; }
.slide-link-input {
    flex: 1;
    padding: 6px 10px;
    border: 1px solid #ddd;
    font-size: 13px;
    font-family: inherit;
    background: #fff;
    outline: none;
    min-width: 0;
}
.slide-link-input:focus { border-color: #111; }
.lang-tabs { display: flex; border-bottom: 1px solid #eee; }
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.3/Sortable.min.js"></script>
<script>
// Sürükle-bırak sıralama
const slideListEl = document.getElementById('slideList');
if (slideListEl) {
    Sortable.create(slideListEl, {
        handle: '.slide-drag',
        animation: 150,
    });
}

// Slide add
function setSlideAdd(item) {
    const path = item.url.split('/storage/')[1];
    document.getElementById('slideImagePath').value = path;
    document.getElementById('slideAddThumb').src = item.url;
    document.getElementById('slideAddPreview').style.display = '';
    document.getElementById('slideAddBtn').disabled = false;
}
function clearSlideAdd() {
    document.getElementById('slideImagePath').value = '';
    document.getElementById('slideAddPreview').style.display = 'none';
    document.getElementById('slideAddBtn').disabled = true;
}

// Slide sıra & link kaydetme formu oluştur
function buildOrderForm() {
    const rows = document.querySelectorAll('#slideList .slide-row');
    const container = document.getElementById('slideOrderInputs');
    container.innerHTML = '';
    rows.forEach((row, i) => {
        const id = row.dataset.id;
        const link = row.querySelector('.slide-link-input')?.value ?? '';
        container.innerHTML += `<input type="hidden" name="slide_ids[]" value="${id}">`;
        container.innerHTML += `<input type="hidden" name="slide_links[${id}]" value="${link}">`;
    });
}
document.getElementById('slideOrderForm').addEventListener('submit', buildOrderForm);

// Lang tabs
document.querySelectorAll('.lang-tab').forEach(tab => {
    tab.addEventListener('click', function () {
        const form = this.closest('.form-card');
        form.querySelectorAll('.lang-tab').forEach(t => t.classList.remove('active'));
        form.querySelectorAll('.lang-panel').forEach(p => p.style.display = 'none');
        this.classList.add('active');
        form.querySelector('#panel-' + this.dataset.lang).style.display = '';
    });
});
</script>
@endpush

@include('admin.partials.media-picker')
