@extends('layouts.app')
@section('title', 'Qurbani Bookings')

@section('content')
<h1 style="font-size:1.5rem; font-weight:700; margin-bottom:1.5rem;"><i class="fas fa-handshake" style="color:var(--primary);"></i> Qurbani Bookings</h1>

{{-- Tabs --}}
<div style="display:flex; gap:1rem; border-bottom:1px solid var(--border); margin-bottom:1.5rem;">
    <button onclick="switchTab('received')" id="tab-received" class="tab-btn active" style="padding:0.5rem 1rem; border:none; background:none; border-bottom:2px solid var(--primary); font-weight:600; color:var(--primary); cursor:pointer;">
        Bookings I Received
    </button>
    <button onclick="switchTab('sent')" id="tab-sent" class="tab-btn" style="padding:0.5rem 1rem; border:none; background:none; border-bottom:2px solid transparent; font-weight:600; color:var(--text-light); cursor:pointer;">
        My Requests (Sent)
    </button>
</div>

{{-- Received Tab (Seller view) --}}
<div id="content-received">
    @if($receivedRequests->count())
        <div class="card table-wrapper">
            <table>
                <thead><tr><th>Ad</th><th>Buyer</th><th>Phone</th><th>Hisse</th><th>Status</th><th>Date</th><th>Action</th></tr></thead>
                <tbody>
                    @foreach($receivedRequests as $req)
                        <tr>
                            <td><a href="{{ route('ads.show', ['id' => $req->ad->id, 'slug' => $req->ad->slug]) }}">{{ Str::limit($req->ad->title, 30) }}</a></td>
                            <td>{{ $req->buyer_name }}</td>
                            <td>{{ $req->buyer_phone }}</td>
                            <td><strong>{{ $req->requested_hisse }}</strong></td>
                            <td><span class="ad-card-badge status-{{ $req->status }}">{{ ucfirst($req->status) }}</span></td>
                            <td>{{ $req->created_at->format('d M') }}</td>
                            <td>
                                @if($req->status === 'pending')
                                    <div style="display:flex; gap:0.3rem;">
                                        <form action="{{ route('hissa_requests.update', $req) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="accepted">
                                            <button class="btn btn-primary btn-sm" title="Accept"><i class="fas fa-check"></i></button>
                                        </form>
                                        <form action="{{ route('hissa_requests.update', $req) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="rejected">
                                            <button class="btn btn-danger btn-sm" title="Reject"><i class="fas fa-times"></i></button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <h3>No requests received</h3>
            <p>You haven't received any booking requests for your Qurbani ads yet.</p>
        </div>
    @endif
</div>

{{-- Sent Tab (Buyer view) --}}
<div id="content-sent" style="display:none;">
    @if($sentRequests->count())
        <div class="card table-wrapper">
            <table>
                <thead><tr><th>Ad</th><th>Hisse Requested</th><th>Status</th><th>Date</th></tr></thead>
                <tbody>
                    @foreach($sentRequests as $req)
                        <tr>
                            <td><a href="{{ route('ads.show', ['id' => $req->ad->id, 'slug' => $req->ad->slug]) }}">{{ Str::limit($req->ad->title, 40) }}</a></td>
                            <td><strong>{{ $req->requested_hisse }}</strong></td>
                            <td><span class="ad-card-badge status-{{ $req->status }}">{{ ucfirst($req->status) }}</span></td>
                            <td>{{ $req->created_at->format('d M') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-paper-plane"></i>
            <h3>No requests sent</h3>
            <p>You haven't sent any booking requests yet.</p>
        </div>
    @endif
</div>

@push('scripts')
<script>
function switchTab(tab) {
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.style.color = 'var(--text-light)';
        btn.style.borderBottomColor = 'transparent';
    });
    document.getElementById('tab-' + tab).style.color = 'var(--primary)';
    document.getElementById('tab-' + tab).style.borderBottomColor = 'var(--primary)';

    document.getElementById('content-received').style.display = 'none';
    document.getElementById('content-sent').style.display = 'none';
    
    document.getElementById('content-' + tab).style.display = 'block';
}
</script>
@endpush
@endsection
