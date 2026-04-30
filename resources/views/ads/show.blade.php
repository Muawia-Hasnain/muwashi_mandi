@extends('layouts.app')
@section('title', $ad->title)
@section('canonical', route('ads.show', ['id' => $ad->id, 'slug' => $ad->slug]))

@section('meta')
    <meta property="og:title" content="{{ $ad->title }} — Muwashi Mandi">
    <meta property="og:description" content="{{ Str::limit($ad->description, 150) }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    @if($ad->images->count())
        <meta property="og:image" content="{{ asset('storage/' . $ad->images->first()->image_path) }}">
    @endif
    <meta name="twitter:card" content="summary_large_image">
    {{-- Schema.org Markup for Google --}}
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org/",
      "@@type": "Product",
      "name": "{{ $ad->title }}",
      "image": [
        @foreach($ad->images as $img)
          "{{ asset('storage/' . $img->image_path) }}"{{ !$loop->last ? ',' : '' }}
        @endforeach
      ],
      "description": "{{ Str::limit(str_replace(["\r", "\n"], ' ', $ad->description), 160) }}",
      "sku": "AD-{{ $ad->id }}",
      "offers": {
        "@@type": "Offer",
        "url": "{{ url()->current() }}",
        "priceCurrency": "PKR",
        "price": "{{ $ad->price }}",
        "itemCondition": "https://schema.org/NewCondition",
        "availability": "https://schema.org/InStock"
      }
    }
    </script>
@endsection

@section('content')
@push('styles')
<style>
    .ad-detail-grid {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 2rem;
        align-items: start;
    }
    @media (max-width: 768px) {
        .ad-detail-grid {
            grid-template-columns: 1fr;
        }
    }
    .breadcrumb {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        color: var(--text-light);
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }
    .breadcrumb a {
        color: var(--primary);
        text-decoration: none;
    }
    .breadcrumb a:hover {
        text-decoration: underline;
    }
    .breadcrumb i {
        font-size: 0.7rem;
    }
</style>
@endpush

<nav class="breadcrumb" aria-label="breadcrumb">
    <a href="{{ route('home') }}"><i class="fas fa-home"></i> Home</a>
    <i class="fas fa-chevron-right"></i>
    <a href="{{ route('ads.index') }}">Ads</a>
    <i class="fas fa-chevron-right"></i>
    <a href="{{ route('ads.index', ['animal_type' => $ad->animal_type]) }}">{{ ucfirst($ad->animal_type) }}</a>
    @if($ad->category)
        <i class="fas fa-chevron-right"></i>
        <a href="{{ route('ads.index', ['category' => $ad->category_id]) }}">{{ $ad->category->name }}</a>
    @endif
    <i class="fas fa-chevron-right"></i>
    <span style="color:var(--text); font-weight:600;">{{ Str::limit($ad->title, 40) }}</span>
</nav>

