@extends('layouts.app')
@section('title', 'Login')

@section('content')
<div style="max-width:450px; margin: 2rem auto;">
    <div class="card" style="padding: 2rem;">
        <h1 style="text-align:center; font-size:1.5rem; font-weight:700; margin-bottom:0.5rem;">
            <i class="fas fa-sign-in-alt" style="color:var(--primary);"></i> Welcome Back
        </h1>
        <p style="text-align:center; color:var(--text-light); margin-bottom:1.5rem;">Login to your Muwashi Mandi account</p>

        @if($errors->any())
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-input" value="{{ old('email') }}" required autofocus placeholder="your@email.com">
            </div>
            <div class="form-group" style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 0.5rem;">
                <label class="form-label" style="margin:0;">Password</label>
                <a href="{{ route('password.request') }}" style="font-size:0.85rem; color:var(--primary);">Forgot Password?</a>
            </div>
            <div class="form-group">
                <input type="password" name="password" class="form-input" required placeholder="••••••••">
            </div>
            <div class="form-group" style="display:flex; align-items:center; gap:0.5rem;">
                <input type="checkbox" name="remember" id="remember" style="accent-color:var(--primary);">
                <label for="remember" style="font-size:0.9rem; color:var(--text-light);">Remember me</label>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; margin-bottom: 1rem;">
                <i class="fas fa-sign-in-alt"></i> Login
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
            Don't have an account? <a href="{{ route('register') }}">Sign Up</a>
        </p>
    </div>
</div>
@endsection
