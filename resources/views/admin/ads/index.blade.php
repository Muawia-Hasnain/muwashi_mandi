@extends('layouts.app')
@section('title', 'Manage Ads')

@section('content')
<div class="flex-between mb-3">
    <h1 style="font-size:1.5rem; font-weight:700;"><i class="fas fa-list" style="color:var(--primary);"></i> Manage Ads</h1>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Dashboard</a>
</div>

{{-- Filter --}}
<div style="display:flex; gap:0.5rem; margin-bottom:1.5rem;">
    <a href="{{ route('admin.ads.index') }}" class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-outline' }}">All</a>
    @foreach(['pending','approved','rejected','sold'] as $s)
        <a href="{{ route('admin.ads.index', ['status' => $s]) }}" class="btn btn-sm {{ request('status') == $s ? 'btn-primary' : 'btn-outline' }}">{{ ucfirst($s) }}</a>
    @endforeach
</div>

<div class="card table-wrapper">
    <table>
        <thead><tr><th>Title</th><th>User</th><th>Type</th><th>Price</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($ads as $ad)
                <tr>
                    <td><a href="{{ route('ads.show', ['id' => $ad->id, 'slug' => $ad->slug]) }}">{{ Str::limit($ad->title, 30) }}</a></td>
                    <td>{{ $ad->user->name }}</td>
                    <td><span class="ad-card-badge badge-{{ $ad->animal_type }}">{{ $ad->animal_type }}</span></td>
                    <td>Rs {{ number_format($ad->price) }}</td>
                    <td><span class="ad-card-badge status-{{ $ad->status }}">{{ $ad->status }}</span></td>
                    <td>
                        <div style="display:flex; gap:0.3rem; flex-wrap:wrap;">
                            @if($ad->status !== 'approved')
                                <form action="{{ route('admin.ads.approve', $ad) }}" method="POST">@csrf @method('PATCH')
                                    <button class="btn btn-primary btn-sm"><i class="fas fa-check"></i></button>
                                </form>
                            @endif
                            @if($ad->status !== 'rejected')
                                <form action="{{ route('admin.ads.reject', $ad) }}" method="POST" style="display:flex; gap:0.2rem;">
                                    @csrf @method('PATCH')
                                    <input type="text" name="rejection_reason" placeholder="Reason..." class="form-input" style="width:120px; padding:0.3rem 0.5rem; font-size:0.8rem;">
                                    <button class="btn btn-danger btn-sm"><i class="fas fa-times"></i></button>
                                </form>
                            @endif
                            <form action="{{ route('admin.ads.destroy', $ad) }}" method="POST" class="delete-form" data-confirm-title="Delete Ad Permanently?" data-confirm-text="This ad will be completely removed from the database.">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm" title="Delete"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center" style="padding:2rem; color:var(--text-light);">No ads found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="pagination">{{ $ads->links() }}</div>
@endsection
