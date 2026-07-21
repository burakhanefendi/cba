@extends('admin.layout')

@section('title', 'Medya Kütüphanesi')

@section('content')

<div class="page-header">
    <h2>Medya Kütüphanesi</h2>
    <label class="btn btn-primary" for="uploadInput">+ Yükle</label>
</div>

{{-- Drop zone --}}
<div class="media-dropzone" id="dropzone">
    <div class="media-dropzone-inner">
        <svg width="32" height="32" fill="none" viewBox="0 0 24 24"><path stroke="#aaa" stroke-width="1.5" d="M12 16V4m0 0-4 4m4-4 4 4M4 20h16"/></svg>
        <p>Görselleri buraya sürükle ya da <label for="uploadInput" style="color:#111;cursor:pointer;text-decoration:underline;">seç</label></p>
        <p style="font-size:11px;color:#bbb;">JPG, PNG, GIF, WEBP — max 8 MB</p>
    </div>
    <form id="uploadForm" action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" id="uploadInput" name="files[]" multiple accept="image/*" style="display:none;">
    </form>
</div>

<div class="media-toolbar">
    <span id="selectedCount" style="font-size:12px;color:#aaa;"></span>
    <button class="btn btn-danger btn-sm" id="deleteSelected" style="display:none;">Seçilenleri Sil</button>
</div>

{{-- Grid --}}
<div class="media-grid" id="mediaGrid">
    @foreach($media as $item)
        <div class="media-item" data-id="{{ $item->id }}" data-url="{{ $item->url }}" data-name="{{ $item->name }}">
            <div class="media-thumb">
                <img src="{{ $item->url }}" alt="{{ $item->name }}" loading="lazy">
                <div class="media-overlay">
                    <label class="media-check">
                        <input type="checkbox" class="media-checkbox" value="{{ $item->id }}">
                    </label>
                </div>
            </div>
            <div class="media-info">
                <span class="media-name">{{ $item->name }}</span>
                <span class="media-size">{{ $item->human_size }}</span>
            </div>
        </div>
    @endforeach
</div>

@if($media->hasPages())
    <div class="pagination-wrap">{{ $media->links() }}</div>
@endif

@endsection

@push('styles')
<style>
.media-dropzone {
    border: 2px dashed #ddd;
    padding: 32px;
    text-align: center;
    margin-bottom: 20px;
    cursor: pointer;
    transition: border-color 0.15s, background 0.15s;
}
.media-dropzone.drag-over {
    border-color: #111;
    background: #f9f9f9;
}
.media-dropzone-inner p { margin: 6px 0 0; font-size: 13px; color: #888; }
.media-toolbar {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
    min-height: 32px;
}
.media-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
    gap: 12px;
    max-width: 900px;
}
.media-item { cursor: pointer; }
.media-item.selected .media-thumb { outline: 2px solid #111; }
.media-thumb {
    position: relative;
    aspect-ratio: 1;
    overflow: hidden;
    background: #f4f4f4;
}
.media-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
.media-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0);
    transition: background 0.15s;
    display: flex;
    align-items: flex-start;
    padding: 6px;
}
.media-item:hover .media-overlay,
.media-item.selected .media-overlay { background: rgba(0,0,0,0.15); }
.media-check input { width: 16px; height: 16px; cursor: pointer; accent-color: #111; }
.media-info {
    padding: 5px 2px 0;
    display: flex;
    flex-direction: column;
    gap: 1px;
}
.media-name {
    font-size: 11px;
    color: #333;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 100%;
    display: block;
}
.media-size { font-size: 10px; color: #aaa; }

/* Upload progress bar */
.upload-progress {
    position: fixed;
    bottom: 24px;
    right: 24px;
    background: #111;
    color: #fff;
    padding: 12px 20px;
    font-size: 12px;
    display: none;
    z-index: 9999;
    min-width: 200px;
}
</style>
@endpush

