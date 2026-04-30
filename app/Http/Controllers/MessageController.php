<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * User sends message to a specific chat (Ad or Support).
     */
    public function store(Request $request, Chat $chat)
    {
        $validated = $request->validate(['body' => 'required|string|max:1000']);
        
        $user = Auth::user();

        // Authorize
        if ($chat->isSupport()) {
            if ($chat->buyer_id !== $user->id && !$user->isAdmin()) abort(403);
        } else {
            if ($chat->buyer_id !== $user->id && $chat->seller_id !== $user->id && !$user->isAdmin()) abort(403);
        }

        $message = $chat->messages()->create([
            'sender_id' => $user->id,
            'body' => $validated['body'],
        ]);

        $chat->touch();

        if ($request->expectsJson()) {
            return response()->json($message);
        }

        return back();
    }

    /**
     * Admin sends message to User Support chat.
     */
    public function adminStore(Request $request, User $user)
    {
        $validated = $request->validate(['body' => 'required|string|max:1000']);
        
        $chat = Chat::firstOrCreate([
            'type' => 'support',
            'buyer_id' => $user->id,
        ]);

        $chat->messages()->create([
            'sender_id' => Auth::id(),
            'body' => $validated['body'],
        ]);

        $chat->touch();

        return back();
    }

    public function poll(Request $request, Chat $chat)
    {
        $user = Auth::user();

        // Authorize
        if ($chat->isSupport()) {
            if ($chat->buyer_id !== $user->id && !$user->isAdmin()) abort(403);
        } else {
            if ($chat->buyer_id !== $user->id && $chat->seller_id !== $user->id && !$user->isAdmin()) abort(403);
        }

        $messages = $chat->messages()
            ->where('id', '>', $request->after_id ?? 0)
            ->get();

        return response()->json($messages);
    }

    public function adminPoll(Request $request, User $user)
    {
        $chat = Chat::where('type', 'support')->where('buyer_id', $user->id)->first();
        if (!$chat) return response()->json([]);

        $messages = $chat->messages()
            ->where('id', '>', $request->after_id ?? 0)
            ->get();

        return response()->json($messages);
    }
}
