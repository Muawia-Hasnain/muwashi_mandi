@extends('layouts.app')
@section('title', 'Home')
@section('meta_description', 'Welcome to Muwashi Mandi, Pakistan\'s premier livestock marketplace. Buy and sell cows, goats, bulls, and sheep online with ease.')

@section('content')
{{-- Hero Section --}}
<section style="background: linear-gradient(135deg, #15803d 0%, #0d9488 100%); border-radius: 16px; padding: 3rem 2rem; color: #fff; text-align: center; margin-bottom: 2.5rem;">
    <h1 style="font-size: 2.5rem; font-weight: 800; margin-bottom: 0.5rem;">
        <i class="fas fa-paw" style="color: #fbbf24;"></i> {{ __('Muwashi Mandi') }}
    </h1>
    <p style="font-size: 1.2rem; opacity: 0.9; margin-bottom: 1.5rem;">{{ __('Pakistan\'s #1 Livestock Marketplace — Buy & Sell Animals Online') }}</p>
    <div style="display:flex; gap:1rem; justify-content:center; flex-wrap:wrap;">
        <a href="{{ route('ads.index') }}" class="btn btn-white"><i class="fas fa-search"></i> {{ __('Browse Animals') }}</a>
        @auth
            <a href="{{ route('ads.create') }}" class="btn btn-accent"><i class="fas fa-plus"></i> {{ __('Post Free Ad') }}</a>
        @else
            <a href="{{ route('register') }}" class="btn btn-accent"><i class="fas fa-user-plus"></i> {{ __('Sign Up Free') }}</a>
        @endauth
    </div>
</section>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    .category-swiper {
        width: 100%;
        padding-bottom: 1.5rem;
        margin-bottom: 2.5rem;
    }
</style>
@endpush

{{-- Category Cards --}}
<h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem;"><i class="fas fa-tags" style="color:var(--primary);"></i> {{ __('Browse by Category') }}</h2>
<div class="swiper category-swiper">
    <div class="swiper-wrapper">
        @foreach($categories as $cat)
            @php
                $colors = ['#dbeafe', '#fef3c7', '#e0e7ff', '#fee2e2', '#f3e8ff', '#f1f5f9'];
                $color = $colors[$loop->index % count($colors)];
            @endphp
            <div class="swiper-slide">
                <a href="{{ route('ads.index', ['category_id' => $cat->id]) }}" class="card" style="display:block; text-align:center; padding: 1.5rem 1rem; background: {{ $color }}; text-decoration:none; color:var(--text);">
                    <div style="font-size: 2.5rem;">{{ $cat->image_icon }}</div>
                    <div style="font-weight: 700; text-transform: capitalize; margin-top: 0.5rem;">{{ __($cat->name) }}</div>
                    <div style="font-size: 0.85rem; color: var(--text-light);">{{ $cat->ads_count }} ads</div>
                </a>
            </div>
        @endforeach
    </div>
</div>

{{-- Featured Ads --}}
@if($featuredAds->count())
    <div class="flex-between mb-2">
        <h2 style="font-size: 1.5rem; font-weight: 700;"><i class="fas fa-crown" style="color:#fbbf24;"></i> {{ __('Premium Ads') ?? 'Premium Ads' }}</h2>
    </div>
    <div class="grid-ads mb-3">
        @foreach($featuredAds as $ad)
            @include('components.ad-card', ['ad' => $ad])
        @endforeach
    </div>
@endif

{{-- Latest Ads --}}
<div class="flex-between mb-2">
    <h2 style="font-size: 1.5rem; font-weight: 700;"><i class="fas fa-clock" style="color:var(--primary);"></i> {{ __('Latest Ads') }}</h2>
    <a href="{{ route('ads.index') }}" class="btn btn-outline btn-sm">{{ __('View All') ?? 'View All' }} <i class="fas fa-arrow-right"></i></a>
</div>

@if($latestAds->count())
    <div class="grid-ads">
        @foreach($latestAds as $ad)
            @include('components.ad-card', ['ad' => $ad])
        @endforeach
    </div>
@else
    <div class="empty-state">
        <i class="fas fa-box-open"></i>
        <p>No ads posted yet. Be the first!</p>
    </div>
@endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        new Swiper('.category-swiper', {
            slidesPerView: 2.5,
            spaceBetween: 10,
            loop: true,
            autoplay: {
                delay: 2000,
                disableOnInteraction: false,
                pauseOnMouseEnter: true,
            },
            breakpoints: {
                480: { slidesPerView: 3.5, spaceBetween: 15 },
                768: { slidesPerView: 4.5, spaceBetween: 20 },
                1024: { slidesPerView: 6, spaceBetween: 20 },
            }
        });
    });
</script>
@endpush
