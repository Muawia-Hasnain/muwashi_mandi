@extends('layouts.app')
@section('title', 'Reset Password')

@section('content')
<div style="max-width:450px; margin: 2rem auto;">
    <div class="card" style="padding: 2rem;">
        <h1 style="text-align:center; font-size:1.5rem; font-weight:700; margin-bottom:0.5rem;">
            <i class="fas fa-key" style="color:var(--primary);"></i> Forgot Password
        </h1>
        <p style="text-align:center; color:var(--text-light); margin-bottom:1.5rem;">Enter your email to receive a password reset link.</p>

        @if (session('success'))
            <div class="alert alert-success" style="margin-bottom: 1rem;">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error" style="margin-bottom: 1rem;">
                <i class="fas fa-exclamation-circle"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-input" value="{{ old('email') }}" required autofocus placeholder="your@email.com">
            </div>
            
            <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">
                <i class="fas fa-paper-plane"></i> Send Password Reset Link
            </button>
        </form>
        
        <p style="text-align:center; margin-top:1.2rem; color:var(--text-light); font-size:0.9rem;">
            Remembered your password? <a href="{{ route('login') }}">Login</a>
        </p>
    </div>
</div>
@endsection
