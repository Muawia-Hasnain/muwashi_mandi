@extends('layouts.app')
@section('title', 'Chat')

@push('styles')
<style>
    .chat-container { display:flex; flex-direction:column; height: calc(100vh - 120px); border:none; border-radius:0; margin: -1rem -1rem 0 -1rem; background: #f8fafc; }
    .chat-header { padding:0.8rem 1rem; border-bottom:1px solid var(--border); display:flex; align-items:center; gap:1rem; background:#fff; color:var(--text); position:sticky; top:0; z-index:10; }
    .chat-ad-banner { 
        padding: 0.6rem 1rem; 
        background: #fff; 
        border-bottom: 1px solid var(--border); 
        display: flex; 
        align-items: center; 
        gap: 0.8rem; 
        text-decoration: none;
        transition: background 0.2s;
    }
    .chat-ad-banner:hover { background: #f1f5f9; }
    .chat-ad-img { width: 45px; height: 45px; border-radius: 6px; object-fit: cover; }
    .chat-ad-info { flex: 1; min-width: 0; }
    .chat-ad-title { font-size: 0.9rem; font-weight: 600; color: var(--text); margin: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .chat-ad-price { font-size: 0.85rem; color: var(--primary); font-weight: 700; }

    .chat-messages { 
        flex:1; 
        overflow-y:auto; 
        padding:1.5rem 1rem; 
        display:flex; 
        flex-direction:column; 
        gap:1rem; 
        background-color: #f1f5f9;
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M54.627 4.878L59.947 10.2l-5.32 5.32-5.32-5.32 5.32-5.32zM4.878 54.627L10.2 59.947l5.32-5.32-5.32-5.32-5.32 5.32zM54.627 54.627L59.947 49.3l-5.32-5.32-5.32 5.32 5.32 5.32zM4.878 4.878L10.2-0.443l5.32 5.32-5.32 5.32-5.32-5.32z' fill='%23cbd5e1' fill-opacity='0.2' fill-rule='evenodd'/%3E%3C/svg%3E");
    }
    .msg { max-width:85%; padding:0.8rem 1rem; border-radius:18px; font-size:0.95rem; line-height:1.4; position:relative; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
    .msg-sent { align-self:flex-end; background: var(--primary); color:#fff; border-bottom-right-radius:4px; }
    .msg-received { align-self:flex-start; background:#fff; color:var(--text); border-bottom-left-radius:4px; border:1px solid var(--border); }
    .msg-time { font-size:0.7rem; opacity:0.7; margin-top:0.4rem; text-align:right; display: flex; align-items: center; justify-content: flex-end; gap: 3px; }
    
    .chat-input-wrapper { padding:1rem; background:#fff; border-top:1px solid var(--border); position:sticky; bottom:0; z-index:10; }
    .chat-input { display:flex; gap:0.8rem; align-items:center; background:#f1f5f9; padding:0.4rem 0.8rem; border-radius:30px; border: 1px solid var(--border); }
    .chat-input input { background:transparent; border:none; padding:0.6rem 0.5rem; flex:1; color: var(--text); font-size: 1rem; }
    .chat-input input:focus { outline:none; }
    .chat-input button { background:transparent; border:none; color:var(--primary); font-size:1.3rem; cursor:pointer; padding:0 0.5rem; }
    .chat-input .icon-btn { color: #94a3b8; font-size: 1.2rem; cursor: pointer; }

    @media (max-width: 768px) {
        .chat-container { height: calc(100vh - 64px); }
        .container { padding: 0; }
        .card { border-radius: 0; }
    }
</style>
@endpush

@section('content')
<div class="card chat-container">
    <div class="chat-header">
        <a href="{{ route('chats.index') }}" style="color:var(--text); font-size:1.2rem;"><i class="fas fa-arrow-left"></i></a>
        
        @if($chat->isSupport())
            <div style="width:40px; height:40px; border-radius:50%; background:var(--primary-light); display:flex; align-items:center; justify-content:center; color:var(--primary);">
                <i class="fas fa-headset"></i>
            </div>
            <div>
                <strong>Muwashi Mandi Support</strong>
                <div style="font-size:0.8rem; color:var(--text-light);">We usually reply within a few hours</div>
            </div>
        @else
            @php
                $isBuyer = $chat->buyer_id === auth()->id();
                $otherUser = $isBuyer ? $chat->seller : $chat->buyer;
            @endphp
            <div style="width:40px; height:40px; border-radius:50%; background:#334155; display:flex; align-items:center; justify-content:center; font-weight:700; color:#fff;">
                {{ strtoupper(substr($otherUser->name, 0, 1)) }}
            </div>
            <div>
                <strong style="display:block;">{{ $otherUser->name }}</strong>
                <div style="font-size:0.75rem; color:#94a3b8;"><i class="fas fa-circle" style="color:#22c55e; font-size:0.5rem;"></i> Online</div>
            </div>
        @endif
    </div>

    @if(!$chat->isSupport() && $chat->ad)
        <a href="{{ route('ads.show', ['id' => $chat->ad->id, 'slug' => $chat->ad->slug]) }}" class="chat-ad-banner">
            @if($chat->ad->images->count())
                <img src="{{ asset('storage/' . $chat->ad->images->first()->image_path) }}" class="chat-ad-img">
            @else
                <div class="chat-ad-img" style="background:#334155; display:flex; align-items:center; justify-content:center;">
                    <i class="fas fa-image" style="color:#94a3b8;"></i>
                </div>
            @endif
            <div class="chat-ad-info">
                <p class="chat-ad-title">{{ $chat->ad->title }}</p>
                <span class="chat-ad-price">Rs {{ number_format($chat->ad->price) }}</span>
            </div>
            <i class="fas fa-chevron-right" style="color:#94a3b8; font-size:0.8rem;"></i>
        </a>
    @endif

    <div class="chat-messages" id="chatMessages">
        @forelse($messages as $msg)
            <div class="msg {{ $msg->sender_id === auth()->id() ? 'msg-sent' : 'msg-received' }}">
                {{ $msg->body }}
                <div class="msg-time">
                    {{ $msg->created_at->format('h:i A') }}
                    @if($msg->sender_id === auth()->id())
                        <i class="fas fa-check" style="color: {{ $msg->is_read ? '#38bdf8' : '#94a3b8' }}; font-size: 0.6rem;"></i>
                    @endif
                </div>
            </div>
        @empty
            <div class="empty-state" style="padding:2rem;">
                <i class="fas fa-comments"></i>
                <p>{{ $chat->isSupport() ? 'How can we help you today? Send us a message!' : 'Start the conversation...' }}</p>
            </div>
        @endforelse
    </div>

    <div class="chat-input-wrapper">
        <form action="{{ route('messages.store', $chat) }}" method="POST" class="chat-input" id="chatForm">
            @csrf
            <i class="fas fa-plus icon-btn"></i>
            <input type="text" name="body" id="msgInput" placeholder="Type a message..." required autocomplete="off">
            <i class="far fa-smile icon-btn"></i>
            <button type="submit"><i class="fas fa-paper-plane"></i></button>
        </form>
    </div>
</div>

{{-- Notification Sound --}}
<audio id="notifSound" src="https://assets.mixkit.co/active_storage/sfx/2354/2354-preview.mp3" preload="auto"></audio>

@push('scripts')
<script>
    const chatBox = document.getElementById('chatMessages');
    const notifSound = document.getElementById('notifSound');
    chatBox.scrollTop = chatBox.scrollHeight;

    // Handle AJAX Send (Optional but better UX)
    document.getElementById('chatForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const input = document.getElementById('msgInput');
        const body = input.value;
        if(!body.trim()) return;

        fetch(this.action, {
            method: 'POST',
            headers: { 
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ body: body })
        });
        
        // Optimistic UI update
        const div = document.createElement('div');
        div.className = 'msg msg-sent';
        div.innerHTML = body + '<div class="msg-time">Just now</div>';
        chatBox.appendChild(div);
        chatBox.scrollTop = chatBox.scrollHeight;
        input.value = '';
    });

    // Smart Polling with Exponential Backoff
    let lastMsgId = {{ $messages->last()?->id ?? 0 }};
    let pollingInterval = 3000; // Start at 3s
    let pollTimer = null;

    function pollMessages() {
        fetch('{{ route("messages.poll", $chat) }}?after_id=' + lastMsgId)
        .then(r => r.json())
        .then(msgs => {
            if (msgs.length > 0) {
                msgs.forEach(msg => {
                    // Check if message already added optimistically
                    if (msg.sender_id != {{ auth()->id() }}) {
                        const div = document.createElement('div');
                        div.className = 'msg msg-received';
                        div.innerHTML = msg.body + '<div class="msg-time">' + new Date(msg.created_at).toLocaleTimeString([], {hour:'2-digit',minute:'2-digit'}) + '</div>';
                        chatBox.appendChild(div);
                        
                        // Play sound if received
                        notifSound.play().catch(e => console.log("Sound play failed", e));
                    }
                    lastMsgId = Math.max(lastMsgId, msg.id);
                });
                chatBox.scrollTop = chatBox.scrollHeight;
                
                // Reset interval to fast if activity detected
                pollingInterval = 3000; 
            } else {
                // No new messages, slow down up to 15s
                pollingInterval = Math.min(pollingInterval + 2000, 15000);
            }
            
            // Schedule next poll
            clearTimeout(pollTimer);
            pollTimer = setTimeout(pollMessages, pollingInterval);
        })
        .catch(() => {
            // Error, try again later
            pollTimer = setTimeout(pollMessages, 15000);
        });
    }

    // Start polling
    pollTimer = setTimeout(pollMessages, pollingInterval);

    // Reset polling interval when user focuses or types
    document.getElementById('msgInput').addEventListener('focus', () => {
        pollingInterval = 3000;
    });
</script>
@endpush
@endsection
