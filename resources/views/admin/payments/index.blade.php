@extends('layouts.app')
@section('title', 'Manage Payments')

@section('content')
<div class="flex-between mb-3">
    <h1 style="font-size:1.5rem; font-weight:700;"><i class="fas fa-receipt" style="color:var(--primary);"></i> Manage Payments</h1>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Dashboard</a>
</div>

<div style="display:flex; gap:0.5rem; margin-bottom:1.5rem;">
    <a href="{{ route('admin.payments.index') }}" class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-outline' }}">All</a>
    <a href="{{ route('admin.payments.index', ['status' => 'pending']) }}" class="btn btn-sm {{ request('status') == 'pending' ? 'btn-primary' : 'btn-outline' }}">Pending</a>
    <a href="{{ route('admin.payments.index', ['status' => 'approved']) }}" class="btn btn-sm {{ request('status') == 'approved' ? 'btn-primary' : 'btn-outline' }}">Approved</a>
    <a href="{{ route('admin.payments.index', ['status' => 'rejected']) }}" class="btn btn-sm {{ request('status') == 'rejected' ? 'btn-primary' : 'btn-outline' }}">Rejected</a>
</div>

<div class="card table-wrapper">
    <table>
        <thead>
            <tr>
                <th>User</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Screenshot</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $payment)
                <tr>
                    <td>
                        <strong>{{ $payment->user->name }}</strong><br>
                        <span style="font-size:0.75rem; color:var(--text-light);">{{ $payment->created_at->format('d M, H:i') }}</span>
                    </td>
                    <td>
                        <span class="badge" style="background:#e0f2fe; color:#0369a1; padding:0.2rem 0.5rem; border-radius:4px; font-size:0.75rem;">
                            {{ ucfirst(str_replace('_', ' ', $payment->type)) }}
                        </span>
                        @if($payment->ad)
                            <div style="font-size:0.75rem; margin-top:0.2rem;">Ad ID: #{{ $payment->ad_id }}</div>
                        @endif
                    </td>
                    <td><strong>Rs. {{ number_format($payment->amount) }}</strong></td>
                    <td>
                        <img src="{{ asset('storage/' . $payment->screenshot_path) }}" 
                             style="width:50px; height:50px; object-fit:cover; border-radius:4px; border:1px solid var(--border); cursor:pointer;"
                             onclick="showImagePopup('{{ asset('storage/' . $payment->screenshot_path) }}')"
                             title="Click to view">
                    </td>
                    <td>
                        <span class="ad-card-badge status-{{ $payment->status }}">{{ ucfirst($payment->status) }}</span>
                    </td>
                    <td>
                        @if($payment->status === 'pending')
                            <div style="display:flex; gap:0.3rem;">
                                <form action="{{ route('admin.payments.approve', $payment) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button class="btn btn-primary btn-sm" title="Approve"><i class="fas fa-check"></i></button>
                                </form>
                                <form action="{{ route('admin.payments.reject', $payment) }}" method="POST" style="display:flex; gap:0.2rem;">
                                    @csrf @method('PATCH')
                                    <input type="text" name="admin_note" placeholder="Reason..." class="form-input" style="width:100px; font-size:0.75rem; padding:0.2rem;">
                                    <button class="btn btn-danger btn-sm" title="Reject"><i class="fas fa-times"></i></button>
                                </form>
                            </div>
                        @else
                            <span style="font-size:0.8rem; color:var(--text-light);">Processed</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center" style="padding:2rem; color:var(--text-light);">No payments found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="pagination">{{ $payments->links() }}</div>

@push('scripts')
<script>
function showImagePopup(url) {
    Swal.fire({
        imageUrl: url,
        imageAlt: 'Payment Screenshot',
        showConfirmButton: false,
        showCloseButton: true,
        width: 'auto',
        padding: '1rem',
        customClass: {
            image: 'max-w-full h-auto rounded'
        }
    });
}
</script>
@endpush
@endsection
