@extends('layouts.app')
@section('title', 'Set New Password')

@section('content')
<div style="max-width:450px; margin: 2rem auto;">
    <div class="card" style="padding: 2rem;">
        <h1 style="text-align:center; font-size:1.5rem; font-weight:700; margin-bottom:0.5rem;">
            <i class="fas fa-lock" style="color:var(--primary);"></i> Set New Password
        </h1>
        <p style="text-align:center; color:var(--text-light); margin-bottom:1.5rem;">Please enter your new password below.</p>

        @if($errors->any())
            <div class="alert alert-error" style="margin-bottom: 1rem;">
                <i class="fas fa-exclamation-circle"></i>
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <input type="email" name="email" class="form-input" value="{{ $email ?? old('email') }}" required readonly>
            </div>
            
            <div class="form-group">
                <label class="form-label">New Password</label>
                <input type="password" name="password" class="form-input" required placeholder="Min 8 characters">
            </div>
            
            <div class="form-group">
                <label class="form-label">Confirm New Password</label>
                <input type="password" name="password_confirmation" class="form-input" required placeholder="Repeat new password">
            </div>
            
            <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center;">
                <i class="fas fa-save"></i> Update Password
            </button>
        </form>
    </div>
</div>
@endsection
