@extends('layouts.app')
@section('title', 'Category Management')

@section('content')
<div class="flex-between mb-2">
    <h1 style="font-size:1.5rem; font-weight:700;"><i class="fas fa-tags" style="color:var(--primary);"></i> Animal Categories</h1>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Add New Category</a>
</div>

<div class="card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Icon</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Ads Count</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                <tr>
                    <td style="font-size:1.5rem;">{{ $category->image_icon }}</td>
                    <td><strong>{{ $category->name }}</strong></td>
                    <td>{{ $category->slug }}</td>
                    <td>{{ $category->ads_count }}</td>
                    <td>
                        <span class="status-badge {{ $category->is_active ? 'status-approved' : 'status-rejected' }}" 
                              id="status-{{ $category->id }}"
                              style="padding:0.2rem 0.5rem; border-radius:4px; font-size:0.8rem; font-weight:600;">
                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex; gap:0.5rem;">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-outline btn-sm">Edit</a>
                            <button onclick="toggleStatus({{ $category->id }})" class="btn btn-secondary btn-sm">Toggle</button>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="delete-form" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
function toggleStatus(id) {
    fetch(`/admin/categories/${id}/toggle`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': window.csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const badge = document.getElementById(`status-${id}`);
            if (data.is_active) {
                badge.textContent = 'Active';
                badge.className = 'status-badge status-approved';
            } else {
                badge.textContent = 'Inactive';
                badge.className = 'status-badge status-rejected';
            }
        }
    });
}
</script>
@endpush
@endsection