@push('scripts')
<script>
const uploadForm   = document.getElementById('uploadForm');
const uploadInput  = document.getElementById('uploadInput');
const dropzone     = document.getElementById('dropzone');
const mediaGrid    = document.getElementById('mediaGrid');
const deleteBtn    = document.getElementById('deleteSelected');
const selectedCount= document.getElementById('selectedCount');

// Upload on file select
uploadInput.addEventListener('change', () => uploadFiles(uploadInput.files));

// Drag & drop
dropzone.addEventListener('dragover', e => { e.preventDefault(); dropzone.classList.add('drag-over'); });
dropzone.addEventListener('dragleave', () => dropzone.classList.remove('drag-over'));
dropzone.addEventListener('drop', e => {
    e.preventDefault();
    dropzone.classList.remove('drag-over');
    uploadFiles(e.dataTransfer.files);
});

function uploadFiles(files) {
    if (!files.length) return;
    const fd = new FormData();
    fd.append('_token', document.querySelector('[name=_token]').value);
    Array.from(files).forEach(f => fd.append('files[]', f));

    const bar = showProgress('Yükleniyor...');

    fetch('{{ route('admin.media.store') }}', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: fd
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                data.files.forEach(f => prependItem(f));
                bar.textContent = data.files.length + ' dosya yüklendi';
                setTimeout(() => bar.style.display = 'none', 2000);
            }
        })
        .catch(() => { bar.textContent = 'Hata!'; setTimeout(() => bar.style.display = 'none', 2000); });
}

function prependItem(f) {
    const div = document.createElement('div');
    div.className = 'media-item';
    div.dataset.id   = f.id;
    div.dataset.url  = f.url;
    div.dataset.name = f.name;
    div.innerHTML = `
        <div class="media-thumb">
            <img src="${f.url}" alt="${f.name}" loading="lazy">
            <div class="media-overlay">
                <label class="media-check"><input type="checkbox" class="media-checkbox" value="${f.id}"></label>
            </div>
        </div>
        <div class="media-info">
            <span class="media-name">${f.name}</span>
            <span class="media-size"></span>
        </div>`;
    mediaGrid.prepend(div);
    bindItem(div);
}

function showProgress(msg) {
    let bar = document.getElementById('uploadBar');
    if (!bar) {
        bar = document.createElement('div');
        bar.id = 'uploadBar';
        bar.className = 'upload-progress';
        document.body.appendChild(bar);
    }
    bar.textContent = msg;
    bar.style.display = 'block';
    return bar;
}

// Selection
function bindItem(item) {
    item.addEventListener('click', function(e) {
        if (e.target.type === 'checkbox') return;
        const cb = this.querySelector('.media-checkbox');
        cb.checked = !cb.checked;
        this.classList.toggle('selected', cb.checked);
        updateSelection();
    });
    item.querySelector('.media-checkbox').addEventListener('change', function() {
        item.classList.toggle('selected', this.checked);
        updateSelection();
    });
}

document.querySelectorAll('.media-item').forEach(bindItem);

function updateSelection() {
    const checked = document.querySelectorAll('.media-checkbox:checked');
    if (checked.length > 0) {
        selectedCount.textContent = checked.length + ' seçili';
        deleteBtn.style.display = '';
    } else {
        selectedCount.textContent = '';
        deleteBtn.style.display = 'none';
    }
}

// Delete selected
deleteBtn.addEventListener('click', function() {
    const ids = Array.from(document.querySelectorAll('.media-checkbox:checked')).map(c => c.value);
    if (!ids.length || !confirm(ids.length + ' dosyayı silmek istediğinizden emin misiniz?')) return;

    Promise.all(ids.map(id =>
        fetch(`/admin/media/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': document.querySelector('[name=_token]').value, 'Accept': 'application/json' }
        })
    )).then(() => {
        ids.forEach(id => document.querySelector(`.media-item[data-id="${id}"]`)?.remove());
        updateSelection();
    });
});
</script>
@endpush
