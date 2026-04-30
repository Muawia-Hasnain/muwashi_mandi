@php
    $isFeatured = $ad->is_featured && $ad->featured_expires_at && $ad->featured_expires_at->isFuture();
    $isBoosted = $ad->is_boosted && $ad->boost_expires_at && $ad->boost_expires_at->isFuture();
@endphp

<a href="{{ route('ads.show', ['id' => $ad->id, 'slug' => $ad->slug]) }}" class="card {{ $isFeatured ? 'card-featured' : '' }}" style="text-decoration:none; color:var(--text); position:relative; {{ in_array($ad->ad_type, ['qurbani', 'ijtamai_hissa']) ? 'border: 2px solid var(--primary); background: #f0fdf4;' : '' }}">
    @if($isFeatured)
        <span class="ad-card-badge badge-featured" style="position:absolute; top:10px; left:10px; z-index:10;">
            <i class="fas fa-star"></i> FEATURED
        </span>
    @elseif($isBoosted)
        <span class="ad-card-badge badge-boosted" style="position:absolute; top:10px; left:10px; z-index:10;">
            <i class="fas fa-rocket"></i> BOOSTED
        </span>
    @endif

    @if($ad->ad_type === 'ijtamai_hissa')
        <span class="ad-card-badge" style="position:absolute; top:10px; right:10px; z-index:10; background:var(--primary); color:white; font-weight:700; box-shadow:0 2px 4px rgba(22, 163, 74, 0.4);">
            <i class="fas fa-kaaba"></i> QURBANI HISSA
        </span>
    @elseif($ad->ad_type === 'qurbani')
        <span class="ad-card-badge" style="position:absolute; top:10px; right:10px; z-index:10; background:#166534; color:white; font-weight:700; box-shadow:0 2px 4px rgba(22, 101, 52, 0.4);">
            <i class="fas fa-kaaba"></i> QURBANI
        </span>
    @endif

    @if($ad->images->count())
        <img src="{{ asset('storage/' . $ad->images->first()->image_path) }}" alt="{{ $ad->title }} - {{ $ad->category ? $ad->category->name : ucfirst($ad->animal_type) }} in {{ $ad->district ? $ad->district->name : $ad->city }}" class="ad-card-img">
    @else
        <div class="ad-card-img" style="display:flex;align-items:center;justify-content:center;background:#e2e8f0;">
            <i class="fas fa-image" style="font-size:2rem;color:#94a3b8;"></i>
        </div>
    @endif
    <div class="card-body">
        <div class="flex-between" style="margin-bottom:0.4rem;">
            <span class="ad-card-badge" style="background:#f1f5f9; color:#475569; display:inline-flex; align-items:center; gap:0.2rem;">
                @if($ad->category)
                    <span>{{ $ad->category->image_icon }}</span> {{ __($ad->category->name) }}
                @else
                    {{ __(ucfirst($ad->animal_type)) }}
                @endif
            </span>
            <span class="ad-location"><i class="fas fa-map-marker-alt"></i> 
                @if($ad->district)
                    {{ $ad->tehsil ? $ad->tehsil->name . ', ' : '' }}{{ $ad->district->name }}
                @else
                    {{ $ad->city }}
                @endif
            </span>
        </div>
        <h3 style="font-size:1rem; font-weight:600; margin-bottom:0.3rem; line-height:1.3;">{{ Str::limit($ad->title, 45) }}</h3>
        
        <div class="ad-price">
            Rs {{ number_format($ad->price) }} 
            @if($ad->ad_type === 'ijtamai_hissa')
                <span style="font-size:0.75rem; color:var(--text-light); font-weight:normal;">/ hissa</span>
            @endif
        </div>
        
        <div style="font-size:0.8rem; color:var(--text-light); margin-top:0.4rem; display:flex; justify-content:space-between; align-items:center;">
            <span><i class="fas fa-clock"></i> {{ $ad->created_at->diffForHumans() }}</span>
            @if($ad->ad_type === 'ijtamai_hissa')
                <span style="color:var(--primary); font-weight:600;">{{ $ad->booked_hisse }}/{{ $ad->total_hisse }} Booked</span>
            @endif
        </div>
    </div>
</a>
