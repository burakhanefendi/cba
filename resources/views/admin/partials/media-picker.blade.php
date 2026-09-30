{{--
    Media Picker Modal
    Kullanım: @include('admin.partials.media-picker')
    JS: window.MediaPicker.open({ multiple: true, onSelect: fn(items) })
--}}

<div class="mp-overlay" id="mediaPicker" style="display:none;">
    <div class="mp-modal">
        <div class="mp-header">
            <span class="mp-title">Medya Seç</span>
            <div class="mp-header-right">
                <select id="mpFolderFilter" class="mp-folder-select">
                    <option value="">Tüm Klasörler</option>
                </select>
                <label class="btn btn-secondary btn-sm" for="mpUploadInput">+ Yükle</label>
                <input type="file" id="mpUploadInput" multiple accept="image/*" style="display:none;">
                <button type="button" class="mp-confirm btn btn-primary btn-sm" id="mpConfirm" disabled>Seç</button>
                <button type="button" class="mp-close" id="mpClose">×</button>
            </div>
        </div>

        <div class="mp-grid" id="mpGrid">
            <div class="mp-loading">Yükleniyor...</div>
        </div>

        <div class="mp-footer">
            <span id="mpSelectedInfo" style="font-size:12px;color:#888;"></span>
            <button type="button" class="btn btn-secondary btn-sm" id="mpLoadMore" style="display:none;">Daha Fazla Yükle</button>
        </div>
    </div>
</div>

