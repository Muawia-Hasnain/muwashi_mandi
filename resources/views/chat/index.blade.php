@extends('layouts.app')
@section('title', 'My Chats')

@push('styles')
<style>
    .inbox-list { display: flex; flex-direction: column; gap: 0.5rem; }
    .inbox-item { 
        display: flex; 
        gap: 1rem; 
        align-items: center; 
        padding: 1rem; 
        background: #fff; 
        border-radius: 12px; 
        text-decoration: none; 
        color: var(--text); 
        transition: all 0.2s; 
        border: 1px solid var(--border);
    }
    .inbox-item:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.05); border-color: var(--primary-light); }
    .inbox-item.unread { background: #f0fdf4; border-color: var(--primary-light); }
    
    .avatar { 
        width: 55px; 
        height: 55px; 
        border-radius: 50%; 
        background: #e2e8f0; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        flex-shrink: 0; 
        font-weight: 700; 
        color: var(--text-light); 
        font-size: 1.2rem;
        position: relative;
    }
    .avatar.support { background: var(--primary-light); color: var(--primary); }
    .online-indicator {
        position: absolute;
        bottom: 2px;
        right: 2px;
        width: 12px;
        height: 12px;
        background: #22c55e;
        border: 2px solid #fff;
        border-radius: 50%;
    }
    
    .inbox-content { flex: 1; min-width: 0; }
    .inbox-header { display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 0.2rem; }
    .inbox-name { font-weight: 700; font-size: 1rem; }
    .inbox-time { font-size: 0.75rem; color: var(--text-light); }
    .inbox-ad { font-size: 0.8rem; color: var(--primary); font-weight: 600; margin-bottom: 0.2rem; display: block; }
    .inbox-msg { 
        font-size: 0.85rem; 
        color: var(--text-light); 
        white-space: nowrap; 
        overflow: hidden; 
        text-overflow: ellipsis; 
    }
    .unread-indicator {
        background: var(--primary);
        color: #fff;
        font-size: 0.7rem;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 10px;
        min-width: 20px;
        text-align: center;
    }
</style>
@endpush

@section('content')
<h1 style="font-size:1.5rem; font-weight:700; margin-bottom:1.5rem;"><i class="fas fa-comments" style="color:var(--primary);"></i> My Chats</h1>

<div class="inbox-list">
    {{-- Support Chat First --}}
    <a href="{{ route('chats.show', $supportChat) }}" class="inbox-item {{ $supportChat->unread_count > 0 ? 'unread' : '' }}">
        <div class="avatar support">
            <i class="fas fa-headset"></i>
            @if($supportChat->unread_count > 0) <div class="online-indicator"></div> @endif
        </div>
        <div class="inbox-content">
            <div class="inbox-header">
                <span class="inbox-name">Muwashi Mandi Support</span>
                @if($supportChat->latestMessage)
                    <span class="inbox-time">{{ $supportChat->latestMessage->created_at->diffForHumans() }}</span>
                @endif
            </div>
            <span class="inbox-ad">Official Support</span>
            <div class="inbox-msg">
                @if($supportChat->latestMessage)
                    {{ $supportChat->latestMessage->sender_id === auth()->id() ? 'You: ' : '' }}{{ $supportChat->latestMessage->body }}
                @else
                    Contact admin for any help or issues.
                @endif
            </div>
        </div>
        @if($supportChat->unread_count > 0)
            <div class="unread-indicator">{{ $supportChat->unread_count }}</div>
        @endif
    </a>

    {{-- Ad Related Chats --}}
    @forelse($adChats as $chat)
        @php
            $isBuyer = $chat->buyer_id === auth()->id();
            $otherUser = $isBuyer ? $chat->seller : $chat->buyer;
        @endphp
        <a href="{{ route('chats.show', $chat) }}" class="inbox-item {{ $chat->unread_count > 0 ? 'unread' : '' }}">
            <div class="avatar">
                {{ strtoupper(substr($otherUser->name, 0, 1)) }}
            </div>
            <div class="inbox-content">
                <div class="inbox-header">
                    <span class="inbox-name">{{ $otherUser->name }} <small style="font-weight:normal; color:var(--text-light);">({{ $isBuyer ? 'Seller' : 'Buyer' }})</small></span>
                    @if($chat->latestMessage)
                        <span class="inbox-time">{{ $chat->latestMessage->created_at->diffForHumans() }}</span>
                    @endif
                </div>
                @if($chat->ad)
                    <span class="inbox-ad">Ad: {{ $chat->ad->title }}</span>
                @else
                    <span class="inbox-ad" style="color:var(--danger);">Ad Deleted</span>
                @endif
                <div class="inbox-msg">
                    @if($chat->latestMessage)
                        {{ $chat->latestMessage->sender_id === auth()->id() ? 'You: ' : '' }}{{ $chat->latestMessage->body }}
                    @else
                        No messages yet.
                    @endif
                </div>
            </div>
            @if($chat->unread_count > 0)
                <div class="unread-indicator">{{ $chat->unread_count }}</div>
            @endif
        </a>
    @empty
        <div class="empty-state card" style="background:#fff;">
            <i class="fas fa-comment-slash"></i>
            <p>No conversations yet. Start chatting with sellers to see them here.</p>
        </div>
    @endforelse
</div>
@endsection
