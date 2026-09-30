@extends('admin.layout')

@section('title', $labels['plural'])

@section('content')

<div class="page-header">
    <h2>{{ $labels['plural'] }}</h2>
    <a href="{{ route($routes . '.create') }}" class="btn btn-primary">+ Yeni {{ $labels['singular'] }}</a>
</div>
<p class="table-hint">Sıralamak için satırları sürükleyin. Bu sıra sitede de kullanılır.</p>

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th style="width:36px;"></th>
                <th>Görsel</th>
                <th>Başlık</th>
                <th>PDF</th>
                <th>Durum</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="sortableEntries">
            @forelse($entries as $entry)
                <tr data-id="{{ $entry->id }}">
                    <td class="drag-handle" title="Sürükle">⠿</td>
                    <td style="width:56px;">
                        @if($entry->image)
                            <img src="{{ asset('storage/' . $entry->image) }}"
                                 style="width:48px;height:48px;object-fit:cover;display:block;">
                        @else
                            <div style="width:48px;height:48px;background:#f0f0f0;"></div>
                        @endif
                    </td>
                    <td><strong>{{ $entry->title }}</strong></td>
                    <td>{{ $entry->pdf ? 'Var' : '—' }}</td>
                    <td>
                        <span class="badge {{ $entry->is_active ? 'badge-active' : 'badge-inactive' }}">
                            {{ $entry->is_active ? 'Aktif' : 'Pasif' }}
                        </span>
                    </td>
                    <td style="text-align:right;white-space:nowrap;">
                        <a href="{{ route($routes . '.edit', $entry) }}" class="btn btn-secondary btn-sm">Düzenle</a>
                        <form action="{{ route($routes . '.destroy', $entry) }}" method="POST" style="display:inline" onsubmit="return confirm('Silinsin mi?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Sil</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" style="color:#bbb;text-align:center;padding:24px;">Henüz kayıt yok.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@include('admin.partials.sortable-table', [
    'tbodyId' => 'sortableEntries',
    'url' => route($routes . '.reorder'),
])

@endsection
