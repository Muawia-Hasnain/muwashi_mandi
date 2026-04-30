@extends('layouts.app')
@section('title', 'Admin - Chats')

@section('content')
<div class="flex-between mb-3">
    <h1 style="font-size:1.5rem; font-weight:700;"><i class="fas fa-comments" style="color:var(--primary);"></i> User Support Chats</h1>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline btn-sm"><i class="fas fa-arrow-left"></i> Dashboard</a>
</div>

@if($chats->count())
    <div style="display:flex; flex-direction:column; gap:0.8rem;">
        @foreach($chats as $chat)
            <a href="{{ route('admin.chats.show', $chat->user) }}" class="card" style="padding:1rem; display:flex; gap:1rem; align-items:center; text-decoration:none; color:var(--text);">
                <div style="width:50px; height:50px; border-radius:50%; background:var(--primary-light); display:flex; align-items:center; justify-content:center; font-weight:700; color:var(--primary); font-size:1.2rem;">
                    {{ strtoupper(substr($chat->user->name, 0, 1)) }}
                </div>

                <div style="flex:1; min-width:0;">
                    <div class="flex-between">
                        <strong style="font-size:0.95rem;">{{ $chat->user->name }}</strong>
                        <span style="font-size:0.75rem; color:var(--text-light);">{{ $chat->updated_at->diffForHumans() }}</span>
                    </div>
                    @if($chat->latestMessage)
                        <div style="font-size:0.85rem; color:var(--text-light); margin-top:0.3rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                            {{ $chat->latestMessage->sender_id === auth()->id() ? 'You: ' : '' }}{{ Str::limit($chat->latestMessage->body, 80) }}
                        </div>
                    @endif
                </div>

                @php
                    $unread = $chat->messages->where('sender_id', '!=', auth()->id())->where('is_read', false)->count();
                @endphp
                @if($unread > 0)
                    <span style="background:var(--primary); color:#fff; border-radius:50%; width:24px; height:24px; display:flex; align-items:center; justify-content:center; font-size:0.75rem; font-weight:700;">{{ $unread }}</span>
                @endif
            </a>
        @endforeach
    </div>
    <div class="pagination">{{ $chats->links() }}</div>
@else
    <div class="empty-state">
        <i class="fas fa-comments"></i>
        <h3>No chats yet</h3>
        <p>User messages will appear here.</p>
    </div>
@endif
@endsection
