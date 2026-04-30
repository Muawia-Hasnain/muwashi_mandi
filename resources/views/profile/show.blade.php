@extends('layouts.app')
@section('title', 'Profile')

@section('content')
<div style="max-width:600px; margin:0 auto;">
    <div class="card" style="padding:2rem; text-align:center;">
        <div style="width:80px; height:80px; border-radius:50%; background:var(--primary-light); display:flex; align-items:center; justify-content:center; font-weight:700; color:var(--primary); font-size:2rem; margin:0 auto 1rem;">
            @if($user->avatar_path)
                <img src="{{ asset('storage/' . $user->avatar_path) }}" style="width:80px; height:80px; border-radius:50%; object-fit:cover;">
            @else
                {{ strtoupper(substr($user->name, 0, 1)) }}
            @endif
        </div>
        <h1 style="font-size:1.5rem; font-weight:700;">{{ $user->name }}</h1>
        <p style="color:var(--text-light);">{{ $user->email }}</p>

        <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:1rem; margin:1.5rem 0; padding:1rem 0; border-top:1px solid var(--border); border-bottom:1px solid var(--border);">
            <div>
                <div style="font-size:1.3rem; font-weight:700; color:var(--primary);">{{ $user->ads_count }}</div>
                <div style="font-size:0.85rem; color:var(--text-light);">Ads</div>
            </div>
            <div>
                <div style="font-size:1.3rem; font-weight:700; color:var(--secondary);">{{ $user->city ?? '—' }}</div>
                <div style="font-size:0.85rem; color:var(--text-light);">City</div>
            </div>
            <div>
                <div style="font-size:1.3rem; font-weight:700; color:var(--accent);">{{ $user->created_at->format('M Y') }}</div>
                <div style="font-size:0.85rem; color:var(--text-light);">Joined</div>
            </div>
        </div>

        <div style="text-align:left; margin-bottom:1.5rem;">
            <div style="margin-bottom:0.5rem;"><strong><i class="fas fa-phone"></i> Phone:</strong> {{ $user->phone ?? 'Not set' }}</div>
            <div><strong><i class="fas fa-map-marker-alt"></i> City:</strong> {{ $user->city ?? 'Not set' }}</div>
        </div>

        <a href="{{ route('profile.edit') }}" class="btn btn-primary"><i class="fas fa-edit"></i> Edit Profile</a>
    </div>
</div>
@endsection
