@extends('layouts.app')
@section('title', 'Register')

@section('content')
<div style="max-width:500px; margin: 2rem auto;">
    <div class="card" style="padding: 2rem;">
        <h1 style="text-align:center; font-size:1.5rem; font-weight:700; margin-bottom:0.5rem;">
            <i class="fas fa-user-plus" style="color:var(--primary);"></i> Create Account
        </h1>
        <p style="text-align:center; color:var(--text-light); margin-bottom:1.5rem;">Join Muwashi Mandi and start selling!</p>

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-input" value="{{ old('name') }}" required placeholder="Muhammad Ali">
                @error('name') <div class="form-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-input" value="{{ old('email') }}" required placeholder="your@email.com">
                @error('email') <div class="form-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Phone Number</label>
                <input type="text" name="phone" class="form-input" value="{{ old('phone') }}" required placeholder="0300-1234567">
                @error('phone') <div class="form-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">City</label>
                <input type="text" name="city" class="form-input" value="{{ old('city') }}" required placeholder="Lahore">
                @error('city') <div class="form-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-input" required placeholder="Min 8 characters">
                @error('password') <div class="form-error">{{ $message }}</div> @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-input" required placeholder="Repeat password">
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; margin-bottom: 1rem;">
                <i class="fas fa-user-plus"></i> Create Account
            </button>

            <div style="text-align:center; position:relative; margin: 1.5rem 0;">
                <hr style="border:0; border-top:1px solid var(--border);">
                <span style="background:#fff; padding:0 10px; position:absolute; top:-10px; left:50%; transform:translateX(-50%); color:var(--text-light); font-size:0.9rem;">OR</span>
            </div>

            <a href="{{ route('auth.google') }}" class="btn btn-outline" style="width:100%; justify-content:center; display:flex; align-items:center; gap:10px;">
                <img src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google" style="width:20px; height:20px;">
                Continue with Google
            </a>
        </form>
        <p style="text-align:center; margin-top:1.2rem; color:var(--text-light); font-size:0.9rem;">
            Already have an account? <a href="{{ route('login') }}">Login</a>
        </p>
    </div>
</div>
@endsection
