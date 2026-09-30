@extends('admin.layout')

@section('title', 'Medya Kütüphanesi')

@section('content')

<div class="page-header">
    <h2>Medya Kütüphanesi</h2>
    <div style="display:flex;gap:8px;align-items:center;">
        <label class="btn btn-primary" for="uploadInput">+ Yükle</label>
    </div>
</div>

<div class="media-layout">

    {{-- Klasör Sidebar --}}
    <div class="media-sidebar">
        <div class="media-sidebar-header">
            <span>Klasörler</span>
            <button type="button" class="btn btn-secondary btn-sm" id="newFolderBtn">+</button>
        </div>
        <div id="newFolderForm" style="display:none;padding:8px 0;">
            <input type="text" id="newFolderInput" placeholder="Klasör adı" style="width:100%;padding:5px 8px;border:1px solid #ddd;font-size:12px;font-family:inherit;">
            <button type="button" class="btn btn-primary btn-sm" style="margin-top:6px;width:100%;" id="newFolderSave">Oluştur</button>
        </div>
        <a href="{{ route('admin.media.index') }}"
           class="media-folder-item {{ !$folder ? 'active' : '' }}">
            <svg width="13" height="13" viewBox="0 0 13 13" fill="none"><path d="M1 3.5A1.5 1.5 0 0 1 2.5 2H5l1.5 1.5H11A1.5 1.5 0 0 1 12.5 5v5A1.5 1.5 0 0 1 11 11.5H2.5A1.5 1.5 0 0 1 1 10V3.5Z" stroke="currentColor" stroke-width="1.2"/></svg>
            Tümü
        </a>
        @foreach($folders as $f)
            <a href="{{ route('admin.media.index', ['folder' => $f]) }}"
               class="media-folder-item {{ $folder === $f ? 'active' : '' }}" data-folder="{{ $f }}">
                <svg width="13" height="13" viewBox="0 0 13 13" fill="none"><path d="M1 3.5A1.5 1.5 0 0 1 2.5 2H5l1.5 1.5H11A1.5 1.5 0 0 1 12.5 5v5A1.5 1.5 0 0 1 11 11.5H2.5A1.5 1.5 0 0 1 1 10V3.5Z" stroke="currentColor" stroke-width="1.2" fill="currentColor" fill-opacity="0.08"/></svg>
                {{ $f }}
            </a>
        @endforeach
    </div>

    {{-- Ana Alan --}}
    <div class="media-main">

        {{-- Klasör başlığı --}}
        @if($folder)
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
                <span style="font-size:13px;font-weight:500;">{{ $folder }}</span>
                <a href="{{ route('admin.media.index') }}" style="font-size:11px;color:#aaa;">← Tümü</a>
            </div>
        @endif

        {{-- Drop zone --}}
        <div class="media-dropzone" id="dropzone">
            <div class="media-dropzone-inner">
                <svg width="32" height="32" fill="none" viewBox="0 0 24 24"><path stroke="#aaa" stroke-width="1.5" d="M12 16V4m0 0-4 4m4-4 4 4M4 20h16"/></svg>
                <p>Görselleri buraya sürükle ya da <label for="uploadInput" style="color:#111;cursor:pointer;text-decoration:underline;">seç</label></p>
                <p style="font-size:11px;color:#bbb;">JPG, PNG, GIF, WEBP — max 8 MB/dosya · İsme göre sıralanır</p>
            </div>
            <form id="uploadForm" action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="folder" id="uploadFolder" value="{{ $folder ?? '' }}">
                <input type="file" id="uploadInput" name="files[]" multiple accept="image/*" style="display:none;">
            </form>
        </div>

        <div class="media-toolbar">
            <span id="selectedCount" style="font-size:12px;color:#aaa;"></span>
            <button class="btn btn-secondary btn-sm" id="moveSelected" style="display:none;">Taşı →</button>
            <button class="btn btn-danger btn-sm" id="deleteSelected" style="display:none;">Seçilenleri Sil</button>
        </div>

        {{-- Taşı Modal --}}
        <div id="moveModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.4);z-index:9999;align-items:center;justify-content:center;">
            <div style="background:#fff;padding:24px;min-width:280px;max-width:360px;width:90%;">
                <div style="font-size:13px;font-weight:500;margin-bottom:16px;">Klasöre Taşı</div>
                <select id="moveFolderSelect" style="width:100%;padding:8px;border:1px solid #ddd;font-size:13px;font-family:inherit;margin-bottom:16px;">
                    <option value="">— Kök Dizin —</option>
                    @foreach($folders as $f)
                        <option value="{{ $f }}">{{ $f }}</option>
                    @endforeach
                </select>
                <div style="display:flex;gap:8px;justify-content:flex-end;">
                    <button class="btn btn-secondary btn-sm" id="moveCancelBtn">İptal</button>
                    <button class="btn btn-primary btn-sm" id="moveConfirmBtn">Taşı</button>
                </div>
            </div>
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

        <div id="mediaSentinel" style="height:1px;margin-top:8px;"></div>
        <div id="mediaSpinner" style="display:none;text-align:center;padding:20px;">
            <span style="font-size:11px;letter-spacing:2px;color:#aaa;">YÜKLENİYOR...</span>
        </div>

    </div>
