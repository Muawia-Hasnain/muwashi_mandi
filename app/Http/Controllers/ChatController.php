<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Display all chats for the user (Ad chats + Support chat).
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.chats.index');
        }

        // Get all ad chats where user is buyer or seller
        $adChats = Chat::where('type', 'ad')
            ->where(function ($q) use ($user) {
                $q->where('buyer_id', $user->id)
                  ->orWhere('seller_id', $user->id);
            })
            ->with(['ad', 'buyer', 'seller', 'latestMessage'])
            ->withCount(['messages as unread_count' => function ($q) use ($user) {
                $q->where('sender_id', '!=', $user->id)->where('is_read', false);
            }])
            ->latest('updated_at')
            ->get();

        // Get or create support chat
        $supportChat = Chat::firstOrCreate([
            'type' => 'support',
            'buyer_id' => $user->id,
        ]);
        
        $supportChat->load(['latestMessage']);
        $supportChat->unread_count = $supportChat->messages()->where('sender_id', '!=', $user->id)->where('is_read', false)->count();

        return view('chat.index', compact('adChats', 'supportChat'));
    }

    /**
     * Start an ad chat.
     */
    public function store(Request $request)
    {
        $request->validate(['ad_id' => 'required|exists:ads,id']);
        
        $ad = Ad::findOrFail($request->ad_id);
        
        if (Auth::id() === $ad->user_id) {
            return back()->with('error', 'You cannot chat with yourself.');
        }

        $chat = Chat::firstOrCreate([
            'type' => 'ad',
            'ad_id' => $ad->id,
            'buyer_id' => Auth::id(),
            'seller_id' => $ad->user_id,
        ]);

        return redirect()->route('chats.show', $chat);
    }

    /**
     * Show a specific chat.
     */
    public function show(Chat $chat)
    {
        $user = Auth::user();

        // Verify authorization
        if ($chat->isSupport()) {
            if ($chat->buyer_id !== $user->id && !$user->isAdmin()) abort(403);
        } else {
            if ($chat->buyer_id !== $user->id && $chat->seller_id !== $user->id && !$user->isAdmin()) abort(403);
        }

        // Mark messages as read
        $chat->messages()->where('sender_id', '!=', $user->id)->update(['is_read' => true]);

        // Load only last 50 messages to prevent performance issues
        $messages = $chat->messages()
            ->orderBy('created_at', 'desc')
            ->take(50)
            ->get()
            ->reverse();

        return view('chat.show', compact('chat', 'messages'));
    }

    /**
     * Admin's list of all support chats.
     */
    public function adminIndex()
    {
        $chats = Chat::where('type', 'support')
            ->with(['user', 'latestMessage'])
            ->latest('updated_at')
            ->paginate(20);
            
        return view('admin.chat.index', compact('chats'));
    }

    /**
     * Admin view of a specific user's support chat.
     */
    public function adminShow(User $user)
    {
        $chat = Chat::firstOrCreate([
            'type' => 'support',
            'buyer_id' => $user->id,
        ]);
        
        // Mark as read
        $chat->messages()->where('sender_id', '!=', Auth::id())->update(['is_read' => true]);

        $messages = $chat->messages()->orderBy('created_at', 'asc')->get();

        return view('admin.chat.show', compact('chat', 'messages', 'user'));
    }
    public function unreadCount()
    {
        return response()->json(['count' => Auth::user()->unreadMessagesCount()]);
    }
}
