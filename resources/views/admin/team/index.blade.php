@extends('admin.layout')

@section('title', 'Ekip')

@section('content')

<div class="page-header">
    <h2>Ekip</h2>
    <a href="{{ route('admin.team.create') }}" class="btn btn-primary">+ Yeni Üye</a>
</div>

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>Fotoğraf</th>
                <th>Ad</th>
                <th>Ünvan</th>
                <th>Sıra</th>
                <th>Durum</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($members as $member)
                <tr>
                    <td style="width:56px;">
                        @if($member->photo)
                            <img src="{{ asset('storage/' . $member->photo) }}"
                                 style="width:48px;height:48px;object-fit:cover;display:block;">
                        @else
                            <div style="width:48px;height:48px;background:#f0f0f0;"></div>
                        @endif
                    </td>
                    <td><strong>{{ $member->name }}</strong></td>
                    <td>{{ $member->title ?: '—' }}</td>
                    <td>{{ $member->order }}</td>
                    <td>
                        <span class="badge {{ $member->is_active ? 'badge-active' : 'badge-inactive' }}">
                            {{ $member->is_active ? 'Aktif' : 'Pasif' }}
                        </span>
                    </td>
                    <td style="text-align:right;white-space:nowrap;">
                        <a href="{{ route('admin.team.edit', $member) }}" class="btn btn-secondary btn-sm">Düzenle</a>
                        <form action="{{ route('admin.team.destroy', $member) }}" method="POST" style="display:inline" onsubmit="return confirm('Silinsin mi?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Sil</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" style="color:#bbb;text-align:center;padding:24px;">Henüz ekip üyesi yok.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
