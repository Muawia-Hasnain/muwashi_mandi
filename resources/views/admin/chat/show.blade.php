@extends('layouts.app')
@section('title', 'Chat with ' . $user->name)

@push('styles')
<style>
    .chat-container { display:flex; flex-direction:column; height: calc(100vh - 220px); }
    .chat-header { padding:1rem; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; }
    .chat-messages { flex:1; overflow-y:auto; padding:1rem; display:flex; flex-direction:column; gap:0.5rem; }
    .msg { max-width:70%; padding:0.7rem 1rem; border-radius:12px; font-size:0.9rem; line-height:1.4; position:relative; }
    .msg-sent { align-self:flex-end; background: var(--primary); color:#fff; border-bottom-right-radius:4px; }
    .msg-received { align-self:flex-start; background:#f1f5f9; color:var(--text); border-bottom-left-radius:4px; }
    .msg-time { font-size:0.7rem; opacity:0.7; margin-top:0.3rem; }
    .chat-input { padding:1rem; border-top:1px solid var(--border); display:flex; gap:0.5rem; }
</style>
@endpush

@section('content')
<div class="card chat-container">
    <div class="chat-header">
        <div style="display:flex; align-items:center; gap:1rem;">
            <a href="{{ route('admin.chats.index') }}" style="color:var(--text);"><i class="fas fa-arrow-left"></i></a>
            <div>
                <strong>Chat with {{ $user->name }}</strong>
                <div style="font-size:0.8rem; color:var(--text-light);">{{ $user->email }}</div>
            </div>
        </div>
    </div>

    <div class="chat-messages" id="chatMessages">
        @foreach($messages as $msg)
            <div class="msg {{ $msg->sender_id === auth()->id() ? 'msg-sent' : 'msg-received' }}">
                {{ $msg->body }}
                <div class="msg-time">{{ $msg->created_at->format('h:i A') }}</div>
            </div>
        @endforeach
    </div>

    <form action="{{ route('admin.messages.store', $user) }}" method="POST" class="chat-input">
        @csrf
        <input type="text" name="body" class="form-input" placeholder="Type a reply..." required autocomplete="off">
        <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i></button>
    </form>
</div>

@push('scripts')
<script>
    const chatBox = document.getElementById('chatMessages');
    chatBox.scrollTop = chatBox.scrollHeight;
</script>
@endpush
@endsection