<style>
.mp-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
    z-index: 10000;
    display: flex;
    align-items: center;
    justify-content: center;
}
.mp-modal {
    background: #fff;
    width: 900px;
    max-width: calc(100vw - 40px);
    max-height: 90vh;
    display: flex;
    flex-direction: column;
}
.mp-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 20px;
    border-bottom: 1px solid #eee;
    flex-shrink: 0;
}
.mp-title { font-size: 12px; font-weight: 600; letter-spacing: 2px; text-transform: uppercase; }
.mp-header-right { display: flex; align-items: center; gap: 8px; }
.mp-folder-select {
    padding: 5px 10px;
    border: 1px solid #ddd;
    font-size: 12px;
    font-family: inherit;
    background: #fafafa;
    color: #333;
    cursor: pointer;
    outline: none;
}
.mp-folder-select:focus { border-color: #111; }
.mp-close {
    background: none;
    border: none;
    font-size: 22px;
    line-height: 1;
    color: #aaa;
    cursor: pointer;
    padding: 0 4px;
}
.mp-close:hover { color: #111; }
.mp-grid {
    flex: 1;
    overflow-y: auto;
    padding: 16px;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
    gap: 10px;
    align-content: start;
}
.mp-loading { grid-column: 1/-1; text-align: center; color: #aaa; font-size: 13px; padding: 40px; }
.mp-item { cursor: pointer; }
.mp-item.selected .mp-thumb { outline: 2px solid #111; }
.mp-thumb {
    position: relative;
    aspect-ratio: 1;
    overflow: hidden;
    background: #f4f4f4;
}
.mp-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
.mp-thumb-check {
    position: absolute;
    top: 6px; left: 6px;
    width: 18px; height: 18px;
    background: #fff;
    border: 1px solid #ccc;
    display: none;
    align-items: center;
    justify-content: center;
    font-size: 12px;
}
.mp-item.selected .mp-thumb-check { display: flex; border-color: #111; background: #111; color: #fff; }
.mp-item:hover .mp-thumb-check { display: flex; }
.mp-name { font-size: 10px; color: #666; margin-top: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.mp-footer {
    padding: 12px 20px;
    border-top: 1px solid #eee;
    flex-shrink: 0;
    min-height: 44px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
</style>

<script>
(function () {
    let _multiple  = false;
    let _onSelect  = null;
    let _selected  = [];
    let _page      = 1;
    let _lastPage  = 1;
    let _folder    = '';
    let _loading   = false;

    const overlay       = document.getElementById('mediaPicker');
    const grid          = document.getElementById('mpGrid');
    const confirm       = document.getElementById('mpConfirm');
    const info          = document.getElementById('mpSelectedInfo');
    const loadMoreBtn   = document.getElementById('mpLoadMore');
    const uploadIn      = document.getElementById('mpUploadInput');
    const folderSelect  = document.getElementById('mpFolderFilter');

    document.getElementById('mpClose').addEventListener('click', close);
    overlay.addEventListener('click', e => { if (e.target === overlay) close(); });

    confirm.addEventListener('click', function () {
        if (_onSelect && _selected.length) _onSelect(_selected);
        close();
    });

    folderSelect.addEventListener('change', function () {
        _folder = this.value;
        resetAndLoad();
    });

    loadMoreBtn.addEventListener('click', function () {
        if (_page < _lastPage) {
            _page++;
            loadMedia(false);
        }
    });

    uploadIn.addEventListener('change', function () {
        if (!this.files.length) return;
        const fd = new FormData();
        fd.append('_token', document.querySelector('meta[name=csrf-token]') ?
            document.querySelector('meta[name=csrf-token]').content :
            document.querySelector('[name=_token]').value);
        fd.append('folder', _folder);
        Array.from(this.files).forEach(f => fd.append('files[]', f));

        fetch('{{ route('admin.media.store') }}', {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: fd
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                data.files.forEach(f => prependItem(f));
            }
        });
        this.value = '';
    });

    function open(opts) {
        _multiple = opts.multiple ?? false;
        _onSelect = opts.onSelect ?? null;
        _selected = [];
        _folder   = '';
        overlay.style.display = 'flex';
        updateUI();
        loadFolders();
        resetAndLoad();
    }

    function close() {
        overlay.style.display = 'none';
        _selected = [];
        document.querySelectorAll('.mp-item').forEach(i => i.classList.remove('selected'));
        updateUI();
    }

    function resetAndLoad() {
        _page = 1;
        _lastPage = 1;
        grid.innerHTML = '<div class="mp-loading">Yükleniyor...</div>';
        loadMedia(true);
    }

    function loadMedia(replace) {
        if (_loading) return;
        _loading = true;

        const url = '{{ route('admin.media.list') }}?page=' + _page + (_folder ? '&folder=' + encodeURIComponent(_folder) : '');

        fetch(url)
            .then(r => r.json())
            .then(data => {
                _loading   = false;
                _lastPage  = data.last_page;

                if (replace) {
                    grid.innerHTML = '';
                }

                if (!data.data.length && replace) {
                    grid.innerHTML = '<div class="mp-loading">Görsel bulunamadı.</div>';
                    loadMoreBtn.style.display = 'none';
                    return;
                }

                data.data.forEach(item => appendItem(item));

                loadMoreBtn.style.display = _page < _lastPage ? '' : 'none';
            })
            .catch(() => { _loading = false; });
    }

    function loadFolders() {
        fetch('{{ route('admin.media.folders') }}')
            .then(r => r.json())
            .then(folders => {
                // Mevcut option'ları temizle (sadece "Tümü" kalsın)
                while (folderSelect.options.length > 1) folderSelect.remove(1);
                folders.forEach(f => {
                    const opt = document.createElement('option');
                    opt.value = f;
                    opt.textContent = f;
                    folderSelect.appendChild(opt);
                });
            });
    }

    function appendItem(item) {
        const div = document.createElement('div');
        div.className = 'mp-item';
        div.dataset.id   = item.id;
        div.dataset.url  = item.url;
        div.dataset.name = item.name;
        div.innerHTML = `
            <div class="mp-thumb">
                <img src="${item.url}" alt="${item.name}" loading="lazy">
                <div class="mp-thumb-check">✓</div>
            </div>
            <div class="mp-name">${item.name}</div>`;

        div.addEventListener('click', function () {
            const id = this.dataset.id;
            if (_multiple) {
                const idx = _selected.findIndex(s => s.id == id);
                if (idx > -1) {
                    _selected.splice(idx, 1);
                    this.classList.remove('selected');
                } else {
                    _selected.push({ id: this.dataset.id, url: this.dataset.url, name: this.dataset.name });
                    this.classList.add('selected');
                }
            } else {
                document.querySelectorAll('.mp-item').forEach(i => i.classList.remove('selected'));
                _selected = [{ id: this.dataset.id, url: this.dataset.url, name: this.dataset.name }];
                this.classList.add('selected');
            }
            updateUI();
        });

        grid.appendChild(div);
    }

    function prependItem(item) {
        const div = document.createElement('div');
        div.className = 'mp-item';
        div.dataset.id   = item.id;
        div.dataset.url  = item.url;
        div.dataset.name = item.name;
        div.innerHTML = `
            <div class="mp-thumb">
                <img src="${item.url}" alt="${item.name}" loading="lazy">
                <div class="mp-thumb-check">✓</div>
            </div>
            <div class="mp-name">${item.name}</div>`;

        div.addEventListener('click', function () {
            const id = this.dataset.id;
            document.querySelectorAll('.mp-item').forEach(i => i.classList.remove('selected'));
            _selected = [{ id: this.dataset.id, url: this.dataset.url, name: this.dataset.name }];
            this.classList.add('selected');
            updateUI();
        });

        grid.insertBefore(div, grid.firstChild);
    }

    function updateUI() {
        const n = _selected.length;
        info.textContent = n ? `${n} görsel seçildi` : '';
        confirm.disabled = n === 0;
    }

    window.MediaPicker = { open, reload: () => resetAndLoad() };
})();
</script>
