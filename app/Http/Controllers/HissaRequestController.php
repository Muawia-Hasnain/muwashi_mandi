<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\HissaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HissaRequestController extends Controller
{
    /**
     * Show dashboard for hissa requests.
     */
    public function index()
    {
        $user = Auth::user();

        // Requests sent by the user (Buyer side)
        $sentRequests = $user->hissaRequests()->with('ad')->latest()->get();

        // Requests received by the user on their ads (Seller side)
        $receivedRequests = $user->receivedHissaRequests()->with(['ad', 'buyer'])->latest()->get();

        return view('hissa_requests.index', compact('sentRequests', 'receivedRequests'));
    }

    /**
     * Store a new hissa request.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ad_id' => 'required|exists:ads,id',
            'buyer_name' => 'required|string|max:100',
            'buyer_phone' => 'required|string|max:20',
            'requested_hisse' => 'required|integer|min:1',
        ]);

        $ad = Ad::findOrFail($validated['ad_id']);

        // Check if the ad is a qurbani ijtamai hissa ad
        if ($ad->ad_type !== 'ijtamai_hissa') {
            return back()->with('error', 'This ad is not available for Hissa booking.');
        }

        // Cannot book own ad
        if ($ad->user_id === Auth::id()) {
            return back()->with('error', 'You cannot book hisse on your own ad.');
        }

        // Check available hisse
        if ($validated['requested_hisse'] > $ad->remaining_hisse) {
            return back()->with('error', 'Not enough hisse available.');
        }

        // Check if user already requested
        $existing = HissaRequest::where('ad_id', $ad->id)
                                ->where('buyer_id', Auth::id())
                                ->where('status', 'pending')
                                ->first();
        if ($existing) {
            return back()->with('error', 'You already have a pending request for this ad.');
        }

        HissaRequest::create([
            'ad_id' => $ad->id,
            'buyer_id' => Auth::id(),
            'buyer_name' => $validated['buyer_name'],
            'buyer_phone' => $validated['buyer_phone'],
            'requested_hisse' => $validated['requested_hisse'],
        ]);

        return back()->with('success', 'Booking request sent to the seller!');
    }

    /**
     * Update request status (Accept/Reject).
     */
    public function update(Request $request, HissaRequest $hissaRequest)
    {
        $validated = $request->validate([
            'status' => 'required|in:accepted,rejected',
        ]);

        // Only the ad owner can accept/reject
        if ($hissaRequest->ad->user_id !== Auth::id()) {
            abort(403);
        }

        // Only process pending requests
        if ($hissaRequest->status !== 'pending') {
            return back()->with('error', 'Request is already processed.');
        }

        if ($validated['status'] === 'accepted') {
            // Re-check availability just in case
            if ($hissaRequest->requested_hisse > $hissaRequest->ad->remaining_hisse) {
                return back()->with('error', 'Not enough hisse available to accept this request.');
            }

            // Mark as accepted and update ad count
            $hissaRequest->update(['status' => 'accepted']);
            $hissaRequest->ad->increment('booked_hisse', $hissaRequest->requested_hisse);
            return back()->with('success', 'Booking request accepted!');
        }

        // If rejected
        $hissaRequest->update(['status' => 'rejected']);
        return back()->with('success', 'Booking request rejected.');
    }

    /**
     * Seller manually marks a hissa as booked (Option 1).
     */
    public function manualBook(Request $request, Ad $ad)
    {
        $validated = $request->validate([
            'hisse_to_book' => 'required|integer|min:1',
        ]);

        if ($ad->user_id !== Auth::id()) {
            abort(403);
        }

        if ($validated['hisse_to_book'] > $ad->remaining_hisse) {
            return back()->with('error', 'Cannot book more hisse than available.');
        }

        $ad->increment('booked_hisse', $validated['hisse_to_book']);

        return back()->with('success', $validated['hisse_to_book'] . ' hisse manually booked!');
    }
}
