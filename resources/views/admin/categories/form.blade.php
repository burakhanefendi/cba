@extends('admin.layout')

@section('title', isset($category) ? 'Kategori Düzenle' : 'Yeni Kategori')

@section('content')

<div class="page-header">
    <h2>{{ isset($category) ? 'Kategori Düzenle' : 'Yeni Kategori' }}</h2>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Geri</a>
</div>

<form action="{{ isset($category) ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
      method="POST">
    @csrf
    @if(isset($category)) @method('PUT') @endif

    <div class="form-single">
        <div class="form-card">
            <div class="form-group">
                <label>Ad *</label>
                <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" required>
                @error('name')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label>Ad (EN)</label>
                <input type="text" name="name_en" value="{{ old('name_en', $category->name_en ?? '') }}">
            </div>
            <div class="form-group">
                <label>Sıra</label>
                <input type="number" name="order" value="{{ old('order', $category->order ?? 0) }}" style="width:90px;">
            </div>
            <div class="form-group" style="margin-bottom:0;">
                <div class="form-check">
                    <input type="checkbox" name="is_active" id="is_active" value="1"
                        {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}>
                    <label for="is_active">Aktif</label>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Kaydet</button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">İptal</a>
        </div>
    </div>
</form>

@endsection
