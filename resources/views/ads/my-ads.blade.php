@extends('layouts.app')
@section('title', 'My Ads')

@section('content')
<div class="flex-between mb-3">
    <h1 style="font-size:1.5rem; font-weight:700;"><i class="fas fa-list" style="color:var(--primary);"></i> My Ads</h1>
    <a href="{{ route('ads.create') }}" class="btn btn-primary"><i class="fas fa-plus"></i> Post New Ad</a>
</div>

@if($ads->count())
    <div class="grid-ads">
        @foreach($ads as $ad)
            <div class="card">
                @if($ad->images->count())
                    <img src="{{ asset('storage/' . $ad->images->first()->image_path) }}" alt="{{ $ad->title }}" class="ad-card-img">
                @else
                    <div class="ad-card-img" style="display:flex;align-items:center;justify-content:center;background:#e2e8f0;">
                        <i class="fas fa-image" style="font-size:2rem;color:#94a3b8;"></i>
                    </div>
                @endif
                <div class="card-body">
                    <div class="flex-between" style="margin-bottom:0.4rem;">
                        <span class="ad-card-badge badge-{{ $ad->animal_type }}">{{ $ad->animal_type }}</span>
                        <span class="ad-card-badge status-{{ $ad->status }}">{{ ucfirst($ad->status) }}</span>
                    </div>
                    <h3 style="font-size:1rem; font-weight:600; margin-bottom:0.3rem;">{{ Str::limit($ad->title, 40) }}</h3>
                    <div class="ad-price">Rs {{ number_format($ad->price) }}</div>

                    @if($ad->status === 'rejected' && $ad->rejection_reason)
                        <div style="background:#fee2e2; color:#991b1b; padding:0.4rem 0.6rem; border-radius:6px; font-size:0.8rem; margin-top:0.5rem;">
                            <i class="fas fa-ban"></i> {{ $ad->rejection_reason }}
                        </div>
                    @endif

                    <div style="display:flex; gap:0.5rem; margin-top:0.8rem;">
                        <a href="{{ route('ads.show', ['id' => $ad->id, 'slug' => $ad->slug]) }}" class="btn btn-outline btn-sm" title="View"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('ads.edit', $ad) }}" class="btn btn-secondary btn-sm" title="Edit"><i class="fas fa-edit"></i></a>
                        @if($ad->status === 'payment_pending')
                            <a href="{{ route('payments.create', ['ad_id' => $ad->id, 'type' => 'ad_post']) }}" class="btn btn-danger btn-sm" title="Pay Now"><i class="fas fa-money-bill-wave"></i> Pay</a>
                        @endif
                        @if($ad->status === 'approved')
                            <form action="{{ route('ads.sold', $ad) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn btn-success btn-sm" title="Mark as Sold"><i class="fas fa-check-circle"></i> Sold</button>
                            </form>
                        @endif
                        <form action="{{ route('ads.destroy', $ad) }}" method="POST" class="delete-form" data-confirm-title="Delete this Ad?" data-confirm-text="This will also permanently delete all images and chats associated with this ad.">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" title="Delete"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="pagination">{{ $ads->links() }}</div>
@else
    <div class="empty-state">
        <i class="fas fa-box-open"></i>
        <h3>No ads yet</h3>
        <p>Start selling by posting your first ad!</p>
        <a href="{{ route('ads.create') }}" class="btn btn-primary mt-2"><i class="fas fa-plus"></i> Post Ad</a>
    </div>
@endif
@endsection