<div class="ad-detail-grid">
    {{-- Left: Images & Details --}}
    <div>
        {{-- Image Gallery --}}
        <div class="card" style="margin-bottom:1.5rem; overflow:hidden;">
            @if($ad->images->count())
                <img id="mainImage" src="{{ asset('storage/' . $ad->images->first()->image_path) }}" alt="{{ $ad->title }} - {{ $ad->category ? $ad->category->name : ucfirst($ad->animal_type) }} in {{ $ad->district ? $ad->district->name : $ad->city }}"
                     style="width:100%; height:400px; object-fit:cover;">
                @if($ad->images->count() > 1)
                    <div style="display:flex; gap:0.5rem; padding:0.8rem; overflow-x:auto;">
                        @foreach($ad->images as $img)
                            <img src="{{ asset('storage/' . $img->image_path) }}" alt="Thumbnail {{ $loop->iteration }} of {{ $ad->title }}"
                                 onclick="document.getElementById('mainImage').src=this.src"
                                 style="width:80px; height:60px; object-fit:cover; border-radius:8px; cursor:pointer; border:2px solid transparent; transition:border 0.2s;"
                                 onmouseover="this.style.borderColor='var(--primary)'" onmouseout="this.style.borderColor='transparent'">
                        @endforeach
                    </div>
                @endif
            @else
                <div style="height:400px; display:flex; align-items:center; justify-content:center; background:#e2e8f0;">
                    <i class="fas fa-image" style="font-size:4rem; color:#94a3b8;"></i>
                </div>
            @endif
        </div>

        {{-- Description --}}
        <div class="card" style="padding:1.5rem;">
            <h2 style="font-size:1.2rem; font-weight:700; margin-bottom:1rem;"><i class="fas fa-info-circle" style="color:var(--primary);"></i> Description</h2>
            <p style="white-space: pre-wrap; line-height:1.7;">{{ $ad->description }}</p>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-top:1.5rem; padding-top:1rem; border-top:1px solid var(--border);">
                @if($ad->breed)
                    <div><strong><i class="fas fa-dna"></i> Breed:</strong> {{ $ad->breed }}</div>
                @endif
                @if($ad->age_info)
                    <div><strong><i class="fas fa-birthday-cake"></i> Age:</strong> {{ $ad->age_info }}</div>
                @endif
                <div><strong><i class="fas fa-eye"></i> Views:</strong> {{ number_format($ad->views_count) }}</div>
                <div><strong><i class="fas fa-clock"></i> Posted:</strong> {{ $ad->created_at->diffForHumans() }}</div>
                @if($ad->expires_at)
                    <div><strong><i class="fas fa-calendar-times"></i> Expires:</strong> {{ $ad->expires_at->format('d M Y') }}</div>
                @endif
            </div>
        </div>
    </div>

    {{-- Right: Price + Seller Info --}}
    <div>
        {{-- Price Card --}}
        <div class="card" style="padding:1.5rem; margin-bottom:1rem;">
            <div style="display:flex; gap:0.5rem; margin-bottom:0.5rem;">
                <span class="ad-card-badge badge-{{ $ad->animal_type }}" style="font-size:0.85rem;">{{ __(ucfirst($ad->animal_type)) }}</span>
                @if($ad->is_featured) <span class="ad-card-badge badge-featured">Featured</span> @endif
                @if($ad->is_boosted) <span class="ad-card-badge badge-boosted">Boosted</span> @endif
            </div>
            <h1 style="font-size:1.4rem; font-weight:700; margin:0.5rem 0;">{{ $ad->title }}</h1>
            
            @if($ad->ad_type === 'ijtamai_hissa')
                <div class="ad-price" style="font-size:1.8rem; margin-bottom:0.2rem;">Rs {{ number_format($ad->price) }} <span style="font-size:0.9rem; font-weight:normal; color:var(--text-light);">per hissa</span></div>
                @if($ad->org_name)
                    <div style="font-size:0.9rem; color:var(--primary); font-weight:600; margin-bottom:0.5rem;"><i class="fas fa-building"></i> {{ $ad->org_name }}</div>
                @endif
            @else
                <div class="ad-price" style="font-size:1.8rem; margin-bottom:0.5rem;">Rs {{ number_format($ad->price) }}</div>
                @if($ad->ad_type === 'qurbani')
                    <div style="font-size:0.9rem; color:var(--primary); font-weight:600; margin-bottom:0.5rem;"><i class="fas fa-kaaba"></i> Verified for Qurbani</div>
                @endif
            @endif
            
            <div class="ad-location" style="font-size:0.95rem;">
                <i class="fas fa-map-marker-alt"></i> 
                @if($ad->district)
                    {{ $ad->village ? $ad->village . ', ' : '' }}{{ $ad->tehsil->name ?? '' }}, {{ $ad->district->name }}
                @else
                    {{ $ad->city }}{{ $ad->area ? ', ' . $ad->area : '' }}
                @endif
            </div>

            @if($ad->status === 'expired')
                <div style="background:#fee2e2; color:#991b1b; padding:1rem; border-radius:8px; margin-top:1rem; text-align:center;">
                    <p style="font-size:0.9rem; font-weight:600; margin-bottom:0.5rem;">This ad has expired.</p>
                    @if(Auth::id() === $ad->user_id)
                        <a href="{{ route('payments.create', ['ad_id' => $ad->id, 'type' => 'renewal']) }}" class="btn btn-primary btn-sm">Renew Ad (Rs. 50)</a>
                    @endif
                </div>
            @elseif($ad->status === 'payment_pending')
                <div style="background:#fee2e2; color:#991b1b; padding:1rem; border-radius:8px; margin-top:1rem; text-align:center; border:1px dashed #ef4444;">
                    <p style="font-size:0.9rem; font-weight:600; margin-bottom:0.5rem;">Payment Verification Pending</p>
                    @if(Auth::id() === $ad->user_id)
                        <a href="{{ route('payments.create', ['ad_id' => $ad->id, 'type' => 'ad_post']) }}" class="btn btn-danger btn-sm">Complete Payment</a>
                    @endif
                </div>
            @endif

            @if($ad->ad_type === 'ijtamai_hissa')
                {{-- Hissa Status Bar --}}
                <div style="margin-top:1.5rem; padding-top:1.5rem; border-top:1px solid var(--border);">
                    <div class="flex-between" style="margin-bottom:0.5rem;">
                        <strong style="font-size:1rem;">Hissa Status</strong>
                        <span style="font-size:0.85rem; font-weight:600; color:var(--primary);">{{ $ad->booked_hisse }} / {{ $ad->total_hisse }} Booked</span>
                    </div>
                    <div style="display:flex; gap:0.2rem; margin-bottom:1rem;">
                        @for($i = 0; $i < $ad->total_hisse; $i++)
                            <div style="flex:1; height:8px; border-radius:4px; background: {{ $i < $ad->booked_hisse ? 'var(--primary)' : '#e2e8f0' }};"></div>
                        @endfor
                    </div>
                    
                    @if($ad->remaining_hisse === 0)
                        <div style="background:#dcfce3; color:#166534; padding:0.6rem; border-radius:6px; text-align:center; font-weight:700;">
                            <i class="fas fa-check-circle"></i> COMPLETED
                        </div>
                    @else
                        @if(Auth::check() && Auth::id() !== $ad->user_id)
                            <button onclick="document.getElementById('bookingModal').style.display='block'" class="btn btn-primary" style="width:100%; justify-content:center;">
                                <i class="fas fa-handshake"></i> Book a Hissa
                            </button>
                        @elseif(Auth::id() === $ad->user_id)
                            <button onclick="document.getElementById('manualBookModal').style.display='block'" class="btn btn-outline" style="width:100%; justify-content:center;">
                                <i class="fas fa-plus"></i> Manual Booking
                            </button>
                        @endif
                    @endif
                </div>
            @endif

            {{-- Share Buttons --}}
            <div style="display:flex; gap:0.5rem; margin-top:1.5rem;">
                <a href="https://wa.me/?text={{ urlencode('Check out this ' . ucfirst($ad->animal_type) . ' for ' . ($ad->ad_type === 'ijtamai_hissa' ? 'Qurbani Hissa' : 'sale') . ' on Muwashi Mandi! ' . route('ads.show', ['id' => $ad->id, 'slug' => $ad->slug])) }}" target="_blank" class="btn btn-sm" style="flex:1; background:#25D366; color:#fff; justify-content:center; border:none;">
                    <i class="fab fa-whatsapp" style="font-size:1.1rem;"></i> Share
                </a>
                <button onclick="copyLink()" class="btn btn-outline btn-sm" style="flex:1; justify-content:center;">
                    <i class="fas fa-link"></i> Copy Link
                </button>
            </div>
        </div>

        {{-- Seller Info --}}
        <div class="card" style="padding:1.5rem; margin-bottom:1rem;">
            <h3 style="font-weight:700; margin-bottom:1rem;"><i class="fas fa-user" style="color:var(--primary);"></i> Seller</h3>
            <div style="display:flex; align-items:center; gap:1rem; margin-bottom:1.5rem;">
                <div style="width:50px; height:50px; border-radius:50%; background:var(--primary-light); display:flex; align-items:center; justify-content:center; font-weight:700; color:var(--primary); font-size:1.2rem;">
                    {{ strtoupper(substr($ad->user->name, 0, 1)) }}
                </div>
                <div>
                    <a href="{{ route('sellers.show', $ad->user) }}" style="text-decoration:none; color:inherit;">
                        <div style="font-weight:600; font-size:1.1rem; color:var(--primary);">{{ $ad->user->name }} <i class="fas fa-chevron-right" style="font-size:0.7rem; margin-left:5px;"></i></div>
                    </a>
                    <div style="font-size:0.85rem; color:var(--text-light);">Member since {{ $ad->user->created_at->format('M Y') }}</div>
                </div>
            </div>

            @auth
                {{-- Disclaimer --}}
                <div style="background:#fffbeb; border:1px solid #fde68a; padding:1rem; border-radius:8px; margin-bottom:1rem;">
                    <strong style="color:#b45309; font-size:0.95rem; display:block; margin-bottom:0.3rem;">{{ __('Disclaimer Title') ?? '⚠️ Disclaimer' }}</strong>
                    <p style="font-size:0.8rem; color:#92400e; line-height:1.5;">{{ __('Disclaimer Body') ?? 'Muwashi Mandi is a platform connecting buyers and sellers. Never make advance payments without inspecting the animal in person. We are not responsible for any fraud or financial loss.' }}</p>
                </div>

                {{-- Show Phone Button --}}
                <button id="showPhoneBtn" onclick="showPhone({{ $ad->id }})" class="btn btn-primary" style="width:100%; justify-content:center; margin-bottom:0.8rem;">
                    <i class="fas fa-phone"></i> <span id="phoneText">Show Phone Number</span>
                </button>

                @if(Auth::id() !== $ad->user_id)
                    <form action="{{ route('chats.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="ad_id" value="{{ $ad->id }}">
                        <button type="submit" class="btn btn-outline" style="width:100%; justify-content:center; margin-bottom:0.8rem;">
                            <i class="fas fa-comment"></i> Chat with Seller
                        </button>
                    </form>
                @endif
            @else
                <a href="{{ route('login') }}" class="btn btn-primary" style="width:100%; justify-content:center; margin-bottom:0.8rem;">
                    <i class="fas fa-sign-in-alt"></i> Login to See Phone & Chat
                </a>
            @endauth
        </div>

        {{-- Promotion Options (Owner Only) --}}
        @auth
            @if(Auth::id() === $ad->user_id && $ad->status === 'approved')
                <div class="card" style="padding:1.5rem; background:#f8fafc; border:1px solid var(--border);">
                    <h3 style="font-weight:700; margin-bottom:1rem; font-size:0.95rem;"><i class="fas fa-bullhorn" style="color:var(--accent);"></i> Promote this Ad</h3>
                    <div style="display:flex; flex-direction:column; gap:0.5rem;">
                        @if(!$ad->is_boosted)
                            <a href="{{ route('payments.create', ['ad_id' => $ad->id, 'type' => 'boost']) }}" class="btn btn-secondary btn-sm" style="justify-content:center;">
                                <i class="fas fa-rocket"></i> Boost Ad (7 Days - Rs. 100)
                            </a>
                        @endif
                        @if(!$ad->is_featured)
                            <a href="{{ route('payments.create', ['ad_id' => $ad->id, 'type' => 'featured']) }}" class="btn btn-accent btn-sm" style="justify-content:center;">
                                <i class="fas fa-star"></i> Feature Ad (20 Days - Rs. 200)
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        @endauth
    </div>
