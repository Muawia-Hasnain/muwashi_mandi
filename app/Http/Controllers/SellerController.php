<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ad;
use Illuminate\Http\Request;

class SellerController extends Controller
{
    /**
     * Show seller profile and their active ads.
     */
    public function show(User $user)
    {
        $ads = $user->ads()
            ->where('status', 'approved')
            ->with(['images', 'district', 'tehsil'])
            ->latest()
            ->paginate(12);

        // Count total ads sold or posted
        $totalAds = $user->ads()->count();
        $activeAds = $user->ads()->where('status', 'approved')->count();

        return view('sellers.show', compact('user', 'ads', 'totalAds', 'activeAds'));
    }
}
