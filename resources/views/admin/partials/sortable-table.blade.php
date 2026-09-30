@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.3/Sortable.min.js"></script>
<script>
(function () {
    const tbody = document.getElementById(@json($tbodyId));
    if (!tbody || !tbody.querySelector('tr[data-id]')) return;

    Sortable.create(tbody, {
        handle: '.drag-handle',
        animation: 150,
        ghostClass: 'sortable-ghost',
        onEnd: function () {
            const ids = Array.from(tbody.querySelectorAll('tr[data-id]')).map(function (el) {
                return el.dataset.id;
            });
            const token = document.querySelector('meta[name="csrf-token"]')?.content
                || document.querySelector('[name=_token]')?.value;

            fetch(@json($url), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ ids: ids })
            });
        }
    });
})();
</script>
@endpush
