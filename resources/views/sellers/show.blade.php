@extends('layouts.app')
@section('title', $user->name . "'s Profile")

@section('content')
<div class="container" style="margin-top: 2rem;">
    {{-- Seller Profile Header --}}
    <div class="card" style="padding: 2rem; margin-bottom: 2rem; background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border: 1px solid var(--border);">
        <div style="display: flex; align-items: center; gap: 2rem; flex-wrap: wrap;">
            <div style="width: 100px; height: 100px; border-radius: 50%; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; font-size: 3rem; font-weight: 700; box-shadow: 0 4px 12px rgba(var(--primary-rgb), 0.3);">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div style="flex: 1; min-width: 250px;">
                <h1 style="font-size: 2rem; font-weight: 800; margin-bottom: 0.5rem; color: var(--text);">{{ $user->name }}</h1>
                <div style="display: flex; gap: 1.5rem; flex-wrap: wrap; color: var(--text-light); font-size: 0.95rem;">
                    <span><i class="fas fa-map-marker-alt" style="color: var(--primary); margin-right: 5px;"></i> {{ $user->city ?? 'Location N/A' }}</span>
                    <span><i class="fas fa-calendar-alt" style="color: var(--primary); margin-right: 5px;"></i> Member since {{ $user->created_at->format('M Y') }}</span>
                    <span><i class="fas fa-check-circle" style="color: #22c55e; margin-right: 5px;"></i> Verified Seller</span>
                </div>
            </div>
            <div style="display: flex; gap: 1rem; text-align: center;">
                <div style="padding: 0.8rem 1.5rem; background: #fff; border-radius: 12px; border: 1px solid var(--border);">
                    <div style="font-size: 1.5rem; font-weight: 800; color: var(--primary);">{{ $activeAds }}</div>
                    <div style="font-size: 0.8rem; color: var(--text-light); text-transform: uppercase; letter-spacing: 1px;">Active Ads</div>
                </div>
                <div style="padding: 0.8rem 1.5rem; background: #fff; border-radius: 12px; border: 1px solid var(--border);">
                    <div style="font-size: 1.5rem; font-weight: 800; color: var(--text);">{{ $totalAds }}</div>
                    <div style="font-size: 0.8rem; color: var(--text-light); text-transform: uppercase; letter-spacing: 1px;">Total Posted</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Seller's Ads --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="font-size: 1.5rem; font-weight: 700; color: var(--text);">Ads by {{ $user->name }}</h2>
    </div>

    @if($ads->count() > 0)
        <div class="grid-ads">
            @foreach($ads as $ad)
                @include('components.ad-card', ['ad' => $ad])
            @endforeach
        </div>
        <div style="margin-top: 2rem;">
            {{ $ads->links() }}
        </div>
    @else
        <div class="empty-state" style="padding: 4rem 2rem;">
            <i class="fas fa-folder-open" style="font-size: 3rem; color: var(--text-light); margin-bottom: 1rem;"></i>
            <p style="color: var(--text-light); font-size: 1.1rem;">This seller currently has no active ads.</p>
            <a href="{{ route('ads.index') }}" class="btn btn-primary" style="margin-top: 1rem;">Browse Other Ads</a>
        </div>
    @endif
</div>
@endsection
