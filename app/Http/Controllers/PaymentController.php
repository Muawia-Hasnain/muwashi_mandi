<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function create(Request $request)
    {
        $adId = $request->ad_id;
        $type = $request->type; // ad_post, boost, featured, renewal
        
        $ad = null;
        if ($adId) {
            $ad = Ad::findOrFail($adId);
            if ($ad->user_id !== Auth::id()) abort(403);
        }

        $prices = [
            'ad_post' => 50,
            'boost' => 100,
            'featured' => 200,
            'renewal' => 50,
        ];

        $amount = $prices[$type] ?? 0;

        return view('payments.create', compact('ad', 'type', 'amount'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ad_id' => 'nullable|exists:ads,id',
            'type' => 'required|in:ad_post,boost,featured,renewal',
            'amount' => 'required|numeric',
            'screenshot' => 'required|image|max:2048',
        ]);
        $prices = [
            'ad_post' => 50,
            'boost' => 100,
            'featured' => 200,
            'renewal' => 50,
        ];
        
        $amount = $prices[$validated['type']] ?? 0;

        $path = $request->file('screenshot')->store('payments', 'public');

        Payment::create([
            'user_id' => Auth::id(),
            'ad_id' => $validated['ad_id'],
            'type' => $validated['type'],
            'amount' => $amount,
            'screenshot_path' => $path,
            'status' => 'pending',
        ]);

        return redirect()->route('ads.mine')->with('success', 'Payment screenshot uploaded! Admin will verify and activate your request soon.');
    }
}
