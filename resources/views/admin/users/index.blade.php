@extends('layouts.app')
@section('title', 'Manage Users')

@section('content')
<div class="flex-between mb-3">
    <h1 style="font-size:1.5rem; font-weight:700;"><i class="fas fa-users" style="color:var(--secondary);"></i> Manage Users</h1>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Dashboard</a>
</div>

{{-- Search --}}
<form method="GET" action="{{ route('admin.users.index') }}" style="margin-bottom:1.5rem; display:flex; gap:0.5rem;">
    <input type="text" name="search" class="form-input" value="{{ request('search') }}" placeholder="Search by name or email..." style="max-width:300px;">
    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i></button>
</form>

<div class="card table-wrapper">
    <table>
        <thead><tr><th>Name</th><th>Email</th><th>City</th><th>Ads</th><th>Role</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->city ?? '—' }}</td>
                    <td>{{ $user->ads_count }}</td>
                    <td><span class="ad-card-badge {{ $user->role === 'admin' ? 'status-approved' : '' }}">{{ $user->role }}</span></td>
                    <td>
                        @if($user->is_banned)
                            <span class="ad-card-badge status-rejected">Banned</span>
                        @else
                            <span class="ad-card-badge status-approved">Active</span>
                        @endif
                    </td>
                    <td>
                        @if(!$user->isAdmin())
                            <form action="{{ route('admin.users.ban', $user) }}" method="POST">
                                @csrf @method('PATCH')
                                <button class="btn btn-sm {{ $user->is_banned ? 'btn-primary' : 'btn-danger' }}">
                                    {{ $user->is_banned ? 'Unban' : 'Ban' }}
                                </button>
                            </form>
                        @else
                            <span style="color:var(--text-light); font-size:0.85rem;">Admin</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center" style="padding:2rem; color:var(--text-light);">No users found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="pagination">{{ $users->links() }}</div>
@endsection
