@extends('admin.layout')

@section('title', 'Kategoriler')

@section('content')

<div class="page-header">
    <h2>Kategoriler</h2>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">+ Yeni Kategori</a>
</div>

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>Ad</th>
                <th>AD (EN)</th>
                <th>Sıra</th>
                <th>Durum</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $cat)
                <tr>
                    <td>{{ $cat->name }}</td>
                    <td>{{ $cat->name_en ?: '—' }}</td>
                    <td>{{ $cat->order }}</td>
                    <td>
                        <span class="badge {{ $cat->is_active ? 'badge-active' : 'badge-inactive' }}">
                            {{ $cat->is_active ? 'Aktif' : 'Pasif' }}
                        </span>
                    </td>
                    <td style="text-align:right; white-space:nowrap;">
                        <a href="{{ route('admin.categories.edit', $cat) }}" class="btn btn-secondary btn-sm">Düzenle</a>
                        <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" style="display:inline" onsubmit="return confirm('Silinsin mi?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Sil</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" style="color:#bbb;text-align:center;padding:24px;">Henüz kategori yok.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
