<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Payment;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_ads' => Ad::count(),
            'pending_ads' => Ad::where('status', 'pending')->count(),
            'payment_pending' => Ad::where('status', 'payment_pending')->count(),
            'approved_ads' => Ad::where('status', 'approved')->count(),
            'pending_payments' => Payment::where('status', 'pending')->count(),
        ];

        $recentAds = Ad::with('user')->latest()->take(5)->get();
        $recentPayments = Payment::with('user')->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentAds', 'recentPayments'));
    }
}
