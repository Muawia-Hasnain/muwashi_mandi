@extends('layouts.app')
@section('title', 'Post Ad')

@section('content')
<div style="max-width:700px; margin:0 auto;">
    <h1 style="font-size:1.5rem; font-weight:700; margin-bottom:1.5rem;"><i class="fas fa-plus-circle" style="color:var(--primary);"></i> Post New Ad</h1>

    <div class="card" style="padding:2rem;">
        <form method="POST" action="{{ route('ads.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="form-label">Ad Title *</label>
                <input type="text" name="title" class="form-input" value="{{ old('title') }}" required maxlength="100" placeholder="e.g. Beautiful Sahiwal Cow for Sale">
                @error('title') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                <div class="form-group">
                    <label class="form-label">Animal Category *</label>
                    <select name="category_id" class="form-select" id="animalType" required>
                        <option value="">Select...</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->image_icon }} {{ __($cat->name) }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label" id="priceLabel">Price (Rs) *</label>
                    <input type="number" name="price" class="form-input" value="{{ old('price') }}" required min="0" placeholder="150000">
                    @error('price') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <!-- Qurbani Section -->
            <div class="card" style="background:#f8fafc; border:1px solid #e2e8f0; padding:1rem; margin-bottom:1.5rem; border-radius:8px;">
                <div class="form-group" style="margin-bottom:1rem;">
                    <label class="form-label" style="color:var(--primary); font-size:1.1rem; font-weight:700;"><i class="fas fa-tag"></i> Ad Type *</label>
                    <select name="ad_type" id="adType" class="form-select" required>
                        <option value="" disabled {{ old('ad_type') ? '' : 'selected' }}>Select Ad Type</option>
                        <option value="for_sale" {{ old('ad_type') == 'for_sale' ? 'selected' : '' }}>For Sale</option>
                        <option value="qurbani" {{ old('ad_type') == 'qurbani' ? 'selected' : '' }}>Qurbani</option>
                        <option value="ijtamai_hissa" {{ old('ad_type') == 'ijtamai_hissa' ? 'selected' : '' }}>Ijtamai / Hissa</option>
                    </select>
                    @error('ad_type') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                
                <div id="qurbaniFields" style="display:{{ old('ad_type') == 'ijtamai_hissa' ? 'block' : 'none' }}; border-top:1px solid #e2e8f0; padding-top:1rem;">
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom: 1rem;">
                        <div class="form-group">
                            <label class="form-label">Organization / Madrassa Name</label>
                            <input type="text" name="org_name" class="form-input" value="{{ old('org_name') }}" placeholder="e.g. Al-Khidmat Foundation">
                            @error('org_name') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">CNIC Number *</label>
                            <input type="text" name="cnic_number" class="form-input" value="{{ old('cnic_number') }}" placeholder="35202-xxxxxxx-x">
                            @error('cnic_number') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Total Hisse (Leave blank for default)</label>
                        <input type="number" name="total_hisse" id="totalHisse" class="form-input" value="{{ old('total_hisse') }}" min="1" max="20" placeholder="e.g. 7 for Cow">
                        @error('total_hisse') <div class="form-error">{{ $message }}</div> @enderror
                        <div style="font-size:0.75rem; color:var(--text-light); margin-top:0.3rem;">Cow/Bull: 7, Camel: 10, Goat/Sheep: 1</div>
                    </div>
                </div>
            </div>
            <!-- End Qurbani Section -->

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                <div class="form-group">
                    <label class="form-label">Breed</label>
                    <input type="text" name="breed" class="form-input" value="{{ old('breed') }}" placeholder="e.g. Sahiwal, Beetal">
                </div>
                <div class="form-group">
                    <label class="form-label">Age</label>
                    <input type="text" name="age_info" class="form-input" value="{{ old('age_info') }}" placeholder="e.g. 2 years">
                </div>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                <div class="form-group">
                    <label class="form-label">District / City *</label>
                    <div style="position:relative;">
                        <input type="text" id="districtSearch" class="form-input" placeholder="🔍 Search district..." style="margin-bottom:0.2rem; font-size:0.85rem; padding: 0.4rem 0.8rem;">
                        <select name="district_id" id="district" class="form-select" required>
                            <option value="">Select District</option>
                            @foreach($districts as $district)
                                <option value="{{ $district->id }}" {{ old('district_id') == $district->id ? 'selected' : '' }}>{{ $district->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('district_id') <div class="form-error">{{ $message }}</div> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Tehsil *</label>
                    <select name="tehsil_id" id="tehsil" class="form-select" required>
                        <option value="">Select Tehsil</option>
                    </select>
                    @error('tehsil_id') <div class="form-error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Village</label>
                <input type="text" name="village" class="form-input" value="{{ old('village') }}" placeholder="Enter village name">
                @error('village') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Description *</label>
                <textarea name="description" class="form-textarea" required maxlength="2000" rows="5" placeholder="Describe your animal in detail — health, weight, milk production, etc.">{{ old('description') }}</textarea>
                @error('description') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Images * (Max 5, each max 2MB)</label>
                <input type="file" name="images[]" class="form-input" multiple accept="image/jpeg,image/png,image/webp" required>
                @error('images') <div class="form-error">{{ $message }}</div> @enderror
                @error('images.*') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div style="background:#f0fdf4; padding:1rem; border-radius:8px; margin-bottom:1.5rem; font-size:0.9rem; color:#166534;">
                <i class="fas fa-info-circle"></i> Your ad will be reviewed by admin before it becomes visible.
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">
                <i class="fas fa-paper-plane"></i> Submit Ad
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const adType = document.getElementById('adType');
        const qurbaniFields = document.getElementById('qurbaniFields');
        const priceLabel = document.getElementById('priceLabel');

        function toggleQurbani() {
            if (adType.value === 'ijtamai_hissa') {
                qurbaniFields.style.display = 'block';
                priceLabel.innerHTML = 'Rate per Hissa (Rs) *';
            } else {
                qurbaniFields.style.display = 'none';
                priceLabel.innerHTML = 'Price (Rs) *';
            }
        }

        adType.addEventListener('change', toggleQurbani);
        toggleQurbani(); // Initial state

        // Searchable District Select
        const districtSearch = document.getElementById('districtSearch');
        const districtSelect = document.getElementById('district');
        const districtOptions = Array.from(districtSelect.options);

        districtSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            districtSelect.innerHTML = '';
            
            const filteredOptions = districtOptions.filter(opt => 
                opt.text.toLowerCase().includes(searchTerm) || opt.value === ""
            );
            
            filteredOptions.forEach(opt => districtSelect.appendChild(opt));
        });

        // Location handling
        const tehsilSelect = document.getElementById('tehsil');
        const oldTehsil = "{{ old('tehsil_id') }}";

        function loadTehsils(districtId, selectedTehsil = null) {
            tehsilSelect.innerHTML = '<option value="">Loading...</option>';
            if (!districtId) {
                tehsilSelect.innerHTML = '<option value="">Select Tehsil</option>';
                return;
            }

            fetch(`/locations/tehsils/${districtId}`)
                .then(res => res.json())
                .then(data => {
                    tehsilSelect.innerHTML = '<option value="">Select Tehsil</option>';
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
                    tehsilSelect.innerHTML = '<option value="">Error loading tehsils</option>';
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
