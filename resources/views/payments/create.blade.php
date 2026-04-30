@extends('layouts.app')
@section('title', 'Complete Payment')

@section('content')
<div style="max-width:600px; margin:0 auto;">
    <h1 style="font-size:1.5rem; font-weight:700; margin-bottom:1.5rem;"><i class="fas fa-money-bill-wave" style="color:var(--primary);"></i> {{ __('Complete Payment') ?? 'Complete Payment' }}</h1>

    @if($type === 'ad_post')
        <div style="background:#fffbeb; padding:1.2rem; border-radius:12px; margin-bottom:1.5rem; border:1px solid #fef08a;">
            <p style="font-size:0.95rem; color:#b45309; margin:0;">
                <i class="fas fa-info-circle"></i> {{ __('Ad Post Fee Reason') ?? 'You have reached the free ad limit (5). Please pay Rs. 50 to make this ad live.' }}
            </p>
        </div>
    @endif

    <div class="card" style="padding:2rem;">
        <div style="background:#f0fdf4; padding:1.2rem; border-radius:12px; margin-bottom:1.5rem; border:1px solid #bcf0da;">
            <h3 style="font-size:1rem; font-weight:700; color:#065f46; margin-bottom:0.5rem;">{{ __('Payment Details') ?? 'Payment Details' }}</h3>
            <p style="font-size:0.95rem; color:#047857;">
                {{ __('Type') ?? 'Type' }}: <strong>{{ ucfirst(str_replace('_', ' ', $type)) }}</strong><br>
                {{ __('Amount to Pay') ?? 'Amount to Pay' }}: <strong style="font-size:1.2rem;">Rs. {{ number_format($amount) }}</strong>
            </p>
            @if($ad)
                <p style="font-size:0.85rem; color:#047857; margin-top:0.5rem; border-top:1px solid #bcf0da; padding-top:0.5rem;">
                    {{ __('For Ad') ?? 'For Ad' }}: <strong>{{ $ad->title }}</strong>
                </p>
            @endif
        </div>

        <div style="margin-bottom:2rem; background:#fefce8; padding:1.2rem; border-radius:12px; border:1px solid #fef08a;">
            <h3 style="font-size:1rem; font-weight:700; margin-bottom:0.8rem; color:#854d0e;">{{ __('How to pay:') ?? 'How to pay:' }}</h3>
            <ol style="font-size:0.95rem; line-height:1.6; color:#a16207; padding-left:1.2rem;">
                <li>{!! __('Payment Instructions 1', ['amount' => number_format($amount)]) ?? 'Transfer <strong>Rs. ' . number_format($amount) . '</strong> to Easypaisa: <strong>03105362449</strong>' !!}</li>
                <li>{!! __('Payment Instructions 2') ?? 'Or Bank Transfer: <strong>Easypaisa Bank</strong><br>IBAN: <strong>PK46TMFB0000000056046691</strong>' !!}</li>
                <li>{!! __('Payment Instructions 3') ?? 'Take a screenshot of the confirmation message and upload below.' !!}</li>
            </ol>
        </div>

        <form method="POST" action="{{ route('payments.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="ad_id" value="{{ $ad?->id }}">
            <input type="hidden" name="type" value="{{ $type }}">
            <input type="hidden" name="amount" value="{{ $amount }}">

            <div class="form-group">
                <label class="form-label">{{ __('Upload Payment Screenshot *') ?? 'Upload Payment Screenshot *' }}</label>
                <input type="file" name="screenshot" class="form-input" accept="image/*" required>
                @error('screenshot') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; padding:0.8rem;">
                <i class="fas fa-check-circle"></i> {{ __('Submit Payment') ?? 'Submit Payment' }}
            </button>
        </form>
    </div>
</div>
@endsection
