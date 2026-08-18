@extends('admin.layout')

@section('title', 'Projeler')

@section('content')

<div class="page-header">
    <h2>Projeler</h2>
    <a href="{{ route('admin.projects.create') }}" class="btn btn-primary">+ Yeni Proje</a>
</div>

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>Görsel</th>
                <th>Başlık</th>
                <th>Kategori</th>
                <th>Yıl</th>
                <th>Durum</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($projects as $project)
                <tr>
                    <td style="width:56px;">
                        @if($project->cover_image)
                            <img src="{{ asset('storage/' . $project->cover_image) }}"
                                 style="width:48px;height:48px;object-fit:cover;display:block;">
                        @else
                            <div style="width:48px;height:48px;background:#f0f0f0;"></div>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $project->title }}</strong>
                        @if($project->subtitle)
                            <br><small style="color:#aaa;">{{ $project->subtitle }}</small>
                        @endif
                    </td>
                    <td>{{ $project->categories->pluck('name')->implode(', ') ?: '—' }}</td>
                    <td>{{ $project->year ?? '—' }}</td>
                    <td>
                        <span class="badge {{ $project->is_active ? 'badge-active' : 'badge-inactive' }}">
                            {{ $project->is_active ? 'Aktif' : 'Pasif' }}
                        </span>
                    </td>
                    <td style="text-align:right; white-space:nowrap;">
                        <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-secondary btn-sm">Düzenle</a>
                        <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" style="display:inline" onsubmit="return confirm('Silinsin mi?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Sil</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" style="color:#bbb;text-align:center;padding:24px;">Henüz proje yok.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top:16px;">{{ $projects->links() }}</div>

@endsection
