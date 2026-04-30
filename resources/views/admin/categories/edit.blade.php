@extends('layouts.app')
@section('title', 'Edit Category')

@section('content')
<div style="max-width:600px; margin:0 auto;">
    <h1 style="font-size:1.5rem; font-weight:700; margin-bottom:1.5rem;"><i class="fas fa-edit" style="color:var(--primary);"></i> Edit Category</h1>

    <div class="card" style="padding:2rem;">
        <form action="{{ route('admin.categories.update', $category) }}" method="POST">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-label">Category Name *</label>
                <input type="text" name="name" class="form-input" value="{{ old('name', $category->name) }}" required>
                @error('name') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Icon (Emoji) *</label>
                <input type="text" name="image_icon" class="form-input" value="{{ old('image_icon', $category->image_icon) }}" required>
                @error('image_icon') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group" style="display:flex; align-items:center; gap:0.5rem;">
                <input type="checkbox" name="is_active" id="isActive" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }} style="width:1.2rem; height:1.2rem;">
                <label for="isActive" class="form-label" style="margin-bottom:0;">Active Status</label>
            </div>

            <div style="margin-top:2rem; display:flex; gap:1rem;">
                <button type="submit" class="btn btn-primary" style="flex:1; justify-content:center;">Update Category</button>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline" style="flex:1; justify-content:center;">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