</div>

{{-- Modals --}}
@if($ad->ad_type === 'ijtamai_hissa' && Auth::check() && $ad->remaining_hisse > 0)
    {{-- Buyer Booking Modal --}}
    @if(Auth::id() !== $ad->user_id)
    <div id="bookingModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999; align-items:center; justify-content:center;">
        <div class="card" style="width:100%; max-width:400px; padding:2rem; margin:auto; margin-top:10vh;">
            <div class="flex-between" style="margin-bottom:1.5rem;">
                <h3 style="font-weight:700;"><i class="fas fa-handshake" style="color:var(--primary);"></i> Request Booking</h3>
                <i class="fas fa-times" style="cursor:pointer;" onclick="document.getElementById('bookingModal').style.display='none'"></i>
            </div>
            <form action="{{ route('hissa_requests.store') }}" method="POST">
                @csrf
                <input type="hidden" name="ad_id" value="{{ $ad->id }}">
                <div class="form-group">
                    <label class="form-label">Your Name *</label>
                    <input type="text" name="buyer_name" class="form-input" value="{{ Auth::user()->name }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Your Phone *</label>
                    <input type="text" name="buyer_phone" class="form-input" value="{{ Auth::user()->phone ?? '' }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Hisse Required *</label>
                    <select name="requested_hisse" class="form-select" required>
                        @for($i = 1; $i <= min(2, $ad->remaining_hisse); $i++)
                            <option value="{{ $i }}">{{ $i }} {{ $i > 1 ? 'Hisse' : 'Hissa' }}</option>
                        @endfor
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">Send Request</button>
            </form>
        </div>
    </div>
    @endif

    {{-- Seller Manual Book Modal --}}
    @if(Auth::id() === $ad->user_id)
    <div id="manualBookModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999; align-items:center; justify-content:center;">
        <div class="card" style="width:100%; max-width:400px; padding:2rem; margin:auto; margin-top:10vh;">
            <div class="flex-between" style="margin-bottom:1.5rem;">
                <h3 style="font-weight:700;"><i class="fas fa-plus" style="color:var(--primary);"></i> Manual Booking</h3>
                <i class="fas fa-times" style="cursor:pointer;" onclick="document.getElementById('manualBookModal').style.display='none'"></i>
            </div>
            <form action="{{ route('hissa_requests.manual_book', $ad) }}" method="POST">
                @csrf @method('PATCH')
                <div class="form-group">
                    <label class="form-label">How many hisse to mark booked?</label>
                    <input type="number" name="hisse_to_book" class="form-input" min="1" max="{{ $ad->remaining_hisse }}" value="1" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">Confirm</button>
            </form>
        </div>
    </div>
    @endif
@endif

{{-- Related Ads --}}
@if($relatedAds->count())
    <h2 style="font-size:1.3rem; font-weight:700; margin:2.5rem 0 1rem;"><i class="fas fa-th" style="color:var(--primary);"></i> Similar Ads</h2>
    <div class="grid-ads">
        @foreach($relatedAds as $related)
            @include('components.ad-card', ['ad' => $related])
        @endforeach
    </div>
@endif

@push('scripts')
<script>
function showPhone(adId) {
    fetch('/ads/' + adId + '/phone', {
        headers: { 'X-CSRF-TOKEN': window.csrfToken, 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById('phoneText').textContent = data.phone || 'Not available';
        document.getElementById('showPhoneBtn').disabled = true;
        document.getElementById('showPhoneBtn').style.background = '#16a34a';
    });
}

function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        Swal.fire({
            icon: 'success',
            title: 'Copied!',
            text: 'Link copied to clipboard',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
    });
}
</script>
@endpush
@endsection
