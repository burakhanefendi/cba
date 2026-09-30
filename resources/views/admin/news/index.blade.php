@extends('admin.layout')

@section('title', 'Haberler')

@section('content')

<div class="page-header">
    <h2>Haberler</h2>
    <a href="{{ route('admin.news.create') }}" class="btn btn-primary">+ Yeni Haber</a>
</div>
<p class="table-hint">Sıralamak için satırları sürükleyin. Bu sıra sitede de kullanılır.</p>

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th style="width:36px;"></th>
                <th>Başlık</th>
                <th>Tarih</th>
                <th>Durum</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="sortableNews">
            @forelse($news as $item)
                <tr data-id="{{ $item->id }}">
                    <td class="drag-handle" title="Sürükle">⠿</td>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->formatted_date }}</td>
                    <td>
                        <span class="badge {{ $item->is_active ? 'badge-active' : 'badge-inactive' }}">
                            {{ $item->is_active ? 'Aktif' : 'Pasif' }}
                        </span>
                    </td>
                    <td style="text-align:right; white-space:nowrap;">
                        <a href="{{ route('admin.news.edit', $item) }}" class="btn btn-secondary btn-sm">Düzenle</a>
                        <form action="{{ route('admin.news.destroy', $item) }}" method="POST" style="display:inline" onsubmit="return confirm('Silinsin mi?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Sil</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" style="color:#bbb;text-align:center;padding:24px;">Henüz haber yok.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@include('admin.partials.sortable-table', [
    'tbodyId' => 'sortableNews',
    'url' => route('admin.news.reorder'),
])

@endsection
