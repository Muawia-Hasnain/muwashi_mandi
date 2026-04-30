@extends('layouts.app')
@section('title', 'Edit Profile')

@section('content')
<div style="max-width:600px; margin: 2rem auto;">
    <div class="card" style="padding: 2rem;">
        <h1 style="font-size:1.5rem; font-weight:700; margin-bottom:1.5rem;"><i class="fas fa-user-edit" style="color:var(--primary);"></i> Edit Profile</h1>

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-group" style="text-align:center; margin-bottom:2rem;">
                <div style="width:100px; height:100px; border-radius:50%; background:var(--primary-light); color:var(--primary); display:flex; align-items:center; justify-content:center; font-size:2.5rem; margin:0 auto 1rem; overflow:hidden;">
                    @if($user->avatar_path)
                        <img src="{{ asset('storage/' . $user->avatar_path) }}" style="width:100%; height:100%; object-fit:cover;">
                    @else
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    @endif
                </div>
                <label class="btn btn-outline btn-sm" style="cursor:pointer; display:inline-block;">
                    Change Photo
                    <input type="file" name="avatar" style="display:none;" accept="image/*">
                </label>
                @error('avatar') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-input" value="{{ old('name', $user->name) }}" required>
                @error('name') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" class="form-input" value="{{ $user->email }}" disabled style="background:#f1f5f9; cursor:not-allowed;">
                <small style="color:var(--text-light);">Email cannot be changed.</small>
            </div>

            <div class="form-group">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone" class="form-input" value="{{ old('phone', $user->phone) }}" required>
                @error('phone') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">City</label>
                <input type="text" name="city" class="form-input" value="{{ old('city', $user->city) }}" required>
                @error('city') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div style="display:flex; gap:1rem; margin-top:2rem;">
                <button type="submit" class="btn btn-primary" style="flex:1; justify-content:center;">
                    <i class="fas fa-save"></i> Save Changes
                </button>
                <a href="{{ route('profile.show') }}" class="btn btn-outline" style="flex:1; justify-content:center;">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