</div>

@endsection

@push('styles')
<style>
.media-layout {
    display: flex;
    gap: 20px;
    align-items: flex-start;
}
.media-sidebar {
    width: 180px;
    flex-shrink: 0;
    background: #fafafa;
    border: 1px solid #eee;
    padding: 12px;
}
.media-sidebar-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    color: #aaa;
    margin-bottom: 10px;
    padding-bottom: 8px;
    border-bottom: 1px solid #eee;
}
.media-folder-item {
    display: flex;
    align-items: center;
    gap: 7px;
    padding: 7px 8px;
    font-size: 12px;
    color: #444;
    text-decoration: none;
    border-radius: 2px;
    transition: background 0.12s, color 0.12s;
    margin-bottom: 2px;
}
.media-folder-item:hover { background: #f0f0f0; color: #111; }
.media-folder-item.active { background: #111; color: #fff; }
.media-folder-item.active svg path { stroke: #fff; }
.media-main { flex: 1; min-width: 0; }
.media-dropzone {
    border: 2px dashed #ddd;
    padding: 24px;
    text-align: center;
    margin-bottom: 16px;
    cursor: pointer;
    transition: border-color 0.15s, background 0.15s;
}
.media-dropzone.drag-over { border-color: #111; background: #f9f9f9; }
.media-dropzone-inner p { margin: 6px 0 0; font-size: 13px; color: #888; }
.media-toolbar {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
    min-height: 32px;
}
.media-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 10px;
}
.media-item { cursor: pointer; }
.media-item.selected .media-thumb { outline: 2px solid #111; }
.media-thumb {
    position: relative;
    aspect-ratio: 1;
    overflow: hidden;
    background: #f4f4f4;
}
.media-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
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
.media-info { padding: 4px 2px 0; }
.media-name {
    font-size: 10px;
    color: #333;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 100%;
    display: block;
}
.media-size { font-size: 10px; color: #aaa; }
.sortable-ghost { opacity: 0.35; background: #f0f0f0; }
.upload-progress {
    position: fixed;
    bottom: 24px; right: 24px;
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
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
const uploadInput  = document.getElementById('uploadInput');
const dropzone     = document.getElementById('dropzone');
const mediaGrid    = document.getElementById('mediaGrid');
const deleteBtn    = document.getElementById('deleteSelected');
const selectedCount= document.getElementById('selectedCount');
const uploadFolder = document.getElementById('uploadFolder');

uploadInput.addEventListener('change', () => uploadFiles(uploadInput.files));

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
    fd.append('folder', uploadFolder.value);
    Array.from(files).forEach(f => fd.append('files[]', f));

    const bar = showProgress('Yükleniyor (' + files.length + ' dosya)...');

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
    if (!bar) { bar = document.createElement('div'); bar.id = 'uploadBar'; bar.className = 'upload-progress'; document.body.appendChild(bar); }
    bar.textContent = msg;
    bar.style.display = 'block';
    return bar;
}

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
    selectedCount.textContent = checked.length > 0 ? checked.length + ' seçili' : '';
    const show = checked.length > 0 ? '' : 'none';
    deleteBtn.style.display = show;
    document.getElementById('moveSelected').style.display = show;
}

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

// Taşı
const moveModal = document.getElementById('moveModal');
document.getElementById('moveSelected').addEventListener('click', function() {
    moveModal.style.display = 'flex';
});
document.getElementById('moveCancelBtn').addEventListener('click', function() {
    moveModal.style.display = 'none';
});
moveModal.addEventListener('click', function(e) {
    if (e.target === moveModal) moveModal.style.display = 'none';
});
document.getElementById('moveConfirmBtn').addEventListener('click', function() {
    const ids    = Array.from(document.querySelectorAll('.media-checkbox:checked')).map(c => c.value);
    const folder = document.getElementById('moveFolderSelect').value;
    if (!ids.length) return;
    fetch('{{ route('admin.media.move') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('[name=_token]').value,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ ids, folder })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            // Seçili itemları grid'den kaldır (başka klasöre gittiler)
            ids.forEach(id => document.querySelector(`.media-item[data-id="${id}"]`)?.remove());
            moveModal.style.display = 'none';
            updateSelection();
        }
    });
});

// Sürükle-bırak sıralama
Sortable.create(mediaGrid, {
    animation: 150,
    ghostClass: 'sortable-ghost',
    onEnd: function () {
        const ids = Array.from(mediaGrid.querySelectorAll('.media-item')).map(el => el.dataset.id);
        fetch('{{ route('admin.media.reorder') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('[name=_token]').value,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ ids })
        });
    }
});

// Lazy load (infinite scroll)
(function () {
    var nextUrl  = @json($media->nextPageUrl());
    var loading  = false;
    var sentinel = document.getElementById('mediaSentinel');
    var spinner  = document.getElementById('mediaSpinner');

    if (!nextUrl || !sentinel) return;

    function loadMore() {
        if (loading || !nextUrl) return;
        loading = true;
        spinner.style.display = 'block';

        fetch(nextUrl, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(function (data) {
            data.data.forEach(function (f) {
                var div = document.createElement('div');
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
                        <span class="media-size">${f.human_size}</span>
                    </div>`;
                mediaGrid.appendChild(div);
                bindItem(div);
            });

            nextUrl = data.next_page_url || null;
            loading = false;
            spinner.style.display = 'none';
        })
        .catch(function () { loading = false; spinner.style.display = 'none'; });
    }

    var observer = new IntersectionObserver(function (entries) {
        if (entries[0].isIntersecting) loadMore();
    }, { rootMargin: '200px' });

    observer.observe(sentinel);
})();

// Klasör oluştur
document.getElementById('newFolderBtn').addEventListener('click', function() {
    const f = document.getElementById('newFolderForm');
    f.style.display = f.style.display === 'none' ? 'block' : 'none';
    if (f.style.display !== 'none') document.getElementById('newFolderInput').focus();
});

document.getElementById('newFolderSave').addEventListener('click', function() {
    const name = document.getElementById('newFolderInput').value.trim();
    if (!name) return;
    fetch('{{ route('admin.media.folder.create') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('[name=_token]').value,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ name })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            window.location.href = '{{ route('admin.media.index') }}?folder=' + encodeURIComponent(data.name);
        }
    });
});

document.getElementById('newFolderInput').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') document.getElementById('newFolderSave').click();
});
</script>
@endpush
