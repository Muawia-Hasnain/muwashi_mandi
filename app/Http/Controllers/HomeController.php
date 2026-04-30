<?php

namespace App\Http\Controllers;

use App\Models\Ad;

class HomeController extends Controller
{
    public function index()
    {
        $featuredAds = Ad::where('status', 'approved')
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->where('is_featured', true)
                      ->whereNotNull('featured_expires_at')
                      ->where('featured_expires_at', '>', now());
                })->orWhere(function ($q) {
                    $q->where('is_boosted', true)
                      ->whereNotNull('boost_expires_at')
                      ->where('boost_expires_at', '>', now());
                });
            })
            ->with(['images', 'user'])
            ->inRandomOrder()
            ->take(4)
            ->get();

        $latestAds = Ad::where('status', 'approved')
            ->with(['images', 'user'])
            ->latest()
            ->take(8)
            ->get();

        $categories = \App\Models\Category::where('is_active', true)
            ->withCount(['ads' => function ($query) {
                $query->where('status', 'approved');
            }])
            ->get();

        return view('home', compact('featuredAds', 'latestAds', 'categories'));
    }
}
