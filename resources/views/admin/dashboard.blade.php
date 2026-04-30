@extends('layouts.app')
@section('title', 'Admin Dashboard')

@push('styles')
<style>
    .stat-card { padding:1.5rem; text-align:center; border-radius:var(--radius); color:#fff; }
    .stat-card .stat-num { font-size:2rem; font-weight:800; }
    .stat-card .stat-label { font-size:0.85rem; opacity:0.9; }
</style>
@endpush

@section('content')
<h1 style="font-size:1.5rem; font-weight:700; margin-bottom:1.5rem;"><i class="fas fa-shield-alt" style="color:var(--accent);"></i> Admin Dashboard</h1>

{{-- Quick Nav --}}
<div style="display:flex; gap:0.5rem; margin-bottom:1.5rem; flex-wrap:wrap;">
    <a href="{{ route('admin.ads.index') }}" class="btn btn-primary"><i class="fas fa-list"></i> Manage Ads</a>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary" style="background:#4b5563;"><i class="fas fa-tags"></i> Categories</a>
    <a href="{{ route('admin.locations.index') }}" class="btn btn-secondary" style="background:#64748b;"><i class="fas fa-map-marker-alt"></i> Locations</a>
    <a href="{{ route('admin.payments.index') }}" class="btn btn-success" style="background:#10b981; color:#fff;"><i class="fas fa-receipt"></i> Payments</a>
    <a href="{{ route('admin.chats.index') }}" class="btn btn-accent"><i class="fas fa-comments"></i> User Support</a>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary"><i class="fas fa-users"></i> Manage Users</a>
</div>

{{-- Stats --}}
<div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(180px,1fr)); gap:1rem; margin-bottom:2rem;">
    <div class="stat-card" style="background:linear-gradient(135deg,#16a34a,#15803d);">
        <div class="stat-num">{{ $stats['total_users'] }}</div>
        <div class="stat-label">Total Users</div>
    </div>
    <div class="stat-card" style="background:linear-gradient(135deg,#0d9488,#0f766e);">
        <div class="stat-num">{{ $stats['total_ads'] }}</div>
        <div class="stat-label">Total Ads</div>
    </div>
    <div class="stat-card" style="background:linear-gradient(135deg,#f59e0b,#d97706);">
        <div class="stat-num">{{ $stats['pending_ads'] }}</div>
        <div class="stat-label">Pending Approval</div>
    </div>
    <div class="stat-card" style="background:linear-gradient(135deg,#ef4444,#dc2626);">
        <div class="stat-num">{{ $stats['payment_pending'] }}</div>
        <div class="stat-label">Wait Payment</div>
    </div>
    <div class="stat-card" style="background:linear-gradient(135deg,#10b981,#059669);">
        <div class="stat-num">{{ $stats['pending_payments'] }}</div>
        <div class="stat-label">Pending Payments</div>
    </div>
</div>

<div style="display:grid; grid-template-columns:1.5fr 1fr; gap:1.5rem;">
    {{-- Recent Ads --}}
    <div>
        <h2 style="font-size:1.2rem; font-weight:700; margin-bottom:1rem;">Recent Ads</h2>
        <div class="card table-wrapper">
            <table>
                <thead><tr><th>Title</th><th>User</th><th>Status</th></tr></thead>
                <tbody>
                    @foreach($recentAds as $ad)
                        <tr>
                            <td><a href="{{ route('ads.show', ['id' => $ad->id, 'slug' => $ad->slug]) }}">{{ Str::limit($ad->title, 30) }}</a></td>
                            <td>{{ $ad->user->name }}</td>
                            <td><span class="ad-card-badge status-{{ $ad->status }}">{{ $ad->status }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Recent Payments --}}
    <div>
        <h2 style="font-size:1.2rem; font-weight:700; margin-bottom:1rem;">Recent Payments</h2>
        <div class="card table-wrapper">
            <table>
                <thead><tr><th>User</th><th>Amount</th><th>Status</th></tr></thead>
                <tbody>
                    @foreach($recentPayments as $payment)
                        <tr>
                            <td>{{ $payment->user->name }}</td>
                            <td>Rs. {{ number_format($payment->amount) }}</td>
                            <td><span class="ad-card-badge status-{{ $payment->status }}">{{ $payment->status }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
