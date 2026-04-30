@extends('layouts.app')
@section('title', 'Browse Ads')
@section('meta_description', 'Search and find the best livestock for sale in Pakistan. Filter by animal type, location, and price.')

@section('content')
<div class="flex-between mb-3">
    <h1 style="font-size:1.5rem; font-weight:700;"><i class="fas fa-search" style="color:var(--primary);"></i> Browse Animals</h1>
</div>

{{-- Filters --}}
<div class="card" style="padding:1.2rem; margin-bottom:1.5rem;">
    <form method="GET" action="{{ route('ads.index') }}" style="display:flex; gap:0.8rem; flex-wrap:wrap; align-items:flex-end;">
        <div style="flex:1; min-width:150px;">
            <label class="form-label">Search</label>
            <input type="text" name="search" class="form-input" value="{{ request('search') }}" placeholder="Search title, breed...">
        </div>
        <div style="min-width:140px;">
            <label class="form-label">Category</label>
            <select name="category_id" class="form-select">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->image_icon }} {{ __($cat->name) }}</option>
                @endforeach
            </select>
        </div>
        <div style="min-width:140px;">
            <label class="form-label">Ad Type</label>
            <select name="ad_type" class="form-select">
                <option value="">All Types</option>
                <option value="for_sale" {{ request('ad_type') == 'for_sale' ? 'selected' : '' }}>For Sale</option>
                <option value="qurbani" {{ request('ad_type') == 'qurbani' ? 'selected' : '' }}>Qurbani</option>
                <option value="ijtamai_hissa" {{ request('ad_type') == 'ijtamai_hissa' ? 'selected' : '' }}>Ijtamai Hissa</option>
            </select>
        </div>
        <div style="min-width:150px;">
            <label class="form-label">District</label>
            <select name="district_id" id="filterDistrict" class="form-select">
                <option value="">All Districts</option>
                @foreach($districts as $district)
                    <option value="{{ $district->id }}" {{ request('district_id') == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                @endforeach
            </select>
        </div>
        <div style="min-width:150px;">
            <label class="form-label">Tehsil</label>
            <select name="tehsil_id" id="filterTehsil" class="form-select">
                <option value="">All Tehsils</option>
            </select>
        </div>
        <div style="min-width:100px;">
            <label class="form-label">Min Price</label>
            <input type="number" name="price_min" class="form-input" value="{{ request('price_min') }}" placeholder="0">
        </div>
        <div style="min-width:100px;">
            <label class="form-label">Max Price</label>
            <input type="number" name="price_max" class="form-input" value="{{ request('price_max') }}" placeholder="Max">
        </div>
        <div style="min-width:120px;">
            <label class="form-label">Sort</label>
            <select name="sort" class="form-select">
                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price Low</option>
                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price High</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Filter</button>
        <a href="{{ route('ads.index') }}" class="btn btn-outline btn-sm">Clear</a>
    </form>
</div>

{{-- Results --}}
@if($ads->count())
    <p style="color:var(--text-light); margin-bottom:1rem;">Showing {{ $ads->total() }} results</p>
    <div class="grid-ads">
        @foreach($ads as $ad)
            @include('components.ad-card', ['ad' => $ad])
        @endforeach
    </div>
    <div class="pagination">{{ $ads->links() }}</div>
@else
    <div class="empty-state">
        <i class="fas fa-search"></i>
        <h3>No ads found</h3>
        <p>Try adjusting your filters or search terms.</p>
    </div>
@endif
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const districtSelect = document.getElementById('filterDistrict');
        const tehsilSelect = document.getElementById('filterTehsil');
        const oldTehsil = "{{ request('tehsil_id') }}";

        function loadTehsils(districtId, selectedTehsil = null) {
            tehsilSelect.innerHTML = '<option value="">Loading...</option>';
            if (!districtId) {
                tehsilSelect.innerHTML = '<option value="">All Tehsils</option>';
                return;
            }

            fetch(`/locations/tehsils/${districtId}`)
                .then(res => res.json())
                .then(data => {
                    tehsilSelect.innerHTML = '<option value="">All Tehsils</option>';
                    data.forEach(tehsil => {
                        const option = document.createElement('option');
                        option.value = tehsil.id;
                        option.textContent = tehsil.name;
                        if (selectedTehsil && selectedTehsil == tehsil.id) {
                            option.selected = true;
                        }
                        tehsilSelect.appendChild(option);
                    });
                })
                .catch(err => {
                    tehsilSelect.innerHTML = '<option value="">All Tehsils</option>';
                });
        }

        districtSelect.addEventListener('change', function() {
            loadTehsils(this.value);
        });

        if (districtSelect.value) {
            loadTehsils(districtSelect.value, oldTehsil);
        }
    });
</script>
@endpush
